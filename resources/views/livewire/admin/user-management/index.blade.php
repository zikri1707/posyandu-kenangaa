@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Pengguna
@endsection

@section('admin-content')
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Daftar Pengguna Sistem</h2>
        <x-button href="{{ route('users.create') }}" icon="plus" variant="primary">
            Tambah Pengguna
        </x-button>
    </div>

    <x-card>
        <div class="mb-6">
            <x-search-bar placeholder="Cari pengguna..." model="search" />
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status
                        </th>
                        <th scope="col"
                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Terakhir
                            Aktif</th>
                        <th scope="col"
                            class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($users as $user)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <x-avatar :initials="$user->initials" size="sm" class="mr-3" />
                                    <div>
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $user->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->email }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $roleColors = [
                                        'superadmin' => 'bg-purple-100 text-purple-800',
                                        'admin' => 'bg-blue-100 text-blue-800',
                                        'officer' => 'bg-green-100 text-green-800',
                                    ];
                                @endphp
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $roleColors[$user->role] }}">
                                    {{ ucfirst($user->role) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $user->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $user->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $user->last_active_at?->diffForHumans() ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex space-x-2 justify-end">
                                    <x-button href="{{ route('users.edit', $user->id) }}" variant="outline" size="sm"
                                        icon="pencil">
                                        Edit
                                    </x-button>
                                    @if ($user->id !== auth()->id())
                                        <x-button wire:click="confirmDelete({{ $user->id }})" variant="danger"
                                            size="sm" icon="trash">
                                            Hapus
                                        </x-button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="mt-4">
            {{ $users->links() }}
        </div>
    </x-card>

    <!-- Delete Confirmation Modal -->
    <x-modal id="confirmUserDeletion" title="Konfirmasi Penghapusan">
        <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus pengguna ini?</p>

        <div class="flex justify-end space-x-3">
            <x-button @click="open = false" variant="outline">Batal</x-button>
            <x-button wire:click="deleteUser" variant="danger" icon="trash">Hapus</x-button>
        </div>
    </x-modal>
@endsection
