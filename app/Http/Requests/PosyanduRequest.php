<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosyanduRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:500',
            'unique_code' => 'required|string|max:100',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama Posyandu wajib diisi.',
            'address.required' => 'Alamat Posyandu wajib diisi.',
        ];
    }
}
