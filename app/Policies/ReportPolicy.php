<?php

namespace App\Policies;

use App\Models\User;

class ReportPolicy
{
    /**
     * Determine if the user can view reports.
     * Superadmin, admin, and coordinator can access reports.
     */
    public function viewAny(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isCoordinator() || $user->isKader();
    }

    /**
     * Determine if the user can export reports.
     * Superadmin, admin, and kader can export reports.
     */
    public function export(User $user): bool
    {
        return $user->isSuperAdmin() || $user->isAdmin() || $user->isCoordinator() || $user->isKader();
    }
}
