<?php


// app/Http/Controllers/Auth/ConfirmPasswordController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfirmPasswordController extends Controller
{
    /**
     * Show the confirm password page.
     *
     * @return \Illuminate\View\View
     */
    public function showConfirmForm()
    {
        return view('auth.confirm-password');
    }

    /**
     * Confirm the user's password.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function confirm(Request $request)
    {
        $request->validate(['password' => 'required|string']);

        if (Auth::check() && Auth::user()->password == bcrypt($request->password)) {
            return redirect()->route('dashboard');
        }

        return back()->withErrors(['password' => 'Incorrect password.']);
    }
}
