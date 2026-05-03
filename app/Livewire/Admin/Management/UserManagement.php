<?php

namespace App\Livewire\Admin\Management;

use App\Models\User;
use App\Models\Posyandu;
use App\Livewire\Shared\BaseAdminComponent;

class UserManagement extends BaseAdminComponent
{
    public string $search = '';
    public string $role = '';
    public string $status = '';
    
    protected $queryString = [
        'search' => ['except' => ''],
        'role'   => ['except' => ''],
        'status' => ['except' => ''],
    ];

    public function render()
    {
        // Scope pengguna berdasarkan level akses (Opsional, di sini kita tampilkan semua untuk SuperAdmin/Admin)
        $query = User::with('posyandu')
            ->when($this->search, function($q) {
                $q->where(function($sq) {
                    $sq->where('name', 'like', '%' . $this->search . '%')
                       ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->role,   fn($q) => $q->where('role', $this->role))
            ->when($this->status, fn($q) => $q->where('is_active', $this->status === 'active'))
            ->latest();

        $stats = User::selectRaw('COUNT(*) as total')
            ->selectRaw('COUNT(CASE WHEN is_active = 0 THEN 1 END) as inactive')
            ->first();

        return view('livewire.admin.user-management.index', [
            'users'          => $query->paginate(10),
            'totalUsers'     => $stats->total ?? 0,
            'totalPosyandu'  => Posyandu::count(),
            'inactiveUsers'  => $stats->inactive ?? 0,
        ]);
    }
}
