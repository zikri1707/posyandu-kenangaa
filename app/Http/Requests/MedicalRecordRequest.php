<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MedicalRecordRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'weight' => 'required|numeric|min:1|max:200',
            'height' => 'required|numeric|min:30|max:250',
            'head_circumference' => 'nullable|numeric|min:10|max:100',
            'temperature' => 'nullable|numeric|between:30,45',
            'blood_pressure' => 'nullable|string|max:10',
            'complaint' => 'required|string',
            'diagnosis' => 'required|string',
            'treatment' => 'required|string',
            'notes' => 'nullable|string',
            'immunization_status' => 'nullable|string',
            'developmental_notes' => 'nullable|string',
            'referral' => 'nullable|string'
        ];
    }

    public function messages(): array
    {
        return [
            'weight.min' => 'Weight cannot be less than 1 kg',
            'height.min' => 'Height cannot be less than 30 cm'
        ];
    }
}