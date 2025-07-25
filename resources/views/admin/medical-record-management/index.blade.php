@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Rekam Medis
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Rekam Medis</h2>
    @if($patientId)
        <x-button href="{{ route('medical-records.create', ['patient_id' => $patientId]) }}" icon="plus" variant="primary">
            Tambah Rekam Medis
        </x-button>
    @else
        <x-button href="{{ route('medical-records.create') }}" icon="plus" variant="primary">
            Tambah Rekam Medis
        </x-button>
    @endif
</div>

<x-card>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <x-search-bar placeholder="Cari rekam medis..." model="search" />
        
        <div class="flex space-x-2">
            @if($patientId)
                <x-button href="{{ route('medical-records.index') }}" variant="outline" icon="arrow-left">
                    Semua Rekam Medis
                </x-button>
            @endif
            
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="funnel">
                        Filter
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="filterByPeriod('all')">Semua</x-dropdown.link>
                <x-dropdown.link wire:click="filterByPeriod('today')">Hari Ini</x-dropdown.link>
                <x-dropdown.link wire:click="filterByPeriod('week')">Minggu Ini</x-dropdown.link>
                <x-dropdown.link wire:click="filterByPeriod('month')">Bulan Ini</x-dropdown.link>
            </x-dropdown>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pasien</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tinggi/Berat</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($medicalRecords as $record)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $record->checkup_date->format('d M Y') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <x-avatar :src="$record->patient->photo_url" :initials="$record->patient->initials" size="sm" class="mr-3" />
                            <div>
                                <div class="font-medium text-gray-900">{{ $record->patient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $record->patient->age }} tahun</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $record->healthWorker->name }}</div>
                        <div class="text-sm text-gray-500">{{ $record->healthWorker->position }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $record->height }} cm / {{ $record->weight }} kg</div>
                        <div class="text-xs text-gray-500">IMT: {{ $record->bmi }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500">
                        {{ Str::limit($record->notes, 50) ?: '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <x-button href="{{ route('medical-records.show', $record->id) }}" variant="outline" size="sm" icon="eye">
                                Detail
                            </x-button>
                            <x-button href="{{ route('medical-records.edit', $record->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                            <x-button wire:click="confirmDelete({{ $record->id }})" variant="danger" size="sm" icon="trash">
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
        {{ $medicalRecords->links() }}
    </div>
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmMedicalRecordDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus rekam medis ini?</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deleteMedicalRecord" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection