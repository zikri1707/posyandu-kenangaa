<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RawMedicalRecordsSheet
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function render(Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'DATA MENTAH REKAM MEDIS');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $headers = [
            'Tanggal Kunjung', 'Nama Pasien', 'NIK', 'Kategori', 'Gender',
            'BB (kg)', 'TB (cm)', 'Lila/Lika', 'Status Gizi', 'Z-Score BB/U',
            'Status Stunting', 'Z-Score TB/U', 'Status Wasting', 'Z-Score BB/TB',
            'Imunisasi', 'Vitamin A', 'Pill FE', 'Keluhan',
        ];

        // Bulk insert headers
        $sheet->fromArray([$headers], null, 'A3');
        $sheet->getStyle('A3:R3')->getFont()->setBold(true);

        $dataRows = [];
        foreach ($this->reportData['raw_medical_records'] ?? [] as $record) {
            $dataRows[] = [
                Carbon::parse($record['visit_date'])->format('d/m/Y'),
                $record['full_name'],
                (string) $record['id_number'],
                ucfirst($record['category'] ?? ''),
                $record['gender'] ?? '',
                $record['weight'] ?? 0,
                $record['height'] ?? 0,
                $record['head_circumference'] ?? 0,
                $record['nutrition_status'] ?? '',
                $record['z_score'] ?? 0,
                $record['stunting_status'] ?? '-',
                $record['z_score_hfa'] ?? '-',
                $record['wasting_status'] ?? '-',
                $record['z_score_wfh'] ?? '-',
                $record['immunization'] ?? '',
                ($record['vitamin_a'] ?? false) ? 'Ya' : 'Tidak',
                ($record['pill_fe'] ?? false) ? 'Ya' : 'Tidak',
                $record['complaint'] ?? '',
            ];
        }

        // Bulk insert data
        if (! empty($dataRows)) {
            $sheet->fromArray($dataRows, null, 'A4');
        }

        // Optimize column widths (fixed for known short columns, auto for text columns)
        $autoCols = ['B', 'I', 'K', 'M', 'O', 'R'];
        foreach ($autoCols as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Fixed widths for numeric/code columns to avoid expensive calculation
        $fixedCols = [
            'A' => 15, 'C' => 18, 'D' => 12, 'E' => 8, 'F' => 8,
            'G' => 8, 'H' => 10, 'J' => 12, 'L' => 12, 'N' => 12,
            'P' => 10, 'Q' => 10,
        ];
        foreach ($fixedCols as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }
    }
}
