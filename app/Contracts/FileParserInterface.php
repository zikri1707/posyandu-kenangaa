<?php

declare(strict_types=1);

namespace App\Contracts;

/**
 * Contract for file parsers used in the import pipeline.
 *
 * Implementations must accept a file path and return a
 * two-dimensional (row × column) array of raw string values.
 */
interface FileParserInterface
{
    /**
     * Parse the given file and return its rows.
     *
     * @param  string  $path  Absolute path to the file on disk.
     * @return array<int, array<int, string>>
     *
     * @throws \RuntimeException when the file cannot be read or parsed.
     */
    public function parse(string $path): array;
}
