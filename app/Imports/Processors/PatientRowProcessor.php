<?php

namespace App\Imports\Processors;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Services\NutritionCalculatorService;
use Carbon\Carbon;

/**
 * Processes a single data row from an import file:
 *  1. Validates required fields (name, birth date).
 *  2. Detects duplicate patients (by NIK or name + birth date).
 *  3. Creates or updates a Patient record.
 *  4. Creates a MedicalRecord for the row's measurement data (if present).
 *
 * All counters and errors are accumulated and readable after processing.
 */
class PatientRowProcessor
{
    public int $imported = 0;

    public int $skipped = 0;

    public int $recordsImported = 0;

    public array $errors = [];

    private int $posyanduId;

    private int $userId;

    private NutritionCalculatorService $nutritionService;

    public function __construct(int $posyanduId, int $userId)
    {
        $this->posyanduId = $posyanduId;
        $this->userId = $userId;
        $this->nutritionService = app(NutritionCalculatorService::class);
    }

    // ── Public API ────────────────────────────────────────────────────

    /**
     * Process all data rows.
     *
     * @param  array<int, array<int, string>>  $rows
     * @param  array<string, int>  $colMap  Column-name → index map
     */
    public function processRows(array $rows, array $colMap): void
    {
        foreach ($rows as $index => $row) {
            $this->processRow($row, $colMap, $index + 2);
        }
    }

    // ── Row processing ────────────────────────────────────────────────

    private function processRow(array $row, array $colMap, int $rowNum): void
    {
        $get = $this->makeGetter($row, $colMap);

        // Extract fields
        $nama = $get('nama_anak');
        $nik = $get('nik');
        $tglLahir = $get('tgl_lahir');
        $jk = $get('jk');
        $namaOrtu = $get('nm_ortu');
        $rt = $get('rt');
        $rw = $get('rw');
        $alamat = $get('alamat');

        // New fields
        $categoryInput = $get('category');
        $husbandName = $get('husband_name');
        $fatherName = $get('father_name');
        $motherName = $get('mother_name');
        $placeOfBirth = $get('place_of_birth');
        $phoneNumber = $get('phone_number');
        $historicalDiseases = $get('historical_diseases');
        $isPregnantInput = $get('is_pregnant');

        // Medical fields
        $tglUkur = $get('tanggal_ukur');
        $berat = $get('berat');
        $tinggi = $get('tinggi');
        $lingkarKepala = $get('lingkar_kepala');
        $vitamin = $get('vitamin');
        $imunisasi = $get('imunisasi');

        // Validate required fields
        if ($nama === '' && $nik === '') {
            $this->skipped++;

            return;
        }
        if ($nama === '') {
            $this->errors[] = "Baris {$rowNum}: Nama anak kosong, dilewati.";
            $this->skipped++;

            return;
        }

        $birthDate = $this->parseDate($tglLahir);
        if ($birthDate === false) {
            $this->errors[] = "Baris {$rowNum}: Format tanggal lahir '{$tglLahir}' tidak valid untuk '{$nama}'.";
            $this->skipped++;

            return;
        }

        $gender = $this->normalizeGender($jk);
        if ($gender === null) {
            $this->errors[] = "Baris {$rowNum}: Jenis kelamin '{$jk}' tidak valid atau kosong untuk '{$nama}'. Gunakan L atau P.";
            $this->skipped++;

            return;
        }
        $fullAddress = $this->buildAddress($alamat, $rt, $rw);
        $nikClean = preg_replace('/[^0-9]/', '', $nik); // Strip everything except digits
        $hasValidNik = strlen($nikClean) >= 15 && strlen($nikClean) <= 17; // More lenient length check

        if (! $hasValidNik) {
            if ($nik === '') {
                $this->errors[] = "Baris {$rowNum}: NIK kosong untuk '{$nama}'. Sistem otomatis membuatkan NIK sementara.";
            } else {
                $this->errors[] = "Baris {$rowNum}: NIK '{$nik}' tidak sesuai format 16 digit untuk '{$nama}'. Sistem otomatis membuatkan NIK sementara.";
            }
        }

        try {
            $patient = $this->resolvePatient(
                $hasValidNik, $nikClean, $nama, $birthDate, $namaOrtu, $fullAddress, $gender,
                $categoryInput, $husbandName, $fatherName, $motherName, $placeOfBirth, $phoneNumber,
                $historicalDiseases, $isPregnantInput, $rt, $rw
            );

            if ($berat !== '' || $tinggi !== '') {
                $this->saveMedicalRecord($patient, $berat, $tinggi, $lingkarKepala, $vitamin, $imunisasi, $birthDate, $tglUkur, $gender, $rowNum);
            }
        } catch (\Exception $e) {
            $this->errors[] = "Baris {$rowNum}: Gagal menyimpan '{$nama}' — ".$e->getMessage();
            $this->skipped++;
        }
    }

    // ── Patient resolution ────────────────────────────────────────────

    private function resolvePatient(
        bool $hasValidNik,
        string $nikClean,
        string $nama,
        ?Carbon $birthDate,
        string $namaOrtu,
        string $fullAddress,
        ?string $gender,
        string $categoryInput = '',
        string $husbandName = '',
        string $fatherName = '',
        string $motherName = '',
        string $placeOfBirth = '',
        string $phoneNumber = '',
        string $historicalDiseases = '',
        string $isPregnantInput = '',
        string $rt = '',
        string $rw = ''
    ): Patient {
        $existing = $this->findExistingPatient($hasValidNik, $nikClean, $nama, $birthDate);

        // Normalize pregnant status
        $isPregnant = false;
        if ($isPregnantInput !== '') {
            $isPregnant = $this->parseBool($isPregnantInput);
        }

        // Determine category
        $category = $this->normalizeCategory($categoryInput, $birthDate, $isPregnant);

        if ($existing) {
            $updateData = [
                'parent_name' => $namaOrtu ?: $existing->parent_name,
                'address' => $fullAddress ?: $existing->address,
                'gender' => $gender ?? $existing->gender,
                'category' => $category,
                'is_pregnant' => $isPregnant ?: $existing->is_pregnant,
            ];

            if ($husbandName !== '') $updateData['husband_name'] = $husbandName;
            if ($fatherName !== '') $updateData['father_name'] = $fatherName;
            if ($motherName !== '') $updateData['mother_name'] = $motherName;
            if ($placeOfBirth !== '') $updateData['place_of_birth'] = $placeOfBirth;
            if ($phoneNumber !== '') $updateData['phone_number'] = $phoneNumber;
            if ($historicalDiseases !== '') $updateData['historical_diseases'] = $historicalDiseases;
            if ($rt !== '') $updateData['rt_domisili'] = $rt;
            if ($rw !== '') $updateData['dusun_rt_rw'] = $rw;

            // If existing has a placeholder NIK (9999...) and we now have a valid one, update it
            if (str_starts_with($existing->id_number, '9999') && $hasValidNik) {
                $updateData['id_number'] = $nikClean;
            }

            $existing->update($updateData);

            return $existing;
        }

        if (! $hasValidNik) {
            $nikClean = $this->generatePlaceholderNik();
        }

        $createData = [
            'posyandu_id' => $this->posyanduId,
            'id_number' => $nikClean,
            'full_name' => $nama,
            'birth_date' => $birthDate,
            'gender' => $gender,
            'category' => $category,
            'parent_name' => $namaOrtu,
            'address' => $fullAddress,
            'phone_number' => $phoneNumber,
            'husband_name' => $husbandName,
            'father_name' => $fatherName,
            'mother_name' => $motherName,
            'place_of_birth' => $placeOfBirth,
            'historical_diseases' => $historicalDiseases,
            'is_pregnant' => $isPregnant,
            'rt_domisili' => $rt,
            'dusun_rt_rw' => $rw,
        ];

        $patient = Patient::create($createData);

        $this->imported++;

        return $patient;
    }

    private function findExistingPatient(
        bool $hasValidNik,
        string $nikClean,
        string $nama,
        ?Carbon $birthDate
    ): ?Patient {
        if ($hasValidNik) {
            $found = Patient::where('id_number_hash', Patient::generateBlindIndex($nikClean))
                ->where('posyandu_id', $this->posyanduId)
                ->first();
            if ($found) {
                return $found;
            }
        }

        if ($birthDate instanceof Carbon) {
            return Patient::where('full_name', $nama)
                ->where('birth_date', $birthDate->format('Y-m-d'))
                ->where('posyandu_id', $this->posyanduId)
                ->first();
        }

        return null;
    }

    // ── Medical record ────────────────────────────────────────────────

    private function saveMedicalRecord(
        Patient $patient,
        string $berat,
        string $tinggi,
        string $lingkarKepala,
        string $vitamin,
        string $imunisasi,
        ?Carbon $birthDate,
        string $tglUkur,
        ?string $gender,
        int $rowNum
    ): void {
        $visitDate = $this->parseDate($tglUkur);
        if (! ($visitDate instanceof Carbon)) {
            $visitDate = now();
        }

        $alreadyExists = MedicalRecord::where('patient_id', $patient->id)
            ->where('visit_date', $visitDate->format('Y-m-d'))
            ->exists();

        if ($alreadyExists) {
            return;
        }

        $weightVal = $this->parseDecimal($berat);
        $heightVal = $this->parseDecimal($tinggi);
        $lkVal = $this->parseDecimal($lingkarKepala);
        $vitaminA = $this->parseBool($vitamin);

        [$zScore, $nutritionStatus] = $this->calcNutrition($weightVal, $heightVal, $birthDate, $visitDate, $gender);

        MedicalRecord::create([
            'patient_id' => $patient->id,
            'user_id' => $this->userId,
            'visit_date' => $visitDate->format('Y-m-d'),
            'weight' => $weightVal,
            'height' => $heightVal,
            'head_circumference' => $lkVal,
            'immunization' => $imunisasi,
            'vitamin_a' => $vitaminA,
            'pill_fe' => false,
            'z_score' => $zScore,
            'nutrition_status' => $nutritionStatus,
            'complaint' => '—',
            'diagnosis' => 'Sehat',
        ]);

        $this->recordsImported++;
    }

    private function calcNutrition(
        ?float $weight,
        ?float $height,
        ?Carbon $birthDate,
        Carbon $visitDate,
        ?string $gender
    ): array {
        if (! $weight || ! ($birthDate instanceof Carbon)) {
            return [null, null];
        }

        $ageMonths = (int) $birthDate->diffInMonths($visitDate);
        if ($ageMonths < 0 || $ageMonths > 59) {
            return [null, null];
        }

        $result = $this->nutritionService->calculate(
            $weight,
            $height ?? 0,
            $ageMonths,
            $gender ?? 'L'
        );

        return [$result['z_score'], $result['status']];
    }

    // ── Scalar helpers ────────────────────────────────────────────────

    /**
     * Build a getter closure that reads a named column from a row.
     *
     * @param  array<int, string>  $row
     * @param  array<string, int>  $colMap
     */
    private function makeGetter(array $row, array $colMap): \Closure
    {
        return static function (string $key) use ($row, $colMap): string {
            $idx = $colMap[$key] ?? null;
            if ($idx === null) {
                return '';
            }

            $val = $row[$idx] ?? '';

            // Handle scientific notation (e.g. 3.27E+15) commonly found in Excel for NIK
            // We use number_format without decimals to get the full string representation
            if (is_numeric($val) && (str_contains(strtoupper((string) $val), 'E') || strlen((string) $val) >= 15)) {
                $val = number_format((float) $val, 0, '', '');
            }

            return trim((string) $val);
        };
    }

    private function buildAddress(string $alamat, string $rt, string $rw): string
    {
        if ($rt === '' && $rw === '') {
            return $alamat;
        }

        $rtRw = 'RT '.str_pad($rt, 2, '0', STR_PAD_LEFT)
              .' / RW '.str_pad($rw, 2, '0', STR_PAD_LEFT);

        return $alamat !== '' ? "{$alamat}, {$rtRw}" : $rtRw;
    }

    private function parseDate(mixed $value): Carbon|null|false
    {
        if ($value === null || trim((string) $value) === '' || trim((string) $value) === '-') {
            return null;
        }
        $str = trim((string) $value);

        try {
            // Excel serial number (e.g. 44380)
            if (is_numeric($str) && (float) $str > 1000) {
                $unixTs = ((float) $str - 25569) * 86400;

                return Carbon::createFromTimestamp((int) $unixTs)->startOfDay();
            }
            // "6 Aug 2022" / "6 August 2022"
            if (preg_match('/^\d{1,2}\s+\w+\s+\d{4}$/', $str)) {
                foreach (['j M Y', 'j F Y', 'd M Y', 'd F Y'] as $fmt) {
                    try {
                        return Carbon::createFromFormat($fmt, $str)->startOfDay();
                    } catch (\Exception) {
                    }
                }
            }

            return Carbon::parse($str)->startOfDay();
        } catch (\Exception) {
            return false;
        }
    }

    private function parseDecimal(string $value): ?float
    {
        $value = trim($value);
        if ($value === '' || $value === '-') {
            return null;
        }
        $clean = str_replace(',', '.', $value);

        return is_numeric($clean) ? (float) $clean : null;
    }

    private function parseBool(string $value): bool
    {
        return in_array(
            strtolower(trim($value)),
            ['1', 'ya', 'yes', 'true', 'v', '✓', 'ada', 'diberikan'],
            true
        );
    }

    private function normalizeGender(string $jk): ?string
    {
        $jk = strtoupper(trim($jk));
        $clean = str_replace([' ', '-', '.', '_'], '', $jk);

        if (in_array($clean, ['L', 'LAKI', 'LAKILAKI', 'MALE', 'M', 'PRIA', 'LAKI2', 'COWOK'], true)) {
            return 'L';
        }
        if (in_array($clean, ['P', 'PEREMPUAN', 'FEMALE', 'F', 'WANITA', 'CEWEK'], true)) {
            return 'P';
        }

        return null;
    }

    private function generatePlaceholderNik(): string
    {
        do {
            $nik = '9999'
                .str_pad((string) $this->posyanduId, 4, '0', STR_PAD_LEFT)
                .str_pad((string) rand(0, 99999999), 8, '0', STR_PAD_LEFT);
        } while (Patient::where('id_number_hash', Patient::generateBlindIndex($nik))->exists());

        return $nik;
    }

    private function normalizeCategory(string $catInput, ?Carbon $birthDate, bool $isPregnant): string
    {
        $cat = strtolower(trim($catInput));
        if (in_array($cat, ['ibu hamil', 'ibu_hamil', 'hamil', 'pregnant']) || $isPregnant) {
            return 'ibu_hamil';
        }
        if (in_array($cat, ['lansia', 'elderly'])) {
            return 'lansia';
        }
        if (in_array($cat, ['balita', 'toddler'])) {
            return 'balita';
        }
        if (in_array($cat, ['bayi', 'baby'])) {
            return 'bayi';
        }
        if (in_array($cat, ['baduta'])) {
            return 'baduta';
        }
        if (in_array($cat, ['anak sekolah', 'anak_sekolah', 'anak'])) {
            return 'anak_sekolah';
        }
        if (in_array($cat, ['remaja', 'teenager'])) {
            return 'remaja';
        }
        if (in_array($cat, ['umum', 'general'])) {
            return 'umum';
        }

        return $this->determineCategory($birthDate);
    }

    /**
     * Menentukan kategori berdasarkan usia anak dalam bulan.
     */
    private function determineCategory(?Carbon $birthDate): string
    {
        if (! $birthDate) {
            return 'umum';
        }

        $ageMonths = (int) $birthDate->diffInMonths(now());

        if ($ageMonths <= 11) {
            return 'bayi';
        }
        if ($ageMonths <= 23) {
            return 'baduta';
        }
        if ($ageMonths <= 59) {
            return 'balita';
        }
        if ($ageMonths <= 119) {
            return 'anak_sekolah';
        } // 5-9 tahun
        if ($ageMonths <= 227) {
            return 'remaja';
        }      // 10-18 tahun
        if ($ageMonths >= 720) {
            return 'lansia';
        }      // 60 tahun+

        return 'umum';
    }
}
