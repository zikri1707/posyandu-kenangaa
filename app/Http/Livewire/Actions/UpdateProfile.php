<?php

namespace App\Http\Livewire\Actions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class UpdateProfile extends Component
{
    public $name, $email;

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|unique:users,email,' . Auth::id(),
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
        return view('livewire.action.update-profile');
    }
}
