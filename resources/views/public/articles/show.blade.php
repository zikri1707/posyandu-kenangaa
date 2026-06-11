@extends('layouts.public-layout')

@section('title', $article->title . ' - Posyandu Digital')

@section('content')
<article class="max-w-screen-xl mx-auto px-6 md:px-12 py-12 md:py-20">
    
    {{-- ── Breadcrumb ── --}}
    <nav class="flex items-center gap-2 text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 mb-12">
        <a href="{{ route('public.articles.index') }}" class="hover:text-indigo-600 transition-colors">Artikel</a>
        <span class="material-symbols-outlined text-[14px]">chevron_right</span>
        <span class="text-slate-300 truncate max-w-[200px]">{{ $article->title }}</span>
    </nav>

    {{-- ── Header ── --}}
    <header class="max-w-4xl mx-auto mb-16">
        <div class="mb-8">
            <span class="inline-block px-4 py-1 bg-slate-100 text-slate-600 text-[10px] font-black rounded-full uppercase tracking-widest">
                {{ $article->category->name ?? 'Informasi Kesehatan' }}
            </span>
        </div>

        <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight mb-10"
            style="font-family: 'Georgia', 'Times New Roman', serif;">
            {{ $article->title }}
        </h1>

        <div class="flex items-center justify-between py-8 border-y border-slate-100">
            <div class="flex items-center gap-5">
                <div class="w-14 h-14 rounded-full bg-indigo-600 flex items-center justify-center text-white text-xl font-black shadow-lg shadow-indigo-200">
                    {{ strtoupper(substr($article->user->name ?? 'A', 0, 1)) }}
                </div>
                <div>
                    <div class="flex items-center gap-2">
                        <h4 class="text-[15px] font-black text-slate-900 tracking-tight">{{ $article->user->name ?? 'Tim Redaksi' }}</h4>
                    </div>
                    <div class="flex items-center gap-3 mt-1">
                        <span class="text-[12px] text-slate-400 font-medium italic">{{ ceil(str_word_count(\App\Services\ArticleService::getExcerpt($article->content, 999999)) / 200) }} mnt baca</span>
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

    {{-- ── Hero Cover Image (selalu tampil) ── --}}
    @if($article->thumbnail)
    <div class="w-full aspect-[21/9] rounded-[3rem] overflow-hidden mb-20 shadow-2xl relative group">
        <img src="{{ asset('storage/' . $article->thumbnail) }}"
             alt="{{ $article->title }}"
             class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-105">
    </div>
    @endif

    {{-- ── Block Content ── --}}
    <div class="max-w-3xl mx-auto mb-24 article-content">
        {!! \App\Services\ArticleService::renderContent($article->content) !!}

        {{-- Tags --}}
        <div class="mt-20 pt-12 border-t border-slate-100 flex flex-wrap gap-3">
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#Kesehatan</span>
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#PosyanduDigital</span>
            @if($article->category)
            <span class="px-5 py-2 bg-slate-50 rounded-full text-xs font-bold text-slate-500">#{{ str_replace(' ', '', $article->category->name) }}</span>
            @endif
        </div>

        {{-- Bottom CTA --}}
        <div class="mt-12 flex items-center justify-between bg-slate-900 p-8 md:p-12 rounded-[3rem] text-white shadow-2xl relative overflow-hidden">
            <div class="relative z-10">
                <h3 class="text-2xl font-black mb-2 italic">Informasi ini bermanfaat?</h3>
                <p class="text-slate-400 text-sm font-medium">Bantu sebarkan informasi kesehatan ini kepada warga lainnya.</p>
            </div>
            <div class="flex gap-4 relative z-10">
                <a href="#" class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white/10 hover:bg-emerald-500 transition-all text-white backdrop-blur-md">
                    <span class="material-symbols-outlined">share</span>
                </a>
                <a href="{{ route('public.articles.index') }}"
                   class="h-14 px-8 flex items-center gap-3 bg-white text-slate-900 rounded-2xl font-black uppercase tracking-widest text-[11px] hover:bg-indigo-500 hover:text-white transition-all shadow-xl active:scale-95">
                    <span class="material-symbols-outlined text-[18px]">arrow_back</span> Kembali
                </a>
            </div>
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-indigo-600/20 rounded-full blur-[80px]"></div>
        </div>
    </div>
</article>

<style>
/* ─── Block Content Styles ─── */

/* GAMBAR: responsif, tidak fixed size */
.article-content .article-figure {
    margin: 2.5rem 0;
    width: 100%;
}
.article-content .article-image {
    width: 100%;
    height: auto;           /* ← ini yang bikin responsif */
    max-width: 100%;
    border-radius: 1.5rem;
    box-shadow: 0 20px 60px rgba(0,0,0,0.1);
    object-fit: cover;
    display: block;
}

/* HEADINGS */
.article-content .article-h1 {
    font-family: 'Georgia', serif;
    font-size: 2rem;
    font-weight: 900;
    color: #0f172a;
    margin: 2.5rem 0 0.75rem;
    line-height: 1.25;
}
.article-content .article-h2 {
    font-family: 'Georgia', serif;
    font-size: 1.5rem;
    font-weight: 900;
    color: #0f172a;
    margin: 2rem 0 0.5rem;
    line-height: 1.35;
}
.article-content .article-h3 {
    font-family: 'Georgia', serif;
    font-size: 1.25rem;
    font-weight: 700;
    color: #1e293b;
    margin: 1.75rem 0 0.5rem;
    line-height: 1.4;
}

/* QUOTE */
.article-content .article-quote {
    border-left: 4px solid #0f172a;
    margin: 2rem 0;
    padding: 0.5rem 0 0.5rem 1.5rem;
}
.article-content .article-quote p {
    font-family: 'Georgia', serif;
    font-size: 1.2rem;
    font-style: italic;
    color: #475569;
    line-height: 1.9;
    margin: 0;
}

/* CALLOUT */
.article-content .article-callout {
    display: flex;
    gap: 0.75rem;
    background: #fffbeb;
    border: 1px solid #fde68a;
    border-radius: 0.75rem;
    padding: 1rem 1.25rem;
    margin: 1.5rem 0;
    font-size: 1.05rem;
    line-height: 1.8;
    color: #78350f;
}
.article-content .article-callout-icon {
    font-size: 1.25rem;
    flex-shrink: 0;
    margin-top: 0.1rem;
}

/* LISTS — merge adjacent items visually */
.article-content .article-list {
    padding-left: 1.5rem;
    margin: 0.25rem 0;
    font-family: 'Georgia', serif;
    font-size: 1.15rem;
    line-height: 1.9;
    color: #374151;
}
.article-content .article-list + .article-list {
    margin-top: -0.5rem; /* merge consecutive list items */
}
.article-content .article-list--numbered {
    list-style: decimal;
}

/* PARAGRAPH */
.article-content .article-paragraph {
    font-family: 'Georgia', 'Times New Roman', serif;
    font-size: 1.25rem;
    line-height: 1.9;
    color: #374151;
    margin-bottom: 1.75rem;
}

/* VIDEO */
.article-content .article-video {
    position: relative;
    margin: 3rem 0;
    border-radius: 1.5rem;
    overflow: hidden;
    aspect-ratio: 16/9;
    background: #0f172a;
    box-shadow: 0 20px 60px rgba(0,0,0,0.2);
}

/* DIVIDER */
.article-content .article-divider{
    border:none;
    border-top:1px solid #e5e7eb;
    margin:2rem 0;
}
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 1.5rem;
    margin: 3.5rem 0;
    color: #cbd5e1;
    font-size: 1.5rem;
}

/* CAPTION */
.article-content .article-caption {
    text-align: center;
    margin-top: 0.75rem;
    font-size: 0.8rem;
    color: #94a3b8;
    font-style: italic;
}

/* Inline formatting dari contenteditable */
.article-content strong, .article-content b { font-weight: 800; color: #0f172a; }
.article-content em, .article-content i     { font-style: italic; }
.article-content u                           { text-decoration: underline; }
.article-content s                           { text-decoration: line-through; color: #94a3b8; }
</style>
@endsection