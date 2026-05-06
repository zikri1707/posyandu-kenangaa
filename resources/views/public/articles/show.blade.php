@extends('layouts.public-layout')

@section('title', $article->title . ' - Posyandu Digital')

@section('content')
<article class="max-w-screen-xl mx-auto px-6 md:px-12 py-12 md:py-20">
    
    {{-- ── Breadcrumb (Minimalist) ── --}}
    <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-12">
        <a href="{{ route('public.articles.index') }}" class="hover:text-primary transition-colors">Artikel</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-300 truncate max-w-[200px]">{{ $article->title }}</span>
    </nav>

    {{-- ── Medium Style Header ── --}}
    <header class="max-w-4xl mx-auto mb-16">
        <div class="mb-8">
            <span class="inline-block px-4 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-full uppercase tracking-widest">
                {{ $article->category->name ?? 'Informasi Kesehatan' }}
            </span>
        </div>

        <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight mb-10">
            {{ $article->title }}
        </h1>

        <div class="flex items-center justify-between py-8 border-y border-slate-100">
            <div class="flex items-center gap-5">
                {{-- Author Avatar --}}
                <div class="w-14 h-14 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr($article->user->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-[15px] font-black text-slate-900 tracking-tight">{{ $article->user->name ?? 'Tim Redaksi' }}</h4>
                        <span class="w-1 h-1 rounded-full bg-slate-300"></span>
                        <button class="text-[13px] font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Ikuti</button>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[12px] text-slate-400 font-medium italic">{{ ceil(str_word_count(strip_tags($article->content)) / 200) }} mnt baca</span>
                        <span class="w-1 h-1 rounded-full bg-slate-200"></span>
                        <span class="text-[12px] text-slate-400 font-medium">{{ $article->published_at ? \Carbon\Carbon::parse($article->published_at)->translatedFormat('d M Y') : $article->created_at->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
            </div>

            <div class="hidden sm:flex items-center gap-3">
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-100 text-slate-400 hover:text-indigo-600 hover:bg-slate-50 transition-all">
                    <span class="material-symbols-outlined text-[20px]">share</span>
                </button>
                <button class="w-10 h-10 flex items-center justify-center rounded-full border border-slate-100 text-slate-400 hover:text-red-500 hover:bg-slate-50 transition-all">
                    <span class="material-symbols-outlined text-[20px]">bookmark</span>
                </button>
            </div>
        </div>
    </header>

    {{-- ── Main Hero Image (Large & Rounded) ── --}}
    <div class="w-full aspect-[21/9] rounded-[3rem] overflow-hidden mb-20 shadow-2xl relative group">
        <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=2070&auto=format&fit=crop' }}" 
             alt="{{ $article->title }}" 
             class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-105">
    </div>

    {{-- ── Article Content (Medium Reading Experience) ── --}}
    <div class="max-w-3xl mx-auto mb-24">
        <div class="prose prose-slate prose-xl max-w-none 
                    prose-headings:font-black prose-headings:text-slate-900 prose-headings:tracking-tight
                    prose-p:text-slate-700 prose-p:leading-[1.8] prose-p:text-[1.3rem]
                    prose-strong:text-slate-900 prose-strong:font-black
                    prose-a:text-indigo-600 prose-a:font-black prose-a:no-underline hover:prose-a:underline
                    prose-blockquote:border-l-4 prose-blockquote:border-indigo-600 prose-blockquote:bg-slate-50 prose-blockquote:py-4 prose-blockquote:px-8 prose-blockquote:rounded-r-2xl prose-blockquote:italic prose-blockquote:text-slate-600
                    prose-img:rounded-3xl prose-img:shadow-xl
                    prose-ul:list-disc prose-li:text-slate-700">
            {!! $article->content !!}
        </div>

        {{-- ── Tags / Keywords ── --}}
        <div class="mt-20 pt-12 border-t border-slate-100 flex flex-wrap gap-3">
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#Kesehatan</span>
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#PosyanduDigital</span>
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#GiziAnak</span>
        </div>

        {{-- ── Bottom Actions (Medium style claps/shares) ── --}}
        <div class="mt-12 flex items-center justify-between bg-slate-900 p-8 md:p-12 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl font-black mb-2 italic">Informasi ini bermanfaat?</h3>
                <p class="text-slate-400 text-sm font-medium">Bantu sebarkan informasi kesehatan ini kepada warga lainnya.</p>
            </div>
            <div class="flex gap-4 relative z-10">
                <a href="#" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-emerald-500 transition-all text-white backdrop-blur-md">
                    <span class="material-symbols-outlined">share</span>
                </a>
                <a href="{{ route('public.articles.index') }}" class="h-14 px-8 flex items-center gap-3 bg-white text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-indigo-500 hover:text-white transition-all shadow-xl active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                </a>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-[80px]"></div>
        </div>
    </div>
</article>
@endsection
