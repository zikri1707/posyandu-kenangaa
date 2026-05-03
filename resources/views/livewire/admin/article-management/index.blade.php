@extends('layouts.admin-layout')

@section('admin-title') Manajemen Artikel & Berita @endsection

@section('admin-actions')
    @can('create', App\Models\Article::class)
        <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center justify-center px-5 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-bold uppercase tracking-widest rounded-2xl transition-all shadow-lg shadow-teal-500/20">
            <span class="material-symbols-outlined text-[18px] mr-2">add</span>
            Buat Artikel
        </a>
    @endcan
@endsection

@section('admin-content')

{{-- ── Header Section & Stats --}}
<div class="mb-8">
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Konten & Edukasi</h2>
            <p class="text-slate-500 text-sm mt-1 max-w-xl">
                Kelola artikel kesehatan, berita kegiatan, dan tips gizi untuk warga Posyandu.
            </p>
        </div>
    </div>

    {{-- Stat Card --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 p-6 shadow-sm flex items-center justify-between relative overflow-hidden group">
        <div class="absolute right-0 top-0 w-32 h-32 bg-teal-50 rounded-full -mr-10 -mt-10 transition-transform group-hover:scale-110"></div>
        
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-8 h-8 rounded-xl bg-teal-100 text-teal-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">article</span>
                </div>
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Total Publikasi</span>
            </div>
            <div class="text-3xl font-black text-slate-900 tracking-tighter">{{ $articles->total() }}</div>
            <p class="text-[10px] font-medium text-slate-400 mt-1">Artikel terdaftar di sistem</p>
        </div>
        
        <div class="hidden sm:block relative z-10">
             <a href="{{ route('admin.articles.create') }}" class="text-teal-600 hover:text-teal-700 text-xs font-bold uppercase tracking-widest flex items-center gap-1">
                Tulis Baru <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
             </a>
        </div>
    </div>
</div>

{{-- ── Search & Filter Bar --}}
<div class="bg-white rounded-[2rem] border border-slate-100 p-4 shadow-sm mb-6">
    <div class="flex flex-col md:flex-row items-center gap-4">
        
        {{-- Search Input --}}
        <div class="relative w-full md:flex-1 group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none text-[20px]">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                  placeholder="Cari judul, isi, atau kategori..."
                  class="search-input-premium w-full">
        </div>

        {{-- Filters Group --}}
        <div class="flex w-full md:w-auto gap-3 overflow-x-auto pb-2 md:pb-0 no-scrollbar">
            {{-- Status Filter --}}
            <select wire:model.live="status"
                   class="h-12 px-4 min-w-[140px] border border-slate-100 rounded-2xl text-xs font-bold uppercase tracking-wide text-slate-600 focus:outline-none focus:border-teal-500 transition-all appearance-none cursor-pointer bg-slate-50/50 hover:bg-white">
                <option value="">Semua Status</option>
                <option value="published">Terbit</option>
                <option value="draft">Draft</option>
            </select>

            {{-- Sort --}}
            <select wire:model.live="sort"
                   class="h-12 px-4 min-w-[140px] border border-slate-100 rounded-2xl text-xs font-bold uppercase tracking-wide text-slate-600 focus:outline-none focus:border-teal-500 transition-all appearance-none cursor-pointer bg-slate-50/50 hover:bg-white">
                <option value="latest">Terbaru</option>
                <option value="oldest">Terlama</option>
            </select>
            
            {{-- Reset Button (Mobile Friendly) --}}
            @if($search || $status || $sort !== 'latest')
            <button wire:click="$set('search', ''); $set('status', ''); $set('sort', 'latest');"
                   class="h-12 px-4 rounded-2xl bg-red-50 text-red-600 hover:bg-red-100 transition-colors flex items-center justify-center" title="Reset Filter">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            @endif
        </div>
    </div>
</div>

{{-- ── Articles Table / List --}}
<div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full min-w-[800px]">
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest w-1/2">Informasi Konten</th>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">Penulis</th>
                    <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                    <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($articles as $article)
                <tr class="group hover:bg-slate-50/50 transition-colors duration-200">
                    {{-- Content Info --}}
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-5">
                            <div class="w-14 h-14 rounded-2xl overflow-hidden bg-slate-100 flex-shrink-0 border border-slate-100 shadow-sm">
                                @if($article->thumbnail)
                                    <img src="{{ asset('storage/'.$article->thumbnail) }}" class="w-full h-full object-cover" alt="Thumbnail">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                                        <span class="material-symbols-outlined text-[28px]">image</span>
                                    </div>
                                @endif
                            </div>
                            <div class="min-w-0">
                                <div class="font-bold text-slate-900 text-sm leading-snug mb-1 truncate pr-4" title="{{ $article->title }}">
                                    {{ $article->title }}
                                </div>
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[9px] font-bold uppercase tracking-wider bg-teal-50 text-teal-700 border border-teal-100">
                                        {{ $article->category->name ?? 'Umum' }}
                                    </span>
                                    <span class="text-slate-400 text-[10px] font-medium">
                                        {{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->translatedFormat('d M Y') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </td>

                    {{-- Author --}}
                    <td class="px-8 py-5">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-indigo-50 flex items-center justify-center text-[10px] font-black text-indigo-500 uppercase ring-2 ring-white shadow-sm">
                                {{ substr($article->user->name ?? 'A', 0, 1) }}
                            </div>
                            <span class="text-xs font-semibold text-slate-600 truncate max-w-[120px]">
                                {{ $article->user->name ?? 'Admin' }}
                            </span>
                        </div>
                    </td>

                    {{-- Status Badge --}}
                    <td class="px-8 py-5 text-center">
                        @if($article->status === 'published')
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                Terbit
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                Draft
                            </span>
                        @endif
                    </td>

                    {{-- Actions --}}
                    <td class="px-8 py-5 text-right">
                        <div class="flex items-center justify-end gap-2">
                            <a href="{{ route('admin.articles.show', $article->id) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-teal-600 hover:border-teal-200 hover:shadow-md transition-all"
                               title="Lihat">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                            
                            @can('update', $article)
                            <a href="{{ route('admin.articles.edit', $article->id) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-md transition-all"
                               title="Edit">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            @endcan

                            @can('delete', $article)
                            <div x-data="{ open: false }" class="relative inline-block">
                                <button @click="open = true"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-200 hover:shadow-md transition-all"
                                        title="Hapus">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                                @include('livewire.admin.article-management.delete')
                            </div>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-8 py-20 text-center">
                        <div class="flex flex-col items-center justify-center text-slate-400">
                            <div class="w-16 h-16 bg-slate-50 rounded-full flex items-center justify-center mb-4">
                                <span class="material-symbols-outlined text-[40px] text-slate-300">article</span>
                            </div>
                            <h3 class="text-lg font-bold text-slate-700 mb-1">Belum ada artikel</h3>
                            <p class="text-sm text-slate-500 mb-6 max-w-xs mx-auto">Mulai bagikan informasi bermanfaat dengan membuat artikel pertama Anda.</p>
                            
                            @can('create', App\Models\Article::class)
                            <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-bold uppercase tracking-widest rounded-xl transition-all shadow-lg shadow-indigo-500/20">
                                <span class="material-symbols-outlined text-[18px] mr-2">add</span>
                                Buat Artikel Pertama
                            </a>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($articles->hasPages())
    <div class="px-8 py-6 border-t border-slate-100 bg-slate-50/30">
        {{ $articles->links() }}
    </div>
    @endif
</div>

@endsection