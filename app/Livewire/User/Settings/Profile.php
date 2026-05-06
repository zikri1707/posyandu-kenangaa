<?php

namespace App\Livewire\User\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component; // Pastikan namespace ini ada untuk penggunaan Auth

class Profile extends Component
{
    // Mendeklarasikan properti untuk user
    public $user;

    // Fungsi untuk mount, mengambil user yang terautentikasi
    public function mount()
    {
        $this->user = Auth::user();  // Pastikan kita menggunakan Auth yang benar
    }

    // Fungsi render untuk menampilkan tampilan
    public function render()
    {
        return view('livewire.user.settings.profile');  // Pastikan view sudah sesuai
    }
}
