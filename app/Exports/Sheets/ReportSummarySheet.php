<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportSummarySheet
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function render(Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'LAPORAN BULANAN POSYANDU');
        $sheet->mergeCells('A1:C1');
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);

        $sheet->setCellValue('A3', 'Nama Posyandu');
        $sheet->setCellValue('B3', ': '.$this->reportData['posyandu']['name']);

        $sheet->setCellValue('A4', 'Alamat');
        $sheet->setCellValue('B4', ': '.$this->reportData['posyandu']['address']);

        $sheet->setCellValue('A5', 'Periode');
        $sheet->setCellValue('B5', ': '.$this->reportData['period']['month_name'].' '.$this->reportData['period']['year']);

        // Recap Table
        $sheet->setCellValue('A7', 'REKAPITULASI KUNJUNGAN');
        $sheet->getStyle('A7')->getFont()->setBold(true);

        $sheet->setCellValue('A8', 'Kategori');
        $sheet->setCellValue('B8', 'Jumlah Kunjungan');
        $sheet->setCellValue('C8', 'Total Sasaran');
        $sheet->getStyle('A8:C8')->getFont()->setBold(true);

        $categories = [
            'balita' => 'Balita',
            'ibu_hamil' => 'Ibu Hamil',
            'remaja' => 'Remaja',
            'lansia' => 'Lansia',
        ];

        $row = 9;
        foreach ($categories as $key => $label) {
            $sheet->setCellValue('A'.$row, $label);
            $sheet->setCellValue('B'.$row, $this->reportData['visits_by_category'][$key] ?? 0);
            $sheet->setCellValue('C'.$row, $this->reportData['total_patients_by_category'][$key] ?? 0);
            $row++;
        }

        $sheet->setCellValue('A'.$row, 'TOTAL');
        $sheet->setCellValue('B'.$row, $this->reportData['total_visits']);
        $sheet->getStyle('A'.$row.':B'.$row)->getFont()->setBold(true);

        // Suplement Recap
        $row += 2;
        $sheet->setCellValue('A'.$row, 'PEMBERIAN SUPLEMEN');
        $sheet->getStyle('A'.$row)->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue('A'.$row, 'Jenis');
        $sheet->setCellValue('B'.$row, 'Jumlah');
        $sheet->getStyle('A'.$row.':B'.$row)->getFont()->setBold(true);
        $row++;

        $sheet->setCellValue('A'.$row, 'Vitamin A');
        $sheet->setCellValue('B'.$row, $this->reportData['vitamin_a_given']);
        $row++;

        $sheet->setCellValue('A'.$row, 'Tablet FE (Pill FE)');
        $sheet->setCellValue('B'.$row, $this->reportData['pill_fe_given']);

        foreach (range('A', 'C') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
