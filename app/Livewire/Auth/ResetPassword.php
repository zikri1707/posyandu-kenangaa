<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;  // Add this line to import Hash facade

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
            ['email' => $this->email, 'password' => $this->password, 'password_confirmation' => $this->password_confirmation, 'token' => $this->token],
            function ($user) {
                $user->password = Hash::make($this->password);  // Use Hash facade here
                $user->save();
                event(new PasswordReset($user));
            }
        );

        if ($status == Password::PASSWORD_RESET) {
            session()->flash('status', 'Your password has been reset!');
            return redirect()->route('login');
        }

        session()->flash('error', 'Failed to reset password.');
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
