<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'posyandu_id' => 'required|exists:posyandus,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'photo' => $this->isMethod('POST') ? 'required|image|mimes:jpeg,png,jpg|max:5120' : 'sometimes|image|mimes:jpeg,png,jpg|max:5120',
            'type' => 'required|in:activity,education,announcement,other',
            'is_featured' => 'sometimes|boolean'
        ];
    }
}