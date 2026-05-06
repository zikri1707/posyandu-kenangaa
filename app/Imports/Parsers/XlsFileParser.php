<?php

namespace App\Imports\Parsers;

use App\Contracts\FileParserInterface;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Parses XLS (Legacy Excel 97-2003) files using PhpSpreadsheet.
 */
class XlsFileParser implements FileParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(string $path): array
    {
        try {
            $spreadsheet = IOFactory::load($path);
            $worksheet = $spreadsheet->getActiveSheet();
            $data = $worksheet->toArray('', true, true, true);

            $rows = [];
            foreach ($data as $row) {
                // Convert associative array from toArray() (A => val, B => val) to indexed array
                $rowData = array_values($row);

                // Skip fully empty rows
                if (count(array_filter($rowData, static fn ($v) => trim((string) $v) !== '')) > 0) {
                    $rows[] = $rowData;
                }
            }

            return $rows;
        } catch (\Exception $e) {
            throw new \RuntimeException('Tidak dapat mem-parse file XLS: '.$e->getMessage());
        }
    }
}
