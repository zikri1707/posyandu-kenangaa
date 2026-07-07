<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function showLoginForm(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $loginField = filter_var($request->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        if (Auth::attempt([$loginField => $request->email, 'password' => $request->password])) {
            return redirect()->route('dashboard')->with('success', 'Berhasil masuk ke sistem.');
        }

        return back()->withErrors(['email' => 'Email/Username atau password yang Anda masukkan salah.']);
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('public.home')->with('success', 'Berhasil keluar.');
    }
}
