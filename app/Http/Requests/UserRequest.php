<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all users to make this request
    }

    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->route('user'),
            'username' => 'required|string|max:255|unique:users,username,' . $this->route('user'),
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|string|in:superadmin,admin,coordinator,staff,medical,patient,partner',
            'is_active' => 'required|boolean',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'Nama pengguna wajib diisi.',
            'email.required' => 'Email pengguna wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'password.required' => 'Password pengguna wajib diisi.',
        ];
    }
}
