@extends('layouts.admin-layout')

@section('admin-title')
    Detail Pasien - {{ $patient->name }}
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">Detail Pasien</h2>
        <x-breadcrumb :items="[
            ['label' => 'Pasien', 'url' => route('patients.index')],
            ['label' => $patient->name, 'active' => true]
        ]" />
    </div>
    <div class="flex space-x-2">
        <x-button href="{{ route('patients.edit', $patient->id) }}" variant="outline" icon="pencil">
            Edit
        </x-button>
        <x-button href="{{ route('patients.index') }}" variant="outline" icon="arrow-left">
            Kembali
        </x-button>
    </div>
</div>

<x-card>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Profil Pasien -->
        <div class="md:col-span-1">
            <div class="flex flex-col items-center text-center">
                <x-avatar :src="$patient->photo_url" size="2xl" class="mb-4" />
                <h3 class="text-xl font-bold text-gray-900">{{ $patient->name }}</h3>
                <p class="text-gray-500">{{ $patient->nik }}</p>
                
                <div class="mt-4">
                    <x-badge :color="$patient->is_active ? 'green' : 'red'">
                        {{ $patient->is_active ? 'Aktif' : 'Non-Aktif' }}
                    </x-badge>
                </div>
                
                <div class="mt-6 w-full space-y-3">
                    <div class="flex justify-between">
                        <span class="text-gray-500">Jenis Kelamin:</span>
                        <span class="font-medium">{{ $patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Umur:</span>
                        <span class="font-medium">{{ $patient->age }} tahun</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500">Gol. Darah:</span>
                        <span class="font-medium">{{ $patient->blood_type ?? '-' }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Data Lengkap -->
        <div class="md:col-span-2">
            <div class="space-y-6">
                <x-detail-section title="Informasi Pribadi">
                    <x-detail-item label="Tempat/Tanggal Lahir">
                        {{ $patient->birth_place }}, {{ $patient->birth_date->format('d F Y') }}
                    </x-detail-item>
                    <x-detail-item label="Alamat">
                        {{ $patient->address }}
                    </x-detail-item>
                    <x-detail-item label="Pedukuhan">
                        {{ $patient->pedukuhan->name }}
                    </x-detail-item>
                    <x-detail-item label="Posyandu">
                        {{ $patient->posyandu->name }}
                    </x-detail-item>
                </x-detail-section>
                
                <x-detail-section title="Kontak">
                    <x-detail-item label="Nomor Telepon">
                        {{ $patient->phone }}
                    </x-detail-item>
                    <x-detail-item label="Email">
                        {{ $patient->email ?? '-' }}
                    </x-detail-item>
                </x-detail-section>
                
                <x-detail-section title="Catatan Medis Terakhir">
                    @if($latestMedicalRecord)
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex justify-between items-start">
                                <div>
                                    <h4 class="font-medium">{{ $latestMedicalRecord->checkup_date->format('d F Y') }}</h4>
                                    <p class="text-sm text-gray-500">Oleh: {{ $latestMedicalRecord->healthWorker->name }}</p>
                                </div>
                                <x-badge color="blue">
                                    {{ $latestMedicalRecord->height }} cm / {{ $latestMedicalRecord->weight }} kg
                                </x-badge>
                            </div>
                            <div class="mt-3">
                                <p class="text-sm">{{ $latestMedicalRecord->notes ?: 'Tidak ada catatan' }}</p>
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">Belum ada catatan medis</p>
                    @endif
                    <div class="mt-3">
                        <x-button href="{{ route('medical-records.create', ['patient_id' => $patient->id]) }}" variant="outline" size="sm">
                            Tambah Catatan Medis
                        </x-button>
                    </div>
                </x-detail-section>
            </div>
        </div>
    </div>
</x-card>

<!-- Riwayat Medis -->
<x-card class="mt-6">
    <h3 class="text-lg font-medium mb-4">Riwayat Medis</h3>
    
    @if($medicalRecords->isEmpty())
        <p class="text-gray-500 py-4 text-center">Belum ada riwayat medis</p>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Petugas</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tinggi/Berat</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Catatan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"></th>
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
                                    <x-avatar :initials="$record->healthWorker->initials" size="sm" class="mr-3" />
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $record->healthWorker->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $record->healthWorker->position }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $record->height }} cm / {{ $record->weight }} kg
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">
                                {{ Str::limit($record->notes, 50) ?: '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <x-button href="{{ route('medical-records.show', $record->id) }}" variant="outline" size="sm">
                                    Detail
                                </x-button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="mt-4">
            {{ $medicalRecords->links() }}
        </div>
    @endif
</x-card>
@endsection