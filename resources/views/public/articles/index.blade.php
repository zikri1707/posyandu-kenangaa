@extends('layouts.public-layout')

@section('title', 'Artikel Kesehatan - Posyandu Digital Bekasi Timur')

@section('content')
<div class="max-w-screen-xl mx-auto px-6 md:px-12 py-16">

    {{-- ── Trending Section (Top Header) ── --}}
    @if($featured)
    <section class="mb-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
        <div class="lg:col-span-7 space-y-8">
            <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600">
                <span class="material-symbols-outlined text-[18px] animate-pulse">star</span>
                <span class="text-[10px] font-black uppercase tracking-[0.2em]">Artikel Terpopuler</span>
            </div>
            
            <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight">
                {{ $featured->title }}
            </h1>
            
            <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-2xl">
                {{ Str::limit(strip_tags($featured->content), 180) }}
            </p>

            <div class="flex items-center gap-6 pt-6">
                <a href="{{ route('public.articles.show', $featured->slug) }}" 
                   class="h-14 px-10 flex items-center justify-center bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl active:scale-95">
                    Mulai Membaca
                </a>
                <span class="text-slate-400 text-[11px] font-bold uppercase tracking-widest flex items-center gap-2">
                    {{ ceil(str_word_count(strip_tags($featured->content)) / 200) }} mnt baca
                </span>
            </div>
        </div>
        <div class="lg:col-span-5">
            <div class="relative group">
                <div class="absolute inset-0 bg-indigo-600 rounded-[3rem] rotate-3 opacity-10 group-hover:rotate-6 transition-transform"></div>
                <div class="relative aspect-[4/3] rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white">
                    <img src="{{ $featured->thumbnail ? asset('storage/'.$featured->thumbnail) : 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=1200&auto=format&fit=crop' }}" 
                         alt="{{ $featured->title }}" 
                         class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">
                </div>
            </div>
        </div>
    </section>
    @endif

    <div class="flex flex-col lg:flex-row gap-20">
        
        {{-- ── Main Articles Stream (Medium-style List) ── --}}
        <div class="flex-1 space-y-16">
            <div class="flex items-center gap-8 mb-12">
                <h2 class="text-2xl font-black text-slate-900 tracking-tight">Koleksi Pengetahuan</h2>
                <div class="h-[1px] flex-1 bg-slate-100"></div>
                
                {{-- Category Filter Pills --}}
                <div class="flex gap-2 overflow-x-auto pb-2 scrollbar-hide max-w-[400px]">
                    <a href="{{ route('public.articles.index') }}" 
                       class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all whitespace-nowrap {{ !request('category') ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-100 hover:border-slate-300' }}">
                        Semua
                    </a>
                    @foreach($categories as $cat)
                    <a href="{{ route('public.articles.index', ['category' => $cat->slug]) }}" 
                       class="px-5 py-2 rounded-full text-[10px] font-black uppercase tracking-widest border transition-all whitespace-nowrap {{ request('category') === $cat->slug ? 'bg-slate-900 text-white border-slate-900' : 'bg-white text-slate-500 border-slate-100 hover:border-slate-300' }}">
                        {{ $cat->name }}
                    </a>
                    @endforeach
                </div>
            </div>

            <div class="space-y-20">
                @forelse($articles as $article)
                <article class="group relative flex flex-col md:flex-row gap-10 items-start">
                    <div class="flex-1 space-y-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center text-[10px] font-black text-slate-500">
                                {{ strtoupper(substr($article->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <span class="text-[12px] font-black text-slate-800 tracking-tight">{{ $article->user->name ?? 'Tim Redaksi' }}</span>
                            <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                            <span class="text-[11px] text-slate-400 font-medium italic">{{ $article->category->name ?? 'Umum' }}</span>
                        </div>
                        
                        <a href="{{ route('public.articles.show', $article->slug) }}" class="block">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 leading-tight tracking-tight group-hover:text-indigo-600 transition-colors">
                                {{ $article->title }}
                            </h3>
                            <p class="mt-4 text-slate-500 font-medium leading-relaxed line-clamp-3 md:line-clamp-2">
                                {{ Str::limit(strip_tags($article->content), 150) }}
                            </p>
                        </a>

                        <div class="flex items-center justify-between pt-6">
                            <div class="flex items-center gap-4 text-[11px] font-bold text-slate-400 uppercase tracking-widest">
                                <span>{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : $article->created_at->format('d M Y') }}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                                <span class="flex items-center gap-1.5"><span class="material-symbols-outlined text-[14px]">timer</span> {{ ceil(str_word_count(strip_tags($article->content)) / 200) }} mnt</span>
                            </div>
                            <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button class="text-slate-300 hover:text-indigo-600"><span class="material-symbols-outlined text-[20px]">share</span></button>
                                <button class="text-slate-300 hover:text-red-500"><span class="material-symbols-outlined text-[20px]">bookmark</span></button>
                            </div>
                        </div>
                    </div>
                    
                    <div class="w-full md:w-56 aspect-[4/3] rounded-3xl overflow-hidden shadow-lg border-4 border-white flex-shrink-0 relative group-hover:scale-[1.02] transition-all duration-500">
                        <img src="{{ $article->thumbnail ? asset('storage/'.$article->thumbnail) : 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=800&auto=format&fit=crop' }}" 
                             alt="{{ $article->title }}" 
                             class="w-full h-full object-cover">
                    </div>
                </article>
                @empty
                <div class="py-32 text-center bg-slate-50 rounded-[4rem] border-2 border-dashed border-slate-200">
                    <span class="material-symbols-outlined text-[84px] text-slate-200 mb-6">description</span>
                    <h3 class="text-xl font-black text-slate-900 tracking-tight italic">Belum Ada Cerita Baru</h3>
                    <p class="text-slate-400 text-sm mt-2 font-medium">Kami sedang meracik konten edukasi terbaik untuk Anda.</p>
                </div>
                @endforelse

                <div class="pt-10 border-t border-slate-100">
                    {{ $articles->links() }}
                </div>
            </div>
        </div>

        {{-- ── Sidebar (Medium Style Sidebar) ── --}}
        <aside class="w-full lg:w-96 space-y-16 flex-shrink-0">
            
            {{-- Search Module --}}
            <div class="space-y-6">
                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.3em]">Cari Pengetahuan</h4>
                <form action="{{ route('public.articles.index') }}" method="GET" class="relative group">
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Ingin cari apa hari ini?"
                           class="w-full h-16 pl-14 pr-6 rounded-2xl bg-white border border-slate-100 text-sm font-bold text-slate-900 placeholder:text-slate-300 focus:ring-4 focus:ring-indigo-500/5 focus:border-indigo-600 transition-all shadow-sm">
                    <span class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-indigo-600 transition-colors">search</span>
                </form>
            </div>

            {{-- Popular Stream --}}
            <div class="space-y-10">
                <h4 class="text-[11px] font-black text-slate-900 uppercase tracking-[0.3em] flex items-center gap-3">
                    <span class="w-6 h-6 rounded-lg bg-indigo-600 flex items-center justify-center text-white"><span class="material-symbols-outlined text-[14px]">trending_up</span></span>
                    Paling Banyak Dibaca
                </h4>
                <div class="space-y-10">
                    @foreach($popularArticles as $index => $pop)
                    <a href="{{ route('public.articles.show', $pop->slug) }}" class="flex gap-6 group">
                        <span class="text-4xl font-black text-slate-100 group-hover:text-indigo-50 transition-colors leading-none">0{{ $index + 1 }}</span>
                        <div class="space-y-2">
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-black text-slate-900 uppercase tracking-tighter">{{ $article->user->name ?? 'Admin' }}</span>
                                <span class="w-0.5 h-0.5 rounded-full bg-slate-300"></span>
                                <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $pop->category->name ?? 'Umum' }}</span>
                            </div>
                            <h5 class="text-[15px] font-black text-slate-800 leading-tight group-hover:text-indigo-600 transition-colors line-clamp-2">
                                {{ $pop->title }}
                            </h5>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

            {{-- Support Bento --}}
            <div class="relative p-10 bg-slate-900 rounded-[3rem] text-white shadow-2xl overflow-hidden group">
                <div class="relative z-10 space-y-6">
                    <h4 class="text-2xl font-black italic leading-tight">Butuh Konsultasi <br><span class="text-indigo-400">Pribadi?</span></h4>
                    <p class="text-slate-400 text-[13px] font-medium leading-relaxed">Hubungi tim medis dan kader kami melalui pesan WhatsApp untuk konsultasi cepat.</p>
                    <a href="{{ route('public.contact') }}" class="h-14 w-full flex items-center justify-center bg-indigo-600 text-white rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-600/20 active:scale-95">
                        Hubungi Kader Sekarang
                    </a>
                </div>
                <span class="material-symbols-outlined absolute -right-8 -bottom-8 text-[140px] text-white/5 group-hover:scale-110 transition-transform duration-700">medical_services</span>
                <div class="absolute -left-10 -top-10 w-40 h-40 bg-indigo-500/10 rounded-full blur-[80px]"></div>
            </div>
        </aside>
    </div>
</div>
@endsection
