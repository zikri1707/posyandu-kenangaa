@extends('layouts.admin-layout')

@section('admin-title')
    Edit Data Pengguna
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Data Pengguna</h2>
    <x-breadcrumb :items="[
        ['label' => 'Pengguna', 'url' => route('users.index')],
        ['label' => $user->name, 'url' => route('users.show', $user->id)],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updateUser">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-6">
                <x-input label="Nama Lengkap" wire:model.defer="name" placeholder="Nama pengguna" required />
                
                <x-input label="Email" wire:model.defer="email" placeholder="email@contoh.com" type="email" required />
                
                <x-input label="Jabatan" wire:model.defer="position" placeholder="Jabatan pengguna" required />
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-6">
                <x-select label="Role" wire:model.defer="role" required>
                    <option value="">Pilih Role</option>
                    <option value="superadmin" @selected($role === 'superadmin')>Super Admin</option>
                    <option value="admin" @selected($role === 'admin')>Admin</option>
                    <option value="officer" @selected($role === 'officer')>Petugas</option>
                </x-select>
                
                <x-input label="Password Baru" wire:model.defer="password" type="password" placeholder="Kosongkan jika tidak diubah" />
                
                <x-input label="Konfirmasi Password" wire:model.defer="password_confirmation" type="password" placeholder="Ulangi password baru" />
                
                <x-switch label="Status Aktif" wire:model.defer="is_active" />
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('users.show', $user->id) }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Update Pengguna</x-button>
        </div>
    </form>
</x-card>
@endsection