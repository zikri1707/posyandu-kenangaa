@extends('layouts.admin-layout')

@section('admin-title')
    Edit Data Pedukuhan
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Data Pedukuhan</h2>
    <x-breadcrumb :items="[
        ['label' => 'Pedukuhan', 'url' => route('pedukuhan.index')],
        ['label' => $pedukuhan->name, 'url' => route('pedukuhan.show', $pedukuhan->id)],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updatePedukuhan">
        <div class="space-y-6">
            <x-input label="Nama Pedukuhan" wire:model.defer="name" placeholder="Nama pedukuhan" required />
            
            <x-textarea label="Deskripsi" wire:model.defer="description" placeholder="Deskripsi singkat tentang pedukuhan" rows="3" />
            
            <x-input label="Luas Wilayah (km²)" wire:model.defer="area" type="number" step="0.01" placeholder="0.00" />
            
            <x-input label="Jumlah Penduduk" wire:model.defer="population" type="number" placeholder="0" />
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('pedukuhan.show', $pedukuhan->id) }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Update Pedukuhan</x-button>
        </div>
    </form>
</x-card>
@endsection