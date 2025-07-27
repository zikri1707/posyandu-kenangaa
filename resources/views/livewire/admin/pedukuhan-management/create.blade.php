@extends('layouts.admin-layout')

@section('admin-title')
    Tambah Pedukuhan Baru
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Tambah Pedukuhan Baru</h2>
    <x-breadcrumb :items="[
        ['label' => 'Pedukuhan', 'url' => route('pedukuhan.index')],
        ['label' => 'Tambah', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="savePedukuhan">
        <div class="space-y-6">
            <x-input label="Nama Pedukuhan" wire:model.defer="name" placeholder="Nama pedukuhan" required />
            
            <x-textarea label="Deskripsi" wire:model.defer="description" placeholder="Deskripsi singkat tentang pedukuhan" rows="3" />
            
            <x-input label="Luas Wilayah (km²)" wire:model.defer="area" type="number" step="0.01" placeholder="0.00" />
            
            <x-input label="Jumlah Penduduk" wire:model.defer="population" type="number" placeholder="0" />
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('pedukuhan.index') }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Simpan Pedukuhan</x-button>
        </div>
    </form>
</x-card>
@endsection