@extends('layouts.admin-layout')

@section('admin-title')
    Dashboard Admin Posyandu
@endsection

@section('admin-content')
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Data Cards -->
    <x-datacard 
        title="Total Pasien" 
        value="{{ $totalPatients }}" 
        color="blue" 
        trend="up" 
        change="5.2" />
        
    <x-datacard 
        title="Jadwal Hari Ini" 
        value="{{ $todaySchedules }}" 
        color="green" 
        trend="down" 
        change="2.1" />
        
    <x-datacard 
        title="Artikel Terbit" 
        value="{{ $publishedArticles }}" 
        color="indigo" />
        
    <x-datacard 
        title="Rekam Medis Baru" 
        value="{{ $newMedicalRecords }}" 
        color="purple" 
        trend="up" 
        change="12.7" />
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Jadwal Terdekat -->
    <x-card title="Jadwal Terdekat" headerAction="{{ $viewAllSchedulesLink }}">
        <div class="space-y-4">
            @forelse($upcomingSchedules as $schedule)
                <div class="flex items-start">
                    <div class="flex-shrink-0 bg-blue-100 p-2 rounded-lg text-blue-600">
                        📅 <!-- Emoji sebagai pengganti ikon kalender -->
                    </div>
                    <div class="ml-4 flex-1">
                        <h4 class="font-medium text-gray-900">{{ $schedule->title }}</h4>
                        <p class="text-sm text-gray-500 mt-1">
                            🕒 <!-- Emoji jam --> {{ $schedule->formatted_date }} • {{ $schedule->time }}
                        </p>
                        <p class="text-sm text-gray-500 mt-1">
                            📍 <!-- Emoji lokasi --> {{ $schedule->location }}
                        </p>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 text-center py-4">Tidak ada jadwal terdekat</p>
            @endforelse
        </div>
    </x-card>

    <!-- Pasien Terbaru -->
    <x-card title="Pasien Terbaru">
        <table class="table-auto w-full">
            <thead>
                <tr>
                    <th class="px-6 py-4 text-left">Nama</th>
                    <th class="px-6 py-4 text-left">Umur</th>
                    <th class="px-6 py-4 text-left">Pedukuhan</th>
                    <th class="px-6 py-4 text-left">Tanggal Daftar</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentPatients as $patient)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <x-avatar :src="$patient->photo_url" :initials="$patient->initials" size="sm" class="mr-3" />
                                <div>
                                    <div class="font-medium text-gray-900">{{ $patient->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $patient->nik }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $patient->age }} tahun
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            {{ $patient->pedukuhan->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $patient->created_at->format('d M Y') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                            Tidak ada pasien terbaru
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </x-card>
</div>
@endsection
