{{-- ── Articles Management — Ultra-Premium UI ── --}}
<div class="max-w-[1440px] mx-auto space-y-10 pb-20">

    {{-- ── Modern Header with Mesh Gradient ── --}}
    <div class="relative rounded-[3rem] p-8 md:p-12 overflow-hidden bg-slate-900 text-white shadow-2xl">
        {{-- Mesh Gradient Background --}}
        <div class="absolute inset-0 opacity-30 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-indigo-500 rounded-full blur-[120px]"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-blue-600 rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[11px] font-black uppercase tracking-[0.2em] text-indigo-300">
                    <span class="w-2 h-2 rounded-full bg-indigo-400 animate-pulse"></span>
                    Content & Education
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">Manajemen Artikel</h1>
                <p class="text-lg text-slate-300 font-medium max-w-2xl">
                    Kelola materi edukasi kesehatan, berita kegiatan, dan informasi penting untuk warga Posyandu.
                </p>
            </div>

            <div class="flex items-center gap-4">
                @can('create', App\Models\Article::class)
                <a href="{{ route('admin.articles.create') }}"
                   class="h-16 px-8 flex items-center gap-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-indigo-600/30 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-[28px]">add_circle</span>
                    Tulis Artikel Baru
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ── Search & Advanced Filters (High Contrast) ── --}}
    <div class="bg-white rounded-[2.5rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 flex flex-col lg:flex-row items-center gap-6">
        {{-- Search Input --}}
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-6 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-indigo-600 transition-colors text-[24px]">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                  placeholder="Cari judul, kategori, atau penulis..."
                  class="w-full h-16 pl-16 pr-8 bg-slate-50 border-none rounded-3xl text-lg font-bold text-slate-900 placeholder:text-slate-400 focus:ring-4 focus:ring-indigo-500/10 transition-all">
        </div>

        {{-- Filters Group --}}
        <div class="flex items-center gap-4 w-full lg:w-auto">
            <div class="flex items-center bg-slate-50 h-16 rounded-3xl px-6 gap-3 border border-transparent focus-within:border-indigo-100 transition-all">
                <span class="material-symbols-outlined text-slate-400 text-[22px]">filter_list</span>
                <select wire:model.live="status"
                        class="bg-transparent border-none focus:ring-0 text-sm font-black text-slate-700 p-0 pr-8 cursor-pointer uppercase tracking-widest">
                    <option value="">Semua Status</option>
                    <option value="published">Terbit</option>
                    <option value="draft">Draft</option>
                </select>
            </div>

            <div class="flex items-center bg-slate-50 h-16 rounded-3xl px-6 gap-3 border border-transparent focus-within:border-indigo-100 transition-all">
                <span class="material-symbols-outlined text-slate-400 text-[22px]">swap_vert</span>
                <select wire:model.live="sort"
                        class="bg-transparent border-none focus:ring-0 text-sm font-black text-slate-700 p-0 pr-8 cursor-pointer uppercase tracking-widest">
                    <option value="latest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                </select>
            </div>

            @if($search || $status || $sort !== 'latest')
            <button wire:click="$set('search', ''); $set('status', ''); $set('sort', 'latest');"
                    class="h-16 w-16 flex items-center justify-center bg-red-50 text-red-600 rounded-3xl hover:bg-red-100 transition-colors shadow-sm" title="Reset Filter">
                <span class="material-symbols-outlined text-[24px]">close</span>
            </button>
            @endif
        </div>
    </div>

    {{-- ── Articles Bento List ── --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Informasi Konten</th>
                        <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Status & Waktu</th>
                        <th class="px-10 py-8 text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($articles as $article)
                    <tr class="group hover:bg-slate-50/80 transition-all duration-300">
                        {{-- Content Section --}}
                        <td class="px-10 py-8">
                            <div class="flex items-center gap-8">
                                <div class="w-24 h-24 rounded-[2rem] overflow-hidden bg-slate-100 flex-shrink-0 border-4 border-white shadow-xl group-hover:scale-105 transition-transform duration-500">
                                    @if($article->thumbnail)
                                        <img src="{{ asset('storage/'.$article->thumbnail) }}" class="w-full h-full object-cover" alt="Thumbnail">
                                    @else
                                        <div class="w-full h-full flex items-center justify-center bg-slate-100 text-slate-300">
                                            <span class="material-symbols-outlined text-[40px]">image</span>
                                        </div>
                                    @endif
                                </div>
                                <div class="space-y-2 max-w-xl">
                                    <div class="flex items-center gap-3">
                                        <span class="px-3 py-1 rounded-full bg-indigo-50 text-indigo-600 text-[10px] font-black uppercase tracking-widest border border-indigo-100">
                                            {{ $article->category->name ?? 'Umum' }}
                                        </span>
                                        <div class="flex items-center gap-2 text-slate-400">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 flex items-center justify-center text-[8px] font-black text-slate-500 uppercase">
                                                {{ substr($article->user->name ?? 'A', 0, 1) }}
                                            </div>
                                            <span class="text-[11px] font-bold uppercase tracking-tight">{{ $article->user->name ?? 'Admin' }}</span>
                                        </div>
                                    </div>
                                    <h3 class="text-xl font-black text-slate-900 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2">
                                        {{ $article->title }}
                                    </h3>
                                </div>
                            </div>
                        </td>

                        {{-- Status & Time Section --}}
                        <td class="px-10 py-8">
                            <div class="flex flex-col gap-3">
                                @if($article->status === 'published')
                                    <span class="inline-flex items-center gap-2 w-fit px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                                        Sudah Terbit
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-2 w-fit px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-amber-50 text-amber-600 border border-amber-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>
                                        Draf Simpanan
                                    </span>
                                @endif
                                <p class="text-[11px] text-slate-400 font-bold uppercase tracking-widest flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[16px]">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($article->published_at ?? $article->created_at)->translatedFormat('d F Y') }}
                                </p>
                            </div>
                        </td>

                        {{-- Action Controls --}}
                        <td class="px-10 py-8">
                            <div class="flex items-center justify-end gap-3">
                                <a href="{{ route('admin.articles.show', $article->id) }}"
                                   class="w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all shadow-sm hover:shadow-indigo-500/20 group/btn"
                                   title="Detail Admin">
                                    <span class="material-symbols-outlined text-[24px]">visibility</span>
                                </a>

                                @if($article->status === 'published')
                                <a href="{{ route('public.articles.show', $article->slug) }}" target="_blank"
                                   class="w-14 h-14 flex items-center justify-center rounded-2xl bg-emerald-50 text-emerald-500 hover:bg-emerald-600 hover:text-white transition-all shadow-sm hover:shadow-emerald-500/20 group/btn"
                                   title="Lihat di Web">
                                    <span class="material-symbols-outlined text-[24px]">open_in_new</span>
                                </a>
                                @endif
                                
                                @can('update', $article)
                                <a href="{{ route('admin.articles.edit', $article->id) }}"
                                   class="w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-blue-600 hover:text-white transition-all shadow-sm hover:shadow-blue-500/20 group/btn">
                                    <span class="material-symbols-outlined text-[24px]">edit</span>
                                </a>
                                @endcan

                                @can('delete', $article)
                                <button wire:click="deleteArticle({{ $article->id }})"
                                        wire:confirm="Hapus artikel ini secara permanen?"
                                        class="w-14 h-14 flex items-center justify-center rounded-2xl bg-slate-50 text-slate-400 hover:bg-red-600 hover:text-white transition-all shadow-sm hover:shadow-red-500/20 group/btn">
                                    <span class="material-symbols-outlined text-[24px]">delete</span>
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-10 py-32 text-center">
                            <div class="flex flex-col items-center gap-6">
                                <div class="w-24 h-24 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <span class="material-symbols-outlined text-[64px]">article</span>
                                </div>
                                <div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-300">Belum ada konten publikasi</p>
                                    @can('create', App\Models\Article::class)
                                    <a href="{{ route('admin.articles.create') }}" class="mt-8 inline-flex items-center gap-3 text-indigo-600 font-black uppercase tracking-widest text-xs hover:text-indigo-800 transition-colors">
                                        Mulai Menulis Artikel Pertama <span class="material-symbols-outlined">arrow_forward</span>
                                    </a>
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Enhanced Pagination --}}
        @if($articles->hasPages())
        <div class="px-10 py-8 border-t border-slate-50 bg-slate-50/30">
            {{ $articles->links() }}
        </div>
        @endif
    </div>
</div>