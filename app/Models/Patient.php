<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Casts\EncryptedCast;
use App\Models\Concerns\HasPosyanduAccess;
use App\Traits\LogsActivity;

class Patient extends Model
{
    use HasFactory, HasPosyanduAccess, LogsActivity;

    protected $fillable = [
        'posyandu_id', 'category', 'parent_name', 'id_number', 'full_name',
        'birth_date', 'gender', 'address', 'phone_number', 'profile_photo'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'id_number'  => EncryptedCast::class,
    ];

    /**
     * Boot function to handle model events.
     */
    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if ($model->isDirty('id_number') && !empty($model->id_number)) {
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
