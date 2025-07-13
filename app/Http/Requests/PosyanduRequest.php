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
            'address' => 'nullable|string|max:1000',
            'phone' => 'nullable|string|max:20',
            'logo_photo' => 'nullable|image|max:2048',
            'pedukuhan_id' => 'required|exists:pedukuhans,id',
        ];
    }
}
