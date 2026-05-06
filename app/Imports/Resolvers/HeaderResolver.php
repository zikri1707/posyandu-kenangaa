<?php

namespace App\Imports\Resolvers;

/**
 * Responsible for:
 * 1. Detecting the header row index inside a raw 2-D data array.
 * 2. Normalizing raw header strings to clean snake_case keys.
 * 3. Building a column-index map that also resolves known column aliases.
 */
class HeaderResolver
{
    /**
     * Known aliases for canonical column names.
     * Key = canonical name; value = list of acceptable alternatives.
     *
     * @var array<string, list<string>>
     */
    private const ALIASES = [
        'nama_anak' => ['nama', 'full_name', 'nama_lengkap'],
        'nik' => ['nomor_nik', 'no_nik', 'nik_balita', 'nomor_induk_kependudukan', 'id_number', 'nik_16_digit'],
        'tgl_lahir' => ['tanggal_lahir', 'birth_date', 'tgl_lahir_anak'],
        'jk' => ['jenis_kelamin', 'gender', 'kelamin'],
        'nm_ortu' => ['nama_ortu', 'parent_name', 'orang_tua', 'nama_orang_tua'],
        'tanggal_ukur' => ['tgl_ukur', 'tanggalukur', 'tanggal_periksa', 'tgl_periksa'],
        'berat' => ['berat_badan', 'bb', 'weight'],
        'tinggi' => ['tinggi_badan', 'tb', 'height', 'panjang'],
        'lingkar_kepala' => ['lk', 'head_circumference', 'lingkarkepala'],
        'vitamin' => ['vitamin_a', 'vita', 'vit_a'],
        'imunisasi' => ['immunization', 'vaksin'],
    ];

    /** Keywords used to detect which row is the header. */
    private const HEADER_KEYWORDS = [
        'nama_anak', 'nama anak', 'nama', 'full_name', 'nik', 'tgl_lahir', 'tgl lahir',
    ];

    // ── Public API ────────────────────────────────────────────────────

    /**
     * Find the first row index that looks like a header row.
     * Returns null when no header can be reliably detected.
     *
     * @param  array<int, array<int, string>>  $rows
     */
    public function findHeaderRowIndex(array $rows): ?int
    {
        foreach ($rows as $i => $row) {
            foreach ($row as $cell) {
                $cellLower = strtolower(trim((string) $cell));
                foreach (self::HEADER_KEYWORDS as $keyword) {
                    if ($cellLower === $keyword || str_contains($cellLower, $keyword)) {
                        return $i;
                    }
                }
            }
        }

        return null;
    }

    /**
     * Normalize raw header strings to clean snake_case identifiers.
     *
     * @param  array<int, string>  $rawHeaders
     * @return array<int, string>
     */
    public function normalizeHeaders(array $rawHeaders): array
    {
        return array_map(function (string $h): string {
            $h = trim($h);
            $h = strtolower($h);
            $h = preg_replace('/[\s\-\.\/]+/', '_', $h);  // spaces/dashes/dots/slashes → _
            $h = preg_replace('/[^a-z0-9_]/', '', $h);   // strip non-alphanumeric

            return trim($h, '_');
        }, $rawHeaders);
    }

    /**
     * Build a column-index map from normalized headers, then patch it
     * by resolving canonical aliases.
     *
     * @param  array<int, string>  $normalizedHeaders
     * @return array<string, int> Map of column name → zero-based column index.
     */
    public function buildColumnMap(array $normalizedHeaders): array
    {
        $map = array_flip($normalizedHeaders);

        foreach (self::ALIASES as $canonical => $alternatives) {
            if (! isset($map[$canonical])) {
                foreach ($alternatives as $alt) {
                    if (isset($map[$alt])) {
                        $map[$canonical] = $map[$alt];
                        break;
                    }
                }
            }
        }

        return $map;
    }
}
