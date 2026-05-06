<?php

namespace App\Exports\Sheets;

use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SchedulesSheet
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    public function render(Worksheet $sheet): void
    {
        $sheet->setCellValue('A1', 'JADWAL KEGIATAN BULAN INI');
        $sheet->getStyle('A1')->getFont()->setBold(true);

        $sheet->setCellValue('A3', 'Kegiatan');
        $sheet->setCellValue('B3', 'Tanggal');
        $sheet->setCellValue('C3', 'Lokasi');
        $sheet->setCellValue('D3', 'Status');
        $sheet->getStyle('A3:D3')->getFont()->setBold(true);

        $row = 4;
        foreach ($this->reportData['schedules'] as $schedule) {
            $sheet->setCellValue('A'.$row, $schedule['title']);
            $sheet->setCellValue('B'.$row, Carbon::parse($schedule['date'])->format('d/m/Y'));
            $sheet->setCellValue('C'.$row, $schedule['location']);
            $sheet->setCellValue('D'.$row, $schedule['status']);
            $row++;
        }

        foreach (range('A', 'D') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
