<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * Indicates if the model should be timestamped.
     * Only created_at is used, no updated_at (immutable logs)
     *
     * @var bool
     */
    public const UPDATED_AT = null;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'user_name',
        'role',
        'action_type',
        'description',
        'entity_type',
        'entity_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relationship with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Log aktivitas create
     */
    public static function logCreate(Model $model, ?string $description = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'role' => auth()->user()?->role ?? 'guest',
            'action_type' => 'create',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'description' => $description ?? 'Membuat data '.class_basename($model),
            'new_values' => $model->toArray(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log aktivitas update
     */
    public static function logUpdate(Model $model, array $oldValues, array $newValues, ?string $description = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'role' => auth()->user()?->role ?? 'guest',
            'action_type' => 'update',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'description' => $description ?? 'Memperbarui data '.class_basename($model),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log aktivitas delete
     */
    public static function logDelete(Model $model, array $oldValues, ?string $description = null): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'role' => auth()->user()?->role ?? 'guest',
            'action_type' => 'delete',
            'entity_type' => get_class($model),
            'entity_id' => $model->id,
            'description' => $description ?? 'Menghapus data '.class_basename($model),
            'old_values' => $oldValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Log aktivitas umum (login, logout, view, dll)
     */
    public static function logActivity(string $action, string $description, $model = null, array $metadata = []): self
    {
        return static::create([
            'user_id' => auth()->id(),
            'user_name' => auth()->user()?->name,
            'role' => auth()->user()?->role ?? 'guest',
            'action_type' => $action,
            'entity_type' => $model ? get_class($model) : null,
            'entity_id' => $model?->id,
            'description' => $description,
            'new_values' => $metadata['new_values'] ?? null,
            'old_values' => $metadata['old_values'] ?? null,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }
}
