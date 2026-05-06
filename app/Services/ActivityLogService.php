<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    /**
     * Log an activity action
     *
     * @param  string  $actionType  Type of action (e.g., 'login', 'logout', 'create_patient', etc.)
     * @param  string  $description  Human-readable description of the action
     * @param  int|null  $entityId  ID of the entity affected (optional)
     * @param  string|null  $entityType  Type of entity affected (e.g., 'Patient', 'MedicalRecord')
     * @param  array|null  $oldValues  Values before the change (optional)
     * @param  array|null  $newValues  Values after the change (optional)
     */
    public function log(
        string $actionType,
        string $description,
        ?int $entityId = null,
        ?string $entityType = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {
        $user = Auth::user();

        return ActivityLog::create([
            'user_id' => $user->id,
            'user_name' => $user->name,
            'role' => $user->role,
            'action_type' => $actionType,
            'description' => $description,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
        ]);
    }
}
