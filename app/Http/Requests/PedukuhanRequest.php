<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedukuhanRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:10',
            'geo_location' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama pedukuhan wajib diisi.',
            'name.max' => 'Nama pedukuhan maksimal 255 karakter.',
            'postal_code.required' => 'Kode pos wajib diisi.',
            'postal_code.max' => 'Kode pos maksimal 10 karakter.',
        ];
    }
}
