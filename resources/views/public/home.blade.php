@extends('layouts.public-layout')

@section('title', 'Beranda - Posyandu Digital')

@section('content')
<div class="max-w-7xl mx-auto px-6 md:px-10 relative">

    {{-- ── DECORATIVE ELEMENTS ── --}}
    <div class="absolute -top-10 -left-10 w-64 h-64 bg-teal-100/50 rounded-full blur-3xl"></div>
    <div class="absolute top-1/2 -right-20 w-80 h-80 bg-emerald-50/50 rounded-full blur-3xl"></div>

    {{-- ── HERO SECTION (LIGHT & COMPACT) ── --}}
    <div class="relative min-h-[500px] flex items-center mb-24 rounded-[3rem] overflow-hidden group border border-slate-100 bg-white shadow-xl shadow-teal-900/5">
        {{-- Soft Background Pattern --}}
        <div class="absolute inset-0 bg-[radial-gradient(#e2e8f0_1px,transparent_1px)] [background-size:20px_20px] opacity-30"></div>
        
        <div class="relative z-10 px-8 md:px-16 py-16 w-full flex flex-col md:flex-row items-center gap-12">
            <div class="flex-1 max-w-2xl">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 bg-teal-50 rounded-full mb-6 border border-teal-100">
                    <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                    <span class="text-[10px] font-black text-teal-700 uppercase tracking-widest">Portal Kesehatan Digital</span>
                </div>
                
                <h1 class="text-4xl md:text-6xl font-black text-slate-900 mb-6 leading-tight tracking-tight font-jakarta">
                    Modernisasi <br> <span class="text-teal-600">Layanan Posyandu.</span>
                </h1>
                
                <p class="text-slate-500 text-lg font-medium max-w-xl leading-relaxed mb-10">
                    Transformasi layanan kesehatan dasar melalui data terintegrasi. Memantau tumbuh kembang anak kini lebih praktis dan akurat.
                </p>
                
                <div class="flex flex-wrap gap-4 items-center">
                    <a href="{{ route('public.articles.index') }}" 
                       class="px-8 py-4 bg-teal-600 text-white text-[12px] font-black uppercase tracking-widest rounded-2xl shadow-lg shadow-teal-600/20 hover:bg-teal-700 hover:-translate-y-1 transition-all">
                        Baca Artikel 
                    </a>
                    <a href="{{ route('public.about') }}" 
                       class="px-8 py-4 bg-white text-slate-600 text-[12px] font-black uppercase tracking-widest rounded-2xl border border-slate-200 hover:bg-slate-50 transition-all">
                        Tentang Kami
                    </a>
                </div>
            </div>

            {{-- Hero Image / Illustration --}}
            <div class="flex-1 relative hidden md:block">
                <div class="relative rounded-3xl overflow-hidden shadow-2xl border-4 border-white rotate-2 group-hover:rotate-0 transition-transform duration-700">
                    <img src="https://images.unsplash.com/photo-1584362946444-1e6a47940e21?q=80&w=800&auto=format&fit=crop" 
                         alt="Posyandu" class="w-full h-[350px] object-cover">
                </div>
                <div class="absolute -bottom-6 -left-6 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 flex items-center gap-4 animate-bounce">
                    <div class="w-10 h-10 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center">
                        <i class="fas fa-check"></i>
                    </div>
                    <p class="text-xs font-black text-slate-800 uppercase tracking-wider">Data Terverifikasi</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── FITUR (COMPACT) ── --}}
    <section class="mb-24">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @php
            $features = [
                ['icon' => 'fa-laptop-medical', 'title' => 'Monitoring Digital', 'color' => 'teal'],
                ['icon' => 'fa-calendar-check', 'title' => 'Jadwal Terpadu', 'color' => 'blue'],
                ['icon' => 'fa-file-waveform', 'title' => 'Laporan Akurat', 'color' => 'indigo']
            ];
            @endphp
            @foreach($features as $f)
            <div class="p-8 rounded-[2rem] bg-white border border-slate-100 shadow-sm hover:shadow-md transition-all flex items-center gap-6 group">
                <div @class([
                    'w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500',
                    'bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white' => $f['color'] === 'teal',
                    'bg-blue-50 text-blue-600 group-hover:bg-blue-600 group-hover:text-white' => $f['color'] === 'blue',
                    'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-600 group-hover:text-white' => $f['color'] === 'indigo',
                ])>
                    <i class="fas {{ $f['icon'] }} text-xl"></i>
                </div>
                <div>
                    <h4 class="text-lg font-black text-slate-900">{{ $f['title'] }}</h4>
                    <p class="text-[12px] text-slate-400 font-bold uppercase tracking-widest mt-1">Layanan Posyandu</p>
                </div>
            </div>
            @endforeach
        </div>
    </section>

    {{-- ── JADWAL (COMPACT BENTO) ── --}}
    <section id="jadwal" class="mb-24">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 font-jakarta tracking-tight">
                Jadwal <span class="text-teal-500 italic">Kegiatan.</span>
            </h2>
            <div class="h-1 w-24 bg-teal-100 rounded-full hidden md:block"></div>
        </div>

        @if($schedules->count() > 0)
            <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                @foreach($schedules as $index => $schedule)
                    <div class="relative overflow-hidden group rounded-[2.5rem] bg-white border border-slate-100 shadow-sm hover:border-teal-200 transition-all {{ $index === 0 ? 'md:col-span-7' : 'md:col-span-5' }}">
                        <div class="p-10 flex flex-col h-full">
                            <div class="flex items-center justify-between mb-10">
                                <span class="px-3 py-1 bg-slate-900 text-white text-[9px] font-black rounded-lg uppercase tracking-widest italic">
                                    Segera Hadir
                                </span>
                            </div>

                            <div class="mt-auto">
                                <h3 class="text-2xl font-black text-slate-900 mb-4 tracking-tight leading-tight">
                                    {{ $schedule->title }}
                                </h3>
                                
                                <div class="flex flex-wrap items-center gap-6 pt-6 border-t border-slate-50">
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-calendar-day text-teal-500"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-3">
                                        <i class="fas fa-location-dot text-amber-500"></i>
                                        <span class="text-xs font-bold text-slate-700">{{ $schedule->location ?: 'Posyandu' }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    {{-- ── ARTIKEL (COMPACT CARDS) ── --}}
    <section class="mb-24">
        <div class="flex items-center justify-between mb-12">
            <h2 class="text-3xl md:text-4xl font-black text-slate-900 font-jakarta tracking-tight">Wawasan <span class="text-teal-400 italic">Kesehatan.</span></h2>
            <a href="{{ route('public.articles.index') }}" class="text-[11px] font-black text-teal-600 uppercase tracking-widest hover:text-teal-800 transition-all">
                Lihat Semua
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($articles as $article)
            <article class="group bg-white rounded-[2rem] border border-slate-100 overflow-hidden hover:shadow-xl transition-all">
                <a href="{{ route('public.articles.show', $article->slug) }}" class="block aspect-video overflow-hidden">
                    <img src="{{ $article->thumbnail ? asset('storage/' . $article->thumbnail) : 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=600&auto=format&fit=crop' }}" 
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </a>
                
                <div class="p-8">
                    <h3 class="text-lg font-black text-slate-900 mb-6 leading-tight group-hover:text-teal-600 transition-colors line-clamp-2">
                        <a href="{{ route('public.articles.show', $article->slug) }}">{{ $article->title }}</a>
                    </h3>

                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">{{ \Carbon\Carbon::parse($article->published_at)->format('d M, Y') }}</span>
                        <i class="fas fa-arrow-right text-slate-300 group-hover:text-teal-500 transition-all"></i>
                    </div>
                </div>
            </article>
            @endforeach
        </div>
    </section>

    {{-- ── CTA (LIGHT BANNER) ── --}}
    <div class="relative rounded-[3rem] bg-teal-600 overflow-hidden p-12 md:p-20 text-center shadow-2xl mb-24">
        <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-white/10 rounded-full blur-3xl"></div>
        
        <div class="relative z-10 max-w-3xl mx-auto">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-8 tracking-tight leading-tight">
                Mari Bangun Masa Depan <br> <span class="text-teal-100">Warga yang Lebih Sehat.</span>
            </h2>
            <a href="{{ route('public.contact') }}" class="inline-block px-12 py-5 bg-white text-teal-600 text-[12px] font-black uppercase tracking-widest rounded-2xl shadow-xl hover:bg-slate-50 transition-all">
                Hubungi Kami
            </a>
        </div>
    </div>
</div>
@endsection
