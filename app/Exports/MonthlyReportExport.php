<?php

namespace App\Exports;

use App\Exports\Sheets\NutritionDistributionSheet;
use App\Exports\Sheets\RawMedicalRecordsSheet;
use App\Exports\Sheets\ReportSummarySheet;
use App\Exports\Sheets\SchedulesSheet;
use App\Exports\Sheets\VisitsByCategorySheet;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Class Export Laporan Bulanan menggunakan PhpSpreadsheet native.
 * Menggantikan implementasi Maatwebsite/Excel yang rusak.
 */
class MonthlyReportExport
{
    protected array $reportData;

    public function __construct(array $reportData)
    {
        $this->reportData = $reportData;
    }

    /**
     * Generate spreadsheet and return the object.
     */
    public function generate(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;

        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        // Sheet 1: Ringkasan
        $summarySheet = new ReportSummarySheet($this->reportData);
        $summarySheet->render($spreadsheet->createSheet()->setTitle('Ringkasan'));

        // Sheet 2: Kunjungan
        $visitsSheet = new VisitsByCategorySheet($this->reportData);
        $visitsSheet->render($spreadsheet->createSheet()->setTitle('Kunjungan'));

        // Sheet 3: Status Gizi
        $nutritionSheet = new NutritionDistributionSheet($this->reportData);
        $nutritionSheet->render($spreadsheet->createSheet()->setTitle('Status Gizi'));

        // Sheet 4: Jadwal
        $schedulesSheet = new SchedulesSheet($this->reportData);
        $schedulesSheet->render($spreadsheet->createSheet()->setTitle('Jadwal'));

        // Sheet 5: Data Mentah (Request User)
        $rawSheet = new RawMedicalRecordsSheet($this->reportData);
        $rawSheet->render($spreadsheet->createSheet()->setTitle('Data Mentah'));

        // Set active sheet to the first one
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Export to file and return the path.
     */
    public function export(string $filePath): void
    {
        $spreadsheet = $this->generate();
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }
}
