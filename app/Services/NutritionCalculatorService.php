<?php

namespace App\Services;

use App\Models\WhoWeightForAge;
use App\Models\WhoHeightForAge;
use App\Models\WhoWeightForHeight;
use App\Models\WhoBmiForAge;

/**
 * Service perhitungan gizi antropometri menggunakan standar WHO 2006 / Kemenkes RI.
 *
 * Mendukung 4 indeks:
 *   - BB/U  (Weight-for-Age)   → status gizi umum / underweight
 *   - TB/U  (Height-for-Age)   → deteksi stunting
 *   - BB/TB (Weight-for-Height)→ deteksi wasting (kurus)
 *   - IMT/U (BMI-for-Age)      → deteksi obesitas
 *
 * Menggunakan 2 metode Z-Score:
 *   1. Metode SD (untuk BB/U): Z = (y - median) / SD
 *   2. Metode LMS (untuk TB/U, BB/TB, IMT/U): Z = ((y/M)^L - 1) / (L × S)
 */
class NutritionCalculatorService
{
    // ─────────────────────────────────────────────
    // Public API
    // ─────────────────────────────────────────────

    /**
     * Hitung semua 4 indeks antropometri sekaligus.
     *
     * @param float  $weight     Berat badan dalam kg
     * @param float  $height     Tinggi badan dalam cm (0 jika tidak diukur)
     * @param int    $ageMonths  Usia dalam bulan (0–59)
     * @param string $gender     'L'/'M' atau 'P'/'F'
     * @return array {
     *   z_score: float|null,           // BB/U
     *   nutrition_status: string,       // Status gizi BB/U
     *   z_score_hfa: float|null,        // TB/U
     *   stunting_status: string,        // Status stunting
     *   z_score_wfh: float|null,        // BB/TB
     *   wasting_status: string,         // Status wasting
     *   z_score_bfa: float|null,        // IMT/U
     *   bmi_status: string,             // Status IMT
     * }
     */
    public function calculateAll(float $weight, float $height, int $ageMonths, string $gender): array
    {
        $gender = $this->normalizeGender($gender);

        $zWfa  = $this->calculateWeightForAge($weight, $ageMonths, $gender);
        $zHfa  = $height > 0 ? $this->calculateHeightForAge($height, $ageMonths, $gender) : null;
        $zWfh  = ($weight > 0 && $height > 0) ? $this->calculateWeightForHeight($weight, $height, $gender) : null;
        $zBfa  = ($weight > 0 && $height > 0 && $height > 45) ? $this->calculateBmiForAge($weight, $height, $ageMonths, $gender) : null;

        return [
            'z_score'          => $zWfa,
            'nutrition_status' => $this->classifyNutritionStatus($zWfa),
            'z_score_hfa'      => $zHfa,
            'stunting_status'  => $this->classifyStuntingStatus($zHfa),
            'z_score_wfh'      => $zWfh,
            'wasting_status'   => $this->classifyWastingStatus($zWfh),
            'z_score_bfa'      => $zBfa,
            'bmi_status'       => $this->classifyBmiStatus($zBfa),
        ];
    }

    /**
     * Hitung Z-Score dan status gizi (backward-compatible dengan kode lama).
     *
     * @param float  $weight     Berat badan dalam kg
     * @param float  $height     Tinggi badan dalam cm
     * @param int    $ageMonths  Usia dalam bulan
     * @param string $gender     'L'/'P' atau 'M'/'F'
     * @return array ['z_score' => float|null, 'status' => string]
     */
    public function calculate(float $weight, float $height, int $ageMonths, string $gender): array
    {
        $zScore = $this->calculateZScore($weight, $ageMonths, $gender);
        $status = $this->classifyNutritionStatus($zScore);

        return [
            'z_score' => $zScore,
            'status'  => $status,
        ];
    }

    // ─────────────────────────────────────────────
    // Indeks 1: BB/U — Weight-for-Age (Metode SD)
    // ─────────────────────────────────────────────

    /**
     * Hitung Z-Score BB/U menggunakan metode SD WHO.
     * Sumber referensi: tabel who_weight_for_age di DB.
     */
    public function calculateZScore(float $weight, int $ageInMonths, string $gender): ?float
    {
        $normalizedGender = $this->normalizeGender($gender);
        if ($normalizedGender === null) return null;
 
        $reference = WhoWeightForAge::getReference($normalizedGender, $ageInMonths);
        if (!$reference) return null;
 
        $median = (float) $reference->median;
 
        // Pilih SD berdasarkan posisi relatif terhadap median
        // Karena sd_plus2 dan sd_minus2 adalah jarak 2 standar deviasi, 
        // kita bagi 2 untuk mendapatkan nilai 1 SD.
        if ($weight >= $median) {
            $sd = ((float) $reference->sd_plus2 - $median) / 2;
        } else {
            $sd = ($median - (float) $reference->sd_minus2) / 2;
        }
 
        if ($sd == 0) return null;
 
        return round(($weight - $median) / $sd, 2);
    }
 
    /**
     * Alias untuk calculateZScore (Weight-for-Age).
     */
    public function calculateWeightForAge(float $weight, int $ageInMonths, string $gender): ?float
    {
        return $this->calculateZScore($weight, $ageInMonths, $gender);
    }

    /**
     * Klasifikasi status gizi BB/U berdasarkan Z-Score.
     */
    public function classifyNutritionStatus(?float $zScore): string
    {
        if ($zScore === null) return 'Tidak Dapat Dihitung';
        if ($zScore < -3)    return 'Gizi Buruk';
        if ($zScore < -2)    return 'Gizi Kurang';
        if ($zScore <= 2)    return 'Gizi Baik';
        return 'Gizi Lebih';
    }

    // ─────────────────────────────────────────────
    // Indeks 2: TB/U — Height-for-Age (LMS)
    // ─────────────────────────────────────────────

    /**
     * Hitung Z-Score TB/U menggunakan metode LMS.
     * Mendeteksi stunting.
     *
     * @param float  $height    Tinggi/panjang badan dalam cm
     * @param int    $ageMonths Usia dalam bulan
     * @param string $gender    'M' atau 'F' (sudah dinormalisasi)
     */
    public function calculateHeightForAge(float $height, int $ageMonths, string $gender): ?float
    {
        $gender = $this->normalizeGender($gender);
        if (!$gender) return null;

        $ref = WhoHeightForAge::getReference($gender, $ageMonths);
        if (!$ref) return null;

        return $this->lmsZScore($height, $ref->l_value, $ref->m_value, $ref->s_value);
    }

    /**
     * Klasifikasi status stunting berdasarkan Z-Score TB/U.
     */
    public function classifyStuntingStatus(?float $zScore): string
    {
        if ($zScore === null) return 'Tidak Dapat Dihitung';
        if ($zScore < -3)    return 'Sangat Pendek (Severely Stunted)';
        if ($zScore < -2)    return 'Pendek (Stunted)';
        if ($zScore <= 3)    return 'Normal';
        return 'Tinggi';
    }

    // ─────────────────────────────────────────────
    // Indeks 3: BB/TB — Weight-for-Height (LMS)
    // ─────────────────────────────────────────────

    /**
     * Hitung Z-Score BB/TB menggunakan metode LMS.
     * Mendeteksi wasting (kurus) dan overweight.
     *
     * @param float  $weight   Berat badan dalam kg
     * @param float  $height   Tinggi badan dalam cm
     * @param string $gender   'M' atau 'F' (sudah dinormalisasi)
     */
    public function calculateWeightForHeight(float $weight, float $height, string $gender): ?float
    {
        $gender = $this->normalizeGender($gender);
        if (!$gender) return null;

        $ref = WhoWeightForHeight::getReference($gender, $height);
        if (!$ref) return null;

        return $this->lmsZScore($weight, $ref->l_value, $ref->m_value, $ref->s_value);
    }

    /**
     * Klasifikasi status wasting berdasarkan Z-Score BB/TB.
     */
    public function classifyWastingStatus(?float $zScore): string
    {
        if ($zScore === null) return 'Tidak Dapat Dihitung';
        if ($zScore < -3)    return 'Sangat Kurus';
        if ($zScore < -2)    return 'Kurus';
        if ($zScore <= 2)    return 'Normal';
        if ($zScore <= 3)    return 'Risiko Gemuk';
        return 'Gemuk';
    }

    // ─────────────────────────────────────────────
    // Indeks 4: IMT/U — BMI-for-Age (LMS)
    // ─────────────────────────────────────────────

    /**
     * Hitung Z-Score IMT/U menggunakan metode LMS.
     * Mendeteksi obesitas.
     *
     * @param float  $weight    Berat badan dalam kg
     * @param float  $height    Tinggi badan dalam cm
     * @param int    $ageMonths Usia dalam bulan
     * @param string $gender    'M' atau 'F' (sudah dinormalisasi)
     */
    public function calculateBmiForAge(float $weight, float $height, int $ageMonths, string $gender): ?float
    {
        $gender = $this->normalizeGender($gender);
        if (!$gender) return null;

        if ($height <= 0) return null;

        // Hitung IMT: berat (kg) / (tinggi (m))^2
        $heightM = $height / 100;
        $bmi     = $weight / ($heightM ** 2);

        $ref = WhoBmiForAge::getReference($gender, $ageMonths);
        if (!$ref) return null;

        return $this->lmsZScore($bmi, $ref->l_value, $ref->m_value, $ref->s_value);
    }

    /**
     * Klasifikasi status IMT/U berdasarkan Z-Score.
     */
    public function classifyBmiStatus(?float $zScore): string
    {
        if ($zScore === null) return 'Tidak Dapat Dihitung';
        if ($zScore < -3)    return 'Sangat Kurus';
        if ($zScore < -2)    return 'Kurus';
        if ($zScore <= 1)    return 'Normal';
        if ($zScore <= 2)    return 'Risiko Gemuk';
        if ($zScore <= 3)    return 'Gemuk (Overweight)';
        return 'Obesitas';
    }

    // ─────────────────────────────────────────────
    // Helper Privat
    // ─────────────────────────────────────────────

    /**
     * Hitung Z-Score menggunakan metode LMS WHO.
     * Rumus: Z = ((y/M)^L - 1) / (L × S)
     *
     * @param float $y Nilai yang diukur (tinggi, berat, atau IMT)
     * @param float $L Nilai L (Box-Cox power transformation)
     * @param float $M Nilai M (Median)
     * @param float $S Nilai S (Coefficient of variation)
     * @return float|null Z-Score atau null jika tidak dapat dihitung
     */
    private function lmsZScore(float $y, float $L, float $M, float $S): ?float
    {
        if ($M <= 0 || $S <= 0 || $y <= 0) return null;

        // Jika L sangat mendekati 0, gunakan pendekatan logaritmik
        if (abs($L) < 0.0001) {
            $z = log($y / $M) / $S;
        } else {
            $z = (pow($y / $M, $L) - 1) / ($L * $S);
        }

        // Clamp Z-Score sesuai konvensi WHO (tidak boleh melebihi ±6)
        $z = max(-6.0, min(6.0, $z));

        return round($z, 2);
    }

    /**
     * Normalisasi format gender ke WHO standard (M/F).
     *
     * @param string $gender Input gender ('L', 'P', 'M', 'F')
     * @return string|null 'M' atau 'F', null jika tidak valid
     */
    private function normalizeGender(string $gender): ?string
    {
        $gender = strtoupper(trim($gender));

        return match ($gender) {
            'L', 'M' => 'M', // Laki-laki / Male
            'P', 'F' => 'F', // Perempuan / Female
            default  => null,
        };
    }
}
