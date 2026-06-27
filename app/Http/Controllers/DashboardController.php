<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        if (! Auth::check()) {
            return redirect()->route('login');
        }

        /** @var User $user */
        $user = Auth::user();

        // Redirect based on user role
        if ($user->isSuperAdmin() || $user->isAdmin() || $user->isKader()) {
            return view('admin.dashboard');
        }

        // Default fallback to admin dashboard for now, or logout if invalid
        return view('admin.dashboard');
    }
}
