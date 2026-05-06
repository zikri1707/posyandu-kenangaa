<?php

namespace App\Imports\Parsers;

use App\Contracts\FileParserInterface;

/**
 * Parses XLSX files using native PHP ZipArchive + SimpleXML.
 *
 * Does NOT depend on PhpSpreadsheet — works with PHP's built-in extensions only.
 * Handles shared strings, inline strings, boolean cells, and sparse column layouts.
 */
class XlsxFileParser implements FileParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(string $path): array
    {
        [$sharedStrings, $sheetContent] = $this->extractZipContents($path);

        $sheetXml = $this->cleanNamespaces($sheetContent);
        $sheet = $this->parseXml($sheetXml);

        $sheetData = $sheet->sheetData ?? $sheet->worksheet->sheetData ?? null;
        if (! $sheetData) {
            return [];
        }

        return $this->buildRows($sheetData, $sharedStrings);
    }

    // ── Private helpers ───────────────────────────────────────────────

    /**
     * Open the XLSX ZIP and extract shared strings + first sheet XML.
     *
     * @return array{0: array<int,string>, 1: string}
     */
    private function extractZipContents(string $path): array
    {
        $zip = new \ZipArchive;
        if ($zip->open($path) !== true) {
            throw new \RuntimeException('Tidak dapat membuka file XLSX. Pastikan file tidak rusak.');
        }

        $sharedStrings = $this->readSharedStrings($zip);
        $sheetContent = $this->readFirstSheet($zip);

        $zip->close();

        return [$sharedStrings, $sheetContent];
    }

    /**
     * @return array<int, string>
     */
    private function readSharedStrings(\ZipArchive $zip): array
    {
        $strings = [];
        $ssContent = $zip->getFromName('xl/sharedStrings.xml');

        if (! $ssContent) {
            return $strings;
        }

        $ssContent = preg_replace('/xmlns[^=]*="[^"]*"/', '', $ssContent);
        $ss = @simplexml_load_string($ssContent);

        if (! $ss) {
            return $strings;
        }

        foreach ($ss->si as $si) {
            // Concatenate all <r><t> rich-text runs
            $text = '';
            if (isset($si->r)) {
                foreach ($si->r as $r) {
                    $text .= (string) ($r->t ?? '');
                }
            }
            if ($text === '' && isset($si->t)) {
                $text = (string) $si->t;
            }
            $strings[] = $text;
        }

        return $strings;
    }

    private function readFirstSheet(\ZipArchive $zip): string
    {
        for ($i = 1; $i <= 10; $i++) {
            $content = $zip->getFromName("xl/worksheets/sheet{$i}.xml");
            if ($content !== false) {
                return $content;
            }
        }
        throw new \RuntimeException('Sheet tidak ditemukan dalam file XLSX.');
    }

    private function cleanNamespaces(string $xml): string
    {
        $xml = preg_replace('/xmlns[^=]*="[^"]*"/', '', $xml);
        $xml = preg_replace('/<[a-z]+:/', '<', $xml);
        $xml = preg_replace('/<\/[a-z]+:/', '</', $xml);

        return $xml;
    }

    private function parseXml(string $xml): \SimpleXMLElement
    {
        $result = @simplexml_load_string($xml);
        if (! $result) {
            throw new \RuntimeException('Tidak dapat mem-parse file XLSX.');
        }

        return $result;
    }

    /**
     * Build a 2-D array from the sheet data element.
     *
     * @param  array<int, string>  $sharedStrings
     * @return array<int, array<int, string>>
     */
    private function buildRows(\SimpleXMLElement $sheetData, array $sharedStrings): array
    {
        $rows = [];

        foreach ($sheetData->row as $row) {
            $cellValues = [];
            $maxColIdx = -1;

            foreach ($row->c as $cell) {
                $ref = (string) ($cell['r'] ?? '');
                if (! preg_match('/^([A-Z]+)(\d+)$/', $ref, $m)) {
                    continue;
                }

                $colIdx = $this->colLetterToIndex($m[1]);
                $value = $this->resolveCellValue($cell, $sharedStrings);

                $cellValues[$colIdx] = $value;
                if ($colIdx > $maxColIdx) {
                    $maxColIdx = $colIdx;
                }
            }

            // Fill sparse columns with empty strings
            $rowData = [];
            for ($i = 0; $i <= $maxColIdx; $i++) {
                $rowData[] = $cellValues[$i] ?? '';
            }

            // Skip fully empty rows
            if (count(array_filter($rowData, static fn ($v) => trim($v) !== '')) > 0) {
                $rows[] = $rowData;
            }
        }

        return $rows;
    }

    /**
     * @param  array<int, string>  $sharedStrings
     */
    private function resolveCellValue(\SimpleXMLElement $cell, array $sharedStrings): string
    {
        $type = (string) ($cell['t'] ?? '');
        $value = (string) ($cell->v ?? '');

        return match ($type) {
            's' => $sharedStrings[(int) $value] ?? '',
            'inlineStr' => (string) ($cell->is->t ?? ''),
            'b' => $value === '1' ? 'TRUE' : 'FALSE',
            default => $value,
        };
    }

    /**
     * Convert an Excel column letter (A → 0, B → 1, AA → 26) to a zero-based index.
     */
    private function colLetterToIndex(string $col): int
    {
        $col = strtoupper($col);
        $index = 0;
        for ($i = 0, $len = strlen($col); $i < $len; $i++) {
            $index = $index * 26 + (ord($col[$i]) - 64);
        }

        return $index - 1;
    }
}
