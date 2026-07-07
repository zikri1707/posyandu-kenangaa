<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GalleryRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'is_featured' => 'boolean',
        ];

        if ($this->isMethod('POST')) {
            $rules['photos'] = 'required|array';
            $rules['photos.*'] = 'required|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm,mkv|max:1048576';
        } else {
            $rules['photos'] = 'nullable|array';
            $rules['photos.*'] = 'nullable|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm,mkv|max:1048576';
        }

        return $rules;
    }

    public function messages()
    {
        return [
            'photos.required' => 'Wajib memilih minimal satu file media.',
            'photos.*.file' => 'File yang diunggah tidak valid.',
            'photos.*.mimes' => 'Format file harus berupa gambar (jpg, jpeg, png, webp, gif) atau video (mp4, mov, avi, webm, mkv).',
            'photos.*.max' => 'Ukuran file tidak boleh melebihi 1GB.',
        ];
    }
}
