<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PosyanduRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'pedukuhan_id' => 'required|exists:pedukuhans,id',
            'address' => 'required|string|max:500',
            'unique_code' => 'nullable|string|max:100',
            'logo_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama posyandu wajib diisi.',
            'pedukuhan_id.required' => 'Pedukuhan wajib dipilih.',
            'pedukuhan_id.exists' => 'Pedukuhan yang dipilih tidak valid.',
            'address.required' => 'Alamat posyandu wajib diisi.',
            'logo_photo.image' => 'File harus berupa gambar.',
            'logo_photo.max' => 'Ukuran gambar maksimal 2 MB.',
        ];
    }
}
