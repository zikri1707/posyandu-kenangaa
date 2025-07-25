<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use \Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{
    // Middleware for role authorization
    public function __construct()
    {
        $this->middleware('user:superadmin')->except(['profile', 'updateProfile', 'changePassword']);
    }

    /**
     * Display a listing of users by role
     */
    public function index(Request $request): View
    {
        $role = $request->query('role');
        $users = User::when($role, fn($q) => $q->where('role', $role))
                    ->orderBy('name')
                    ->get();

        return view('admin.user-management.index', compact('users'));
    }

    /**
     * Show form to create a new user
     */
    public function create(): View
    {
        $roles = ['admin', 'coordinator', 'staff', 'medical', 'patient', 'partner'];
        return view('admin.user-management.create', compact('roles'));
    }

    /**
     * Store new user
     */
    public function store(UserRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);
        
        User::create($data);

        return redirect()->route('users.index')
            ->with('success', 'User created successfully');
    }

    /**
     * Show user details
     */
    public function show(User $user): View
    {
        return view('admin.user-management.show', compact('user'));
    }

    /**
     * Edit user
     */
    public function edit(User $user): View
    {
        $roles = ['admin', 'coordinator', 'staff', 'medical', 'patient', 'partner'];
        return view('admin.user-management.edit', compact('user', 'roles'));
    }

    /**
     * Update user
     */
    public function update(UserRequest $request, User $user): RedirectResponse
    {
        $data = $request->validated();
        
        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $user->update($data);

        return redirect()->route('users.index')
            ->with('success', 'User updated successfully');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user): RedirectResponse
    {
        $user->update(['is_active' => false]);

        return back()->with('success', 'User deactivated');
    }

    /**
     * Activate user
     */
    public function activate(User $user): RedirectResponse
    {
        $user->update(['is_active' => true]);

        return back()->with('success', 'User activated');
    }

    /**
     * Show user profile
     */
    public function profile(): View
    {
        return view('profile', ['user' => Auth::user()]);
    }

    /**
     * Update user profile
     */
    public function updateProfile(Request $request): RedirectResponse
    {
        $user = User::findOrFail(Auth::id());
        $data = $request->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$user->id}",
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated');
    }

    /**
     * Change password
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $authUser = Auth::user();
        $user = User::findOrFail($authUser->id);

        if (!Hash::check($request->input('current_password'), (string) $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect password']);
        }

        $user->update([
            'password' => Hash::make($request->input('new_password'))
        ]);

        return back()->with('success', 'Password changed successfully');
    }
}
