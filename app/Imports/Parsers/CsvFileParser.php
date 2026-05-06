<?php

namespace App\Imports\Parsers;

use App\Contracts\FileParserInterface;

/**
 * Parses CSV files (comma, semicolon, or tab-delimited) into a 2D array.
 *
 * Handles:
 * - UTF-8 BOM stripping
 * - Encoding detection & conversion (Windows-1252, ISO-8859-1 → UTF-8)
 * - Auto-detection of delimiter (tab / semicolon / comma)
 * - Skipping fully empty rows
 */
class CsvFileParser implements FileParserInterface
{
    /**
     * {@inheritDoc}
     */
    public function parse(string $path): array
    {
        $content = $this->readContent($path);
        $content = $this->normalizeEncoding($content);
        $content = $this->normalizeLineEndings($content);

        $delimiter = $this->detectDelimiter($content);

        return $this->parseLines($content, $delimiter);
    }

    // ── Private helpers ───────────────────────────────────────────────

    private function readContent(string $path): string
    {
        $content = file_get_contents($path);
        if ($content === false) {
            throw new \RuntimeException('Tidak dapat membaca file CSV.');
        }

        // Strip UTF-8 BOM
        return preg_replace('/^\xEF\xBB\xBF/', '', $content);
    }

    private function normalizeEncoding(string $content): string
    {
        $encoding = mb_detect_encoding($content, ['UTF-8', 'ISO-8859-1', 'Windows-1252'], true);
        if ($encoding && $encoding !== 'UTF-8') {
            $content = mb_convert_encoding($content, 'UTF-8', $encoding);
        }

        return $content;
    }

    private function normalizeLineEndings(string $content): string
    {
        return str_replace(["\r\n", "\r"], "\n", $content);
    }

    private function detectDelimiter(string $content): string
    {
        $firstLine = strtok($content, "\n");
        $tabCount = substr_count($firstLine, "\t");
        $semiCount = substr_count($firstLine, ';');
        $commaCount = substr_count($firstLine, ',');

        if ($tabCount >= $semiCount && $tabCount >= $commaCount) {
            return "\t";
        }

        return $semiCount > $commaCount ? ';' : ',';
    }

    private function parseLines(string $content, string $delimiter): array
    {
        $rows = [];
        $lines = explode("\n", $content);

        foreach ($lines as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $row = $delimiter === "\t"
                ? array_map('trim', explode("\t", $line))
                : str_getcsv($line, $delimiter, '"', '\\');

            // Skip fully empty rows
            if (count(array_filter($row, static fn ($v) => trim($v) !== '')) === 0) {
                continue;
            }

            $rows[] = $row;
        }

        return $rows;
    }
}
