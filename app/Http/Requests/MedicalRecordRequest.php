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
        $patientId = $this->input('patient_id');
        $patient = $patientId ? \App\Models\Patient::find($patientId) : null;
        $isChild = $patient ? in_array($patient->category, ['bayi', 'baduta', 'balita', 'anak_sekolah']) : true;

        return [
            'patient_id' => 'required|exists:patients,id',
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

            // Patient Identity Fields (if updated during record creation)
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'weight_at_birth' => 'nullable|numeric|min:0.5|max:10',
            'height_at_birth' => 'nullable|numeric|min:30|max:60',
            
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
}
