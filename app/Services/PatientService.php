<?php

namespace App\Services;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

class PatientService
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Create a new patient.
     *
     * @throws ValidationException
     */
    public function createPatient(array $data, User $user): Patient
    {
        // Enforce posyandu_id for non-superadmin users
        if (! $user->isSuperAdmin()) {
            $data['posyandu_id'] = $user->posyandu_id;
        }

        // Validate unique NIK per posyandu
        $existingPatient = Patient::where('id_number', $data['id_number'])
            ->where('posyandu_id', $data['posyandu_id'])
            ->first();

        if ($existingPatient) {
            throw ValidationException::withMessages([
                'id_number' => 'NIK sudah terdaftar dalam sistem untuk Posyandu ini.',
            ]);
        }

        if (isset($data['profile_photo']) && $data['profile_photo'] instanceof UploadedFile) {
            $data['profile_photo'] = $data['profile_photo']->store('patients', 'public');
        }

        $patient = Patient::create($data);

        // Log activity
        $this->activityLogService->log(
            'create_patient',
            "Menambahkan data warga: {$patient->full_name} (NIK: {$patient->id_number})",
            $patient->id,
            'Patient',
            null,
            $patient->toArray()
        );

        return $patient;
    }

    /**
     * Update an existing patient.
     *
     * @throws ValidationException
     */
    public function updatePatient(Patient $patient, array $data, User $user): Patient
    {
        // Store old values before update
        $oldValues = $patient->toArray();

        // Enforce posyandu_id for non-superadmin users
        if (! $user->isSuperAdmin()) {
            $data['posyandu_id'] = $user->posyandu_id;
        }

        // Validate unique NIK per posyandu (excluding current patient)
        $existingPatient = Patient::where('id_number', $data['id_number'])
            ->where('posyandu_id', $data['posyandu_id'])
            ->where('id', '!=', $patient->id)
            ->first();

        if ($existingPatient) {
            throw ValidationException::withMessages([
                'id_number' => 'NIK sudah terdaftar dalam sistem untuk Posyandu ini.',
            ]);
        }

        if (isset($data['profile_photo']) && $data['profile_photo'] instanceof UploadedFile) {
            // Optionally delete old photo here if desired:
            // if ($patient->profile_photo) Storage::disk('public')->delete($patient->profile_photo);
            $data['profile_photo'] = $data['profile_photo']->store('patients', 'public');
        }

        $patient->update($data);

        // Log activity with old and new values
        $this->activityLogService->log(
            'update_patient',
            "Mengubah data warga: {$patient->full_name} (NIK: {$patient->id_number})",
            $patient->id,
            'Patient',
            $oldValues,
            $patient->fresh()->toArray()
        );

        return $patient;
    }

    /**
     * Delete a patient.
     */
    public function deletePatient(Patient $patient): void
    {
        // Store patient data before deletion
        $patientData = $patient->toArray();
        $patientName = $patient->full_name;
        $patientNik = $patient->id_number;
        $patientId = $patient->id;

        $patient->delete();

        // Log activity
        $this->activityLogService->log(
            'delete_patient',
            "Menghapus data warga: {$patientName} (NIK: {$patientNik})",
            $patientId,
            'Patient',
            $patientData,
            null
        );
    }
}
