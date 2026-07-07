<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;
use App\Services\ActivityLogService;

class MedicalRecordPolicy
{
    public function viewAny(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return ($user->isAdmin() || $user->isKader()) && $user->posyandu_id !== null;
    }

    /**
     * Determine if the user can view the medical record.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return ($user->isAdmin() || $user->isKader()) && $user->posyandu_id === $medicalRecord->patient->posyandu_id;
    }

    /**
     * Determine if the user can create medical records.
     */
    public function create(User $user): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $user->posyandu_id !== null;
    }

    /**
     * Determine if the user can update the medical record.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $user->posyandu_id === $medicalRecord->patient->posyandu_id;
    }

    /**
     * Determine if the user can delete the medical record.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        if ($user->isSuperAdmin()) {
            return true;
        }

        return $user->isAdmin() && $user->posyandu_id === $medicalRecord->patient->posyandu_id;
    }

    /**
     * Log unauthorized access attempt
     */
    private function logUnauthorizedAccess(User $user, string $action, string $entityType, ?int $entityId = null): void
    {
        $activityLogService = app(ActivityLogService::class);
        $activityLogService->log(
            'unauthorized_access',
            "Percobaan akses tidak sah: {$action} pada {$entityType}".($entityId ? " (ID: {$entityId})" : ''),
            $entityId,
            $entityType,
            null,
            ['action' => $action, 'user_role' => $user->role]
        );
    }
}
