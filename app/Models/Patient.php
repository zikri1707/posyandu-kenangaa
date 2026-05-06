<?php

namespace App\Models;

use App\Casts\EncryptedCast;
use App\Models\Concerns\HasPosyanduAccess;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory, HasPosyanduAccess, LogsActivity;

    protected $fillable = [
        'posyandu_id', 'category', 'parent_name', 'id_number', 'full_name',
        'birth_date', 'gender', 'address', 'phone_number', 'profile_photo',
        'last_notifications_read_at', 'place_of_birth', 'head_of_family_name',
        'mother_nik', 'kia_book_ownership', 'guardian_status', 'education',
        'job', 'number_of_children', 'is_pregnant', 'living_status',
        'independence_status', 'family_member_count', 'house_condition',
        'water_access', 'has_latrine', 'economic_status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'id_number' => EncryptedCast::class,
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('id_number') && ! empty($model->id_number)) {
                $model->id_number_hash = static::generateBlindIndex($model->id_number);
            }
        });
    }

    /**
     * Generate a deterministic hash for searching (Blind Index).
     */
    public static function generateBlindIndex($value): string
    {
        return hash_hmac('sha256', $value, config('app.encryption_key') ?? 'default_pepper');
    }

    // Relationship with Posyandu
    public function posyandu()
    {
        return $this->belongsTo(Posyandu::class);
    }

    // Relationship with MedicalRecord
    public function medicalRecords()
    {
        return $this->hasMany(MedicalRecord::class);
    }

    // Age accessors
    public function getAgeAttribute(): string
    {
        return $this->birth_date ? $this->birth_date->diff(now())->format('%y thn, %m bln') : '-';
    }

    /**
     * Ensure id_number is always a string.
     */
    public function getIdNumberAttribute($value): string
    {
        return (string) $value;
    }

    public function getAgeInMonthsAttribute(): int
    {
        return $this->birth_date ? (int) $this->birth_date->diffInMonths(now()) : 0;
    }

    /**
     * Scope to filter patients by posyandu
     */
    public function scopeByPosyandu(Builder $query, int $posyanduId): Builder
    {
        return $query->where('posyandu_id', $posyanduId);
    }
}
