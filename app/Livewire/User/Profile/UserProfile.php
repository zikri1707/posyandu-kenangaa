<?php

namespace App\Livewire\User\Profile;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserProfile extends Component
{
    public $name;

    public $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,'.Auth::id(),
    ];

    public function mount()
    {
        $this->name = Auth::user()->name;
        $this->email = Auth::user()->email;
    }

    public function updateProfile()
    {
        $this->validate();

        Auth::user()->update([
            'name' => $this->name,
            'email' => $this->email,
        ]);

        session()->flash('status', 'Profile updated successfully.');
    }

    public function render()
    {
        return view('livewire.user.profile.user-profile');
    }
}
