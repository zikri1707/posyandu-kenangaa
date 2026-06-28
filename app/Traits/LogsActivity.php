<?php

namespace App\Traits;

use App\Models\ActivityLog;

/**
 * @mixin \Illuminate\Database\Eloquent\Model
 */
trait LogsActivity
{
    /**
     * Boot the trait - register model events
     */
    public static function bootLogsActivity()
    {
        // Log create event
        static::created(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }
            ActivityLog::logCreate($model);
        });

        // Log update event
        static::updated(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            // Get only the changed attributes
            $oldValues = [];
            $newValues = [];

            foreach ($model->getChanges() as $key => $value) {
                $oldValues[$key] = $model->getOriginal($key);
                $newValues[$key] = $value;
            }

            if (! empty($oldValues) || ! empty($newValues)) {
                ActivityLog::logUpdate($model, $oldValues, $newValues);
            }
        });

        // Log delete event
        static::deleted(function ($model) {
            if (app()->runningInConsole()) {
                return;
            }

            // Get all attributes before deletion
            $oldValues = $model->toArray();
            ActivityLog::logDelete($model, $oldValues);
        });
    }

    /**
     * Get all activity logs for this model
     */
    public function activityLogs()
    {
        return $this->morphMany(ActivityLog::class, 'entity');
    }
}
