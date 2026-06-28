<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatientRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        if ($this->has('id_number') && !empty($this->id_number)) {
            $this->merge([
                'id_number_hash' => \App\Models\Patient::generateBlindIndex($this->id_number)
            ]);
        }
    }

    /**
     * Configure the validator instance.
     */
    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->has('id_number_hash')) {
                $validator->errors()->add('id_number', 'NIK sudah terdaftar dalam sistem.');
            }
            if ($this->input('category') === 'ibu_hamil' && $this->input('gender') !== 'F') {
                $validator->errors()->add('gender', 'Kategori Ibu Hamil harus berjenis kelamin Perempuan.');
            }
        });
    }

    public function rules()
    {
        $patientId = $this->route('patient') ? $this->route('patient')->id : null;

        return [
            'full_name' => 'required|string|max:255',
            'head_of_family_name' => 'nullable|string|max:255',
            'id_number' => 'required|digits:16',
            'id_number_hash' => 'required|unique:patients,id_number_hash,'.$patientId,
            'category' => 'required|string|in:bayi,baduta,balita,anak_sekolah,ibu_hamil,remaja,lansia,umum',
            'parent_name' => 'nullable|string|max:255',
            'mother_nik' => 'nullable|digits:16',
            'kia_book_ownership' => 'nullable|boolean',
            'gender' => 'required|string|in:M,F',
            'birth_date' => 'required|date|before_or_equal:today',
            'place_of_birth' => 'nullable|string|max:255',
            'address' => 'required|string|max:500',
            'phone_number' => 'required|string|max:15',
            'posyandu_id' => 'nullable|exists:posyandus,id',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'weight_at_birth' => 'nullable|numeric|min:0.5|max:10',
            'height_at_birth' => 'nullable|numeric|min:30|max:60',

            // New Detail Fields
            'husband_name' => 'nullable|string|max:255',
            'dusun_rt_rw' => 'nullable|string|max:255',
            'desa_kelurahan' => 'nullable|string|max:255',
            'kecamatan' => 'nullable|string|max:255',
            'father_name' => 'nullable|string|max:255',
            'mother_name' => 'nullable|string|max:255',
            'guardian_status' => 'nullable|string|max:100',
            'education' => 'nullable|string|max:100',
            'job' => 'nullable|string|max:100',
            'number_of_children' => 'nullable|integer|min:0',
            'is_pregnant' => 'nullable|boolean',
            'living_status' => 'nullable|string|max:100',
            'independence_status' => 'nullable|string|max:100',
            'family_member_count' => 'nullable|integer|min:0',
            'house_condition' => 'nullable|string|max:100',
            'water_access' => 'nullable|string|max:100',
            'has_latrine' => 'nullable|boolean',
            'economic_status' => 'nullable|string|max:100',
            'rt_domisili' => 'nullable|string|max:10',
            'historical_diseases' => 'nullable|string',
        ];
    }

    public function messages()
    {
        return [
            'full_name.required' => 'Nama lengkap wajib diisi.',
            'category.required' => 'Kategori wajib dipilih.',
            'category.in' => 'Kategori harus salah satu dari: bayi, baduta, balita, anak_sekolah, ibu_hamil, remaja, lansia, atau umum.',
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
            'weight_at_birth.max' => 'Berat badan lahir tidak boleh lebih dari 10 kg.',
            'weight_at_birth.numeric' => 'Berat badan lahir harus berupa angka.',
            'weight_at_birth.min' => 'Berat badan lahir minimal 0,5 kg.',
            'height_at_birth.max' => 'Tinggi badan lahir tidak boleh lebih dari 60 cm.',
            'height_at_birth.numeric' => 'Tinggi badan lahir harus berupa angka.',
            'height_at_birth.min' => 'Tinggi badan lahir minimal 30 cm.',
        ];
    }
}
