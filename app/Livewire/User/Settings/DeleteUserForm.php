<?php

namespace App\Livewire\User\Settings;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class DeleteUserForm extends Component
{
    public $password;

    protected $rules = [
        'password' => 'required|min:6',
    ];

    public function deleteUser()
    {
        // Validasi password
        $this->validate();

        // Cek apakah password saat ini sesuai dengan yang ada di database
        if (! Hash::check($this->password, Auth::user()->getAuthPassword())) {
            throw ValidationException::withMessages([
                'password' => 'The password you entered is incorrect.',
            ]);
        }

        // Hapus user setelah validasi
        Auth::user()->delete();
        Auth::logout();

        session()->flash('status', 'Your account has been deleted.');

        return redirect()->route('login'); // Redirect to home page after deletion
    }

    public function render()
    {
        return view('livewire.user.settings.delete-user-form');
    }
}
