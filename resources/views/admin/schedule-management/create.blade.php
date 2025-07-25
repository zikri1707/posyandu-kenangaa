@extends('layouts.admin-layout')

@section('admin-title')
    Tambah Jadwal Baru
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Tambah Jadwal Posyandu</h2>
    <x-breadcrumb :items="[
        ['label' => 'Jadwal', 'url' => route('schedules.index')],
        ['label' => 'Tambah', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="saveSchedule">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-6">
                <x-input label="Judul Kegiatan" wire:model.defer="title" placeholder="Contoh: Posyandu Bulanan" required />
                
                <x-textarea label="Deskripsi" wire:model.defer="description" placeholder="Deskripsi kegiatan" rows="4" />
                
                <x-select label="Jenis Kegiatan" wire:model.defer="type" required>
                    <option value="">Pilih Jenis Kegiatan</option>
                    <option value="regular">Posyandu Rutin</option>
                    <option value="vaccination">Imunisasi</option>
                    <option value="health_check">Pemeriksaan Kesehatan</option>
                    <option value="education">Penyuluhan Kesehatan</option>
                    <option value="other">Lainnya</option>
                </x-select>
            </div>
            
            <!-- Kolom 2 -->
            <div class="space-y-6">
                <x-input label="Tanggal" wire:model.defer="date" type="date" required />
                
                <x-input label="Waktu" wire:model.defer="time" type="time" required />
                
                <x-input label="Lokasi" wire:model.defer="location" placeholder="Lokasi posyandu" required />
                
                <x-select label="Posyandu" wire:model.defer="posyandu_id" required>
                    <option value="">Pilih Posyandu</option>
                    @foreach($posyandus as $posyandu)
                        <option value="{{ $posyandu->id }}">{{ $posyandu->name }}</option>
                    @endforeach
                </x-select>
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('schedules.index') }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Simpan Jadwal</x-button>
        </div>
    </form>
</x-card>
@endsection