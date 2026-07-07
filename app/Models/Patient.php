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
        'father_name', 'mother_name', 'weight_at_birth', 'height_at_birth',
        'birth_date', 'gender', 'address', 'phone_number', 'profile_photo',
        'last_notifications_read_at', 'place_of_birth', 'head_of_family_name',
        'mother_nik', 'kia_book_ownership', 'guardian_status', 'education',
        'job', 'number_of_children', 'is_pregnant', 'living_status',
        'independence_status', 'family_member_count', 'house_condition',
        'water_access', 'has_latrine', 'economic_status', 'rt_domisili',
        'historical_diseases', 'husband_name', 'dusun_rt_rw', 'desa_kelurahan', 'kecamatan',
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

        static::saved(function ($patient) {
            \App\Models\AnalyticsSnapshot::where(function ($q) use ($patient) {
                if ($patient->posyandu_id) {
                    $q->where('posyandu_id', $patient->posyandu_id)->orWhereNull('posyandu_id');
                } else {
                    $q->whereNull('posyandu_id');
                }
            })->delete();
        });

        static::deleted(function ($patient) {
            \App\Models\AnalyticsSnapshot::where(function ($q) use ($patient) {
                if ($patient->posyandu_id) {
                    $q->where('posyandu_id', $patient->posyandu_id)->orWhereNull('posyandu_id');
                } else {
                    $q->whereNull('posyandu_id');
                }
            })->delete();
        });
    }

    /**
     * Generate a deterministic hash for searching (Blind Index).
     */
    public static function generateBlindIndex(string $value): string
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
        return $this->hasMany(MedicalRecord::class)->orderBy('visit_date', 'desc');
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

    public function getComputedCategoryAttribute(): string
    {
        $dbCat = $this->category;
        if ($dbCat === 'balita') {
            $age = $this->age_in_months;
            if ($age < 12) {
                return 'bayi';
            } elseif ($age < 24) {
                return 'baduta';
            }
        }
        return $dbCat;
    }

    /**
     * Get immunization schedule and status based on Indonesian standard.
     */
    public function getImmunizationStatus(): array
    {
        $ageMonths = $this->age_in_months;

        $records = $this->relationLoaded('medicalRecords')
            ? $this->medicalRecords
            : $this->medicalRecords()->get();

        $receivedFromVaccineName = $records
            ->filter(fn ($record) => ! is_null($record->vaccine_name))
            ->flatMap(function ($record) {
                return explode(', ', $record->vaccine_name);
            })
            ->unique()
            ->toArray();

        $receivedFromImmunization = $records
            ->filter(fn ($record) => ! is_null($record->immunization) && $record->immunization !== '' && $record->immunization !== 'Tidak ada')
            ->flatMap(function ($record) {
                $parts = preg_split('/[,;]+/', $record->immunization);
                $mapped = [];
                foreach ($parts as $part) {
                    $trimmed = trim($part);
                    if ($trimmed === '' || strtolower($trimmed) === 'tidak ada') {
                        continue;
                    }

                    // Normalize standard immunization name
                    $normalized = match (strtolower($trimmed)) {
                        'campak', 'campak mr', 'mr' => 'MR',
                        'hepatitis b', 'hb0', 'hb 0', 'hb-0' => 'HB-0',
                        'bcg' => 'BCG',
                        'polio 0', 'polio0' => 'Polio 0',
                        'polio 1', 'polio1' => 'Polio 1',
                        'polio 2', 'polio2' => 'Polio 2',
                        'polio 3', 'polio3' => 'Polio 3',
                        'polio 4', 'polio4' => 'Polio 4',
                        'dpt', 'dpt 1', 'dpt-hb-hib 1', 'dpt-hb-hib1' => 'DPT-HB-Hib 1',
                        'dpt 2', 'dpt-hb-hib 2', 'dpt-hb-hib2' => 'DPT-HB-Hib 2',
                        'dpt 3', 'dpt-hb-hib 3', 'dpt-hb-hib3' => 'DPT-HB-Hib 3',
                        'pcv 1', 'pcv1' => 'PCV 1',
                        'pcv 2', 'pcv2' => 'PCV 2',
                        'pcv 3', 'pcv3' => 'PCV 3',
                        'rv 1', 'rv1' => 'RV 1',
                        'rv 2', 'rv2' => 'RV 2',
                        'rv 3', 'rv3' => 'RV 3',
                        'ipv', 'ipv 1', 'ipv1' => 'IPV 1',
                        'ipv 2', 'ipv2' => 'IPV 2',
                        'dpt lanjutan', 'dpt-hb-hib lanjutan', 'dpt booster' => 'DPT-HB-Hib Lanjutan',
                        'campak lanjutan', 'mr lanjutan', 'campak booster', 'mr booster' => 'MR Lanjutan',
                        default => $trimmed
                    };
                    $mapped[] = $normalized;
                }

                return $mapped;
            })
            ->unique()
            ->toArray();

        $receivedVaccines = array_unique(array_merge($receivedFromVaccineName, $receivedFromImmunization));

        $schedule = [
            ['age' => 0, 'label' => '0 Bulan', 'vaccines' => [
                ['name' => 'HB-0', 'prevent' => 'Hepatitis B'],
                ['name' => 'Polio 0', 'prevent' => 'Polio'],
            ]],
            ['age' => 1, 'label' => '1 Bulan', 'vaccines' => [
                ['name' => 'BCG', 'prevent' => 'TBC'],
                ['name' => 'Polio 1', 'prevent' => 'Polio'],
            ]],
            ['age' => 2, 'label' => '2 Bulan', 'vaccines' => [
                ['name' => 'DPT-HB-Hib 1', 'prevent' => 'Difteri, Pertusis, Tetanus, Hep B, Hib'],
                ['name' => 'Polio 2', 'prevent' => 'Polio'],
                ['name' => 'PCV 1', 'prevent' => 'Pneumonia & Meningitis'],
                ['name' => 'RV 1', 'prevent' => 'Rotavirus'],
            ]],
            ['age' => 3, 'label' => '3 Bulan', 'vaccines' => [
                ['name' => 'DPT-HB-Hib 2', 'prevent' => 'Difteri, Pertusis, Tetanus, Hep B, Hib'],
                ['name' => 'Polio 3', 'prevent' => 'Polio'],
                ['name' => 'PCV 2', 'prevent' => 'Pneumonia & Meningitis'],
                ['name' => 'RV 2', 'prevent' => 'Rotavirus'],
            ]],
            ['age' => 4, 'label' => '4 Bulan', 'vaccines' => [
                ['name' => 'DPT-HB-Hib 3', 'prevent' => 'Difteri, Pertusis, Tetanus, Hep B, Hib'],
                ['name' => 'Polio 4', 'prevent' => 'Polio'],
                ['name' => 'IPV 1', 'prevent' => 'Polio (Suntik)'],
                ['name' => 'RV 3', 'prevent' => 'Rotavirus'],
            ]],
            ['age' => 9, 'label' => '9 Bulan', 'vaccines' => [
                ['name' => 'MR', 'prevent' => 'Campak & Rubella'],
                ['name' => 'IPV 2', 'prevent' => 'Polio (Suntik)'],
            ]],
            ['age' => 12, 'label' => '12 Bulan', 'vaccines' => [
                ['name' => 'PCV 3', 'prevent' => 'Pneumonia & Meningitis'],
            ]],
            ['age' => 18, 'label' => '18 Bulan', 'vaccines' => [
                ['name' => 'DPT-HB-Hib Lanjutan', 'prevent' => 'Booster DPT-HB-Hib'],
                ['name' => 'MR Lanjutan', 'prevent' => 'Booster MR'],
            ]],
        ];

        foreach ($schedule as &$group) {
            foreach ($group['vaccines'] as &$vax) {
                $vax['received'] = in_array($vax['name'], $receivedVaccines);
                $vax['is_due'] = $ageMonths >= $group['age'];
            }
        }

        return $schedule;
    }

    /**
     * Get missing/overdue vaccines.
     */
    public function getMissingVaccines(): array
    {
        if ($this->category !== 'balita' && $this->category !== 'bayi' && $this->category !== 'baduta') {
            return [];
        }

        $status = $this->getImmunizationStatus();
        $missing = [];

        foreach ($status as $group) {
            foreach ($group['vaccines'] as $vax) {
                if ($vax['is_due'] && ! $vax['received']) {
                    $missing[] = $vax['name'];
                }
            }
        }

        return $missing;
    }

    /**
     * Scope to filter patients by posyandu
     */
    public function scopeByPosyandu(Builder $query, int $posyanduId): Builder
    {
        return $query->where('posyandu_id', $posyanduId);
    }
}
