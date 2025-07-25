@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Pasien
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Pasien</h2>
    <x-button href="{{ route('patients.create') }}" icon="plus" variant="primary">
        Tambah Pasien
    </x-button>
</div>

<x-card>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <x-search-bar placeholder="Cari pasien..." model="search" />
        
        <div class="flex space-x-2">
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="funnel">
                        Filter
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="filterByStatus('all')">Semua</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('active')">Aktif</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('inactive')">Non-Aktif</x-dropdown.link>
            </x-dropdown>
            
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="bars-arrow-down">
                        Urutkan
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="sortBy('name')">Nama (A-Z)</x-dropdown.link>
                <x-dropdown.link wire:click="sortBy('created_at')">Terbaru</x-dropdown.link>
                <x-dropdown.link wire:click="sortBy('age')">Umur</x-dropdown.link>
            </x-dropdown>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">NIK</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Umur</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pedukuhan</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($patients as $patient)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <x-avatar :src="$patient->photo_url" :initials="$patient->initials" size="sm" class="mr-3" />
                            <div>
                                <div class="font-medium text-gray-900">{{ $patient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $patient->phone }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $patient->nik }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $patient->age }} tahun
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $patient->pedukuhan->name }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($patient->is_active)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Aktif
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                Non-Aktif
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <x-button href="{{ route('patients.show', $patient->id) }}" variant="outline" size="sm" icon="eye">
                                Detail
                            </x-button>
                            <x-button href="{{ route('patients.edit', $patient->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                            <x-button wire:click="confirmDelete({{ $patient->id }})" variant="danger" size="sm" icon="trash">
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $patients->links() }}
    </div>
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmPatientDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus pasien ini? Data yang dihapus tidak dapat dikembalikan.</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deletePatient" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection