<?php

namespace App\Http\Livewire\Actions;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class DeleteAccount extends Component
{
    public $password;

    protected $rules = [
        'password' => 'required|min:6',
    ];

    public function deleteAccount()
    {
        // Validasi password
        $this->validate();

        // Cek apakah password saat ini sesuai dengan yang ada di database
        if (!Hash::check($this->password, Auth::user()->getAuthPassword())) {
            throw ValidationException::withMessages([
                'password' => 'The password you entered is incorrect.',
            ]);
        }

        // Hapus user setelah validasi
        Auth::user()->delete();
        Auth::logout();

        session()->flash('status', 'Your account has been deleted successfully.');
        return redirect()->route('home'); // Redirect to home page after deletion
    }

    public function render()
    {
        return view('livewire.action.delete-account');
    }
}
