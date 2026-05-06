<?php

namespace App\Policies;

use App\Models\MedicalRecord;
use App\Models\User;
use App\Services\ActivityLogService;

class MedicalRecordPolicy
{
    /**
     * Determine if the user can view any medical records.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin can view all medical records
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin and kader can view medical records from their posyandu
        if ($user->isAdmin() || $user->isKader()) {
            return $user->posyandu_id !== null;
        }

        return false;
    }

    /**
     * Determine if the user can view the medical record.
     */
    public function view(User $user, MedicalRecord $medicalRecord): bool
    {
        // Superadmin can view any medical record
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin and kader can only view medical records from their posyandu
        if ($user->isAdmin() || $user->isKader()) {
            return $user->posyandu_id === $medicalRecord->patient->posyandu_id;
        }

        return false;
    }

    /**
     * Determine if the user can create medical records.
     */
    public function create(User $user): bool
    {
        // Superadmin and Admin can create medical records
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $user->posyandu_id !== null;
        }

        // Kader cannot create (read-only per user request)
        return false;
    }

    /**
     * Determine if the user can update the medical record.
     */
    public function update(User $user, MedicalRecord $medicalRecord): bool
    {
        // Superadmin can update any medical record
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Only Admin can update medical records from their posyandu
        if ($user->isAdmin()) {
            return $user->posyandu_id === $medicalRecord->patient->posyandu_id;
        }

        // Kader cannot update (read-only per user request)
        return false;
    }

    /**
     * Determine if the user can delete the medical record.
     */
    public function delete(User $user, MedicalRecord $medicalRecord): bool
    {
        // ONLY Superadmin and Admin (scoped) can delete medical records
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $user->posyandu_id === $medicalRecord->patient->posyandu_id;
        }

        return false;
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
