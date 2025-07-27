@extends('layouts.admin-layout')

@section('admin-title')
    Edit Foto Galeri
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Foto Galeri</h2>
    <x-breadcrumb :items="[
        ['label' => 'Galeri', 'url' => route('galleries.index')],
        ['label' => $gallery->title, 'url' => route('galleries.show', $gallery->id)],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updateGallery" enctype="multipart/form-data">
        <div class="space-y-6">
            <!-- Preview Gambar -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto</label>
                <div class="flex flex-col items-center">
                    @if($image)
                        <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-64 rounded-lg shadow-md">
                    @else
                        <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="h-64 rounded-lg shadow-md">
                    @endif
                    <input type="file" wire:model="image" class="hidden" id="imageUpload">
                    <label for="imageUpload" class="mt-4 cursor-pointer">
                        <x-button variant="outline" type="button" icon="photo">Ubah Foto</x-button>
                    </label>
                    @if($image)
                        <x-button wire:click="removeImage" variant="danger-outline" size="sm" icon="trash" class="mt-2">
                            Hapus Foto Baru
                        </x-button>
                    @endif
                    @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                </div>
            </div>
            
            <x-input label="Judul Foto" wire:model.defer="title" placeholder="Judul untuk foto" required />
            
            <x-textarea label="Deskripsi" wire:model.defer="description" placeholder="Deskripsi singkat tentang foto" rows="3" />
            
            <x-select label="Posyandu" wire:model.defer="posyandu_id">
                <option value="">Pilih Posyandu (opsional)</option>
                @foreach($posyandus as $posyandu)
                    <option value="{{ $posyandu->id }}" @selected($posyandu_id == $posyandu->id)>
                        {{ $posyandu->name }}
                    </option>
                @endforeach
            </x-select>
            
            <x-switch label="Tampilkan di Beranda" wire:model.defer="is_featured" />
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('galleries.show', $gallery->id) }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">Update Foto</x-button>
        </div>
    </form>
</x-card>
@endsection