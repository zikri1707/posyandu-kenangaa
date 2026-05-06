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
        $userId = $this->route('user') ? $this->route('user')->id : null;

        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$userId,
            'username' => 'required|string|max:255|unique:users,username,'.$userId,
            'password' => ($userId ? 'nullable' : 'required').'|string|min:8|confirmed',
            'role' => 'required|string|in:superadmin,admin,kader,admin1,admin2,kader1,kader2',
            'is_active' => 'sometimes|boolean',
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
