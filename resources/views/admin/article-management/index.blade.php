@extends('layouts.admin-layout')

@section('admin-title')
    Manajemen Artikel
@endsection

@section('admin-content')
<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-bold">Daftar Artikel Posyandu</h2>
    <x-button href="{{ route('articles.create') }}" icon="plus" variant="primary">
        Buat Artikel
    </x-button>
</div>

<x-card>
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 space-y-4 md:space-y-0">
        <x-search-bar placeholder="Cari artikel..." model="search" />
        
        <div class="flex space-x-2">
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="funnel">
                        Filter Status
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="filterByStatus('all')">Semua</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('published')">Publikasi</x-dropdown.link>
                <x-dropdown.link wire:click="filterByStatus('draft')">Draft</x-dropdown.link>
            </x-dropdown>
            
            <x-dropdown>
                <x-slot name="trigger">
                    <x-button variant="outline" icon="bars-arrow-down">
                        Urutkan
                    </x-button>
                </x-slot>
                <x-dropdown.link wire:click="sortBy('latest')">Terbaru</x-dropdown.link>
                <x-dropdown.link wire:click="sortBy('oldest')">Terlama</x-dropdown.link>
                <x-dropdown.link wire:click="sortBy('popular')">Populer</x-dropdown.link>
            </x-dropdown>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Penulis</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($articles as $article)
                <tr>
                    <td class="px-6 py-4">
                        <div class="font-medium text-gray-900">{{ $article->title }}</div>
                        <div class="text-sm text-gray-500 mt-1">
                            {{ Str::limit($article->excerpt, 70) }}
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex items-center">
                            <x-avatar :initials="$article->author->initials" size="sm" class="mr-3" />
                            <div class="text-sm font-medium text-gray-900">{{ $article->author->name }}</div>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($article->is_published)
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Publikasi
                            </span>
                        @else
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                Draft
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        {{ $article->published_at?->format('d M Y') ?? '-' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex space-x-2 justify-end">
                            <x-button href="{{ route('articles.show', $article->id) }}" variant="outline" size="sm" icon="eye">
                                Lihat
                            </x-button>
                            <x-button href="{{ route('articles.edit', $article->id) }}" variant="outline" size="sm" icon="pencil">
                                Edit
                            </x-button>
                            <x-button wire:click="confirmDelete({{ $article->id }})" variant="danger" size="sm" icon="trash">
                                Hapus
                            </x-button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $articles->links() }}
    </div>
</x-card>

<!-- Delete Confirmation Modal -->
<x-modal id="confirmArticleDeletion" title="Konfirmasi Penghapusan">
    <p class="text-gray-600 mb-4">Apakah Anda yakin ingin menghapus artikel ini?</p>
    
    <div class="flex justify-end space-x-3">
        <x-button @click="open = false" variant="outline">Batal</x-button>
        <x-button wire:click="deleteArticle" variant="danger" icon="trash">Hapus</x-button>
    </div>
</x-modal>
@endsection