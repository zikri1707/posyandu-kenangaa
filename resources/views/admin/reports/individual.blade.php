@extends('layouts.admin-layout')

@section('admin-title', 'Rapor Perkembangan Individu')

@section('admin-content')
@php
    $patient = $reportData['patient'];
    $cat = $patient['category'];
    $theme = match($cat) {
        'bayi', 'baduta', 'balita' => [
            'name' => 'Balita',
            'gradient' => 'from-teal-600 to-emerald-500',
            'bg-mesh' => 'bg-gradient-to-br from-teal-500/10 via-emerald-500/5 to-transparent',
            'shadow' => 'shadow-[0_8px_30px_-8px_rgba(13,148,136,0.2)]',
            'bg-light' => 'bg-teal-50',
            'text' => 'text-teal-600',
            'border' => 'border-teal-100',
        ],
        'lansia' => [
            'name' => 'Lansia',
            'gradient' => 'from-amber-600 to-orange-500',
            'bg-mesh' => 'bg-gradient-to-br from-amber-500/10 via-orange-500/5 to-transparent',
            'shadow' => 'shadow-[0_8px_30px_-8px_rgba(245,158,11,0.2)]',
            'bg-light' => 'bg-amber-50',
            'text' => 'text-amber-600',
            'border' => 'border-amber-100',
        ],
        'ibu_hamil' => [
            'name' => 'Ibu Hamil',
            'gradient' => 'from-rose-500 to-pink-500',
            'bg-mesh' => 'bg-gradient-to-br from-rose-500/10 via-pink-500/5 to-transparent',
            'shadow' => 'shadow-[0_8px_30px_-8px_rgba(244,63,94,0.2)]',
            'bg-light' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'border' => 'border-rose-100',
        ],
        default => [
            'name' => str_replace('_', ' ', ucfirst($cat)),
            'gradient' => 'from-indigo-600 to-slate-500',
            'bg-mesh' => 'bg-gradient-to-br from-indigo-500/10 via-slate-500/5 to-transparent',
            'shadow' => 'shadow-[0_8px_30px_-8px_rgba(99,102,241,0.2)]',
            'bg-light' => 'bg-indigo-50',
            'text' => 'text-indigo-600',
            'border' => 'border-indigo-100',
        ]
    };
@endphp

<div class="max-w-6xl mx-auto space-y-6 pb-16 px-4 md:px-8 pt-4">

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-8 relative z-10">
        <div class="space-y-4">
            <nav class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 px-4 py-2 rounded-2xl bg-white/80 backdrop-blur-md border border-white/60 shadow-sm text-[10px] font-black uppercase tracking-widest text-slate-400">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-slate-400 hover:text-teal-600 transition-colors">
                        <span class="material-symbols-outlined text-[16px]">home</span>
                        Beranda
                    </a>
                    <span class="material-symbols-outlined text-[16px] text-slate-300">chevron_right</span>
                    <a href="{{ route('admin.reports.index') }}" class="text-slate-400 hover:text-teal-600 transition-colors">Rekap Laporan</a>
                    <span class="material-symbols-outlined text-[16px] text-slate-300">chevron_right</span>
                    <span class="{{ $theme['text'] }}">Rapor Individu</span>
                </div>
            </nav>
            <h1 class="text-3xl md:text-4xl font-black tracking-tight leading-none text-slate-800">
                Rapor Perkembangan: <br class="md:hidden"><span class="text-transparent bg-clip-text bg-linear-to-r {{ $theme['gradient'] }} drop-shadow-sm">{{ $patient['full_name'] }}</span>
            </h1>
        </div>
    </div>

    {{-- ── 1. Filter Rentang Periode (Floating Glass) ── --}}
    <section class="bg-white/80 backdrop-blur-xl rounded-3xl border border-white/50 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] p-6 relative overflow-hidden">
        <div class="absolute -right-24 -top-24 w-64 h-64 bg-slate-100 rounded-full blur-3xl pointer-events-none -z-10"></div>
        <div class="flex items-center gap-2 mb-4 ml-1">
            <span class="material-symbols-outlined text-slate-400 text-[20px]">date_range</span>
            <h3 class="text-[11px] font-black text-slate-500 uppercase tracking-widest">Rentang Periode Rapor</h3>
        </div>
        
        <form id="filterForm" method="GET" action="{{ route('admin.reports.individual', $patient['id']) }}" onsubmit="updateHiddenFields()">
            {{-- Hidden fields to maintain backend compatibility --}}
            <input type="hidden" name="start_month" id="start_month" value="{{ $startMonth }}">
            <input type="hidden" name="start_year" id="start_year" value="{{ $startYear }}">
            <input type="hidden" name="end_month" id="end_month" value="{{ $endMonth }}">
            <input type="hidden" name="end_year" id="end_year" value="{{ $endYear }}">

            <div class="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                
                {{-- Mulai --}}
                <div class="md:col-span-5">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Periode Mulai</label>
                    <input type="month" id="start_period" value="{{ sprintf('%04d-%02d', $startYear, $startMonth) }}" onchange="updateHiddenFields()" class="w-full h-12 px-4 rounded-2xl border border-slate-200/60 text-sm font-semibold bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 transition-all shadow-inner-sm">
                </div>

                {{-- Divider --}}
                <div class="hidden md:flex md:col-span-1 items-center justify-center pb-2">
                    <span class="material-symbols-outlined text-slate-300">arrow_forward</span>
                </div>

                {{-- Selesai --}}
                <div class="md:col-span-4">
                    <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 ml-1">Periode Selesai</label>
                    <input type="month" id="end_period" value="{{ sprintf('%04d-%02d', $endYear, $endMonth) }}" onchange="updateHiddenFields()" class="w-full h-12 px-4 rounded-2xl border border-slate-200/60 text-sm font-semibold bg-slate-50/50 focus:bg-white focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 transition-all shadow-inner-sm">
                </div>

                {{-- Action --}}
                <div class="md:col-span-2">
                    <button type="submit" class="w-full h-12 bg-slate-800 hover:bg-slate-900 active:scale-95 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-md flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">sync</span>
                        Terapkan
                    </button>
                </div>
            </div>
            
            {{-- Presets --}}
            <div class="flex items-center gap-3 mt-5 pt-4 border-t border-slate-100/60 overflow-x-auto hide-scrollbar pb-1">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest whitespace-nowrap">Pilih Cepat:</span>
                <div class="flex gap-2">
                    <button type="button" onclick="setPeriodPreset(1)" class="px-4 py-2 bg-white hover:bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600 border border-slate-200 transition-colors whitespace-nowrap shadow-sm">1 Bulan</button>
                    <button type="button" onclick="setPeriodPreset(3)" class="px-4 py-2 bg-white hover:bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600 border border-slate-200 transition-colors whitespace-nowrap shadow-sm">3 Bulan Terakhir</button>
                    <button type="button" onclick="setPeriodPreset(6)" class="px-4 py-2 bg-white hover:bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600 border border-slate-200 transition-colors whitespace-nowrap shadow-sm">6 Bulan Terakhir</button>
                    <button type="button" onclick="setPeriodPreset(12)" class="px-4 py-2 bg-white hover:bg-slate-50 rounded-xl text-[11px] font-bold text-slate-600 border border-slate-200 transition-colors whitespace-nowrap shadow-sm">1 Tahun Terakhir</button>
                </div>
            </div>
        </form>
    </section>

    {{-- Bento Grid layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── 2. Identitas Warga Bento-Card (Left Col - 4 span) ── --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-3xl border border-slate-100/60 p-8 flex flex-col items-center text-center relative overflow-hidden group shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] {{ $theme['bg-mesh'] }}">
                <div class="absolute -right-16 -top-16 w-48 h-48 bg-white/40 backdrop-blur-3xl rounded-full pointer-events-none"></div>
                
                {{-- Profile icon/photo --}}
                <div class="w-32 h-32 rounded-4xl bg-linear-to-br {{ $theme['gradient'] }} p-1 mb-6 {{ $theme['shadow'] }} relative z-10 group-hover:scale-105 transition-transform duration-500">
                    <div class="w-full h-full bg-white rounded-[1.8rem] flex items-center justify-center overflow-hidden">
                        @if(!empty($patient['profile_photo']))
                            <img src="{{ asset('storage/' . $patient['profile_photo']) }}" class="w-full h-full object-cover">
                        @else
                            <span class="material-symbols-outlined text-[72px] {{ $theme['text'] }} opacity-90">face</span>
                        @endif
                    </div>
                </div>

                <h2 class="text-2xl font-black text-slate-800 leading-tight mb-2 tracking-tight">{{ $patient['full_name'] }}</h2>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-white/60 backdrop-blur-sm border border-slate-200/60 rounded-xl text-[11px] font-bold text-slate-500 font-mono shadow-sm">
                    <span class="material-symbols-outlined text-[14px]">pin</span>
                    {{ $patient['id_number'] }}
                </div>

                <div class="w-full mt-8 pt-6 border-t border-slate-200/50 grid grid-cols-2 gap-4 text-left relative z-10">
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-white">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Kategori</span>
                        <span class="text-sm font-black text-slate-800 uppercase">{{ str_replace('_', ' ', $patient['category']) }}</span>
                    </div>
                    <div class="bg-white/60 backdrop-blur-sm rounded-2xl p-4 border border-white">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Usia Saat Ini</span>
                        <span class="text-sm font-black text-slate-800">{{ $patient['age'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Detail Keluarga --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 space-y-5 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] relative overflow-hidden">
                <div class="absolute right-0 top-0 w-24 h-24 bg-slate-50 rounded-bl-[4rem] -z-10"></div>
                <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-outlined text-teal-500 text-[20px]">family_restroom</span>
                    Informasi Keluarga
                </h4>
                
                <div class="space-y-4">
                    @if($cat !== 'lansia')
                    <div class="flex justify-between items-center py-2 border-b border-slate-50/80">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Ibu</span>
                        <span class="text-sm font-bold text-slate-700 text-right">{{ $patient['mother_name'] }}</span>
                    </div>
                    <div class="flex justify-between items-center py-2 border-b border-slate-50/80">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Nama Ayah</span>
                        <span class="text-sm font-bold text-slate-700 text-right">{{ $patient['father_name'] }}</span>
                    </div>
                    @endif
                    <div class="flex justify-between items-center py-2 border-b border-slate-50/80">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">No. Telp</span>
                        <span class="text-sm font-bold text-slate-700 font-mono">{{ $patient['phone_number'] }}</span>
                    </div>
                    <div class="pt-2">
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block mb-2">Alamat Domisili</span>
                        <p class="text-sm font-semibold text-slate-600 leading-relaxed bg-slate-50 p-4 rounded-2xl border border-slate-100">{{ $patient['address'] }}</p>
                    </div>
                </div>
            </div>

            {{-- Actions / Exports --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)]">
                <h4 class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Ekspor Laporan</h4>
                
                <div class="space-y-3">
                    {{-- PDF Export Form --}}
                    <form action="{{ route('admin.reports.individual.pdf', $patient['id']) }}" method="POST" target="_blank">
                        @csrf
                        <input type="hidden" name="start_month" value="{{ $startMonth }}">
                        <input type="hidden" name="start_year" value="{{ $startYear }}">
                        <input type="hidden" name="end_month" value="{{ $endMonth }}">
                        <input type="hidden" name="end_year" value="{{ $endYear }}">
                        <button type="submit" class="w-full h-12 bg-rose-50 hover:bg-rose-100 border border-rose-200 text-rose-600 active:scale-95 rounded-2xl text-[11px] font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                            Unduh Rapor PDF
                        </button>
                    </form>

                    {{-- Excel Export Form --}}
                    <form action="{{ route('admin.reports.individual.excel', $patient['id']) }}" method="POST">
                        @csrf
                        <input type="hidden" name="start_month" value="{{ $startMonth }}">
                        <input type="hidden" name="start_year" value="{{ $startYear }}">
                        <input type="hidden" name="end_month" value="{{ $endMonth }}">
                        <input type="hidden" name="end_year" value="{{ $endYear }}">
                        <button type="submit" class="w-full h-12 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 text-emerald-600 active:scale-95 rounded-2xl text-[11px] font-black uppercase tracking-widest flex items-center justify-center gap-2 transition-all shadow-sm">
                            <span class="material-symbols-outlined text-[18px]">description</span>
                            Unduh Data Excel
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── 3. Chart Analytics & History (Right Col - 8 span) ── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- 3.1. Grafik Tumbuh Kembang (Hanya untuk Balita) --}}
            @if(in_array($cat, ['bayi', 'baduta', 'balita']))
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] p-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="material-symbols-outlined text-teal-500 bg-teal-50 p-2 rounded-xl">monitoring</span>
                            Visualisasi Grafik Tumbuh Kembang
                        </h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-1 ml-11">Tren pertumbuhan berat dan tinggi badan periode ini.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Weight Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-teal-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Berat Badan (kg)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="weightChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- Height Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tinggi Badan (cm)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="heightChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 3.1.b Grafik Kesehatan Lansia (Hanya untuk Lansia) --}}
            @if($cat === 'lansia')
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] p-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="material-symbols-outlined text-amber-500 bg-amber-50 p-2 rounded-xl">monitoring</span>
                            Visualisasi Grafik Faktor Risiko Metabolik
                        </h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-1 ml-11">Tren parameter tensi, gula darah, asam urat, dan kolesterol periode ini.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Tensi Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-red-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Tekanan Darah (mmHg)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="tensiChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- Gula Darah Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-yellow-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Gula Darah (mg/dL)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="sugarChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- Asam Urat Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-purple-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Asam Urat (mg/dL)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="uricChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- Kolesterol Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-blue-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Kolesterol (mg/dL)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="cholesterolChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 3.1.c Grafik Pemantauan Ibu Hamil (Hanya untuk Ibu Hamil) --}}
            @if($cat === 'ibu_hamil')
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] p-8 relative overflow-hidden">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2">
                            <span class="material-symbols-outlined text-rose-500 bg-rose-50 p-2 rounded-xl">monitoring</span>
                            Visualisasi Grafik Pemantauan Ibu Hamil
                        </h3>
                        <p class="text-[11px] font-medium text-slate-400 mt-1 ml-11">Tren peningkatan berat badan dan lingkar lengan atas (LiLA) KEK periode ini.</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Gestational Weight Gain Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-pink-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Peningkatan Berat Badan (kg)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="gwgChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- LiLA Chart --}}
                    <div class="bg-slate-50/50 p-4 rounded-2xl border border-slate-100">
                        <div class="flex items-center gap-2 mb-4">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            <span class="text-[10px] font-black text-slate-500 uppercase tracking-widest">Lingkar Lengan Atas / LiLA (cm)</span>
                        </div>
                        <div class="h-56 w-full relative">
                            <canvas id="lilaChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 3.2. Tabel Detail Pengukuran Bulanan --}}
            <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col">
                <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/30">
                    <h3 class="text-sm font-black text-slate-800 tracking-tight flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-500 bg-indigo-50 p-2 rounded-xl">table_rows</span>
                        Riwayat Pengukuran Bulanan
                    </h3>
                </div>
                
                <div class="overflow-x-auto p-4">
                    <table class="w-full text-left border-collapse whitespace-nowrap">
                        <thead>
                            <tr>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Periode</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tgl Kunjungan</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">BB (kg)</th>
                                <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">TB (cm)</th>
                                @if(in_array($cat, ['bayi', 'baduta', 'balita']))
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">LILA / LK</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Status Gizi</th>
                                @elseif($cat === 'lansia')
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tensi / Gula / Urat / Kol</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Catatan</th>
                                @else
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Tensi / Gula</th>
                                    <th class="px-5 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest border-b border-slate-100">Catatan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100/50">
                            @foreach($reportData['monthly_records'] as $slot)
                                @php $record = $slot['record']; @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-[11px] font-black uppercase tracking-wider">
                                            {{ $slot['period']['label'] }}
                                        </span>
                                    </td>
                                    @if($record)
                                        <td class="px-5 py-4 text-sm font-semibold text-slate-500">{{ $record['visit_date'] }}</td>
                                        <td class="px-5 py-4 text-sm font-black text-slate-800">{{ $record['weight'] ?? '-' }}</td>
                                        <td class="px-5 py-4 text-sm font-black text-slate-800">{{ $record['height'] ?? '-' }}</td>
                                        
                                        @if(in_array($cat, ['bayi', 'baduta', 'balita']))
                                            <td class="px-5 py-4 text-sm font-medium text-slate-500">
                                                {{ $record['upper_arm_circumference'] ?? '-' }} / {{ $record['head_circumference'] ?? '-' }}
                                            </td>
                                            <td class="px-5 py-4">
                                                @php
                                                    $st = $record['nutrition_status'] ?? null;
                                                    $badge = match($st) {
                                                        'Normal', 'Gizi Baik' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
                                                        'Gizi Kurang', 'Kurang' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
                                                        'Gizi Buruk/Stunting', 'Gizi Buruk' => 'bg-rose-50 text-rose-700 ring-1 ring-rose-600/20',
                                                        'Gizi Lebih', 'Berisiko Gizi Lebih', 'Obesitas' => 'bg-orange-50 text-orange-700 ring-1 ring-orange-600/20',
                                                        default => 'bg-slate-100 text-slate-500 ring-1 ring-slate-400/20',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-[10px] font-black uppercase tracking-wider {{ $badge }}">{{ $st ?? '-' }}</span>
                                            </td>
                                        @elseif($cat === 'lansia')
                                            <td class="px-5 py-4 text-sm font-medium text-slate-500">
                                                {{ $record['blood_pressure'] ?? '-' }} / 
                                                {{ $record['blood_sugar'] ?? '-' }} / 
                                                {{ $record['uric_acid'] ?? '-' }} / 
                                                {{ $record['cholesterol'] ?? '-' }}
                                            </td>
                                            <td class="px-5 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $record['health_note'] ?? $record['complaint'] ?? '-' }}</td>
                                        @else
                                            <td class="px-5 py-4 text-sm font-medium text-slate-500">
                                                {{ $record['blood_pressure'] ?? '-' }} / {{ $record['blood_sugar'] ?? '-' }}
                                            </td>
                                            <td class="px-5 py-4 text-sm text-slate-500 max-w-xs truncate">{{ $record['health_note'] ?? $record['complaint'] ?? '-' }}</td>
                                        @endif
                                    @else
                                        <td colspan="5" class="px-5 py-4 text-center">
                                            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-widest bg-slate-50 px-3 py-1 rounded-lg border border-slate-100">Tidak Hadir / Kosong</span>
                                        </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3.3. Imunisasi & Vitamin A --}}
            @if($cat !== 'lansia')
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Riwayat Vitamin --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] flex flex-col">
                    <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500 bg-amber-50 p-1.5 rounded-lg text-[18px]">medication</span>
                        Vitamin A & Obat Cacing
                    </h4>
                    
                    <div class="space-y-3 flex-1">
                        @forelse($reportData['vitamins_in_period'] as $vit)
                            <div class="flex items-center justify-between p-3 bg-white border border-slate-100 rounded-2xl shadow-sm hover:border-teal-200 transition-colors group">
                                <div class="flex flex-col">
                                    <span class="text-[13px] font-bold text-slate-700 group-hover:text-teal-700 transition-colors">{{ $vit['note'] }}</span>
                                    <span class="text-[10px] font-medium text-slate-400 mt-0.5"><span class="font-bold">Diberikan:</span> {{ $vit['date'] }}</span>
                                </div>
                                <span class="text-[9px] font-black uppercase px-3 py-1.5 rounded-xl border {{ $vit['color'] == 'red' ? 'bg-rose-50 text-rose-600 border-rose-100' : 'bg-blue-50 text-blue-600 border-blue-100' }} shadow-inner-sm">{{ $vit['color'] }}</span>
                            </div>
                        @empty
                            <div class="h-full flex flex-col items-center justify-center py-8 text-center">
                                <div class="w-12 h-12 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-center mb-3">
                                    <span class="material-symbols-outlined text-slate-300">event_busy</span>
                                </div>
                                <p class="text-[11px] font-medium text-slate-400">Tidak ada pemberian<br>pada periode ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- Riwayat Imunisasi Wajib --}}
                <div class="bg-white rounded-3xl border border-slate-100 p-6 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] flex flex-col">
                    <h4 class="text-[11px] font-black text-slate-800 uppercase tracking-widest border-b border-slate-100 pb-4 mb-4 flex items-center gap-2">
                        <span class="material-symbols-outlined text-sky-500 bg-sky-50 p-1.5 rounded-lg text-[18px]">vaccines</span>
                        Kelengkapan Imunisasi
                    </h4>
                    
                    <div class="space-y-2.5 max-h-75 overflow-y-auto pr-2 custom-scrollbar">
                        @foreach($reportData['immunization_status'] as $group)
                            @foreach($group['vaccines'] as $vax)
                                <div class="flex items-center justify-between p-3 rounded-2xl border {{ $vax['received'] ? 'bg-emerald-50/50 border-emerald-100/50' : 'bg-white border-slate-100 hover:border-slate-200' }} transition-colors">
                                    <div class="flex flex-col min-w-0 mr-3">
                                        <span class="text-[13px] font-bold {{ $vax['received'] ? 'text-emerald-800' : 'text-slate-700' }} truncate">{{ $vax['name'] }}</span>
                                        <span class="text-[10px] font-medium {{ $vax['received'] ? 'text-emerald-600' : 'text-slate-400' }} truncate mt-0.5">{{ $group['label'] }}</span>
                                    </div>
                                    
                                    @if($vax['received'])
                                        <div class="w-8 h-8 shrink-0 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 shadow-inner-sm">
                                            <span class="material-symbols-outlined text-[16px]" style="font-variation-settings:'FILL' 1;">check</span>
                                        </div>
                                    @elseif($vax['is_due'])
                                        <span class="text-[9px] font-black uppercase px-2.5 py-1 bg-amber-50 border border-amber-100 text-amber-600 rounded-lg shrink-0">Due</span>
                                    @else
                                        <div class="w-8 h-8 shrink-0 rounded-full bg-slate-50 border border-slate-100 flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined text-[16px]">horizontal_rule</span>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

            </div>
            @endif

        </div>

    </div>

</div>

{{-- Custom Styles for Scrollbar & Animations --}}
@push('styles')
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 4px; }
    .custom-scrollbar:hover::-webkit-scrollbar-thumb { background: #cbd5e1; }
</style>
@endpush

{{-- Preset JavaScript --}}
<script>
    function updateHiddenFields() {
        const startVal = document.getElementById('start_period').value;
        if (startVal) {
            const [sy, sm] = startVal.split('-');
            document.getElementById('start_month').value = parseInt(sm, 10);
            document.getElementById('start_year').value = parseInt(sy, 10);
        }
        
        const endVal = document.getElementById('end_period').value;
        if (endVal) {
            const [ey, em] = endVal.split('-');
            document.getElementById('end_month').value = parseInt(em, 10);
            document.getElementById('end_year').value = parseInt(ey, 10);
        }
    }

    function setPeriodPreset(months) {
        const endVal = document.getElementById('end_period').value;
        if (!endVal) return;
        
        let [endY, endM] = endVal.split('-').map(Number);
        
        let startM = endM - months + 1;
        let startY = endY;
        
        while (startM <= 0) {
            startM += 12;
            startY -= 1;
        }
        
        const startMStr = startM.toString().padStart(2, '0');
        document.getElementById('start_period').value = `${startY}-${startMStr}`;
        updateHiddenFields();
        
        document.getElementById('filterForm').submit();
    }
</script>

{{-- Chart.js Rendering (browser only) --}}
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Chart === 'undefined') return;

        const records = @json($reportData['raw_records']);
        const category = "{{ $cat }}";
        
        // Sort ascending by date
        records.sort((a, b) => new Date(a.visit_date) - new Date(b.visit_date));

        const labels = records.map(r => {
            if (!r.visit_date) return '';
            const d = new Date(r.visit_date);
            const dateStr = d.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
            if (category === 'ibu_hamil' && r.gestational_age) {
                return [r.gestational_age, dateStr];
            }
            return dateStr;
        });

        // Common Chart Options
        const commonOptions = {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { 
                legend: { display: false },
                tooltip: {
                    backgroundColor: 'rgba(15, 23, 42, 0.9)',
                    titleFont: { size: 11, family: "'Inter', sans-serif" },
                    bodyFont: { size: 13, weight: 'bold', family: "'Inter', sans-serif" },
                    padding: 10,
                    cornerRadius: 8,
                    displayColors: false,
                }
            },
            scales: {
                y: { 
                    grid: { color: 'rgba(15, 23, 42, 0.04)', drawBorder: false },
                    ticks: { font: { size: 10, family: "'Inter', sans-serif" }, color: '#94a3b8' },
                    border: { display: false }
                },
                x: { 
                    grid: { display: false, drawBorder: false },
                    ticks: { font: { size: 10, family: "'Inter', sans-serif" }, color: '#94a3b8' },
                    border: { display: false }
                }
            },
            interaction: { mode: 'index', intersect: false }
        };

        if (['bayi', 'baduta', 'balita'].includes(category)) {
            // 1. Weight Chart
            const weightData = records.map(r => parseFloat(r.weight) || null);
            const wCanvas = document.getElementById('weightChartCanvas');
            if (wCanvas) {
                new Chart(wCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Berat Badan (kg)',
                            data: weightData,
                            borderColor: '#14b8a6',
                            backgroundColor: 'rgba(20, 184, 166, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#14b8a6',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 0 }
                        }
                    }
                });
            }

            // 2. Height Chart
            const heightData = records.map(r => parseFloat(r.height) || null);
            const hCanvas = document.getElementById('heightChartCanvas');
            if (hCanvas) {
                new Chart(hCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Tinggi Badan (cm)',
                            data: heightData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 40 }
                        }
                    }
                });
            }
        } else if (category === 'lansia') {
            // 1. Tensi Chart (Blood Pressure: Systolic & Diastolic)
            const tensiCanvas = document.getElementById('tensiChartCanvas');
            if (tensiCanvas) {
                const sysData = records.map(r => parseInt(r.systolic_bp) || null);
                const diaData = records.map(r => parseInt(r.diastolic_bp) || null);
                new Chart(tensiCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Sistolik (mmHg)',
                                data: sysData,
                                borderColor: '#ef4444',
                                backgroundColor: 'rgba(239, 68, 68, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#ef4444',
                                pointBorderWidth: 2,
                            },
                            {
                                label: 'Diastolik (mmHg)',
                                data: diaData,
                                borderColor: '#3b82f6',
                                backgroundColor: 'rgba(59, 130, 246, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#3b82f6',
                                pointBorderWidth: 2,
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            legend: { display: true }
                        },
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 50, suggestedMax: 150 }
                        }
                    }
                });
            }

            // 2. Gula Darah Chart
            const sugarCanvas = document.getElementById('sugarChartCanvas');
            if (sugarCanvas) {
                const sugarData = records.map(r => parseInt(r.blood_sugar) || null);
                new Chart(sugarCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Gula Darah (mg/dL)',
                            data: sugarData,
                            borderColor: '#eab308',
                            backgroundColor: 'rgba(234, 179, 8, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#eab308',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 70, suggestedMax: 200 }
                        }
                    }
                });
            }

            // 3. Asam Urat Chart
            const uricCanvas = document.getElementById('uricChartCanvas');
            if (uricCanvas) {
                const uricData = records.map(r => parseFloat(r.uric_acid) || null);
                new Chart(uricCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Asam Urat (mg/dL)',
                            data: uricData,
                            borderColor: '#8b5cf6',
                            backgroundColor: 'rgba(139, 92, 246, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#8b5cf6',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 2, suggestedMax: 10 }
                        }
                    }
                });
            }

            // 4. Kolesterol Chart
            const cholCanvas = document.getElementById('cholesterolChartCanvas');
            if (cholCanvas) {
                const cholData = records.map(r => parseInt(r.cholesterol) || null);
                new Chart(cholCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Kolesterol (mg/dL)',
                            data: cholData,
                            borderColor: '#3b82f6',
                            backgroundColor: 'rgba(59, 130, 246, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#3b82f6',
                            pointBorderWidth: 2,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 100, suggestedMax: 250 }
                        }
                    }
                });
            }
        } else if (category === 'ibu_hamil') {
            // 1. Gestational Weight Gain Chart
            const gwgCanvas = document.getElementById('gwgChartCanvas');
            if (gwgCanvas) {
                // Find starting weight
                let startingWeight = null;
                for (const r of records) {
                    if (parseFloat(r.starting_weight) > 0) {
                        startingWeight = parseFloat(r.starting_weight);
                        break;
                    }
                }
                if (!startingWeight && records.length > 0) {
                    startingWeight = parseFloat(records[0].weight) || 0;
                }

                const gwgData = records.map(r => {
                    const w = parseFloat(r.weight);
                    if (!w) return null;
                    const startW = parseFloat(r.starting_weight) || startingWeight;
                    return Math.max(0, w - startW);
                });

                new Chart(gwgCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Peningkatan Berat (kg)',
                            data: gwgData,
                            borderColor: '#ec4899',
                            backgroundColor: 'rgba(236, 72, 153, 0.05)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff',
                            pointBorderColor: '#ec4899',
                            pointBorderWidth: 2,
                            pointHoverRadius: 6,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 0, suggestedMax: 10 }
                        }
                    }
                });
            }

            // 2. LiLA Chart
            const lilaCanvas = document.getElementById('lilaChartCanvas');
            if (lilaCanvas) {
                const lilaData = records.map(r => parseFloat(r.upper_arm_circumference) || null);
                new Chart(lilaCanvas, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'LiLA (cm)',
                                data: lilaData,
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99, 102, 241, 0.05)',
                                borderWidth: 3,
                                fill: true,
                                tension: 0.4,
                                pointRadius: 4,
                                pointBackgroundColor: '#fff',
                                pointBorderColor: '#6366f1',
                                pointBorderWidth: 2,
                                pointHoverRadius: 6,
                            },
                            {
                                label: 'Batas KEK (23.5 cm)',
                                data: records.map(() => 23.5),
                                borderColor: '#ef4444',
                                borderWidth: 1.5,
                                borderDash: [5, 5],
                                fill: false,
                                pointRadius: 0,
                                pointHoverRadius: 0,
                            }
                        ]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            legend: { display: true }
                        },
                        scales: {
                            ...commonOptions.scales,
                            y: { ...commonOptions.scales.y, suggestedMin: 20, suggestedMax: 30 }
                        }
                    }
                });
            }
        }
    });
</script>
@endpush
@endsection
