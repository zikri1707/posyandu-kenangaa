@extends('layouts.admin-layout')

@section('admin-title')
    Detail Jadwal - {{ $schedule->title }}
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">Detail Jadwal Posyandu</h2>
        <x-breadcrumb :items="[
            ['label' => 'Jadwal', 'url' => route('schedules.index')],
            ['label' => $schedule->title, 'active' => true]
        ]" />
    </div>
    <div class="flex space-x-2">
        <x-button href="{{ route('schedules.edit', $schedule->id) }}" variant="outline" icon="pencil">
            Edit
        </x-button>
        <x-button href="{{ route('schedules.index') }}" variant="outline" icon="arrow-left">
            Kembali
        </x-button>
    </div>
</div>

<x-card>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Info Utama -->
        <div class="md:col-span-2">
            <h3 class="text-xl font-bold text-gray-900 mb-4">{{ $schedule->title }}</h3>
            
            <div class="space-y-4">
                <x-detail-item label="Jenis Kegiatan">
                    {{ ucfirst($schedule->type) }}
                </x-detail-item>
                
                <x-detail-item label="Tanggal & Waktu">
                    {{ $schedule->formatted_date }} • {{ $schedule->time }}
                </x-detail-item>
                
                <x-detail-item label="Lokasi">
                    {{ $schedule->location }}
                </x-detail-item>
                
                <x-detail-item label="Posyandu">
                    {{ $schedule->posyandu->name }}
                </x-detail-item>
                
                <x-detail-item label="Status">
                    <x-badge :color="[
                        'upcoming' => 'blue',
                        'ongoing' => 'green',
                        'completed' => 'gray'
                    ][$schedule->status]">
                        {{ ucfirst($schedule->status) }}
                    </x-badge>
                </x-detail-item>
                
                <x-detail-item label="Deskripsi">
                    {{ $schedule->description ?: '-' }}
                </x-detail-item>
            </div>
        </div>
        
        <!-- Statistik -->
        <div class="space-y-6">
            <div class="bg-blue-50 p-4 rounded-lg">
                <h4 class="font-medium text-blue-800 mb-2">Kehadiran</h4>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold">{{ $schedule->attendances_count }}</p>
                        <p class="text-sm text-blue-600">Total Hadir</p>
                    </div>
                    <x-icon name="user-group" class="w-10 h-10 text-blue-400" />
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg">
                <h4 class="font-medium text-green-800 mb-2">Imunisasi</h4>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold">{{ $schedule->vaccinations_count }}</p>
                        <p class="text-sm text-green-600">Total Diberikan</p>
                    </div>
                    <x-icon name="syringe" class="w-10 h-10 text-green-400" />
                </div>
            </div>
            
            <div class="bg-purple-50 p-4 rounded-lg">
                <h4 class="font-medium text-purple-800 mb-2">Pemeriksaan</h4>
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-bold">{{ $schedule->medical_records_count }}</p>
                        <p class="text-sm text-purple-600">Catatan Medis</p>
                    </div>
                    <x-icon name="document-text" class="w-10 h-10 text-purple-400" />
                </div>
            </div>
        </div>
    </div>
</x-card>

<!-- Daftar Kehadiran -->
<x-card class="mt-6">
    <h3 class="text-lg font-medium mb-4">Daftar Kehadiran</h3>
    
    @if($schedule->attendances->isEmpty())
        <p class="text-gray-500 py-4 text-center">Belum ada data kehadiran</p>
    @else
        <x-table :headers="['Nama Pasien', 'Waktu Hadir', 'Keterangan']">
            @foreach($schedule->attendances as $attendance)
                <x-table.row>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <x-avatar :src="$attendance->patient->photo_url" :initials="$attendance->patient->initials" size="sm" class="mr-3" />
                            <div>
                                <div class="font-medium text-gray-900">{{ $attendance->patient->name }}</div>
                                <div class="text-sm text-gray-500">{{ $attendance->patient->pedukuhan->name }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $attendance->created_at->format('d M Y H:i') }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $attendance->notes ?: '-' }}
                    </td>
                </x-table.row>
            @endforeach
        </x-table>
    @endif
</x-card>
@endsection