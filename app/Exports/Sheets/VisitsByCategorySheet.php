<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class VisitsByCategorySheet
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function render(Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'DETAIL KUNJUNGAN PER KATEGORI');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A3', 'Kategori');
        $sheet->setCellValue('B3', 'Jumlah Kunjungan');
        $sheet->getStyle('A3:B3')->getFont()->setBold(true);

        $row = 4;
        foreach ($this->reportData['visits_by_category'] as $category => $count) {
            $sheet->setCellValue('A'.$row, ucfirst($category));
            $sheet->setCellValue('B'.$row, $count);
            $row++;
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
    }
}
