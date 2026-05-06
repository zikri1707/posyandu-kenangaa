<?php

namespace App\Livewire\User\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class Password extends Component
{
    public $current_password;

    public $new_password;

    public $new_password_confirmation;

    protected $rules = [
        'current_password' => 'required|min:6',
        'new_password' => 'required|min:6|confirmed',
    ];

    public function updatePassword()
    {
        // Validasi input pengguna
        $this->validate();

        // Cek apakah password saat ini sesuai dengan yang ada di database
        if (! Hash::check($this->current_password, Auth::user()->getAuthPassword())) {
            throw ValidationException::withMessages([
                'current_password' => 'The current password is incorrect.',
            ]);
        }

        // Update password jika valid
        Auth::user()->update([
            'password' => Hash::make($this->new_password),
        ]);

        session()->flash('status', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.user.settings.password');
    }
}
