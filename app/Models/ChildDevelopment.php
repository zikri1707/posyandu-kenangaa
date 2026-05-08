<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildDevelopment extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_record_id',
        'age_group_months',
        'motor_gross',
        'motor_fine',
        'language',
        'social',
        'development_status',
        'note',
    ];

    protected $casts = [
        'age_group_months' => 'integer',
        'motor_gross' => 'boolean',
        'motor_fine' => 'boolean',
        'language' => 'boolean',
        'social' => 'boolean',
    ];

    public function medicalRecord()
    {
        return $this->belongsTo(MedicalRecord::class);
    }
}
