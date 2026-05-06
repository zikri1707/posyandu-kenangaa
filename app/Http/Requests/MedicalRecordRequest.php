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
        return [
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date|before_or_equal:today',
            'weight' => 'required|numeric|min:0.5|max:200',
            'height' => 'required|numeric|min:30|max:300',
            'head_circumference' => 'nullable|numeric|min:20|max:70',
            'measurement_method' => 'required|in:recumbent,standing',
            'blood_pressure' => 'nullable|string|max:20',
            'vitamin_a' => 'nullable|boolean',
            'pill_fe' => 'nullable|boolean',
            'is_exclusive_breastfeeding' => 'nullable|boolean',
            'diagnosis' => 'required|string',
            'complaint' => 'nullable|string',
            'disease_history' => 'nullable|string',
            'health_note' => 'nullable|string',
            'immunization' => 'nullable|string',
            'vaccine_name' => 'nullable|string|max:50',
            'vaccine_dose' => 'nullable|integer|min:1|max:10',
            'vitamin_a_color' => 'nullable|in:biru,merah,none',
            'deworming_medicine' => 'nullable|boolean',
            'nutrition_status' => 'nullable|string',
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
            'height.min' => 'Tinggi badan minimal 30 cm.',
            'height.max' => 'Tinggi badan maksimal 300 cm.',
            'measurement_method.required' => 'Cara ukur wajib dipilih.',
            'measurement_method.in' => 'Cara ukur harus Telentang (Recumbent) atau Berdiri (Standing).',
            'head_circumference.numeric' => 'Lingkar kepala harus berupa angka.',
            'head_circumference.min' => 'Lingkar kepala minimal 20 cm.',
            'head_circumference.max' => 'Lingkar kepala maksimal 70 cm.',
            'diagnosis.required' => 'Diagnosis/Hasil akhir wajib diisi.',
            'vaccine_dose.integer' => 'Dosis vaksin harus berupa angka.',
            'vaccine_dose.min' => 'Dosis vaksin minimal 1.',
            'vitamin_a_color.in' => 'Warna Vitamin A harus Biru, Merah, atau Tidak Ada.',
        ];
    }
}
