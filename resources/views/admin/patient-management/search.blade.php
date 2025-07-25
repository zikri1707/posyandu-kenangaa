@extends('layouts.admin-layout')

@section('admin-title')
    Pencarian Pasien
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Hasil Pencarian Pasien</h2>
    <x-button href="{{ route('patients.index') }}" variant="outline" icon="arrow-left">
        Kembali ke Daftar
    </x-button>
</div>

<x-card>
    <div class="mb-4">
        <p class="text-gray-600">Menampilkan hasil pencarian untuk: <span class="font-semibold">{{ $searchTerm }}</span></p>
        <p class="text-sm text-gray-500">{{ $patients->total() }} hasil ditemukan</p>
    </div>

    @if($patients->isEmpty())
        <div class="text-center py-8">
            <x-icon name="magnifying-glass" class="mx-auto w-12 h-12 text-gray-400" />
            <h3 class="mt-2 text-lg font-medium text-gray-900">Tidak ada hasil ditemukan</h3>
            <p class="mt-1 text-sm text-gray-500">Coba dengan kata kunci yang berbeda</p>
        </div>
    @else
        <x-table :headers="['Nama', 'NIK', 'Umur', 'Pedukuhan', 'Status', 'Aksi']">
            @foreach($patients as $patient)
                <x-table.row>
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
                        <x-badge :color="$patient->is_active ? 'green' : 'red'">
                            {{ $patient->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </x-badge>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2">
                            <x-button href="{{ route('patients.show', $patient->id) }}" variant="outline" size="sm" icon="eye">
                                Detail
                            </x-button>
                            <x-button href="{{ route('patients.edit', $patient->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                        </div>
                    </td>
                </x-table.row>
            @endforeach
        </x-table>

        <div class="mt-4">
            {{ $patients->appends(['search' => $searchTerm])->links() }}
        </div>
    @endif
</x-card>
@endsection