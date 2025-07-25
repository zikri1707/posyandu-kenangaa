@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Galeri
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Galeri Foto Posyandu</h2>
    <x-button href="{{ route('galleries.create') }}" icon="plus" variant="primary">
        Tambah Foto
    </x-button>
</div>

<x-card>
    <div class="mb-6">
        <x-search-bar placeholder="Cari foto..." model="search" />
    </div>

    @if($galleries->isEmpty())
        <div class="text-center py-12">
            <x-icon name="photo" class="mx-auto w-12 h-12 text-gray-400" />
            <h3 class="mt-2 text-lg font-medium text-gray-900">Belum ada foto</h3>
            <p class="mt-1 text-sm text-gray-500">Tambahkan foto pertama Anda ke galeri</p>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
                <div class="group relative rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-200">
                    <img src="{{ $gallery->image_url }}" alt="{{ $gallery->title }}" class="w-full h-48 object-cover">
                    
                    <div class="absolute inset-0 bg-black bg-opacity-0 group-hover:bg-opacity-30 transition-all duration-200 flex items-end">
                        <div class="w-full p-4 transform translate-y-full group-hover:translate-y-0 transition-transform duration-200 bg-gradient-to-t from-black to-transparent">
                            <h3 class="text-white font-medium">{{ $gallery->title }}</h3>
                            <p class="text-sm text-gray-300">{{ $gallery->created_at->format('d M Y') }}</p>
                            
                            <div class="mt-2 flex space-x-2">
                                <x-button href="{{ route('galleries.edit', $gallery->id) }}" variant="outline-white" size="xs" icon="pencil">
                                    Edit
                                </x-button>
                                <x-button wire:click="confirmDelete({{ $gallery->id }})" variant="danger" size="xs" icon="trash">
                                    Hapus
                                </x-button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-4">
            {{ $galleries->links() }}
        </div>
    @endif
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmGalleryDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus foto ini?</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deleteGallery" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection