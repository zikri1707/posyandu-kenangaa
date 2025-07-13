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
            'name' => 'required|string|max:100|unique:pedukuhans,name,'.$this->pedukuhan?->id,
            'postal_code' => 'required|string|max:10',
            'village' => 'required|string|max:100',
            'subdistrict' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'province' => 'required|string|max:100',
            'geo_location' => 'nullable|json',
            'population' => 'nullable|integer',
            'area_size' => 'nullable|numeric'
        ];
    }
}