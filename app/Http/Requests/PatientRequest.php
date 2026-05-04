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
        $patientId = $this->route('patient') ? $this->route('patient')->id : null;
        
        return [
            'full_name' => 'required|string|max:255',
            'id_number' => 'required|digits:16|unique:patients,id_number,' . $patientId,
            'category' => 'required|string|in:balita,ibu_hamil,remaja,lansia',
            'parent_name' => 'nullable|string|max:255',
            'gender' => 'required|string|in:M,F',
            'birth_date' => 'required|date|before_or_equal:today',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori harus salah satu dari: balita, ibu_hamil, remaja, atau lansia.',
            'id_number.required' => 'NIK wajib diisi.',
            'id_number.digits' => 'NIK harus terdiri dari tepat 16 digit angka.',
            'id_number.unique' => 'NIK ini sudah terdaftar di sistem.',
            'gender.required' => 'Jenis kelamin wajib dipilih.',
            'gender.in' => 'Jenis kelamin harus L atau P.',
            'birth_date.required' => 'Tanggal lahir wajib diisi.',
            'birth_date.date' => 'Tanggal lahir harus berupa tanggal yang valid.',
            'birth_date.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'address.required' => 'Alamat wajib diisi.',
            'phone_number.required' => 'Nomor telepon wajib diisi.',
        ];
    }
}
