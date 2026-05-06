<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class UserService
{
    protected ActivityLogService $activityLogService;

    public function __construct(ActivityLogService $activityLogService)
    {
        $this->activityLogService = $activityLogService;
    }

    /**
     * Create a new user.
     */
    public function createUser(array $data, bool $isActive): User
    {
        $data['is_active'] = $isActive ? 1 : 0;
        $data['email_verified_at'] = now();

        // Handle virtual roles (admin1, admin2, kader1, kader2)
        if (preg_match('/^(admin|kader)([12])$/', $data['role'], $matches)) {
            $data['role'] = $matches[1];
            $data['posyandu_id'] = $matches[2] == '1' ? 3 : 2;
        }

        return User::create($data);
    }

    /**
     * Update an existing user.
     */
    public function updateUser(User $user, array $data, bool $isActive): User
    {
        $data['is_active'] = $isActive ? 1 : 0;

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $oldRole = $user->role;
        $newRole = $data['role'] ?? $oldRole;

        // Handle virtual roles (admin1, admin2, kader1, kader2)
        if (isset($data['role']) && preg_match('/^(admin|kader)([12])$/', $data['role'], $matches)) {
            $data['role'] = $matches[1];
            $data['posyandu_id'] = $matches[2] == '1' ? 3 : 2;
            $newRole = $data['role'];
        }

        $user->update($data);

        // Log activity if role changed
        if ($oldRole !== $newRole) {
            $this->activityLogService->log(
                'change_user_access',
                "Mengubah role pengguna: {$user->name} dari '{$oldRole}' menjadi '{$newRole}'",
                $user->id,
                'User',
                ['role' => $oldRole],
                ['role' => $newRole]
            );
        }

        return $user;
    }

    /**
     * Update current user profile.
     */
    public function updateProfile(User $user, array $data): User
    {
        $oldValues = ['name' => $user->name, 'email' => $user->email];

        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        $this->activityLogService->log(
            'update_profile',
            "Memperbarui profil: {$user->name}",
            $user->id,
            'User',
            $oldValues,
            $user->fresh()->toArray()
        );

        return $user;
    }

    /**
     * Reset user password.
     */
    public function resetPassword(User $user, string $newPassword): void
    {
        $user->update([
            'password' => Hash::make($newPassword),
        ]);

        $this->activityLogService->log(
            'reset_password',
            "Mereset password untuk: {$user->name}",
            $user->id,
            'User'
        );
    }

    /**
     * Delete account with password verification.
     */
    public function deleteAccount(User $user, string $password): void
    {
        if (! Hash::check($password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => 'Password yang Anda masukkan salah.',
            ]);
        }

        $userName = $user->name;
        $userId = $user->id;

        $user->delete();

        $this->activityLogService->log(
            'delete_account',
            "Menghapus akun sendiri: {$userName}",
            $userId,
            'User'
        );
    }
}
