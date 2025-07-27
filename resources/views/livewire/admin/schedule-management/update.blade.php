@extends('layouts.admin-layout')

@section('admin-title')
    Edit Jadwal Posyandu
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Jadwal Posyandu</h2>
    <x-breadcrumb :items="[
        ['label' => 'Jadwal', 'url' => route('schedules.index')],
        ['label' => $schedule->title, 'url' => route('schedules.show', $schedule->id)],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updateSchedule">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <!-- Kolom 1 -->
            <div class="space-y-6">
                <x-input label="Judul Kegiatan" wire:model.defer="title" placeholder="Contoh: Posyandu Bulanan" required />
                
                <x-textarea label="Deskripsi" wire:model.defer="description" placeholder="Deskripsi kegiatan" rows="4" />
                
                <x-select label="Jenis Kegiatan" wire:model.defer="type" required>
                    <option value="">Pilih Jenis Kegiatan</option>
                    <option value="regular" @selected($type === 'regular')>Posyandu Rutin</option>
                    <option value="vaccination" @selected($type === 'vaccination')>Imunisasi</option>
                    <option value="health_check" @selected($type === 'health_check')>Pemeriksaan Kesehatan</option>
                    <option value="education" @selected($type === 'education')>Penyuluhan Kesehatan</option>
                    <option value="other" @selected($type === 'other')>Lainnya</option>
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
                        <option value="{{ $posyandu->id }}" @selected($posyandu_id == $posyandu->id)>
                            {{ $posyandu->name }}
                        </option>
                    @endforeach
                </x-select>
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('schedules.show', $schedule->id) }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Update Jadwal</x-button>
        </div>
    </form>
</x-card>
@endsection