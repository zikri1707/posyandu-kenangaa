<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => 'required|exists:posyandus,id',
            'id_number' => 'required|string|max:20|unique:patients,id_number,'.$this->patient?->id,
            'full_name' => 'required|string|max:255',
            'birth_date' => 'required|date',
            'gender' => 'required|in:L,P',
            'address' => 'required|string',
            'phone_number' => 'required|string|max:15',
            'age_category' => 'required|in:bayi,balita,remaja,dewasa,lansia',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048',
            'blood_type' => 'nullable|string|max:2',
            'family_card_number' => 'nullable|string|max:20'
        ];
    }
}