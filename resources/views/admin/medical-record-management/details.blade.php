@extends('layouts.admin-layout')

@section('admin-title')
    Detail Rekam Medis - {{ $medicalRecord->patient->name }}
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h2 class="text-2xl font-bold">Detail Rekam Medis</h2>
        <x-breadcrumb :items="[
            ['label' => 'Rekam Medis', 'url' => route('medical-records.index')],
            ['label' => $medicalRecord->patient->name, 'active' => true]
        ]" />
    </div>
    <div class="flex space-x-2">
        <x-button href="{{ route('medical-records.edit', $medicalRecord->id) }}" variant="outline" icon="pencil">
            Edit
        </x-button>
        <x-button href="{{ route('medical-records.index') }}" variant="outline" icon="arrow-left">
            Kembali
        </x-button>
    </div>
</div>

<x-card>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
        <!-- Info Pasien -->
        <div class="md:col-span-1">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Pasien</h3>
            
            <div class="flex items-center space-x-4 mb-4">
                <x-avatar :src="$medicalRecord->patient->photo_url" size="xl" />
                <div>
                    <h4 class="font-bold">{{ $medicalRecord->patient->name }}</h4>
                    <p class="text-sm text-gray-500">{{ $medicalRecord->patient->age }} tahun</p>
                    <p class="text-sm text-gray-500">{{ $medicalRecord->patient->pedukuhan->name }}</p>
                </div>
            </div>
            
            <div class="space-y-2">
                <x-detail-item label="NIK">
                    {{ $medicalRecord->patient->nik }}
                </x-detail-item>
                <x-detail-item label="Jenis Kelamin">
                    {{ $medicalRecord->patient->gender === 'L' ? 'Laki-laki' : 'Perempuan' }}
                </x-detail-item>
                <x-detail-item label="Golongan Darah">
                    {{ $medicalRecord->patient->blood_type ?: '-' }}
                </x-detail-item>
            </div>
        </div>
        
        <!-- Detail Pemeriksaan -->
        <div class="md:col-span-2">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Detail Pemeriksaan</h3>
            
            <div class="bg-gray-50 p-6 rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tanggal Pemeriksaan</h4>
                        <p class="font-medium">{{ $medicalRecord->checkup_date->format('d F Y') }}</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Petugas Kesehatan</h4>
                        <div class="flex items-center">
                            <x-avatar :initials="$medicalRecord->healthWorker->initials" size="sm" class="mr-3" />
                            <div>
                                <p class="font-medium">{{ $medicalRecord->healthWorker->name }}</p>
                                <p class="text-sm text-gray-500">{{ $medicalRecord->healthWorker->position }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Tinggi Badan</h4>
                        <p class="font-medium">{{ $medicalRecord->height }} cm</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Berat Badan</h4>
                        <p class="font-medium">{{ $medicalRecord->weight }} kg</p>
                    </div>
                    
                    <div>
                        <h4 class="text-sm font-medium text-gray-500 mb-1">Indeks Massa Tubuh</h4>
                        <p class="font-medium">{{ $medicalRecord->bmi }} ({{ $medicalRecord->bmi_category }})</p>
                    </div>
                </div>
                
                <div class="mb-6">
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Keluhan</h4>
                    <p class="font-medium">{{ $medicalRecord->complaints ?: '-' }}</p>
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-500 mb-1">Diagnosa & Catatan</h4>
                    <div class="prose max-w-none">
                        {!! $medicalRecord->notes !!}
                    </div>
                </div>
            </div>
            
            <!-- Tindakan & Resep -->
            <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Tindakan</h4>
                    @if($medicalRecord->treatments->isEmpty())
                        <p class="text-gray-500">Tidak ada tindakan</p>
                    @else
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($medicalRecord->treatments as $treatment)
                                <li>{{ $treatment->name }}</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
                
                <div>
                    <h4 class="text-sm font-medium text-gray-900 mb-2">Resep Obat</h4>
                    @if($medicalRecord->prescriptions->isEmpty())
                        <p class="text-gray-500">Tidak ada resep obat</p>
                    @else
                        <ul class="list-disc pl-5 space-y-1">
                            @foreach($medicalRecord->prescriptions as $prescription)
                                <li>{{ $prescription->medicine }} ({{ $prescription->dosage }})</li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-card>
@endsection