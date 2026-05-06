<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\WhoHeightForAge;
use App\Models\WhoWeightForAge;
use Illuminate\Support\Collection;

/**
 * Service untuk memproses data grafik pertumbuhan anak sesuai standar WHO.
 * Menghasilkan dataset untuk Chart.js yang mencakup kurva referensi dan data historis anak.
 */
class GrowthChartService
{
    /**
     * Mendapatkan dataset lengkap untuk grafik Berat Badan menurut Umur (BB/U).
     */
    public function getWeightForAgeData(Patient $patient): array
    {
        $gender = $this->normalizeGender($patient->gender);
        $records = $patient->medicalRecords()->orderBy('visit_date')->get();

        // Ambil referensi WHO 0-60 bulan
        $references = WhoWeightForAge::where('gender', $gender)
            ->where('age_months', '<=', 60)
            ->orderBy('age_months')
            ->get();

        return [
            'labels' => $references->pluck('age_months')->toArray(),
            'datasets' => [
                $this->createReferenceDataset('Median', $references->pluck('median')->toArray(), '#16a34a', 3), // Green
                $this->createReferenceDataset('+2 SD', $references->pluck('sd_plus2')->toArray(), '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('-2 SD', $references->pluck('sd_minus2')->toArray(), '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('+3 SD', $references->pluck('sd_plus3')->toArray(), '#000000', 1, 'solid'), // Black
                $this->createReferenceDataset('-3 SD', $references->pluck('sd_minus3')->toArray(), '#000000', 1, 'solid'), // Black
                $this->createChildDataset('Berat Badan Anak', $this->mapRecordsToAge($patient, $records, 'weight'), '#ffffff'), // White for high contrast on themed bg
            ],
            'weight' => $records->pluck('weight')->toArray(),
            'height' => $records->pluck('height')->toArray(),
            'nutrition_status' => $records->pluck('nutrition_status')->toArray(),
        ];
    }

    /**
     * Mendapatkan dataset lengkap untuk grafik Tinggi Badan menurut Umur (TB/U) - Grafik Stunting.
     */
    public function getHeightForAgeData(Patient $patient): array
    {
        $gender = $this->normalizeGender($patient->gender);
        $records = $patient->medicalRecords()->where('height', '>', 0)->orderBy('visit_date')->get();

        $references = WhoHeightForAge::where('gender', $gender)
            ->where('age_months', '<=', 60)
            ->orderBy('age_months')
            ->get();

        return [
            'labels' => $references->pluck('age_months')->toArray(),
            'datasets' => [
                $this->createReferenceDataset('Median', $references->pluck('m_value')->toArray(), '#16a34a', 3), // Green
                $this->createReferenceDataset('+2 SD', $references->pluck('sd_plus2')->toArray(), '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('-2 SD', $references->pluck('sd_minus2')->toArray(), '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('+3 SD', $references->pluck('sd_plus3')->toArray(), '#000000', 1, 'solid'), // Black
                $this->createReferenceDataset('-3 SD', $references->pluck('sd_minus3')->toArray(), '#000000', 1, 'solid'), // Black
                $this->createChildDataset('Tinggi Badan Anak', $this->mapRecordsToAge($patient, $records, 'height'), '#ffffff'), // White
            ],
        ];
    }

    // ─────────────────────────────────────────────
    // Helper Methods
    // ─────────────────────────────────────────────

    private function createReferenceDataset(string $label, $data, string $color, int $width = 1, string $style = 'solid'): array
    {
        return [
            'label' => $label,
            'data' => $data,
            'borderColor' => $color,
            'backgroundColor' => 'transparent',
            'borderWidth' => $width,
            'pointRadius' => 0,
            'borderDash' => $style === 'dash' ? [5, 5] : [],
            'fill' => false,
            'tension' => 0.4,
        ];
    }

    private function createChildDataset(string $label, array $data, string $color): array
    {
        return [
            'label' => $label,
            'data' => $data,
            'borderColor' => $color,
            'backgroundColor' => $color,
            'borderWidth' => 3,
            'pointRadius' => 5,
            'pointHoverRadius' => 8,
            'fill' => false,
            'tension' => 0.2,
            'zIndex' => 10,
        ];
    }

    /**
     * Memetakan rekam medis ke index bulan usia untuk Chart.js.
     */
    private function mapRecordsToAge(Patient $patient, Collection $records, string $field): array
    {
        $data = array_fill(0, 61, null);
        foreach ($records as $record) {
            $ageMonths = (int) $patient->birth_date->diffInMonths($record->visit_date);
            if ($ageMonths <= 60) {
                $data[$ageMonths] = (float) $record->$field;
            }
        }

        return $data;
    }

    private function normalizeGender(?string $gender): string
    {
        if (! $gender) {
            return 'M';
        }
        $gender = strtoupper($gender);

        return ($gender === 'L' || $gender === 'M') ? 'M' : 'F';
    }
}
