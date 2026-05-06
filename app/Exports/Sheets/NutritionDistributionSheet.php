<?php

namespace App\Exports\Sheets;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class NutritionDistributionSheet
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function render(Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'DISTRIBUSI STATUS GIZI BALITA');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $headers = ['Status Gizi', 'Jumlah'];
        $sheet->fromArray([$headers], null, 'A3');
        $sheet->getStyle('A3:B3')->getFont()->setBold(true);

        $dataRows = [];
        foreach ($this->reportData['nutrition_distribution'] as $status => $count) {
            $dataRows[] = [$status, $count];
        }

        if (! empty($dataRows)) {
            $sheet->fromArray($dataRows, null, 'A4');
        }

        $sheet->getColumnDimension('A')->setAutoSize(true);
        $sheet->getColumnDimension('B')->setAutoSize(true);
    }
}
