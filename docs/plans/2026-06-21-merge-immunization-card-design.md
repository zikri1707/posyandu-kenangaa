# Design: Merging Immunization Coverage Status with Immunization Card

We are merging the "Capaian Imunisasi" stats block with the "Kartu Imunisasi" card inside the child patient profile page.

## Proposed Layout Changes

### 1. Kartu Imunisasi (`details/balita.blade.php`)
- Add calculation logic at the top of the file to compute:
  - `$receivedCount`: Number of due vaccines already received by the child.
  - `$totalCount`: Total number of due vaccines for the child's age (defaults to 12 if 0).
- Enhance the header of the "Kartu Imunisasi" card by adding a premium widget on the right side.
- This widget will show:
  - Numeric achievement: `{{ $receivedCount }} / {{ $totalCount }} Vaksin`
  - Status capsule: `Lengkap` (green) if `$receivedCount >= $totalCount` else `Belum Lengkap` (amber).

### 2. Growth Chart Quick Stats (`growth-chart.blade.php`)
- Remove the "Capaian Imunisasi" stats card from the Quick Stats row.
- Change the columns class of the stats row from `grid-cols-1 md:grid-cols-3` to `grid-cols-1 md:grid-cols-2` to accommodate the remaining two cards gracefully.
- Remove the calculation logic of `$receivedCount` and `$totalCount` inside `growth-chart.blade.php` to clean up the code.

## Verification
- Visit a child (balita) patient profile page.
- Verify that the stats row shows only two cards ("Digital Pass" and "Insight Pertumbuhan").
- Verify that the "Kartu Imunisasi" card header contains the new immunization coverage count and completeness status badge.
