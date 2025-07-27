<?php

namespace App\Livewire\Auth;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;  // Add this line to import the Hash facade

class ConfirmPassword extends Component
{
    public $password;

    protected $rules = [
        'password' => 'required|min:6',
    ];

    public function confirmPassword()
    {
        $this->validate();

        if (Auth::user()->password !== Hash::make($this->password)) {  // Use Hash facade here
            session()->flash('error', 'The password entered is incorrect.');
            return;
        }

        session()->flash('status', 'Password confirmed!');
        return redirect()->route('dashboard');
    }

    public function render()
    {
        return view('livewire.auth.confirm-password');
    }
}
