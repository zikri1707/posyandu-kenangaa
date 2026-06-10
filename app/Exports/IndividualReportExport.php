<?php
namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class IndividualReportExport
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
        $spreadsheet = new Spreadsheet();

        // Remove default sheet
        $spreadsheet->removeSheetByIndex(0);

        // Sheet 1: Ringkasan & Profil
        $this->renderSummarySheet($spreadsheet->createSheet()->setTitle('Ringkasan & Profil'));

        // Sheet 2: Riwayat Pengukuran
        $this->renderHistorySheet($spreadsheet->createSheet()->setTitle('Riwayat Pengukuran'));

        // Sheet 3: Imunisasi & Vitamin
        $this->renderImmunizationSheet($spreadsheet->createSheet()->setTitle('Imunisasi & Vitamin'));

        // Set active sheet to the first one
        $spreadsheet->setActiveSheetIndex(0);

        return $spreadsheet;
    }

    /**
     * Export to file.
     */
    public function export(string $filePath): void
    {
        $spreadsheet = $this->generate();
        $writer = new Xlsx($spreadsheet);
        $writer->save($filePath);
    }

    /**
     * Render Sheet 1: Summary & Profil
     */
    protected function renderSummarySheet($sheet): void
    {
        $patient = $this->reportData['patient'];
        
        // Disable gridlines setting
        $sheet->setShowGridLines(true);

        // Style presets
        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 11],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        $titleStyle = [
            'font' => ['bold' => true, 'size' => 16, 'color' => ['rgb' => '0F172A']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        // Title Block
        $sheet->setCellValue('A2', 'RAPOR PERKEMBANGAN & KESEHATAN INDIVIDU');
        $sheet->mergeCells('A2:F2');
        $sheet->getStyle('A2')->applyFromArray($titleStyle);

        $sheet->setCellValue('A3', 'Posyandu: ' . $patient['posyandu_name'] . ' | Periode: ' . $this->reportData['period_label']);
        $sheet->mergeCells('A3:F3');
        $sheet->getStyle('A3')->applyFromArray([
            'font' => ['italic' => true, 'size' => 10, 'color' => ['rgb' => '475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        // Section A: BIODATA WARGA
        $sheet->setCellValue('A5', 'BIODATA WARGA');
        $sheet->mergeCells('A5:F5');
        $sheet->getStyle('A5')->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']]
        ]);

        $biodataFields = [
            ['Nama Lengkap', $patient['full_name'], 'NIK', $patient['id_number']],
            ['Kategori', str_replace('_', ' ', ucfirst($patient['category'])), 'Jenis Kelamin', $patient['gender'] == 'L' || $patient['gender'] == 'M' ? 'Laki-laki' : 'Perempuan'],
            ['Tanggal Lahir', $patient['birth_date'], 'Usia', $patient['age']],
            ['Nama Ayah', $patient['father_name'], 'Nama Ibu', $patient['mother_name']],
            ['Alamat', $patient['address'], 'No. Telepon', $patient['phone_number']],
        ];

        $row = 6;
        foreach ($biodataFields as $field) {
            $sheet->setCellValue('A' . $row, $field[0]);
            $sheet->setCellValue('B' . $row, ': ' . $field[1]);
            $sheet->setCellValue('D' . $row, $field[2]);
            $sheet->setCellValue('E' . $row, ': ' . $field[3]);
            
            // Format labels bold
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('D' . $row)->getFont()->setBold(true);
            $row++;
        }

        // Section B: RINGKASAN DATA
        $row++;
        $sheet->setCellValue('A' . $row, 'RINGKASAN PERKEMBANGAN PERIODE INI');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row)->applyFromArray([
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0F766E']]
        ]);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Kunjungan');
        $sheet->setCellValue('B' . $row, ': ' . count($this->reportData['raw_records']) . ' Kali');
        
        $lastWeight = '-';
        $lastHeight = '-';
        $lastStatus = '-';
        if (!empty($this->reportData['raw_records'])) {
            $lastRec = end($this->reportData['raw_records']);
            $lastWeight = $lastRec['weight'] . ' kg';
            $lastHeight = $lastRec['height'] . ' cm';
            $lastStatus = $lastRec['nutrition_status'] ?? '-';
        }

        $row++;
        $sheet->setCellValue('A' . $row, 'Berat Badan Terakhir');
        $sheet->setCellValue('B' . $row, ': ' . $lastWeight);
        $sheet->setCellValue('D' . $row, 'Tinggi Badan Terakhir');
        $sheet->setCellValue('E' . $row, ': ' . $lastHeight);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);
        $sheet->getStyle('D' . $row)->getFont()->setBold(true);

        $row++;
        $sheet->setCellValue('A' . $row, 'Status Gizi Terakhir');
        $sheet->setCellValue('B' . $row, ': ' . $lastStatus);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true);

        // Auto column widths
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Render Sheet 2: Visit History
     */
    protected function renderHistorySheet($sheet): void
    {
        $sheet->setShowGridLines(true);

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER, 'vertical' => Alignment::VERTICAL_CENTER, 'wrapText' => true]
        ];

        // Headers
        $headers = [
            'A3' => 'Bulan Periode',
            'B3' => 'Tgl Kunjungan',
            'C3' => 'Berat (kg)',
            'D3' => 'Tinggi (cm)',
            'E3' => 'LILA (cm)',
            'F3' => 'Lingkar Kepala (cm)',
            'G3' => 'Status Gizi (BB/U)',
            'H3' => 'Status Stunting',
            'I3' => 'Tren Gizi',
            'J3' => 'Pemberian PMT',
            'K3' => 'Vaksin Diberikan',
            'L3' => 'Catatan / Keluhan'
        ];

        $sheet->setCellValue('A1', 'TABEL RIWAYAT PERKEMBANGAN BULANAN');
        $sheet->mergeCells('A1:L1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        foreach ($headers as $cell => $text) {
            $sheet->setCellValue($cell, $text);
            $sheet->getStyle($cell)->applyFromArray($headerStyle);
        }
        $sheet->getRowDimension(3)->setRowHeight(28);

        $row = 4;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];

        foreach ($this->reportData['monthly_records'] as $slot) {
            $period = $slot['period'];
            $record = $slot['record'];

            $sheet->setCellValue('A' . $row, $period['label']);
            
            if ($record) {
                $sheet->setCellValue('B' . $row, $record['visit_date']);
                $sheet->setCellValue('C' . $row, $record['weight'] > 0 ? (float) $record['weight'] : '-');
                $sheet->setCellValue('D' . $row, $record['height'] > 0 ? (float) $record['height'] : '-');
                $sheet->setCellValue('E' . $row, $record['upper_arm_circumference'] > 0 ? (float) $record['upper_arm_circumference'] : '-');
                $sheet->setCellValue('F' . $row, $record['head_circumference'] > 0 ? (float) $record['head_circumference'] : '-');
                $sheet->setCellValue('G' . $row, $record['nutrition_status'] ?? '-');
                $sheet->setCellValue('H' . $row, $record['stunting_status'] ?? '-');
                $sheet->setCellValue('I' . $row, ucfirst($record['nutrition_trend'] ?? '-'));
                
                // PMT / exclusive BF
                $sheet->setCellValue('J' . $row, isset($record['pmt_given']) ? $record['pmt_given'] : '-');
                $sheet->setCellValue('K' . $row, $record['vaccine_name'] ?? '-');
                
                $note = $record['complaint'] ?? '';
                if ($record['health_note']) {
                    $note .= ($note ? '; ' : '') . $record['health_note'];
                }
                $sheet->setCellValue('L' . $row, $note ?: '-');
            } else {
                $sheet->setCellValue('B' . $row, 'Tidak Hadir');
                $sheet->mergeCells('B' . $row . ':L' . $row);
                $sheet->getStyle('B' . $row)->applyFromArray([
                    'font' => ['italic' => true, 'color' => ['rgb' => '94A3B8']],
                    'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
                ]);
            }
            
            $sheet->getStyle('A' . $row . ':L' . $row)->applyFromArray($borderStyle);
            $row++;
        }

        // Auto widths
        foreach (range('A', 'L') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }

    /**
     * Render Sheet 3: Vaccines & Vitamins
     */
    protected function renderImmunizationSheet($sheet): void
    {
        $sheet->setShowGridLines(true);

        $headerStyle = [
            'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF'], 'size' => 10],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '475569']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ];

        // Section 1: Imunisasi
        $sheet->setCellValue('A2', 'KARTU STATUS IMUNISASI WAKIB');
        $sheet->mergeCells('A2:C2');
        $sheet->getStyle('A2')->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '334155']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $sheet->setCellValue('A3', 'Kelompok Usia');
        $sheet->setCellValue('B3', 'Vaksin');
        $sheet->setCellValue('C3', 'Status');
        
        $sheet->getStyle('A3:C3')->applyFromArray($headerStyle);

        $row = 4;
        $borderStyle = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'E2E8F0'],
                ],
            ],
        ];

        foreach ($this->reportData['immunization_status'] as $group) {
            foreach ($group['vaccines'] as $i => $vax) {
                $sheet->setCellValue('A' . $row, $i === 0 ? $group['label'] : '');
                $sheet->setCellValue('B' . $row, $vax['name'] . ' (' . $vax['prevent'] . ')');
                $sheet->setCellValue('C' . $row, $vax['received'] ? 'Sudah Diberikan' : ($vax['is_due'] ? 'Belum (Jatuh Tempo)' : 'Belum Waktunya'));
                
                // Color status column
                if ($vax['received']) {
                    $sheet->getStyle('C' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('047857')); // Green
                    $sheet->getStyle('C' . $row)->getFont()->setBold(true);
                } elseif ($vax['is_due']) {
                    $sheet->getStyle('C' . $row)->getFont()->setColor(new \PhpOffice\PhpSpreadsheet\Style\Color('B45309')); // Amber
                    $sheet->getStyle('C' . $row)->getFont()->setBold(true);
                }

                $sheet->getStyle('A' . $row . ':C' . $row)->applyFromArray($borderStyle);
                $row++;
            }
        }

        // Section 2: Vitamin A
        $vRow = 2;
        $sheet->setCellValue('E' . $vRow, 'RIWAYAT PEMBERIAN VITAMIN A & OBAT CACING PERIODE INI');
        $sheet->mergeCells('E' . $vRow . ':G' . $vRow);
        $sheet->getStyle('E' . $vRow)->applyFromArray([
            'font' => ['bold' => true, 'size' => 12],
            'fill' => ['fillType' => Fill::FILL_SOLID, 'startColor' => ['rgb' => '0D9488']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
        ]);

        $vRow++;
        $sheet->setCellValue('E' . $vRow, 'Tanggal');
        $sheet->setCellValue('F' . $vRow, 'Jenis Vitamin / Dosis');
        $sheet->setCellValue('G' . $vRow, 'Warna Kapsul');
        $sheet->getStyle('E' . $vRow . ':G' . $vRow)->applyFromArray($headerStyle);

        $vRow++;
        if (!empty($this->reportData['vitamins_in_period'])) {
            foreach ($this->reportData['vitamins_in_period'] as $vit) {
                $sheet->setCellValue('E' . $vRow, $vit['date']);
                $sheet->setCellValue('F' . $vRow, $vit['note']);
                $sheet->setCellValue('G' . $vRow, ucfirst($vit['color']));
                
                $sheet->getStyle('E' . $vRow . ':G' . $vRow)->applyFromArray($borderStyle);
                $vRow++;
            }
        } else {
            $sheet->setCellValue('E' . $vRow, 'Tidak ada pemberian vitamin A di periode ini');
            $sheet->mergeCells('E' . $vRow . ':G' . $vRow);
            $sheet->getStyle('E' . $vRow)->applyFromArray([
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER]
            ]);
            $sheet->getStyle('E' . $vRow . ':G' . $vRow)->applyFromArray($borderStyle);
        }

        // Auto widths
        foreach (range('A', 'G') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
    }
}
