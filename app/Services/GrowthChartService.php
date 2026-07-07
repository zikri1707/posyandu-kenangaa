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
        
        $records = $patient->relationLoaded('medicalRecords')
            ? $patient->medicalRecords->sortBy('visit_date')->values()
            : $patient->medicalRecords()->reorder('visit_date', 'asc')->get();

        // Ambil referensi WHO 0-60 bulan
        $references = \Illuminate\Support\Facades\Cache::rememberForever("who_wfa_{$gender}", function () use ($gender) {
            return WhoWeightForAge::where('gender', $gender)
                ->where('age_months', '<=', 60)
                ->orderBy('age_months')
                ->get();
        });

        $fields = ['median', 'sd_plus2', 'sd_minus2', 'sd_plus3', 'sd_minus3'];
        $interpolatedRefs = $this->interpolateReferences($references, $fields);

        $medianData = [];
        $sd2Plus = [];
        $sd2Minus = [];
        $sd3Plus = [];
        $sd3Minus = [];

        for ($m = 0; $m <= 60; $m++) {
            $medianData[] = $interpolatedRefs[$m]->median;
            $sd2Plus[] = $interpolatedRefs[$m]->sd_plus2;
            $sd2Minus[] = $interpolatedRefs[$m]->sd_minus2;
            $sd3Plus[] = $interpolatedRefs[$m]->sd_plus3;
            $sd3Minus[] = $interpolatedRefs[$m]->sd_minus3;
        }

        return [
            'labels' => range(0, 60),
            'datasets' => [
                $this->createReferenceDataset('Median', $medianData, '#16a34a', 3), // Green
                $this->createReferenceDataset('+2 SD', $sd2Plus, '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('-2 SD', $sd2Minus, '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('+3 SD', $sd3Plus, '#000000', 1, 'solid'), // Black
                $this->createReferenceDataset('-3 SD', $sd3Minus, '#000000', 1, 'solid'), // Black
                $this->createChildDataset('Berat Badan Anak', $this->mapRecordsToAge($patient, $records, 'weight'), '#ffffff'), // White
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
        
        $records = $patient->relationLoaded('medicalRecords')
            ? $patient->medicalRecords->where('height', '>', 0)->sortBy('visit_date')->values()
            : $patient->medicalRecords()->where('height', '>', 0)->reorder('visit_date', 'asc')->get();

        $references = \Illuminate\Support\Facades\Cache::rememberForever("who_hfa_{$gender}", function () use ($gender) {
            return WhoHeightForAge::where('gender', $gender)
                ->where('age_months', '<=', 60)
                ->orderBy('age_months')
                ->get();
        });

        $fields = ['m_value', 'sd_plus2', 'sd_minus2', 'sd_plus3', 'sd_minus3'];
        $interpolatedRefs = $this->interpolateReferences($references, $fields);

        $medianData = [];
        $sd2Plus = [];
        $sd2Minus = [];
        $sd3Plus = [];
        $sd3Minus = [];

        for ($m = 0; $m <= 60; $m++) {
            $medianData[] = $interpolatedRefs[$m]->m_value;
            $sd2Plus[] = $interpolatedRefs[$m]->sd_plus2;
            $sd2Minus[] = $interpolatedRefs[$m]->sd_minus2;
            $sd3Plus[] = $interpolatedRefs[$m]->sd_plus3;
            $sd3Minus[] = $interpolatedRefs[$m]->sd_minus3;
        }

        return [
            'labels' => range(0, 60),
            'datasets' => [
                $this->createReferenceDataset('Median', $medianData, '#16a34a', 3), // Green
                $this->createReferenceDataset('+2 SD', $sd2Plus, '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('-2 SD', $sd2Minus, '#dc2626', 1, 'solid'), // Red
                $this->createReferenceDataset('+3 SD', $sd3Plus, '#000000', 1, 'solid'), // Black
                $this->createReferenceDataset('-3 SD', $sd3Minus, '#000000', 1, 'solid'), // Black
                $this->createChildDataset('Tinggi Badan Anak', $this->mapRecordsToAge($patient, $records, 'height'), '#ffffff'), // White
            ],
        ];
    }

    /**
     * Interpolates missing monthly reference values linearly from sparse WHO reference data.
     */
    private function interpolateReferences($references, array $fields): array
    {
        $lookup = [];
        foreach ($references as $ref) {
            $lookup[(int) $ref->age_months] = $ref;
        }

        if (empty($lookup)) {
            $interpolated = [];
            for ($m = 0; $m <= 60; $m++) {
                $newRef = new \stdClass;
                $newRef->age_months = $m;
                foreach ($fields as $field) {
                    $newRef->$field = 0;
                }
                $interpolated[$m] = $newRef;
            }

            return $interpolated;
        }

        $interpolated = [];
        for ($m = 0; $m <= 60; $m++) {
            if (isset($lookup[$m])) {
                $interpolated[$m] = $lookup[$m];
            } else {
                $lowMonth = null;
                for ($i = $m - 1; $i >= 0; $i--) {
                    if (isset($lookup[$i])) {
                        $lowMonth = $i;
                        break;
                    }
                }

                $highMonth = null;
                for ($i = $m + 1; $i <= 60; $i++) {
                    if (isset($lookup[$i])) {
                        $highMonth = $i;
                        break;
                    }
                }

                if ($lowMonth !== null && $highMonth !== null) {
                    $lowRef = $lookup[$lowMonth];
                    $highRef = $lookup[$highMonth];
                    $factor = ($m - $lowMonth) / ($highMonth - $lowMonth);

                    $newRef = new \stdClass;
                    $newRef->age_months = $m;
                    foreach ($fields as $field) {
                        $newRef->$field = round($lowRef->$field + ($highRef->$field - $lowRef->$field) * $factor, 3);
                    }
                    $interpolated[$m] = $newRef;
                } else {
                    $closest = ($lowMonth !== null) ? $lookup[$lowMonth] : $lookup[$highMonth];
                    $newRef = new \stdClass;
                    $newRef->age_months = $m;
                    foreach ($fields as $field) {
                        $newRef->$field = round($closest->$field, 3);
                    }
                    $interpolated[$m] = $newRef;
                }
            }
        }

        return $interpolated;
    }

    // ─────────────────────────────────────────────
    // Helper Methods
    // ─────────────────────────────────────────────

    private function createReferenceDataset(string $label, array $data, string $color, int $width = 1, string $style = 'solid'): array
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

    /**
     * Mendapatkan data riwayat kesehatan berkala Lansia (Posbindu).
     */
    public function getLansiaHealthData(Patient $patient): array
    {
        $records = $patient->medicalRecords()
            ->reorder('visit_date', 'asc')
            ->get();

        $labels = [];
        $weightData = [];
        $systolicData = [];
        $diastolicData = [];
        $bloodSugarData = [];
        $uricAcidData = [];
        $cholesterolData = [];

        foreach ($records as $record) {
            $labels[] = $record->visit_date->translatedFormat('d M Y');
            $weightData[] = $record->weight ? (float) $record->weight : null;
            $systolicData[] = $record->systolic_bp ? (int) $record->systolic_bp : null;
            $diastolicData[] = $record->diastolic_bp ? (int) $record->diastolic_bp : null;
            $bloodSugarData[] = $record->blood_sugar ? (int) $record->blood_sugar : null;
            $uricAcidData[] = $record->uric_acid ? (float) $record->uric_acid : null;
            $cholesterolData[] = $record->cholesterol ? (int) $record->cholesterol : null;
        }

        return [
            'labels' => $labels,
            'weight' => $weightData,
            'systolic' => $systolicData,
            'diastolic' => $diastolicData,
            'blood_sugar' => $bloodSugarData,
            'uric_acid' => $uricAcidData,
            'cholesterol' => $cholesterolData,
        ];
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
