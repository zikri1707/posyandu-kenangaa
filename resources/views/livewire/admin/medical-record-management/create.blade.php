@extends('layouts.admin-layout')

@section('admin-title')
    Tambah Rekam Medis
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Tambah Rekam Medis</h2>
    <x-breadcrumb :items="[
        ['label' => 'Rekam Medis', 'url' => route('medical-records.index')],
        ['label' => 'Tambah', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="saveMedicalRecord">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-6">
                <x-select label="Pasien" wire:model.defer="patient_id" required>
                    <option value="">Pilih Pasien</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}" @selected($patientId == $patient->id)>
                            {{ $patient->name }} ({{ $patient->nik }})
                        </option>
                    @endforeach
                </x-select>
                
                <x-input label="Tanggal Pemeriksaan" wire:model.defer="checkup_date" type="date" required />
                
                <x-input label="Tinggi Badan (cm)" wire:model.defer="height" type="number" step="0.1" required />
                
                <x-input label="Berat Badan (kg)" wire:model.defer="weight" type="number" step="0.1" required />
                
                <x-textarea label="Keluhan" wire:model.defer="complaints" placeholder="Keluhan pasien" rows="3" />
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-6">
                <x-select label="Petugas Kesehatan" wire:model.defer="health_worker_id" required>
                    <option value="">Pilih Petugas</option>
                    @foreach($healthWorkers as $worker)
                        <option value="{{ $worker->id }}">
                            {{ $worker->name }} ({{ $worker->position }})
                        </option>
                    @endforeach
                </x-select>
                
                <x-input label="Suhu Tubuh (°C)" wire:model.defer="temperature" type="number" step="0.1" />
                
                <x-input label="Tekanan Darah (mmHg)" wire:model.defer="blood_pressure" placeholder="Contoh: 120/80" />
                
                <x-textarea label="Diagnosa & Catatan" wire:model.defer="notes" placeholder="Hasil pemeriksaan dan diagnosa" rows="5" required />
            </div>
        </div>
        
        <!-- Tindakan -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Tindakan Medis</h3>
            
            <div class="space-y-4">
                @foreach($treatments as $index => $treatment)
                    <div class="flex items-start space-x-3">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-2 gap-3">
                            <x-input 
                                label="Nama Tindakan" 
                                wire:model.defer="treatments.{{ $index }}.name" 
                                placeholder="Contoh: Imunisasi DPT" />
                                
                            <x-textarea 
                                label="Keterangan" 
                                wire:model.defer="treatments.{{ $index }}.notes" 
                                placeholder="Catatan tambahan" 
                                rows="2" />
                        </div>
                        <button type="button" wire:click="removeTreatment({{ $index }})" class="mt-6 text-red-500 hover:text-red-700">
                            <x-icon name="trash" class="w-5 h-5" />
                        </button>
                    </div>
                @endforeach
                
                <x-button type="button" wire:click="addTreatment" variant="outline" size="sm" icon="plus">
                    Tambah Tindakan
                </x-button>
            </div>
        </div>
        
        <!-- Resep Obat -->
        <div class="mt-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Resep Obat</h3>
            
            <div class="space-y-4">
                @foreach($prescriptions as $index => $prescription)
                    <div class="flex items-start space-x-3">
                        <div class="flex-1 grid grid-cols-1 md:grid-cols-3 gap-3">
                            <x-input 
                                label="Nama Obat" 
                                wire:model.defer="prescriptions.{{ $index }}.medicine" 
                                placeholder="Contoh: Paracetamol" />
                                
                            <x-input 
                                label="Dosis" 
                                wire:model.defer="prescriptions.{{ $index }}.dosage" 
                                placeholder="Contoh: 3x1 sehari" />
                                
                            <x-textarea 
                                label="Instruksi" 
                                wire:model.defer="prescriptions.{{ $index }}.instructions" 
                                placeholder="Instruksi penggunaan" 
                                rows="2" />
                        </div>
                        <button type="button" wire:click="removePrescription({{ $index }})" class="mt-6 text-red-500 hover:text-red-700">
                            <x-icon name="trash" class="w-5 h-5" />
                        </button>
                    </div>
                @endforeach
                
                <x-button type="button" wire:click="addPrescription" variant="outline" size="sm" icon="plus">
                    Tambah Resep
                </x-button>
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('medical-records.index') }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Simpan Rekam Medis</x-button>
        </div>
    </form>
</x-card>
@endsection