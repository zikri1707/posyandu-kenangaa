<?php

namespace App\Imports;

use App\Contracts\FileParserInterface;
use App\Imports\Parsers\CsvFileParser;
use App\Imports\Parsers\XlsxFileParser;
use App\Imports\Processors\PatientRowProcessor;
use App\Imports\Resolvers\HeaderResolver;
use Illuminate\Http\UploadedFile;

/**
 * PatientImport — Orchestrator (Clean Code, OOP, SRP).
 *
 * Coordinates the full import pipeline without containing any
 * parsing, resolution, or processing logic itself:
 *
 *   UploadedFile
 *     → FileParser (CSV / XLSX)
 *     → HeaderResolver (detect + normalize + alias)
 *     → PatientRowProcessor (validate → Patient → MedicalRecord)
 *
 * Supported formats: CSV (comma / semicolon / tab), XLSX.
 *
 * @see CsvFileParser
 * @see XlsxFileParser
 * @see HeaderResolver
 * @see PatientRowProcessor
 */
class PatientImport
{
    // ── Counters & diagnostics (read after import()) ──────────────────

    /** Number of new patients created. */
    public int $imported = 0;

    /** Number of rows that were skipped due to validation errors or being empty. */
    public int $skipped = 0;

    /** Number of MedicalRecord entries created. */
    public int $recordsImported = 0;

    /** Accumulated warning/error messages. */
    public array $errors = [];

    /** Raw header row found in the file (for debugging). */
    public array $debugHeaders = [];

    // ── Dependencies ──────────────────────────────────────────────────

    private HeaderResolver $headerResolver;

    private PatientRowProcessor $rowProcessor;

    public function __construct(int $posyanduId, int $userId)
    {
        $this->headerResolver = new HeaderResolver;
        $this->rowProcessor = new PatientRowProcessor($posyanduId, $userId);
    }

    // ── Public API ────────────────────────────────────────────────────

    /**
     * Run the full import pipeline for the given uploaded file.
     *
     * @throws \InvalidArgumentException for unsupported or unconvertible formats.
     * @throws \RuntimeException for unreadable / corrupt files.
     */
    public function import(UploadedFile $file): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        $parser = $this->resolveParser($extension);
        $rows = $parser->parse($file->getRealPath());

        if (empty($rows)) {
            $this->errors[] = 'File kosong atau tidak dapat dibaca.';

            return;
        }

        [$dataRows, $colMap] = $this->resolveHeaders($rows);

        $this->rowProcessor->processRows($dataRows, $colMap);

        $this->syncCounters();
    }

    // ── Private helpers ───────────────────────────────────────────────

    private function resolveParser(string $extension): FileParserInterface
    {
        return match ($extension) {
            'csv' => new CsvFileParser,
            'xlsx' => new XlsxFileParser,
            'xls' => new \App\Imports\Parsers\XlsFileParser,
            default => throw new \InvalidArgumentException(
                "Format '{$extension}' tidak didukung. Gunakan CSV, XLSX, atau XLS."
            ),
        };
    }

    /**
     * Detect the header row, normalize it, and build the column map.
     *
     * @param  array<int, array<int, string>>  $rows
     * @return array{0: array, 1: array<string, int>}
     */
    private function resolveHeaders(array $rows): array
    {
        $headerRowIndex = $this->headerResolver->findHeaderRowIndex($rows);

        if ($headerRowIndex === null) {
            $headerRowIndex = 0;
            $this->errors[] = 'Peringatan: Header tidak terdeteksi otomatis, menggunakan baris pertama sebagai header.';
        }

        $rawHeaders = $rows[$headerRowIndex];
        $this->debugHeaders = $rawHeaders;

        $normalizedHeaders = $this->headerResolver->normalizeHeaders($rawHeaders);
        $colMap = $this->headerResolver->buildColumnMap($normalizedHeaders);
        $dataRows = array_slice($rows, $headerRowIndex + 1);

        return [$dataRows, $colMap];
    }

    /**
     * Copy counters and errors from the row processor back to this orchestrator.
     */
    private function syncCounters(): void
    {
        $this->imported = $this->rowProcessor->imported;
        $this->skipped = $this->rowProcessor->skipped;
        $this->recordsImported = $this->rowProcessor->recordsImported;
        $this->errors = array_merge($this->errors, $this->rowProcessor->errors);
    }
}
