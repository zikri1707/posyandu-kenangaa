<?php

namespace App\Livewire\Actions;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;  // Add this line to import the Hash facade
use Illuminate\Validation\ValidationException;

class ResetPassword extends Component
{
    public $email, $token, $password, $password_confirmation;

    protected $rules = [
        'password' => 'required|min:6|confirmed',
    ];

    public function mount($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->password = Hash::make($this->password);  // Using Hash facade here
                $user->save();
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', 'Password has been reset.');
            return redirect()->route('login');
        }

        session()->flash('error', 'Failed to reset the password.');
    }

    public function render()
    {
        return view('livewire.action.reset-password');
    }
}
