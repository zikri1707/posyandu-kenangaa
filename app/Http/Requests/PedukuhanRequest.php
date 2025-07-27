<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PedukuhanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'postal_code' => 'required|string|max:5',
            'geo_location' => 'required|json',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama pedukuhan wajib diisi.',
            'postal_code.required' => 'Kode pos wajib diisi.',
        ];
    }
}
