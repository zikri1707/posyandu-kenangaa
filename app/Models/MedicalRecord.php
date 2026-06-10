<?php

namespace App\Models;

use App\Models\Concerns\HasPosyanduAccess;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalRecord extends Model
{
    use HasFactory, HasPosyanduAccess, LogsActivity;

    // BB/U (Weight-for-Age) — Status Berat Badan
    const STATUS_BB_U_SANGAT_KURANG = 'Gizi Buruk';

    const STATUS_BB_U_KURANG = 'Gizi Kurang';

    const STATUS_BB_U_NORMAL = 'Gizi Baik';

    const STATUS_BB_U_RISIKO_LEBIH = 'Gizi Lebih';

    // TB/U (Height-for-Age) — Status Tinggi Badan
    const STATUS_TB_U_SANGAT_PENDEK = 'Sangat Pendek';

    const STATUS_TB_U_PENDEK = 'Pendek';

    const STATUS_TB_U_NORMAL = 'Normal';

    const STATUS_TB_U_TINGGI = 'Tinggi';

    // BB/TB (Weight-for-Height) & IMT/U — Status Gizi
    const STATUS_GIZI_BURUK = 'Gizi Buruk';

    const STATUS_GIZI_KURANG = 'Gizi Kurang';

    const STATUS_GIZI_BAIK = 'Gizi Baik';

    const STATUS_GIZI_BERISIKO_LEBIH = 'Berisiko Gizi Lebih';

    const STATUS_GIZI_LEBIH = 'Gizi Lebih';

    const STATUS_GIZI_OBESITAS = 'Obesitas';

    protected $fillable = [
        'patient_id', 'user_id', 'visit_date', 'weight', 'height',
        'weight_status', 'kpsp_status', 'tbc_screening_cough', 'tbc_screening_fever',
        'tbc_screening_contact', 'tbc_screening_lethargy', 'tbc_screening_lumps', 
        'other_symptoms', 'pmt_given', 'counseling_notes',
        'referral_type',
        'blood_pressure', 'measurement_method', 'head_circumference', 'upper_arm_circumference', 'immunization',
        'vitamin_a', 'pill_fe', 'is_exclusive_breastfeeding', 'mp_asi',
        'complaint', 'diagnosis', 'disease_history', 'health_note',
        'vaccine_name', 'vaccine_dose', 'vitamin_a_color', 'deworming_medicine',
        'is_basic_immunization_complete',
        'systolic_bp', 'diastolic_bp', 'blood_sugar', 'uric_acid', 'cholesterol',
        'current_medication',
        // Gizi BB/U
        'nutrition_status', 'z_score', 'nutrition_trend',
        // Gizi TB/U (stunting)
        'z_score_hfa', 'stunting_status',
        // Gizi BB/TB (wasting)
        'z_score_wfh', 'wasting_status',
        // Gizi IMT/U (obesitas)
        'z_score_bfa',
        // ANC & Postpartum fields
        'pregnancy_number', 'pregnancy_spacing', 'starting_weight', 'starting_height', 'delivery_date', 'delivery_method',
        'gestational_age', 'imt_plotting_status', 'lila_plotting_status', 'bp_plotting_status', 'tbc_screening_weight_loss',
        'nakes_gives_fe_mms', 'consumes_fe_mms_regularly', 'nakes_gives_mt_kek', 'mt_package_details', 'consumes_mt_kek_regularly',
        'counseling_topic', 'joins_pregnant_class', 'anc_referral',
        'postpartum_period', 'postpartum_imt_plotting', 'postpartum_bp_plotting', 'nakes_gives_vit_a', 'vit_a_capsule_count',
        'consumes_vit_a_regularly', 'is_breastfeeding', 'postpartum_kb', 'postpartum_counseling_topic', 'postpartum_referral',
        // Lansia additional fields
        'waist_circumference', 'eye_test', 'ear_test', 'puma_screening', 'tbc_screening_status', 'mental_screening',
        'contraception', 'family_disease_history', 'risk_behaviors', 'imt', 'education',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'delivery_date' => 'date',
        'vitamin_a' => 'boolean',
        'pill_fe' => 'boolean',
        'is_exclusive_breastfeeding' => 'boolean',
        'mp_asi' => 'boolean',
        'deworming_medicine' => 'boolean',
        'is_basic_immunization_complete' => 'boolean',
        'tbc_screening_cough' => 'boolean',
        'tbc_screening_fever' => 'boolean',
        'tbc_screening_contact' => 'boolean',
        'tbc_screening_lethargy' => 'boolean',
        'tbc_screening_lumps' => 'boolean',
        'tbc_screening_weight_loss' => 'boolean',
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'upper_arm_circumference' => 'decimal:2',
        'waist_circumference' => 'decimal:2',
        'imt' => 'decimal:2',
        'uric_acid' => 'decimal:2',
        'starting_weight' => 'decimal:2',
        'starting_height' => 'decimal:2',
        // Z-Score semua indeks
        'z_score' => 'decimal:2',  // BB/U
        'z_score_hfa' => 'decimal:2',  // TB/U
        'z_score_wfh' => 'decimal:2',  // BB/TB
        'z_score_bfa' => 'decimal:2',  // IMT/U
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
     * Relationship with ChildDevelopment (KPSP)
     */
    public function childDevelopment()
    {
        return $this->hasOne(ChildDevelopment::class);
    }

    /**
     * Override scopeByPosyandu untuk MedicalRecord (akses melalui patient)
     */
    protected function scopeByPosyandu($query, $user)
    {
        if (! $user->posyandu_id) {
            return $query->whereNull('id');
        }

        return $query->whereHas('patient', function ($q) use ($user) {
            $q->where('posyandu_id', $user->posyandu_id);
        });
    }

}
