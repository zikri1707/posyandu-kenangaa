<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::with('posyandu')
            ->filter([
                'search' => $request->search,
                'role' => $request->role,
                'status' => $request->status,
            ])
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        $totalUsers = User::count();
        $totalRoles = User::distinct('role')->count('role');
        $inactiveUsers = User::where('is_active', false)->count();
        $totalPosyandu = \App\Models\Posyandu::count();

        return view('livewire.admin.user-management.index', compact(
            'users', 'totalUsers', 'totalRoles', 'inactiveUsers', 'totalPosyandu'
        ));
    }

    public function create()
    {
        return view('livewire.admin.user-management.create');
    }

    public function store(UserRequest $request, \App\Services\UserService $userService)
    {
        try {
            $userService->createUser($request->validated(), $request->has('is_active'));
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
        }
    }

    public function show(User $user)
    {
        return view('livewire.admin.user-management.details', compact('user'));
    }

    public function edit(User $user)
    {
        return view('livewire.admin.user-management.update', compact('user'));
    }

    public function update(UserRequest $request, User $user, \App\Services\UserService $userService)
    {
        try {
            $userService->updateUser($user, $request->validated(), $request->has('is_active'));
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->withInput()->with('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
        }
    }

    public function destroy(User $user, \App\Services\UserService $userService)
    {
        try {
            $userService->deleteUser($user);
            return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users.index')->with('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
        }
    }
}
