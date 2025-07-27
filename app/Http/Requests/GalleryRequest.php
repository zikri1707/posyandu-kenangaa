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
        return [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Judul gambar wajib diisi.',
            'photo.required' => 'Foto gambar wajib diunggah.',
            'photo.image' => 'File yang diunggah harus berupa gambar.',
        ];
    }
}
