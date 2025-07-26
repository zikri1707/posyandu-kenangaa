<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\PosyanduController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\ArticleController;
use App\Http\Controllers\Admin\MedicalRecordController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PedukuhanController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\ConfirmPasswordController;

// Home Page (Welcome page)
Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::middleware('guest')->group(function () {
    // Login Routes
    Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('login', [LoginController::class, 'login']);

    // Register Routes
    Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [RegisterController::class, 'register']);

    // Password Reset Routes
    Route::get('password/reset', [PasswordResetController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('password/email', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('password/reset/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
    Route::post('password/reset', [PasswordResetController::class, 'reset'])->name('password.update');

    // Email Verification Routes
    Route::get('email/verify', [VerifyEmailController::class, 'show'])->name('verification.notice');
    Route::get('email/verify/{id}/{hash}', [VerifyEmailController::class, 'verify'])->name('verification.verify');
    Route::post('email/resend', [VerifyEmailController::class, 'resend'])->name('verification.resend');

    // Password Confirmation Routes
    Route::get('password/confirm', [ConfirmPasswordController::class, 'showConfirmForm'])->name('password.confirm');
    Route::post('password/confirm', [ConfirmPasswordController::class, 'confirm']);
});

// Protected Routes (Require Login)
Route::middleware(['auth', 'verified'])->group(function () {
    // Dashboard (Admin Dashboard)
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin Management Routes
    Route::resource('admin/patients', PatientController::class);
    Route::resource('admin.posyandu', PosyanduController::class);
    Route::resource('admin.schedules', ScheduleController::class);
    Route::resource('admin.gallery', GalleryController::class);
    Route::resource('admin.articles', ArticleController::class);
    Route::resource('admin.medical-records', MedicalRecordController::class);
    Route::resource('admin.users', UserController::class);
    Route::resource('admin.pedukuhans', PedukuhanController::class);

    // Logout
    Route::post('logout', [LoginController::class, 'logout'])->name('logout');
});

// Route for 404 page
Route::fallback(function () {
    return response()->view('errors.404', [], 404);
});
