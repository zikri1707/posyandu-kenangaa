<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $category = $this->input('category');
        $patientId = $this->input('patient_id');
        $patient = $patientId ? \App\Models\Patient::find($patientId) : null;
        $patientCategory = $patient ? $patient->category : $category;
        $isChild = in_array($patientCategory ?? 'balita', ['bayi', 'baduta', 'balita', 'anak_sekolah']);

        return [
            'patient_id' => 'required_without:full_name|exists:patients,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'weight' => 'required|numeric|min:0.5|max:200',
            'height' => 'required|numeric|min:20|max:300',
            'head_circumference' => 'nullable|numeric|min:20|max:70',
            'upper_arm_circumference' => 'nullable|numeric|min:5|max:40',
            'measurement_method' => $isChild ? 'required|in:recumbent,standing' : 'nullable|in:recumbent,standing',
            'blood_pressure' => 'nullable|string|max:20',
            'vitamin_a' => 'nullable|boolean',
            'pill_fe' => 'nullable|boolean',
            'is_exclusive_breastfeeding' => 'nullable|boolean',
            'mp_asi' => 'nullable|boolean',
            'diagnosis' => 'required|string',
            'complaint' => 'nullable|string',
            'disease_history' => 'nullable|string',
            'health_note' => 'nullable|string',
            'immunization' => 'nullable|string',
            'vaccine_name' => 'nullable|string|max:50',
            'vaccine_dose' => 'nullable|integer|min:1|max:10',
            'is_basic_immunization_complete' => 'nullable|boolean',
            'vitamin_a_color' => 'nullable|in:biru,merah,none',
            'deworming_medicine' => 'nullable|boolean',
            
            // New UI Integration Fields
            'weight_status' => 'nullable|in:N,T,2T',
            'kpsp_status' => 'nullable|in:Lengkap,Tidak Lengkap',
            'tbc_screening_cough' => 'nullable|boolean',
            'tbc_screening_fever' => 'nullable|boolean',
            'tbc_screening_contact' => 'nullable|boolean',
            'tbc_screening_lethargy' => 'nullable|boolean',
            'tbc_screening_lumps' => 'nullable|boolean',
            'other_symptoms' => 'nullable|string',
            'pmt_given' => 'nullable|string|max:255',
            'counseling_notes' => 'nullable|string',
            'referral_type' => 'nullable|in:None,Pustu,Puskesmas,RS',

            // Patient Identity Fields (if updated/created during record creation)
            'full_name' => 'nullable|string|max:255',
            'id_number' => 'nullable|string|max:20',
            'birth_date' => 'nullable|date',
            'phone_number' => 'nullable|string|max:20',
            'husband_name' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'dusun_rt_rw' => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'weight_at_birth' => 'nullable|numeric|min:0.5|max:10',
            'height_at_birth' => 'nullable|numeric|min:30|max:60',

            // Pregnancy details
            'pregnancy_number' => 'nullable|integer|min:1',
            'pregnancy_spacing' => 'nullable|string|max:255',
            'starting_weight' => 'nullable|numeric|min:30|max:200',
            'starting_height' => 'nullable|numeric|min:100|max:250',
            'delivery_date' => 'nullable|date',
            'delivery_method' => 'nullable|string|max:255',
            
            // KPSP (Child Development) fields
            'kpsp_age_group' => 'nullable|integer',
            'kpsp_motor_gross' => 'nullable|boolean',
            'kpsp_motor_fine' => 'nullable|boolean',
            'kpsp_language' => 'nullable|boolean',
            'kpsp_social' => 'nullable|boolean',
            'kpsp_note' => 'nullable|string',

            // Lansia Metrics
            'systolic_bp' => 'nullable|integer|min:30|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'blood_sugar' => 'nullable|integer|min:10|max:1000',
            'uric_acid' => 'nullable|numeric|min:0.1|max:30',
            'cholesterol' => 'nullable|integer|min:50|max:600',
            'current_medication' => 'nullable|string',
            'waist_circumference' => 'nullable|numeric|min:30|max:200',
            'eye_test' => 'nullable|string|max:255',
            'ear_test' => 'nullable|string|max:255',
            'puma_screening' => 'nullable|string|max:255',
            'tbc_screening_status' => 'nullable|string|max:255',
            'mental_screening' => 'nullable|string|max:255',
            'contraception' => 'nullable|string|max:255',
            'family_disease_history' => 'nullable|array',
            'risk_behaviors' => 'nullable|array',
            'imt' => 'nullable|numeric',
            'education' => 'nullable|string',
            'gender' => 'nullable|in:L,P',
            'category' => 'nullable|string|in:ibu_hamil,lansia,balita',

            // Section 2 additions
            'gestational_age' => 'nullable|string|max:255',
            'imt_plotting_status' => 'nullable|string|max:255',
            'lila_plotting_status' => 'nullable|string|max:255',
            'bp_plotting_status' => 'nullable|string|max:255',
            'tbc_screening_weight_loss' => 'nullable|boolean',
            'nakes_gives_fe_mms' => 'nullable|string|max:255',
            'consumes_fe_mms_regularly' => 'nullable|string|max:255',
            'nakes_gives_mt_kek' => 'nullable|string|max:255',
            'mt_package_details' => 'nullable|string|max:255',
            'consumes_mt_kek_regularly' => 'nullable|string|max:255',
            'counseling_topic' => 'nullable|string|max:255',
            'joins_pregnant_class' => 'nullable|string|max:255',
            'anc_referral' => 'nullable|string',

            // Section 3 additions
            'postpartum_period' => 'nullable|string|max:255',
            'postpartum_imt_plotting' => 'nullable|string|max:255',
            'postpartum_bp_plotting' => 'nullable|string|max:255',
            'nakes_gives_vit_a' => 'nullable|string|max:255',
            'vit_a_capsule_count' => 'nullable|string|max:255',
            'consumes_vit_a_regularly' => 'nullable|string|max:255',
            'is_breastfeeding' => 'nullable|string|max:255',
            'postpartum_kb' => 'nullable|string|max:255',
            'postpartum_counseling_topic' => 'nullable|string|max:255',
            'postpartum_referral' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'ID pasien wajib diisi.',
            'patient_id.exists' => 'Pasien yang dipilih tidak ditemukan.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
            'visit_date.date' => 'Format tanggal kunjungan tidak valid.',
            'visit_date.before_or_equal' => 'Tanggal kunjungan tidak boleh melebihi tanggal hari ini.',
            'weight.required' => 'Berat badan wajib diisi.',
            'weight.numeric' => 'Berat badan harus berupa angka.',
            'weight.min' => 'Berat badan minimal 0,5 kg.',
            'weight.max' => 'Berat badan maksimal 200 kg.',
            'height.required' => 'Tinggi badan wajib diisi.',
            'height.numeric' => 'Tinggi badan harus berupa angka.',
            'height.min' => 'Tinggi badan minimal 20 cm.',
            'height.max' => 'Tinggi badan maksimal 300 cm.',
            'measurement_method.required' => 'Cara ukur wajib dipilih.',
            'measurement_method.in' => 'Cara ukur harus Telentang (Recumbent) atau Berdiri (Standing).',
            'head_circumference.numeric' => 'Lingkar kepala harus berupa angka.',
            'head_circumference.min' => 'Lingkar kepala minimal 20 cm.',
            'head_circumference.max' => 'Lingkar kepala maksimal 70 cm.',
            'upper_arm_circumference.numeric' => 'Lingkar lengan atas harus berupa angka.',
            'upper_arm_circumference.min' => 'Lingkar lengan atas minimal 5 cm.',
            'upper_arm_circumference.max' => 'Lingkar lengan atas maksimal 40 cm.',
            'diagnosis.required' => 'Diagnosis/Hasil akhir wajib diisi.',
            'vaccine_dose.integer' => 'Dosis vaksin harus berupa angka.',
            'vaccine_dose.min' => 'Dosis vaksin minimal 1.',
            'vitamin_a_color.in' => 'Warna Vitamin A harus Biru, Merah, atau Tidak Ada.',
        ];
    }

    /**
     * Add after-validation hook.
     */
    public function after(): array
    {
        return [
            function ($validator) {
                $category = $this->input('category');
                $idNumber = $this->input('id_number');
                $patientId = $this->input('patient_id');

                if ($idNumber && $category) {
                    $hash = \App\Models\Patient::generateBlindIndex($idNumber);
                    $existing = \App\Models\Patient::where('id_number_hash', $hash)
                        ->when($patientId, function($q) use ($patientId) {
                            $q->where('id', '!=', $patientId);
                        })
                        ->first();

                    if ($existing) {
                        $existingCat = $existing->category;
                        $childCategories = ['bayi', 'baduta', 'balita', 'anak_sekolah'];
                        
                        $isExistingChild = in_array($existingCat, $childCategories);
                        $isCurrentChild = in_array($category, $childCategories);

                        if (($isExistingChild && !$isCurrentChild) || (!$isExistingChild && $isCurrentChild) || (!$isExistingChild && !$isCurrentChild && $existingCat !== $category)) {
                            $catLabels = [
                                'ibu_hamil' => 'Ibu Hamil',
                                'lansia' => 'Lansia',
                                'balita' => 'Balita',
                                'bayi' => 'Bayi',
                                'baduta' => 'Baduta',
                                'anak_sekolah' => 'Anak Sekolah'
                            ];
                            $existingLabel = $catLabels[$existingCat] ?? $existingCat;
                            $currentLabel = $catLabels[$category] ?? $category;

                            $validator->errors()->add(
                                'id_number',
                                "NIK ini sudah terdaftar sebagai pasien {$existingLabel}. Tidak bisa mendaftarkannya sebagai {$currentLabel}."
                            );
                        }
                    }
                }
            }
        ];
    }
}
