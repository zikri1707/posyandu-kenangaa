<?php

use App\Http\Controllers\Auth\VerifyEmailController;
use App\Livewire\Actions\Logout;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt; // Pastikan Logout terimport dengan benar

// Rute untuk pengguna tamu (guest)
Route::middleware('guest')->group(function () {
    Volt::route('login', 'auth.login') // Halaman login
        ->name('login');

    Volt::route('register', 'auth.register') // Halaman registrasi (jika diperlukan)
        ->name('register');

    Volt::route('forgot-password', 'auth.forgot-password') // Halaman untuk meminta reset password
        ->name('password.request');

    Volt::route('reset-password/{token}', 'auth.reset-password') // Halaman untuk reset password
        ->name('password.reset');
});

// Rute untuk pengguna yang sudah terautentikasi
Route::middleware('auth')->group(function () {
    // Halaman verifikasi email
    Volt::route('verify-email', 'auth.verify-email')
        ->name('verification.notice');

    // Rute untuk verifikasi email
    Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['auth', 'signed', 'throttle:6,1'])
        ->name('verification.verify');

    // Rute untuk mengirim ulang link verifikasi email
    Route::post('email/verification-notification', [VerifyEmailController::class, 'resend'])
        ->middleware(['auth', 'throttle:6,1'])
        ->name('verification.resend');

    // Halaman konfirmasi password (untuk pengguna yang sudah login)
    Volt::route('confirm-password', 'auth.confirm-password')
        ->name('password.confirm');
});

// Rute untuk logout
Route::post('logout', Logout::class) // Gunakan Livewire Logout action
    ->name('logout');
