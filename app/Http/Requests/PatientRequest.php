<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'full_name' => 'required|string|max:255',
            'age_category' => 'required|string|in:child,adult,elderly',
            'gender' => 'required|string|in:male,female',
            'birth_date' => 'required|date',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'age_category.required' => 'Kategori usia wajib diisi.',
            'gender.required' => 'Jenis kelamin wajib diisi.',
        ];
    }
}
