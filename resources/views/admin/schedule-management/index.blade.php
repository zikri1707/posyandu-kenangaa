@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Jadwal
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Jadwal Posyandu</h2>
    <x-button href="{{ route('schedules.create') }}" icon="plus" variant="primary">
        Tambah Jadwal
    </x-button>
</div>

<x-card>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <x-search-bar placeholder="Cari jadwal..." model="search" />
        
        <div class="flex space-x-2">
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="calendar">
                        {{ $selectedDateRange ?: 'Filter Tanggal' }}
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="filterByDate('today')">Hari Ini</x-dropdown.link>
                <x-dropdown.link wire:click="filterByDate('week')">Minggu Ini</x-dropdown.link>
                <x-dropdown.link wire:click="filterByDate('month')">Bulan Ini</x-dropdown.link>
                <x-dropdown.link wire:click="filterByDate('all')">Semua Jadwal</x-dropdown.link>
            </x-dropdown>
            
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="funnel">
                        Filter Status
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="filterByStatus('all')">Semua</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('upcoming')">Akan Datang</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('ongoing')">Berlangsung</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('completed')">Selesai</x-dropdown.link>
            </x-dropdown>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Lokasi</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($schedules as $schedule)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                        {{ $schedule->title }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm text-gray-900">{{ $schedule->formatted_date }}</div>
                        <div class="text-sm text-gray-500">{{ $schedule->time }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $schedule->location }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @php
                            $statusClasses = [
                                'upcoming' => 'bg-blue-100 text-blue-800',
                                'ongoing' => 'bg-green-100 text-green-800',
                                'completed' => 'bg-gray-100 text-gray-800'
                            ];
                        @endphp
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClasses[$schedule->status] }}">
                            {{ ucfirst($schedule->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <x-button href="{{ route('schedules.show', $schedule->id) }}" variant="outline" size="sm" icon="eye">
                                Detail
                            </x-button>
                            <x-button href="{{ route('schedules.edit', $schedule->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                            <x-button wire:click="confirmDelete({{ $schedule->id }})" variant="danger" size="sm" icon="trash">
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
        {{ $schedules->links() }}
    </div>
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmScheduleDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus jadwal ini?</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deleteSchedule" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection