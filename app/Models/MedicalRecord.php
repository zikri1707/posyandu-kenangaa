<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Concerns\HasPosyanduAccess;
use App\Traits\LogsActivity;

class MedicalRecord extends Model
{
    use HasFactory, HasPosyanduAccess, LogsActivity;

    // Nutrition Status (BB/U)
    const NUTRITION_NORMAL      = 'Normal';
    const NUTRITION_GIZI_BAIK   = 'Gizi Baik';
    const NUTRITION_GIZI_KURANG = 'Gizi Kurang';
    const NUTRITION_GIZI_LEBIH  = 'Gizi Lebih';
    const NUTRITION_GIZI_BURUK  = 'Gizi Buruk';
    const NUTRITION_STUNTING    = 'Gizi Buruk/Stunting';

    // Stunting Status (TB/U)
    const STUNTING_NORMAL       = 'Normal';
    const STUNTING_PENDEK       = 'Pendek';
    const STUNTING_SANGAT_PENDEK = 'Sangat Pendek';
    const STUNTING_TINGGI       = 'Tinggi';

    protected $fillable = [
        'patient_id', 'user_id', 'visit_date', 'weight', 'height',
        'measurement_method', 'head_circumference', 'immunization', 
        'vitamin_a', 'pill_fe', 'is_exclusive_breastfeeding',
        'complaint', 'diagnosis',
        // Gizi BB/U
        'nutrition_status', 'z_score', 'nutrition_trend',
        // Gizi TB/U (stunting)
        'z_score_hfa', 'stunting_status',
        // Gizi BB/TB (wasting)
        'z_score_wfh', 'wasting_status',
        // Gizi IMT/U (obesitas)
        'z_score_bfa',
    ];

    protected $casts = [
        'visit_date'                => 'date',
        'vitamin_a'                 => 'boolean',
        'pill_fe'                   => 'boolean',
        'is_exclusive_breastfeeding' => 'boolean',
        'weight'                    => 'decimal:2',
        'height'                    => 'decimal:2',
        'head_circumference'        => 'decimal:2',
        // Z-Score semua indeks
        'z_score'            => 'decimal:2',  // BB/U
        'z_score_hfa'        => 'decimal:2',  // TB/U
        'z_score_wfh'        => 'decimal:2',  // BB/TB
        'z_score_bfa'        => 'decimal:2',  // IMT/U
    ];

    /**
     * Relationship with Patient
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * Relationship with User (Kader yg input)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Override scopeByPosyandu untuk MedicalRecord (akses melalui patient)
     */
    private function scopeByPosyandu($query, $user)
    {
        if (!$user->posyandu_id) {
            return $query->whereNull('id');
        }

        return $query->whereHas('patient', function ($q) use ($user) {
            $q->where('posyandu_id', $user->posyandu_id);
        });
    }

    /**
     * Override scopeByCoordinator untuk MedicalRecord (akses melalui patient.posyandu.pedukuhan)
     */
    private function scopeByCoordinator($query, $user)
    {
        $pedukuhanId = $user->getPedukuhanId();

        if (!$pedukuhanId) {
            return $query->whereNull('id');
        }

        return $query->whereHas('patient.posyandu', function ($q) use ($pedukuhanId) {
            $q->where('pedukuhan_id', $pedukuhanId);
        });
    }
}
