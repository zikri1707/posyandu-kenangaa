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
            $patient = $this->getPatientOrFail($data['patient_id']);
            $this->verifyPatientAccess($patient, $user);

            $preparedData = $this->prepareRecordData($data, $patient, $user);
            $medicalRecord = MedicalRecord::create($preparedData);

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
            $patient = $this->getPatientOrFail($data['patient_id']);

            $this->verifyPatientAccess($patient, $user);

            $preparedData = $this->prepareUpdateData($data, $patient, $medicalRecord, $oldValues);
            $medicalRecord->update($preparedData);

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
        if (! $user->isSuperAdmin() && ! $user->isCoordinator()) {
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
        $data = $this->calculateNutrition($data, $patient);

        $data['user_id'] = $user->id;
        $data['immunization'] = $data['immunization'] ?? 'Tidak ada';
        $data['complaint'] = $data['complaint'] ?? '—';

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
        $weightChanged = isset($data['weight']) && $data['weight'] != $oldValues['weight'];
        $heightChanged = isset($data['height']) && $data['height'] != $oldValues['height'];

        if ($weightChanged || $heightChanged) {
            $data = $this->calculateNutrition($data, $patient);
        }

        $data['immunization'] = $data['immunization'] ?? $medicalRecord->immunization ?? 'Tidak ada';
        $data['complaint'] = $data['complaint'] ?? $medicalRecord->complaint ?? '—';

        return $data;
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
}
