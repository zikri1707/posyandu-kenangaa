<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Verified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\User;


class VerifyEmailController extends Controller
{
    /**
     * Menampilkan halaman konfirmasi verifikasi email.
     */
    public function show(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('info', 'Your email is already verified.');
        }

        return view('auth.verify-email');
    }

    /**
     * Memverifikasi email pengguna berdasarkan ID dan hash.
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'hash' => 'required|string',
        ]);

        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard')->with('info', 'Your email is already verified.');
        }

        if ($request->user()->markEmailAsVerified()) {
            event(new Verified($request->user()));
        }

        return redirect()->route('dashboard')->with('success', 'Your email has been verified.');
    }

    /**
     * Mengirim ulang link verifikasi email.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->sendEmailVerificationNotification();

        return back()->with('resent', true);
    }
}
