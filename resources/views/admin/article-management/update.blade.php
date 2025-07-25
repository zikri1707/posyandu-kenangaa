@extends('layouts.admin-layout')

@section('admin-title')
    Edit Artikel - {{ $article->title }}
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Edit Artikel</h2>
    <x-breadcrumb :items="[
        ['label' => 'Artikel', 'url' => route('articles.index')],
        ['label' => $article->title, 'url' => route('articles.show', $article->id)],
        ['label' => 'Edit', 'active' => true]
    ]" />
</div>

<x-card>
    <form wire:submit.prevent="updateArticle">
        <div class="space-y-6">
            <x-input label="Judul Artikel" wire:model.defer="title" placeholder="Judul yang menarik" required />
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Isi Artikel</label>
                <div wire:ignore>
                    <textarea id="editor" wire:model.defer="content" class="hidden">{{ $content }}</textarea>
                </div>
                @error('content') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            
            <x-textarea label="Kutipan (Excerpt)" wire:model.defer="excerpt" placeholder="Ringkasan singkat artikel" rows="3" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Gambar Utama</label>
                    <div class="flex flex-col items-center">
                        @if($image)
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="h-48 rounded-lg shadow-md mb-2">
                        @else
                            <img src="{{ $article->image_url }}" alt="{{ $article->title }}" class="h-48 rounded-lg shadow-md mb-2">
                        @endif
                        <input type="file" wire:model="image" class="hidden" id="imageUpload">
                        <label for="imageUpload" class="cursor-pointer">
                            <x-button variant="outline" type="button" icon="photo">
                                {{ $image ? 'Ganti Gambar' : 'Ubah Gambar' }}
                            </x-button>
                        </label>
                        @if($image)
                            <x-button wire:click="removeImage" variant="danger-outline" size="sm" icon="trash" class="mt-2">
                                Hapus Gambar Baru
                            </x-button>
                        @endif
                        @error('image') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>
                
                <div class="space-y-4">
                    <x-select label="Kategori" wire:model.defer="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" @selected($category_id == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </x-select>
                    
                    <x-input label="Tag (pisahkan dengan koma)" wire:model.defer="tags" placeholder="contoh: kesehatan, anak, imunisasi" />
                    
                    <x-switch label="Publikasikan Sekarang" wire:model.defer="is_published" />
                    
                    @if($is_published)
                        <x-input label="Tanggal Publikasi" wire:model.defer="published_at" type="datetime-local" />
                    @endif
                </div>
            </div>
        </div>

        <div class="flex justify-end mt-8 space-x-3">
            <x-button href="{{ route('articles.show', $article->id) }}" variant="outline">Batal</x-button>
            <x-button type="submit" variant="primary" icon="check">
                {{ $is_published ? 'Update & Publikasikan' : 'Simpan Perubahan' }}
            </x-button>
        </div>
    </form>
</x-card>

@push('scripts')
<script src="https://cdn.ckeditor.com/ckeditor5/34.0.0/classic/ckeditor.js"></script>
<script>
    document.addEventListener('livewire:load', function() {
        ClassicEditor
            .create(document.querySelector('#editor'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertTable', 'undo', 'redo']
            })
            .then(editor => {
                editor.model.document.on('change:data', () => {
                    @this.set('content', editor.getData());
                });
            })
            .catch(error => {
                console.error(error);
            });
    });
</script>
@endpush
@endsection