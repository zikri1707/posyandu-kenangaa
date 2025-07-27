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
            'visit_date' => 'required|date',
            'weight' => 'nullable|numeric',
            'height' => 'nullable|numeric',
            'diagnosis' => 'required|string',
            'nutrition_status' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'patient_id.required' => 'ID pasien wajib diisi.',
            'visit_date.required' => 'Tanggal kunjungan wajib diisi.',
            'diagnosis.required' => 'Diagnosis wajib diisi.',
        ];
    }
}
