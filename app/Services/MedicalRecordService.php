<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Carbon;

/**
 * Service untuk mengelola logika bisnis rekam medis
 *
 * Menerapkan prinsip:
 * - Single Responsibility Principle
 * - Separation of Concerns
 * - Encapsulation
 */
class MedicalRecordService
{
    protected ActivityLogService $activityLogService;

    protected NutritionCalculatorService $nutritionService;

    protected WhatsAppService $whatsAppService;

    /**
     * Constructor dengan dependency injection
     */
    public function __construct(
        ActivityLogService $activityLogService,
        NutritionCalculatorService $nutritionService,
        WhatsAppService $whatsAppService
    ) {
        $this->activityLogService = $activityLogService;
        $this->nutritionService = $nutritionService;
        $this->whatsAppService = $whatsAppService;
    }

    /**
     * Periksa duplikasi pemberian Vitamin A dan Pill FE dalam bulan yang sama
     */
    public function getDuplicateWarnings(
        int $patientId,
        ?Carbon $visitDate = null,
        ?int $excludeRecordId = null
    ): ?MedicalRecord {
        $date = $visitDate ?? now();

        $query = $this->buildDuplicateQuery($patientId, $date, $excludeRecordId);

        return $query->first();
    }

    /**
     * Buat rekam medis baru
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function createRecord(array $data, User $user): MedicalRecord
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($data, $user) {
            $patient = null;
            if (!empty($data['patient_id'])) {
                $patient = $this->getPatientOrFail($data['patient_id']);
            } elseif (!empty($data['id_number'])) {
                $hash = Patient::generateBlindIndex($data['id_number']);
                $patient = Patient::where('id_number_hash', $hash)->first();
            }

            if (!$patient) {
                $patientCategory = $data['category'] ?? 'ibu_hamil';
                $patient = Patient::create([
                    'posyandu_id' => $user->posyandu_id ?? \App\Models\Posyandu::first()?->id ?? 1,
                    'category' => $patientCategory,
                    'full_name' => $data['full_name'] ?? ($patientCategory === 'lansia' ? 'Lansia Baru' : 'Ibu Hamil Baru'),
                    'id_number' => $data['id_number'] ?? null,
                    'birth_date' => $data['birth_date'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'husband_name' => $data['husband_name'] ?? null,
                    'address' => $data['address'] ?? null,
                    'dusun_rt_rw' => $data['dusun_rt_rw'] ?? null,
                    'desa_kelurahan' => $data['desa_kelurahan'] ?? null,
                    'kecamatan' => $data['kecamatan'] ?? null,
                    'is_pregnant' => ($patientCategory === 'ibu_hamil'),
                    'gender' => ($patientCategory === 'lansia') ? ($data['gender'] ?? 'P') : 'P',
                ]);
            }

            $this->verifyPatientAccess($patient, $user);

            $data['patient_id'] = $patient->id;
            $preparedData = $this->prepareRecordData($data, $patient, $user);
            $medicalRecord = MedicalRecord::create($preparedData);

            $this->saveChildDevelopment($medicalRecord, $data);

            $this->logActivity('create_medical_record', $patient, $medicalRecord, null, $preparedData);

            return $medicalRecord;
        });
    }

    /**
     * Update rekam medis yang sudah ada
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function updateRecord(
        MedicalRecord $medicalRecord,
        array $data,
        User $user
    ): MedicalRecord {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($medicalRecord, $data, $user) {
            $oldValues = $medicalRecord->toArray();
            
            $patient = null;
            if (!empty($data['patient_id'])) {
                $patient = $this->getPatientOrFail($data['patient_id']);
            } elseif (!empty($data['id_number'])) {
                $hash = Patient::generateBlindIndex($data['id_number']);
                $patient = Patient::where('id_number_hash', $hash)->first();
            }

            if (!$patient) {
                $patientCategory = $data['category'] ?? 'ibu_hamil';
                $patient = Patient::create([
                    'posyandu_id' => $user->posyandu_id ?? \App\Models\Posyandu::first()?->id ?? 1,
                    'category' => $patientCategory,
                    'full_name' => $data['full_name'] ?? ($patientCategory === 'lansia' ? 'Lansia Baru' : 'Ibu Hamil Baru'),
                    'id_number' => $data['id_number'] ?? null,
                    'birth_date' => $data['birth_date'] ?? null,
                    'phone_number' => $data['phone_number'] ?? null,
                    'husband_name' => $data['husband_name'] ?? null,
                    'address' => $data['address'] ?? null,
                    'dusun_rt_rw' => $data['dusun_rt_rw'] ?? null,
                    'desa_kelurahan' => $data['desa_kelurahan'] ?? null,
                    'kecamatan' => $data['kecamatan'] ?? null,
                    'is_pregnant' => ($patientCategory === 'ibu_hamil'),
                    'gender' => ($patientCategory === 'lansia') ? ($data['gender'] ?? 'P') : 'P',
                ]);
            }

            $this->verifyPatientAccess($patient, $user);

            $data['patient_id'] = $patient->id;
            $preparedData = $this->prepareUpdateData($data, $patient, $medicalRecord, $oldValues);
            $medicalRecord->update($preparedData);

            $this->saveChildDevelopment($medicalRecord, $data);

            $this->logActivity(
                'update_medical_record',
                $patient,
                $medicalRecord->fresh(),
                $oldValues,
                $medicalRecord->fresh()->toArray()
            );

            return $medicalRecord;
        });
    }

    /**
     * Hapus rekam medis
     */
    public function deleteRecord(MedicalRecord $medicalRecord): void
    {
        $recordData = $medicalRecord->toArray();
        $patientName = $medicalRecord->patient->full_name;
        $visitDate = $medicalRecord->visit_date->format('d/m/Y');

        $medicalRecord->delete();

        $this->activityLogService->log(
            'delete_medical_record',
            "Menghapus rekam medis untuk: {$patientName} (Tanggal: {$visitDate})",
            null,
            'MedicalRecord',
            $recordData,
            null
        );
    }

    /**
     * Bangun query untuk memeriksa duplikasi
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function buildDuplicateQuery(
        int $patientId,
        Carbon $date,
        ?int $excludeRecordId
    ) {
        $query = MedicalRecord::where('patient_id', $patientId)
            ->whereYear('visit_date', $date->year)
            ->whereMonth('visit_date', $date->month)
            ->where(function ($q) {
                $q->where('vitamin_a', true)
                    ->orWhere('vitamin_a_color', '!=', 'none')
                    ->orWhere('pill_fe', true);
            });

        if ($excludeRecordId) {
            $query->where('id', '!=', $excludeRecordId);
        }

        return $query;
    }

    /**
     * Dapatkan patient atau throw exception
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    private function getPatientOrFail(int $patientId): Patient
    {
        return Patient::findOrFail($patientId);
    }

    /**
     * Verifikasi user memiliki akses ke patient
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    private function verifyPatientAccess(Patient $patient, User $user): void
    {
        if (! $user->isSuperAdmin()) {
            if ($patient->posyandu_id !== $user->posyandu_id) {
                abort(403, 'Anda tidak memiliki akses untuk membuat rekam medis untuk pasien ini.');
            }
        }
    }

    /**
     * Siapkan data untuk pembuatan rekam medis baru
     */
    private function prepareRecordData(array $data, Patient $patient, User $user): array
    {
        $this->updatePatientData($patient, $data);

        $data['height'] = $data['height'] ?? $data['starting_height'] ?? 0;

        $data = $this->calculateNutrition($data, $patient);

        $data['user_id'] = $user->id;
        $data['immunization'] = $data['immunization'] ?? 'Tidak ada';
        $data['complaint'] = $data['complaint'] ?? '—';
        $data['diagnosis'] = $data['diagnosis'] ?? 'Sehat';
        $data['nutrition_status'] = $data['nutrition_status'] ?? 'Belum Dihitung';
        $data['vitamin_a_color'] = $data['vitamin_a_color'] ?? 'none';
        $data['deworming_medicine'] = $data['deworming_medicine'] ?? false;
        
        // Defaults for TBC screening
        $data['tbc_screening_cough'] = $data['tbc_screening_cough'] ?? false;
        $data['tbc_screening_fever'] = $data['tbc_screening_fever'] ?? false;
        $data['tbc_screening_contact'] = $data['tbc_screening_contact'] ?? false;
        $data['tbc_screening_lethargy'] = $data['tbc_screening_lethargy'] ?? false;
        $data['tbc_screening_lumps'] = $data['tbc_screening_lumps'] ?? false;
        $data['tbc_screening_weight_loss'] = $data['tbc_screening_weight_loss'] ?? false;

        $data['measurement_method'] = $data['measurement_method'] ?? 'recumbent';

        if (!empty($data['blood_pressure'])) {
            $bpVal = str_replace(' mmHg', '', $data['blood_pressure']);
            $bpParts = explode('/', $bpVal);
            if (count($bpParts) === 2) {
                $data['systolic_bp'] = (int) trim($bpParts[0]);
                $data['diastolic_bp'] = (int) trim($bpParts[1]);
            }
            unset($data['blood_pressure']);
        }
        if (isset($data['family_disease_history']) && is_array($data['family_disease_history'])) {
            $data['family_disease_history'] = json_encode($data['family_disease_history']);
        }
        if (isset($data['risk_behaviors']) && is_array($data['risk_behaviors'])) {
            $data['risk_behaviors'] = json_encode($data['risk_behaviors']);
        }

        return $data;
    }

    /**
     * Siapkan data untuk update rekam medis
     */
    private function prepareUpdateData(
        array $data,
        Patient $patient,
        MedicalRecord $medicalRecord,
        array $oldValues
    ): array {
        $this->updatePatientData($patient, $data);

        $data['height'] = $data['height'] ?? $data['starting_height'] ?? $medicalRecord->height ?? 0;

        $weightChanged = isset($data['weight']) && $data['weight'] != $oldValues['weight'];
        $heightChanged = isset($data['height']) && $data['height'] != $oldValues['height'];

        if ($weightChanged || $heightChanged) {
            $data = $this->calculateNutrition($data, $patient);
        }

        $data['immunization'] = $data['immunization'] ?? $medicalRecord->immunization ?? 'Tidak ada';
        $data['complaint'] = $data['complaint'] ?? $medicalRecord->complaint ?? '—';
        $data['diagnosis'] = $data['diagnosis'] ?? $medicalRecord->diagnosis ?? 'Sehat';
        $data['nutrition_status'] = $data['nutrition_status'] ?? $medicalRecord->nutrition_status ?? 'Belum Dihitung';
        $data['vitamin_a_color'] = $data['vitamin_a_color'] ?? $medicalRecord->vitamin_a_color ?? 'none';
        
        // Ensure booleans are always set
        $data['vitamin_a'] = $data['vitamin_a'] ?? false;
        $data['pill_fe'] = $data['pill_fe'] ?? false;
        $data['deworming_medicine'] = $data['deworming_medicine'] ?? false;
        $data['is_exclusive_breastfeeding'] = $data['is_exclusive_breastfeeding'] ?? false;
        $data['mp_asi'] = $data['mp_asi'] ?? false;
        $data['is_basic_immunization_complete'] = $data['is_basic_immunization_complete'] ?? false;

        // TBC Screening
        $data['tbc_screening_cough'] = $data['tbc_screening_cough'] ?? false;
        $data['tbc_screening_fever'] = $data['tbc_screening_fever'] ?? false;
        $data['tbc_screening_contact'] = $data['tbc_screening_contact'] ?? false;
        $data['tbc_screening_lethargy'] = $data['tbc_screening_lethargy'] ?? false;
        $data['tbc_screening_lumps'] = $data['tbc_screening_lumps'] ?? false;
        $data['tbc_screening_weight_loss'] = $data['tbc_screening_weight_loss'] ?? false;

        $data['measurement_method'] = $data['measurement_method'] ?? $medicalRecord->measurement_method ?? 'recumbent';

        if (!empty($data['blood_pressure'])) {
            $bpVal = str_replace(' mmHg', '', $data['blood_pressure']);
            $bpParts = explode('/', $bpVal);
            if (count($bpParts) === 2) {
                $data['systolic_bp'] = (int) trim($bpParts[0]);
                $data['diastolic_bp'] = (int) trim($bpParts[1]);
            }
            unset($data['blood_pressure']);
        }
        if (isset($data['family_disease_history']) && is_array($data['family_disease_history'])) {
            $data['family_disease_history'] = json_encode($data['family_disease_history']);
        }
        if (isset($data['risk_behaviors']) && is_array($data['risk_behaviors'])) {
            $data['risk_behaviors'] = json_encode($data['risk_behaviors']);
        }

        return $data;
    }

    /**
     * Update data dasar pasien jika dikirimkan bersama rekam medis
     */
    private function updatePatientData(Patient $patient, array $data): void
    {
        $patientFields = [
            'father_name', 'mother_name', 'weight_at_birth', 'height_at_birth',
            'full_name', 'birth_date', 'phone_number', 'husband_name', 'address',
            'dusun_rt_rw', 'desa_kelurahan', 'kecamatan', 'gender', 'category'
        ];
        $updateData = [];

        foreach ($patientFields as $field) {
            if (isset($data[$field]) && ! empty($data[$field])) {
                if ($field === 'category') {
                    $existingCat = $patient->category;
                    $newCat = $data[$field];
                    $childCats = ['bayi', 'baduta', 'balita', 'anak_sekolah'];
                    $isExistingChild = in_array($existingCat, $childCats);
                    $isNewChild = in_array($newCat, $childCats);

                    if ($existingCat && $existingCat !== $newCat) {
                        if ($isExistingChild && $isNewChild) {
                            // child to child category transition is allowed
                        } else {
                            // do not update the patient's category to a conflicting one
                            continue;
                        }
                    }
                }
                $updateData[$field] = $data[$field];
            }
        }

        if (! empty($updateData)) {
            $patient->update($updateData);
        }
    }

    /**
     * Hitung data gizi (z-score, status, trend)
     */
    private function calculateNutrition(array $data, Patient $patient): array
    {
        if (! $this->shouldCalculateNutrition($patient, $data)) {
            return $data;
        }

        $ageInMonths = $patient->birth_date->diffInMonths(now());

        $nutritionResult = $this->nutritionService->calculateAll(
            (float) $data['weight'],
            (float) ($data['height'] ?? 0),
            $ageInMonths,
            $patient->gender
        );

        $data = array_merge($data, $nutritionResult->toArray());
        $data['nutrition_trend'] = $this->calculateNutritionTrend($patient, $data);

        $this->checkGrowthTrends($patient, $data);

        return $data;
    }

    /**
     * Tentukan apakah perhitungan gizi diperlukan
     */
    private function shouldCalculateNutrition(Patient $patient, array $data): bool
    {
        return $patient->category === 'balita'
            && isset($data['weight'])
            && $patient->birth_date;
    }

    /**
     * Hitung tren gizi berdasarkan rekam sebelumnya
     */
    private function calculateNutritionTrend(Patient $patient, array $data): string
    {
        $previousRecord = MedicalRecord::where('patient_id', $patient->id)
            ->where('visit_date', '<', Carbon::parse($data['visit_date']))
            ->whereNotNull('nutrition_status')
            ->orderBy('visit_date', 'desc')
            ->first();

        if (! $previousRecord || ! $previousRecord->nutrition_status) {
            return 'tetap';
        }

        return $this->compareNutritionStatus(
            $previousRecord->nutrition_status,
            $data['nutrition_status']
        );
    }

    /**
     * Bandingkan status gizi dan tentukan tren
     */
    private function compareNutritionStatus(string $previousStatus, string $currentStatus): string
    {
        $statusRank = [
            'Gizi Buruk' => 1,
            'Gizi Kurang' => 2,
            'Gizi Baik' => 3,
            'Normal' => 3,
            'Berisiko Gizi Lebih' => 2,
            'Gizi Lebih' => 1,
            'Obesitas' => 1,
            'Sangat Pendek' => 1,
            'Pendek' => 2,
            'Tinggi' => 3,
            'Tidak Dapat Dihitung' => 0,
        ];

        $prevRank = $statusRank[$previousStatus] ?? 0;
        $currRank = $statusRank[$currentStatus] ?? 0;

        if ($prevRank === 0 || $currRank === 0) {
            return 'tetap';
        }

        if ($currRank > $prevRank) {
            return 'naik';
        } elseif ($currRank < $prevRank) {
            return 'turun';
        }

        return 'tetap';
    }

    /**
     * Deteksi tren pertumbuhan dan kirim peringatan jika perlu
     */
    private function checkGrowthTrends(Patient $patient, array $data): void
    {
        $this->checkStuntingAlert($patient, $data);
        $this->checkTwoTAlert($patient, $data);
    }

    /**
     * Periksa alert stunting baru
     */
    private function checkStuntingAlert(Patient $patient, array $data): void
    {
        $stuntingStatus = $data['stunting_status'] ?? '';

        if ($stuntingStatus !== 'Normal' && $stuntingStatus !== 'Tidak Dapat Dihitung') {
            $message = "Perhatian: Balita {$patient->full_name} terdeteksi memiliki status {$stuntingStatus}. Mohon konsultasikan dengan petugas kesehatan.";
            $this->sendGrowthAlert($patient, $message);
        }
    }

    /**
     * Periksa alert 2T (Tidak Naik 2 kali berturut-turut)
     */
    private function checkTwoTAlert(Patient $patient, array $data): void
    {
        $recentRecords = $this->getPreviousRecords($patient, Carbon::parse($data['visit_date']), 2);

        if ($recentRecords->count() < 2) {
            return;
        }

        $lastWeight = (float) $data['weight'];
        $prevWeight1 = (float) $recentRecords[0]->weight;
        $prevWeight2 = (float) $recentRecords[1]->weight;

        if ($lastWeight <= $prevWeight1 && $prevWeight1 <= $prevWeight2) {
            $message = "Peringatan 2T: Berat badan {$patient->full_name} tidak naik dalam 2 penimbangan terakhir. Segera konsultasikan ke Posyandu atau Puskesmas.";
            $this->sendGrowthAlert($patient, $message);
        }
    }

    /**
     * Dapatkan rekam medis sebelumnya
     */
    private function getPreviousRecords(
        Patient $patient,
        Carbon $beforeDate,
        int $limit = 2
    ): Collection {
        return MedicalRecord::where('patient_id', $patient->id)
            ->where('visit_date', '<', $beforeDate)
            ->orderBy('visit_date', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Kirim notifikasi alert pertumbuhan via WhatsApp
     */
    private function sendGrowthAlert(Patient $patient, string $message): void
    {
        $target = $this->getContactNumber($patient);

        if ($target) {
            $this->whatsAppService->sendMessage($target, $message);
        }
    }

    /**
     * Dapatkan nomor kontak dari patient
     */
    private function getContactNumber(Patient $patient): ?string
    {
        return $patient->phone_number ?? $patient->parent_phone ?? null;
    }

    /**
     * Log aktivitas sistem
     */
    private function logActivity(
        string $action,
        Patient $patient,
        MedicalRecord $medicalRecord,
        ?array $oldValues,
        ?array $newValues
    ): void {
        $visitDate = $medicalRecord->visit_date->format('Y-m-d');
        $description = $action === 'create_medical_record'
            ? "Menambahkan rekam medis: {$patient->full_name} (Tanggal: {$visitDate})"
            : "Mengubah rekam medis: {$patient->full_name} (Tanggal: {$visitDate})";

        $this->activityLogService->log(
            $action,
            $description,
            $medicalRecord->id,
            'MedicalRecord',
            $oldValues,
            $newValues
        );
    }

    /**
     * Simpan data Ceklis Perkembangan (KPSP)
     */
    private function saveChildDevelopment(MedicalRecord $medicalRecord, array $data): void
    {
        if (! isset($data['kpsp_age_group']) || empty($data['kpsp_age_group'])) {
            return;
        }

        $kpspData = [
            'age_group_months' => $data['kpsp_age_group'],
            'motor_gross' => $data['kpsp_motor_gross'] ?? false,
            'motor_fine' => $data['kpsp_motor_fine'] ?? false,
            'language' => $data['kpsp_language'] ?? false,
            'social' => $data['kpsp_social'] ?? false,
            'note' => $data['kpsp_note'] ?? null,
        ];

        // Hitung status otomatis: Jika ada jawaban "Tidak" (false), maka Meragukan/Penyimpangan.
        // Asumsi standar: KPSP punya 9-10 pertanyaan. Di sini kita simplifikasi jadi 4 area.
        // Jika semua true = Sesuai. Jika 1 false = Meragukan. Jika >= 2 false = Penyimpangan.
        $falseCount = 0;
        foreach (['motor_gross', 'motor_fine', 'language', 'social'] as $area) {
            if (! $kpspData[$area]) {
                $falseCount++;
            }
        }

        if ($falseCount === 0) {
            $kpspData['development_status'] = 'Sesuai';
        } elseif ($falseCount === 1) {
            $kpspData['development_status'] = 'Meragukan';
        } else {
            $kpspData['development_status'] = 'Penyimpangan';
        }

        $medicalRecord->childDevelopment()->updateOrCreate(
            ['medical_record_id' => $medicalRecord->id],
            $kpspData
        );
    }
}
