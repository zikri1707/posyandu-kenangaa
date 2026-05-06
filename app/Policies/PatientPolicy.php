<?php

namespace App\Policies;

use App\Models\Patient;
use App\Models\User;
use App\Services\ActivityLogService;

class PatientPolicy
{
    /**
     * Determine if the user can view any patients.
     */
    public function viewAny(User $user): bool
    {
        // Superadmin can view all patients
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin and kader can view patients from their posyandu
        if ($user->isAdmin() || $user->isKader()) {
            return $user->posyandu_id !== null;
        }

        return false;
    }

    /**
     * Determine if the user can view the patient.
     */
    public function view(User $user, Patient $patient): bool
    {
        // Superadmin can view any patient
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Admin and kader can only view patients from their posyandu
        if ($user->isAdmin() || $user->isKader()) {
            return $user->posyandu_id === $patient->posyandu_id;
        }

        return false;
    }

    /**
     * Determine if the user can create patients.
     */
    public function create(User $user): bool
    {
        // Superadmin and Admin can create patients
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
     * Determine if the user can update the patient.
     */
    public function update(User $user, Patient $patient): bool
    {
        // Superadmin can update any patient
        if ($user->isSuperAdmin()) {
            return true;
        }

        // Only Admin can update patients from their posyandu
        if ($user->isAdmin()) {
            return $user->posyandu_id === $patient->posyandu_id;
        }

        // Kader cannot update (read-only)
        return false;
    }

    /**
     * Determine if the user can delete the patient.
     */
    public function delete(User $user, Patient $patient): bool
    {
        // ONLY Superadmin and Admin (scoped) can delete patients
        if ($user->isSuperAdmin()) {
            return true;
        }

        if ($user->isAdmin()) {
            return $user->posyandu_id === $patient->posyandu_id;
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
