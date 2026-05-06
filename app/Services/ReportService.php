<?php

namespace App\Services;

use App\Exports\MonthlyReportExport;
use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\Posyandu;
use App\Models\Schedule;
use Illuminate\Support\Facades\DB;

class ReportService
{
    /**
     * Generate monthly report for a posyandu
     *
     * @param  int  $posyanduId  Posyandu ID
     * @param  int  $month  Month (1-12)
     * @param  int  $year  Year (e.g., 2024)
     * @return array Report data structure
     */
    public function generateMonthlyReport(int $posyanduId, int $month, int $year): array
    {
        $posyandu = Posyandu::findOrFail($posyanduId);

        // Date range for the month
        $startDate = sprintf('%04d-%02d-01', $year, $month);
        $endDate = date('Y-m-t', strtotime($startDate)); // Last day of month

        // 1. Kunjungan per kategori
        $visitsByCategory = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->select('patients.category', DB::raw('COUNT(*) as total'))
            ->groupBy('patients.category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();

        // Ensure all categories are present
        $categories = ['balita', 'ibu_hamil', 'remaja', 'lansia'];
        foreach ($categories as $category) {
            if (! isset($visitsByCategory[$category])) {
                $visitsByCategory[$category] = 0;
            }
        }

        // 2. Distribusi status gizi (hanya untuk balita)
        $nutritionDistribution = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->where('patients.category', 'balita')
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->whereNotNull('medical_records.nutrition_status')
            ->select('medical_records.nutrition_status', DB::raw('COUNT(*) as total'))
            ->groupBy('medical_records.nutrition_status')
            ->get()
            ->pluck('total', 'nutrition_status')
            ->toArray();

        // 3. Pemberian Vitamin A
        $vitaminACount = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->where('medical_records.vitamin_a', true)
            ->count();

        // 4. Pemberian Pill FE
        $pillFeCount = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->where('medical_records.pill_fe', true)
            ->count();

        // 5. Jadwal kegiatan bulan ini
        $schedules = Schedule::query()
            ->where('posyandu_id', $posyanduId)
            ->whereYear('start_time', $year)
            ->whereMonth('start_time', $month)
            ->orderBy('start_time')
            ->get()
            ->map(function ($schedule) {
                return [
                    'title' => $schedule->title,
                    'date' => $schedule->start_time,
                    'location' => $schedule->location,
                    'status' => $schedule->status,
                ];
            })
            ->toArray();

        // 6. Total sasaran terdaftar per kategori
        $totalPatientsByCategory = Patient::query()
            ->where('posyandu_id', $posyanduId)
            ->select('category', DB::raw('COUNT(*) as total'))
            ->groupBy('category')
            ->get()
            ->pluck('total', 'category')
            ->toArray();

        foreach ($categories as $category) {
            if (! isset($totalPatientsByCategory[$category])) {
                $totalPatientsByCategory[$category] = 0;
            }
        }

        // 7. Data mentah rekam medis (untuk sheet detail)
        $rawRecords = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->select('medical_records.*', 'patients.full_name', 'patients.id_number', 'patients.category', 'patients.gender')
            ->orderBy('medical_records.visit_date')
            ->get()
            ->toArray();

        // 8. Pokja IV Data
        $pokjaIvData = [
            'rows' => [
                'S' => $this->initAgeGroupArray(),
                'K' => $this->initAgeGroupArray(),
                'N' => $this->initAgeGroupArray(),
                'T' => $this->initAgeGroupArray(),
                'U' => $this->initAgeGroupArray(),
                'B' => $this->initAgeGroupArray(),
                'O' => $this->initAgeGroupArray(),
                'GB' => $this->initAgeGroupArray(),
                'GK' => $this->initAgeGroupArray(),
                'GR' => $this->initAgeGroupArray(),
                'GL' => $this->initAgeGroupArray(),
            ],
            'cohorts' => [
                '0-11_baru' => ['L' => 0, 'P' => 0],
                '12-59_baru' => ['L' => 0, 'P' => 0],
                '12_bln' => ['L' => 0, 'P' => 0],
                '24_bln' => ['L' => 0, 'P' => 0],
                '36_bln' => ['L' => 0, 'P' => 0],
                '48_bln' => ['L' => 0, 'P' => 0],
                '60_bln' => ['L' => 0, 'P' => 0],
                'lulus' => ['L' => 0, 'P' => 0],
            ],
            'kader' => [
                'total' => \App\Models\User::where('posyandu_id', $posyanduId)->where('role', 'kader')->count(),
                'trained' => 0,
                'active' => 0,
            ],
        ];

        // Fill Row S & K
        $allBalitas = Patient::where('posyandu_id', $posyanduId)
            ->where('category', 'balita')
            ->get();
        foreach ($allBalitas as $p) {
            $age = (int) $p->birth_date->diffInMonths($endDate);
            if ($age > 59) {
                continue;
            }
            $this->incrementAgeGroup($pokjaIvData['rows']['S'], $p->gender, $age);
            $this->incrementAgeGroup($pokjaIvData['rows']['K'], $p->gender, $age);
        }

        // Fill other rows from medical records
        $records = MedicalRecord::query()
            ->join('patients', 'medical_records.patient_id', '=', 'patients.id')
            ->where('patients.posyandu_id', $posyanduId)
            ->where('patients.category', 'balita')
            ->whereBetween('medical_records.visit_date', [$startDate, $endDate])
            ->select('medical_records.*', 'patients.birth_date', 'patients.gender')
            ->get();

        foreach ($records as $record) {
            $age = (int) \Carbon\Carbon::parse($record->birth_date)->diffInMonths(\Carbon\Carbon::parse($record->visit_date));
            if ($age > 59) {
                continue;
            }

            $gender = $record->gender;
            $g = (strtoupper(substr($gender, 0, 1)) === 'L' || strtoupper(substr($gender, 0, 1)) === 'M') ? 'L' : 'P';

            // Row U (Used as 'D' / Datang in this context based on user image flow)
            $this->incrementAgeGroup($pokjaIvData['rows']['U'], $gender, $age);

            // Row N (Naik)
            if ($record->nutrition_trend === 'naik') {
                $this->incrementAgeGroup($pokjaIvData['rows']['N'], $gender, $age);
            }

            // Row T (Tidak Naik)
            if ($record->nutrition_trend === 'turun' || $record->nutrition_trend === 'tetap') {
                $this->incrementAgeGroup($pokjaIvData['rows']['T'], $gender, $age);
            }

            // Row O (Vitamin A)
            if ($record->vitamin_a) {
                $this->incrementAgeGroup($pokjaIvData['rows']['O'], $gender, $age);
            }

            // Row GB (Gizi Buruk)
            if ($record->nutrition_status === 'Gizi Buruk') {
                $this->incrementAgeGroup($pokjaIvData['rows']['GB'], $gender, $age);
            }

            // Row GK (Gizi Kurang)
            if ($record->nutrition_status === 'Gizi Kurang') {
                $this->incrementAgeGroup($pokjaIvData['rows']['GK'], $gender, $age);
            }

            // Cohorts Logic
            $isFirstVisit = ! MedicalRecord::where('patient_id', $record->patient_id)
                ->where('visit_date', '<', $record->visit_date)
                ->exists();

            if ($isFirstVisit) {
                if ($age <= 11) {
                    $pokjaIvData['cohorts']['0-11_baru'][$g]++;
                } else {
                    $pokjaIvData['cohorts']['12-59_baru'][$g]++;
                }
            }

            if ($age === 12) {
                $pokjaIvData['cohorts']['12_bln'][$g]++;
            }
            if ($age === 24) {
                $pokjaIvData['cohorts']['24_bln'][$g]++;
            }
            if ($age === 36) {
                $pokjaIvData['cohorts']['36_bln'][$g]++;
            }
            if ($age === 48) {
                $pokjaIvData['cohorts']['48_bln'][$g]++;
            }
            if ($age === 59) {
                $pokjaIvData['cohorts']['60_bln'][$g]++;
            } // Handling 60 as 59 edge

            if ($record->weight >= 11.5) {
                $pokjaIvData['cohorts']['lulus'][$g]++;
            }
        }

        return [
            'posyandu' => [
                'id' => $posyandu->id,
                'name' => $posyandu->name,
                'address' => $posyandu->address,
            ],
            'period' => [
                'month' => $month,
                'year' => $year,
                'month_name' => $this->getMonthName($month),
                'start_date' => $startDate,
                'end_date' => $endDate,
            ],
            'visits_by_category' => $visitsByCategory,
            'total_patients_by_category' => $totalPatientsByCategory,
            'nutrition_distribution' => $nutritionDistribution,
            'vitamin_a_given' => $vitaminACount,
            'pill_fe_given' => $pillFeCount,
            'schedules' => $schedules,
            'total_visits' => array_sum($visitsByCategory),
            'raw_medical_records' => $rawRecords,
            'pokja_iv' => $pokjaIvData,
        ];
    }

    /**
     * Export report data to Excel file (CSV format — no external library needed)
     *
     * @param  array  $reportData  Report data from generateMonthlyReport
     * @param  string  $posyanduName  Posyandu name for filename
     * @return string Path to the generated CSV file
     */
    public function exportToExcel(array $reportData, string $posyanduName): string
    {
        $fileName = sprintf(
            'Laporan_%s_%s_%s.xlsx',
            str_replace([' ', '/'], '_', $posyanduName),
            $reportData['period']['month_name'],
            $reportData['period']['year']
        );

        $directory = storage_path('app/public/exports');
        if (! file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $filePath = $directory.'/'.$fileName;

        // Gunakan export class baru berbasis PhpSpreadsheet
        $export = new MonthlyReportExport($reportData);
        $export->export($filePath);

        return $filePath;
    }

    /**
     * Export report data to PDF file
     *
     * @param  array  $reportData  Report data from generateMonthlyReport
     * @param  string  $posyanduName  Posyandu name for filename
     * @return string Path to the generated PDF file
     */
    public function exportToPdf(array $reportData, string $posyanduName): string
    {
        $fileName = sprintf(
            'Laporan_%s_%s_%s.pdf',
            str_replace(' ', '_', $posyanduName),
            $reportData['period']['month_name'],
            $reportData['period']['year']
        );

        $filePath = 'exports/'.$fileName;

        // Generate PDF using dompdf
        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('reports.monthly-report-pdf', [
            'reportData' => $reportData,
        ]);

        // Set paper size and orientation
        $pdf->setPaper('A4', 'portrait');

        // Save to storage
        $fullPath = storage_path('app/public/'.$filePath);

        // Ensure directory exists
        $directory = dirname($fullPath);
        if (! file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf->save($fullPath);

        return $fullPath;
    }

    /**
     * Get Indonesian month name
     *
     * @param  int  $month  Month number (1-12)
     * @return string Month name in Indonesian
     */
    private function getMonthName(int $month): string
    {
        $months = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember',
        ];

        return $months[$month] ?? '';
    }

    /**
     * Inisialisasi array struktur umur Pokja IV.
     */
    private function initAgeGroupArray(): array
    {
        return [
            'male' => ['0-5' => 0, '6-11' => 0, '12-23' => 0, '24-59' => 0, 'total' => 0],
            'female' => ['0-5' => 0, '6-11' => 0, '12-23' => 0, '24-59' => 0, 'total' => 0],
        ];
    }

    /**
     * Increment age group berdasarkan gender dan umur.
     */
    private function incrementAgeGroup(array &$target, string $gender, int $ageInMonths): void
    {
        $normalizedGender = strtoupper(trim($gender));
        $g = ($normalizedGender === 'L' || $normalizedGender === 'M' || $normalizedGender === 'LAKI-LAKI' || $normalizedGender === 'MALE') ? 'male' : 'female';

        if ($ageInMonths <= 5) {
            $target[$g]['0-5']++;
        } elseif ($ageInMonths <= 11) {
            $target[$g]['6-11']++;
        } elseif ($ageInMonths <= 23) {
            $target[$g]['12-23']++;
        } elseif ($ageInMonths <= 59) {
            $target[$g]['24-59']++;
        }

        $target[$g]['total']++;
    }
}
