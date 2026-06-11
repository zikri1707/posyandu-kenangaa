<div class="space-y-8 p-6 md:p-8 pt-2 md:pt-4">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
        <div class="relative pl-6">
            {{-- Vertical Bar --}}
            <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full"></div>
            
            <div class="flex flex-col gap-4">

                <div>
                    <h1 class="text-4xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">
                        Artikel & Berita
                    </h1>
                    <p class="text-sm font-bold text-slate-900 mt-3">Kelola konten edukasi kesehatan dan informasi penting posyandu.</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Modern Header with Hero Mesh Banner ── --}}
    <div class="relative rounded-[2rem] p-8 md:p-10 overflow-hidden text-white shadow-2xl shadow-emerald-100"
         style="background-color: #064e3b; background-image: radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.5) 0px, transparent 50%), radial-gradient(at 50% 0%, hsla(168, 76%, 36%, 0.5) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.3) 0px, transparent 50%);">
        {{-- Decorative Elements --}}
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>

        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[10px] font-black uppercase tracking-[0.3em] text-white -mt-4 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Content & Education
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight text-white">Manajemen Artikel</h1>
                <p class="text-base text-white font-medium max-w-2xl leading-relaxed">
                    Kelola materi edukasi kesehatan, berita kegiatan, dan informasi penting untuk warga Posyandu secara profesional.
                </p>
            </div>

            <div class="flex items-center">
                @can('create', App\Models\Article::class)
                <a href="{{ route('admin.articles.create') }}"
                   class="h-16 px-10 flex items-center gap-4 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white rounded-[1.5rem] text-xs font-black uppercase tracking-[0.2em] shadow-2xl shadow-teal-500/40 transition-all hover:-translate-y-1 active:scale-95 group">
                    <span class="material-symbols-outlined text-[24px] group-hover:rotate-90 transition-transform">add_circle</span>
                    Tulis Artikel Baru
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ── Search & Advanced Filters (Compact & High Contrast) ── --}}
    <div class="bg-white rounded-[2rem] p-4 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col lg:flex-row items-center gap-4">
        {{-- Search Input --}}
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors text-[20px]">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                  placeholder="Cari judul, kategori, atau penulis..."
                  class="w-full h-12 pl-12 pr-6 bg-slate-50 border-none rounded-2xl text-sm font-bold text-slate-900 placeholder:text-slate-400 focus:ring-4 focus:ring-teal-500/10 transition-all">
        </div>

        {{-- Filters Group --}}
        <div class="flex items-center gap-3 w-full lg:w-auto">
            <div class="w-44">
                <x-forms.select-input wire:model.live="status" placeholder="Semua Status" :placeholderDisabled="false" value="{{ $status }}" class="!h-12 !rounded-2xl !bg-slate-50 !border-none !text-[11px] !font-black !text-slate-700 !uppercase !tracking-widest pr-10">
                    <option value="published">Terbit</option>
                    <option value="draft">Draft</option>
                </x-forms.select-input>
            </div>

            <div class="w-44">
                <x-forms.select-input wire:model.live="sort" placeholder="" value="{{ $sort }}" class="!h-12 !rounded-2xl !bg-slate-50 !border-none !text-[11px] !font-black !text-slate-700 !uppercase !tracking-widest pr-10">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </x-forms.select-input>
            </div>

            @if($search || $status || $sort !== 'latest')
            <button wire:click="$set('search', ''); $set('status', ''); $set('sort', 'latest');"
                    class="h-12 w-12 flex items-center justify-center bg-red-50 text-red-600 rounded-2xl hover:bg-red-100 transition-colors shadow-sm" title="Reset Filter">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>
            @endif
        </div>
    </div>

    {{-- ── Articles Grid Layout (Medium Style) ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($articles as $article)
        <div class="group bg-white rounded-[2rem] border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden flex flex-col h-full">
            {{-- Thumbnail Section --}}
            <div class="relative h-48 bg-gradient-to-br from-slate-100 to-slate-200 overflow-hidden group-hover:scale-105 transition-transform duration-500">
                @if($article->thumbnail && Storage::disk('public')->exists($article->thumbnail))
                    <img src="{{ asset('storage/'.$article->thumbnail) }}" class="w-full h-full object-cover" alt="{{ $article->title }}">
                @else
                    <div class="w-full h-full flex items-center justify-center text-slate-300">
                        <span class="material-symbols-outlined text-[80px]">image</span>
                    </div>
                @endif
                {{-- Status Badge --}}
                <div class="absolute top-4 left-4 flex items-center gap-2">
                    @if($article->status === 'published')
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-500/90 text-white backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white animate-pulse"></span>
                            Terbit
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-500/90 text-white backdrop-blur-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>
                            Draft
                        </span>
                    @endif
                </div>
            </div>

            {{-- Content Section --}}
            <div class="p-6 flex-1 flex flex-col gap-4">
                {{-- Category & Meta --}}
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-600 text-[9px] font-black uppercase tracking-widest border border-indigo-100">
                        {{ $article->category->name ?? 'Umum' }}
                    </span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wide">
                        <span class="material-symbols-outlined text-[14px] align-middle">schedule</span> {{ $article->reading_time }}
                    </span>
                </div>

                {{-- Title --}}
                <h3 class="text-lg font-black text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-3">
                    {{ $article->title }}
                </h3>

                {{-- Excerpt --}}
                <p class="text-sm text-slate-500 leading-relaxed line-clamp-2 flex-1">
                    {{ $article->excerpt }}
                </p>

                {{-- Author Info --}}
                <div class="flex items-center gap-3 pt-4 border-t border-slate-50">
                    <div class="w-9 h-9 rounded-full bg-indigo-100 flex items-center justify-center text-[11px] font-black text-indigo-600 uppercase flex-shrink-0">
                        {{ substr($article->user->name ?? 'A', 0, 1) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs font-bold text-slate-900 truncate">{{ $article->user->name ?? 'Admin' }}</p>
                        <p class="text-[10px] text-slate-400 uppercase tracking-tight">
                            {{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->translatedFormat('d M Y') }}
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons --}}
            <div class="px-6 pb-6 pt-4 flex items-center gap-2 border-t border-slate-50">
                <a href="{{ route('admin.articles.show', $article->id) }}"
                   class="flex-1 h-10 flex items-center justify-center rounded-lg bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold text-xs uppercase tracking-widest transition-all"
                   title="Lihat Detail">
                    <span class="material-symbols-outlined text-[16px]">visibility</span>
                </a>

                @if($article->status === 'published')
                <a href="{{ route('public.articles.show', $article->slug) }}" target="_blank"
                   class="flex-1 h-10 flex items-center justify-center rounded-lg bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white font-bold text-xs uppercase tracking-widest transition-all"
                   title="Lihat di Web">
                    <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                </a>
                @endif
                
                @can('update', $article)
                <a href="{{ route('admin.articles.edit', $article->id) }}"
                   class="flex-1 h-10 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white font-bold text-xs uppercase tracking-widest transition-all">
                    <span class="material-symbols-outlined text-[16px]">edit</span>
                </a>
                @endcan

                @can('delete', $article)
                <button wire:click="deleteArticle({{ $article->id }})"
                        wire:confirm="Hapus artikel ini secara permanen?"
                        class="flex-1 h-10 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-600 hover:text-white font-bold text-xs uppercase tracking-widest transition-all">
                    <span class="material-symbols-outlined text-[16px]">delete</span>
                </button>
                @endcan
            </div>
        </div>
        @empty
        <div class="col-span-full py-32">
            <div class="flex flex-col items-center gap-6 text-center">
                <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                    <span class="material-symbols-outlined text-[64px]">article</span>
                </div>
                <div>
                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-300 mb-4">Belum ada konten publikasi</p>
                    @can('create', App\Models\Article::class)
                    <a href="{{ route('admin.articles.create') }}" class="inline-flex items-center gap-3 text-indigo-600 font-black uppercase tracking-widest text-sm hover:text-indigo-700 transition-colors px-6 py-3 bg-indigo-50 rounded-xl border border-indigo-200">
                        <span class="material-symbols-outlined">add_circle</span>
                        Mulai Menulis Artikel Pertama
                    </a>
                    @endcan
                </div>
            </div>
        </div>
        @endforelse
    </div>

    {{-- Enhanced Pagination --}}
    @if($articles->hasPages())
    <div class="flex justify-center pt-12">
        {{ $articles->links() }}
    </div>
    @endif
</div>