<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user?->id;

        return [
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($userId)
            ],
            'username' => [
                'required',
                'string',
                'max:30',
                Rule::unique('users')->ignore($userId)
            ],
            'password' => $this->isMethod('POST') ? 'required|min:8' : 'sometimes|min:8',
            'role' => [
                'required',
                Rule::in(['superadmin', 'admin', 'coordinator', 'staff', 'medical', 'patient', 'partner'])
            ],
            'is_active' => 'sometimes|boolean',
            'profile_photo' => 'sometimes|image|mimes:jpeg,png,jpg|max:2048'
        ];
    }

    public function messages(): array
    {
        return [
            'role.in' => 'Role must be one of: superadmin, admin, coordinator, staff, medical, patient, partner'
        ];
    }
}