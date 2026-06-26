@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet" />
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #134e4a 100%);
        }

        .hero-orb-1 {
            background: radial-gradient(circle, rgba(20, 184, 166, 0.25) 0%, transparent 70%);
        }

        .hero-orb-2 {
            background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
        }

        @keyframes ping-slow {
            0%, 100% {
                transform: scale(1);
                opacity: 0.8;
            }
            50% {
                transform: scale(1.8);
                opacity: 0;
            }
        }

        .animate-ping-slow {
            animation: ping-slow 2s ease-in-out infinite;
        }
    </style>
@endpush

<div class="max-w-7xl mx-auto space-y-6 pb-20 px-4 sm:px-6">

    {{-- ── Hero Section (Analitik) ── --}}
    <section class="relative rounded-2xl overflow-hidden" style="background:#0f172a;">
        {{-- Background layers --}}
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute top-0 left-1/4 w-72 h-72 hero-orb-1 rounded-full filter blur-[60px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-72 h-72 hero-orb-2 rounded-full filter blur-[60px]"
            style="animation:pulse 4s ease-in-out 1.5s infinite;"></div>
        {{-- Grid overlay --}}
        <div class="absolute inset-0 opacity-[0.04]"
            style="background-image:url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjAuNSIgZmlsbD0ibm9uZSI+PHBhdGggZD0iTTQwIDBMMCA0ME0wIDBsNDAgNDAiLz48L2c+PC9zdmc+');">
        </div>

        <div class="relative z-10 px-8 py-10 md:px-12 md:py-12 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
            <div class="space-y-3">
                {{-- Live badge --}}
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full"
                    style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);">
                    <span class="relative flex h-2.5 w-2.5">
                        <span class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                        <span class="relative inline-flex h-2.5 w-2.5 rounded-full bg-teal-400"></span>
                    </span>
                    <span class="text-xs font-bold text-teal-100 tracking-wide uppercase">Sistem Analitik Pelayanan Kesehatan</span>
                </div>

                <h1 class="text-3xl md:text-4xl font-extrabold text-white tracking-tight leading-tight" style="letter-spacing:-0.02em;">
                    Dashboard Analitik & Wawasan Posyandu
                </h1>

                <p class="text-slate-300 text-sm md:text-base leading-relaxed font-medium" style="opacity:0.9;">
                    Data Wawasan Klinis & Keaktifan Posyandu Periode Tahun <strong class="text-white font-black">{{ $selectedYear }}</strong>
                    @if(auth()->user()->posyandu)
                        | <span class="text-teal-300 font-bold">Unit Kerja: {{ auth()->user()->posyandu->name }}</span>
                    @endif
                </p>
            </div>

            <div class="flex items-center no-print mt-4 md:mt-0">
                <div class="text-xs md:text-sm font-bold text-teal-100 bg-white/10 border border-white/20 backdrop-blur-xs px-4 py-2.5 rounded-xl shadow-xs">
                    Terakhir Diperbarui: <span class="text-white font-extrabold">{{ $lastUpdated }}</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ── Accessible Tab Navigation ── --}}
    <div class="flex flex-wrap items-center bg-slate-100 p-1.5 rounded-2xl border border-slate-200 shadow-xs gap-1">
        <button wire:click="$set('activeTab', 'overview')" @class([
            'flex-1 min-w-[120px] py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 border cursor-pointer',
            'bg-teal-600 text-white border-teal-700 shadow-xs' => $activeTab === 'overview',
            'text-slate-700 hover:text-slate-900 hover:bg-slate-200/60 border-transparent' => $activeTab !== 'overview'
        ])>
            <span class="material-symbols-outlined text-[20px]">dashboard</span>
            Overview Ringkasan
        </button>
        <button wire:click="$set('activeTab', 'balita')" @class([
            'flex-1 min-w-[120px] py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 border cursor-pointer',
            'bg-teal-600 text-white border-teal-700 shadow-xs' => $activeTab === 'balita',
            'text-slate-700 hover:text-slate-900 hover:bg-slate-200/60 border-transparent' => $activeTab !== 'balita'
        ])>
            <span class="material-symbols-outlined text-[20px]">child_care</span>
            Balita & Anak
        </button>
        <button wire:click="$set('activeTab', 'pregnancy')" @class([
            'flex-1 min-w-[120px] py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 border cursor-pointer',
            'bg-teal-600 text-white border-teal-700 shadow-xs' => $activeTab === 'pregnancy',
            'text-slate-700 hover:text-slate-900 hover:bg-slate-200/60 border-transparent' => $activeTab !== 'pregnancy'
        ])>
            <span class="material-symbols-outlined text-[20px]">pregnant_woman</span>
            Ibu Hamil
        </button>
        <button wire:click="$set('activeTab', 'lansia')" @class([
            'flex-1 min-w-[120px] py-3 px-4 rounded-xl text-sm font-bold transition-all duration-200 flex items-center justify-center gap-2 border cursor-pointer',
            'bg-teal-600 text-white border-teal-700 shadow-xs' => $activeTab === 'lansia',
            'text-slate-700 hover:text-slate-900 hover:bg-slate-200/60 border-transparent' => $activeTab !== 'lansia'
        ])>
            <span class="material-symbols-outlined text-[20px]">elderly</span>
            Lanjut Usia (Lansia)
        </button>
    </div>

    {{-- ── Unified Control Card (Filter + View Settings) ── --}}
    <div class="bg-white rounded-2xl shadow-xs border border-slate-200 overflow-hidden">

        {{-- ── Baris 1: Filter Global ── --}}
        <div class="p-5 sm:p-6">
            <div class="flex flex-col xl:flex-row gap-5 items-start xl:items-end">

                {{-- Label kiri atas --}}
                <div class="flex-1 w-full">
                    <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[15px] text-teal-600">tune</span>
                        Filter Data
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">

                        {{-- Wilayah Posyandu --}}
                        @if(auth()->user()->isSuperAdmin())
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Wilayah Posyandu</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-teal-500">
                                    <span class="material-symbols-outlined text-[18px]">location_on</span>
                                </span>
                                <select wire:model.live="selectedPosyandu"
                                    class="w-full h-11 pl-9 pr-4 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs transition-shadow hover:border-slate-300">
                                    <option value="">Semua Wilayah</option>
                                    @foreach(\App\Models\Posyandu::all() as $p)
                                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        @endif

                        {{-- Tahun --}}
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tahun</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center text-slate-400">
                                    <span class="material-symbols-outlined text-[18px]">calendar_today</span>
                                </span>
                                <select wire:model.live="selectedYear"
                                    class="w-full h-11 pl-9 pr-4 rounded-xl border border-slate-200 text-sm font-semibold text-slate-800 bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs transition-shadow hover:border-slate-300">
                                    @foreach($years as $y)
                                        <option value="{{ $y }}">{{ $y }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        {{-- Bulan --}}
                        <div class="flex flex-col gap-1">
                            <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Bulan</label>
                            <div class="relative">
                                <span class="pointer-events-none absolute inset-y-0 left-3 flex items-center {{ ($activeTab === 'overview' && $viewMode === 'yearly') ? 'text-slate-300' : 'text-slate-400' }}">
                                    <span class="material-symbols-outlined text-[18px]">event_note</span>
                                </span>
                                <select wire:model.live="selectedMonth"
                                    @if($activeTab === 'overview' && $viewMode === 'yearly') disabled @endif
                                    class="w-full h-11 pl-9 pr-4 rounded-xl border text-sm font-semibold bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs transition-shadow
                                        {{ ($activeTab === 'overview' && $viewMode === 'yearly')
                                            ? 'border-slate-100 text-slate-300 bg-slate-50 cursor-not-allowed'
                                            : 'border-slate-200 text-slate-800 hover:border-slate-300 cursor-pointer' }}">
                                    <option value="">{{ ($activeTab === 'overview' && $viewMode === 'yearly') ? 'Nonaktif (Mode YoY)' : 'Semua Bulan (Tahunan)' }}</option>
                                    @foreach(range(1, 12) as $m)
                                        <option value="{{ $m }}">{{ Carbon\Carbon::create(2000, $m)->translatedFormat('F') }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex gap-2.5 w-full xl:w-auto shrink-0">
                    <button wire:click="resetFilters"
                        class="flex-1 xl:flex-none h-11 px-4 xl:px-5 inline-flex items-center justify-center gap-2 bg-white hover:bg-slate-50 text-slate-700 font-bold text-xs uppercase tracking-wider rounded-xl border border-slate-200 hover:border-slate-300 shadow-xs transition-all cursor-pointer">
                        <span class="material-symbols-outlined text-[18px]">filter_alt_off</span>
                        Reset
                    </button>
                    <button wire:click="refreshStats"
                        wire:loading.attr="disabled"
                        class="flex-1 xl:flex-none h-11 px-4 xl:px-6 inline-flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-700 active:bg-teal-800 text-white font-bold text-xs uppercase tracking-wider rounded-xl shadow-xs transition-all cursor-pointer disabled:opacity-60 disabled:cursor-not-allowed">
                        <span class="material-symbols-outlined text-[18px]" wire:loading.class="animate-spin" wire:target="refreshStats">sync</span>
                        Perbarui Data
                    </button>
                </div>
            </div>
        </div>

        {{-- ── Baris 2: Pengaturan Visualisasi (hanya untuk tab Overview) ── --}}
        @if($activeTab === 'overview')
        <div class="border-t border-slate-100 bg-slate-50/60 px-5 sm:px-6 py-4">
            <p class="text-[11px] font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-1.5">
                <span class="material-symbols-outlined text-[15px] text-indigo-500">insert_chart</span>
                Konfigurasi Visualisasi Grafik Overview
            </p>
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4">

                {{-- Segmented view mode control --}}
                <div class="flex flex-col gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mode Tampilan</span>
                    <div class="inline-flex items-center bg-slate-200/70 rounded-xl p-1 gap-1">
                        <button wire:click="$set('viewMode', 'monthly')" @class([
                            'inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all cursor-pointer',
                            'bg-white text-teal-700 shadow-sm border border-slate-200/80' => $viewMode === 'monthly',
                            'text-slate-600 hover:text-slate-900 hover:bg-white/50' => $viewMode !== 'monthly',
                        ])>
                            <span class="material-symbols-outlined text-[16px]">bar_chart</span>
                            Tampilan Bulanan
                        </button>
                        <button wire:click="$set('viewMode', 'yearly')" @class([
                            'inline-flex items-center gap-1.5 px-4 py-2 rounded-lg text-xs font-bold tracking-wide transition-all cursor-pointer',
                            'bg-white text-indigo-700 shadow-sm border border-slate-200/80' => $viewMode === 'yearly',
                            'text-slate-600 hover:text-slate-900 hover:bg-white/50' => $viewMode !== 'yearly',
                        ])>
                            <span class="material-symbols-outlined text-[16px]">show_chart</span>
                            Year-over-Year (Tahunan)
                        </button>
                    </div>
                </div>

                <div class="hidden sm:block h-8 w-px bg-slate-200 self-end mb-0.5"></div>

                {{-- Compare toggle --}}
                <div class="flex flex-col gap-1">
                    <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Bandingkan Periode</span>
                    <button wire:click="$toggle('compareMode')" @class([
                        'inline-flex items-center gap-3 h-10 px-4 rounded-xl border font-bold text-sm transition-all cursor-pointer',
                        'bg-teal-50 border-teal-300 text-teal-800' => $compareMode,
                        'bg-white border-slate-200 text-slate-600 hover:border-slate-300' => !$compareMode,
                    ])>
                        {{-- Toggle track --}}
                        <span @class([
                            'relative inline-flex h-5 w-9 items-center rounded-full transition-colors shrink-0',
                            'bg-teal-500' => $compareMode,
                            'bg-slate-300' => !$compareMode,
                        ])>
                            <span @class([
                                'inline-block h-3.5 w-3.5 rounded-full bg-white shadow transition-transform',
                                'translate-x-4' => $compareMode,
                                'translate-x-0.5' => !$compareMode,
                            ])></span>
                        </span>
                        <span>Mode Bandingkan (Bulan Lalu)</span>
                    </button>
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Tab Contents ── --}}
    @if($activeTab === 'overview')
        {{-- ================= OVERVIEW TAB ================= --}}
        <div class="space-y-8 animate-fadeIn">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $overviewCards = [
                        ['label' => 'Total Kunjungan', 'val' => number_format($totalKunjungan), 'unit' => 'Pemeriksaan', 'icon' => 'analytics', 'color' => 'slate', 'desc' => 'Total rekam medis terdaftar'],
                        ['label' => 'Balita & Anak', 'val' => number_format($totalBalita), 'unit' => 'Jiwa', 'icon' => 'child_care', 'color' => 'teal', 'desc' => 'Kategori Balita, Bayi & Baduta'],
                        ['label' => 'Ibu Hamil', 'val' => number_format($totalIbuHamil), 'unit' => 'Jiwa', 'icon' => 'pregnant_woman', 'color' => 'rose', 'desc' => 'Ibu mengandung terdaftar'],
                        ['label' => 'Lansia', 'val' => number_format($totalLansia), 'unit' => 'Jiwa', 'icon' => 'elderly', 'color' => 'indigo', 'desc' => 'Kategori Lanjut Usia'],
                    ];
                @endphp

                @foreach($overviewCards as $c)
                <div @class([
                    'relative overflow-hidden bg-white rounded-2xl p-6 border-2 transition-all duration-300 group shadow-xs',
                    'border-slate-200 hover:border-slate-400' => $c['color'] === 'slate',
                    'border-teal-200 hover:border-teal-500' => $c['color'] === 'teal',
                    'border-rose-200 hover:border-rose-500' => $c['color'] === 'rose',
                    'border-indigo-200 hover:border-indigo-500' => $c['color'] === 'indigo',
                ])>
                    <div class="relative z-10 flex items-center justify-between mb-3">
                        <div @class([
                            'w-11 h-11 rounded-lg flex items-center justify-center',
                            'bg-slate-900 text-white' => $c['color'] === 'slate',
                            'bg-teal-600 text-white' => $c['color'] === 'teal',
                            'bg-rose-600 text-white' => $c['color'] === 'rose',
                            'bg-indigo-600 text-white' => $c['color'] === 'indigo',
                        ])>
                            <span class="material-symbols-outlined text-[24px]">{{ $c['icon'] }}</span>
                        </div>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $c['unit'] }}</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $c['val'] }}</h3>
                        <p class="text-sm font-extrabold text-slate-800 uppercase tracking-tight">{{ $c['label'] }}</p>
                        <p class="text-xs text-slate-500 mt-2 border-t border-slate-100 pt-2 font-medium">{{ $c['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Combined Trend Line Chart --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-lg md:text-xl font-extrabold text-slate-900 tracking-tight">Tren Kunjungan Bulanan Gabungan</h3>
                        <p class="text-xs md:text-sm text-slate-500 font-semibold mt-1">Perbandingan tren frekuensi kunjungan pasien per kategori di posyandu (Dapat diklik untuk detail)</p>
                    </div>
                    <button onclick="downloadChart(visitsTrendChart, 'tren_kunjungan')" class="p-2.5 text-slate-500 hover:text-slate-800 rounded-xl bg-slate-50 border border-slate-300 transition-colors shadow-xs cursor-pointer flex items-center justify-center" title="Unduh Gambar Grafik">
                        <span class="material-symbols-outlined text-[20px]">download</span>
                    </button>
                </div>
                <div class="relative h-96">
                    <canvas id="visitsTrendChart" wire:ignore></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/95 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 rounded-2xl" id="error-visitsTrendChart">
                        <span class="material-symbols-outlined text-rose-600 text-4xl mb-2">error</span>
                        <p class="text-sm font-extrabold text-slate-800">Gagal memuat data grafik</p>
                        <button onclick="initCharts()" class="mt-3 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-700 cursor-pointer">Coba Lagi</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($activeTab === 'balita')
        {{-- ================= BALITA TAB ================= --}}
        <div class="space-y-8 animate-fadeIn">
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                    $balitaCards = [
                        ['label' => 'Total Balita', 'val' => number_format($totalBalita), 'unit' => 'Jiwa', 'icon' => 'child_care', 'color' => 'teal', 'desc' => 'Tumbuh kembang aktif terpantau'],
                        ['label' => 'Prevalensi Stunting', 'val' => $stuntingRate . '%', 'unit' => 'Persentase', 'icon' => 'trending_down', 'color' => 'red', 'desc' => $stuntingRate >= 14 ? 'Butuh perhatian khusus' : 'Kategori aman & terkendali'],
                        ['label' => 'Cakupan Imunisasi', 'val' => $cakupanImunisasi . '%', 'unit' => 'Target 100%', 'icon' => 'vaccines', 'color' => 'blue', 'desc' => 'Persentase dosis imunisasi dasar'],
                        ['label' => 'Kader Lapangan', 'val' => $kaderAktif, 'unit' => 'Personel', 'icon' => 'badge', 'color' => 'amber', 'desc' => 'Kader aktif membina balita'],
                    ];
                @endphp

                @foreach($balitaCards as $c)
                <div @class([
                    'relative overflow-hidden bg-white rounded-2xl p-6 border-2 transition-all duration-300 group shadow-xs',
                    'border-teal-200 hover:border-teal-500' => $c['color'] === 'teal',
                    'border-red-200 hover:border-red-500' => $c['color'] === 'red',
                    'border-blue-200 hover:border-blue-500' => $c['color'] === 'blue',
                    'border-amber-200 hover:border-amber-500' => $c['color'] === 'amber',
                ])>
                    <div class="relative z-10 flex items-center justify-between mb-3">
                        <div @class([
                            'w-11 h-11 rounded-lg flex items-center justify-center',
                            'bg-teal-600 text-white' => $c['color'] === 'teal',
                            'bg-red-600 text-white' => $c['color'] === 'red',
                            'bg-blue-600 text-white' => $c['color'] === 'blue',
                            'bg-amber-600 text-white' => $c['color'] === 'amber',
                        ])>
                            <span class="material-symbols-outlined text-[24px]">{{ $c['icon'] }}</span>
                        </div>
                        <span class="text-xs font-black uppercase tracking-wider text-slate-500 bg-slate-100 px-2 py-0.5 rounded-md">{{ $c['unit'] }}</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-3xl font-black text-slate-900 tracking-tight">{{ $c['val'] }}</h3>
                        <p class="text-sm font-extrabold text-slate-800 uppercase tracking-tight">{{ $c['label'] }}</p>
                        <p class="text-xs text-slate-500 mt-2 border-t border-slate-100 pt-2 font-medium">{{ $c['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Nutrition Trend Line Chart --}}
                <div class="lg:col-span-2 bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-slate-900">Prevalensi Pertumbuhan Balita</h3>
                        <button onclick="downloadChart(nutritionTrendChart, 'tren_status_gizi_balita')" class="p-2.5 text-slate-500 hover:text-slate-800 rounded-xl bg-slate-50 border border-slate-300 transition-colors shadow-xs cursor-pointer flex items-center justify-center" title="Unduh Gambar Grafik">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                        </button>
                    </div>
                    <div class="relative h-85">
                        <canvas id="nutritionTrendChart" wire:ignore></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/95 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 rounded-2xl" id="error-nutritionTrendChart">
                            <span class="material-symbols-outlined text-rose-600 text-4xl mb-2">error</span>
                            <p class="text-sm font-extrabold text-slate-800">Gagal memuat data grafik</p>
                            <button onclick="initCharts()" class="mt-3 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-700 cursor-pointer">Coba Lagi</button>
                        </div>
                    </div>
                </div>

                {{-- Nutrition Donut Chart --}}
                <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs flex flex-col justify-between">
                    <div class="flex items-start justify-between mb-2">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900">Status Gizi Balita</h3>
                            <p class="text-xs text-slate-500 mt-1">Distribusi data pemeriksaan terbaru</p>
                        </div>
                        <button onclick="downloadChart(nutritionDonutChart, 'distribusi_status_gizi_balita')" class="p-2.5 text-slate-500 hover:text-slate-800 rounded-xl bg-slate-50 border border-slate-300 transition-colors shadow-xs cursor-pointer flex items-center justify-center" title="Unduh Gambar Grafik">
                            <span class="material-symbols-outlined text-[18px]">download</span>
                        </button>
                    </div>
                    <div class="relative flex justify-center my-6">
                        <canvas id="nutritionDonutChart" width="180" height="180" style="max-width:180px;max-height:180px;" wire:ignore></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/95 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 rounded-2xl" id="error-nutritionDonutChart">
                            <span class="material-symbols-outlined text-rose-600 text-4xl mb-2">error</span>
                            <p class="text-sm font-extrabold text-slate-800">Gagal memuat grafik</p>
                            <button onclick="initCharts()" class="mt-3 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-700 cursor-pointer">Coba Lagi</button>
                        </div>
                    </div>
                    <div class="space-y-2 max-h-40 overflow-y-auto pr-1">
                        @php $i = 0; @endphp
                        @foreach($nutritionLabels as $label)
                        @php
                            $val = $nutritionData[$i] ?? 0;
                            $sum = array_sum($nutritionData);
                            $pct = $sum > 0 ? round(($val / $sum) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-xs font-bold text-slate-800 border-b border-slate-100 pb-1">
                            <span>{{ $label }}</span>
                            <span class="font-extrabold text-slate-900">{{ $val }} ({{ $pct }}%)</span>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Vaccine Bar Chart --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-slate-900">Capaian Imunisasi Per Jenis</h3>
                    <button onclick="downloadChart(vaccineChart, 'capaian_imunisasi_balita')" class="p-2.5 text-slate-500 hover:text-slate-800 rounded-xl bg-slate-50 border border-slate-300 transition-colors shadow-xs cursor-pointer flex items-center justify-center" title="Unduh Gambar Grafik">
                        <span class="material-symbols-outlined text-[18px]">download</span>
                    </button>
                </div>
                <div class="relative h-70">
                    <canvas id="vaccineBarChart" wire:ignore></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center bg-white/95 backdrop-blur-xs opacity-0 pointer-events-none transition-opacity duration-300 rounded-2xl" id="error-vaccineBarChart">
                        <span class="material-symbols-outlined text-rose-600 text-4xl mb-2">error</span>
                        <p class="text-sm font-extrabold text-slate-800">Gagal memuat data grafik</p>
                        <button onclick="initCharts()" class="mt-3 px-4 py-2 bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider hover:bg-slate-700 cursor-pointer">Coba Lagi</button>
                    </div>
                </div>
            </div>

            {{-- Detailed Stunting per Posyandu --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs">
                    <h3 class="text-lg font-bold text-slate-900 mb-6">Prevalensi Stunting per Wilayah Posyandu</h3>
                    <div class="space-y-6">
                        @forelse($stuntingByPosyandu as $item)
                        <div>
                            <div class="flex justify-between items-end mb-2 text-xs font-bold text-slate-700">
                                <span>{{ $item['name'] }} ({{ $item['stunting'] }}/{{ $item['total'] }} Balita)</span>
                                <span class="font-extrabold text-slate-900">{{ $item['rate'] }}%</span>
                            </div>
                            <div class="h-3 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $item['width'] }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-6">Tidak ada data posyandu</p>
                        @endforelse
                    </div>
                </div>

                {{-- Demographic segments and insights --}}
                <div class="space-y-6 flex flex-col justify-between">
                    <div class="bg-white rounded-2xl p-6 md:p-8 border border-slate-200 shadow-xs">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 text-center">Segmentasi Usia Terpantau</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900">{{ $usia0_12 }}</span>
                                <span class="text-xs md:text-sm font-bold text-slate-600 uppercase tracking-wider">0–12 Bulan</span>
                            </div>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900">{{ $usia12_24 }}</span>
                                <span class="text-xs md:text-sm font-bold text-slate-600 uppercase tracking-wider">12–24 Bulan</span>
                            </div>
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900">{{ $usia24plus }}</span>
                                <span class="text-xs md:text-sm font-bold text-slate-600 uppercase tracking-wider">>24 Bulan</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-900 rounded-2xl p-6 md:p-8 text-white relative overflow-hidden flex-1 flex flex-col justify-center">
                        <div class="relative z-10 space-y-3">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-teal-400 text-[24px]">insights</span>
                                <h4 class="text-sm font-bold uppercase tracking-wider text-teal-300">Wawasan Balita</h4>
                            </div>
                            <p class="text-xs md:text-sm text-slate-300 leading-relaxed font-semibold">
                                @if($stuntingRate >= 14)
                                    Lampu Kuning: Prevalensi stunting mencapai {{ $stuntingRate }}% (ambang batas WHO: 14%). Tingkatkan pemantauan status gizi (tinggi badan & berat badan) dan distribusi PMT kaya protein.
                                @else
                                    Kondisi Kondusif: Prevalensi stunting terpantau di angka {{ $stuntingRate }}% yang aman di bawah ambang batas WHO. Pertahankan cakupan pemberian ASI eksklusif dan pendampingan kader.
                                @endif
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Recent Records for Balita --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pemeriksaan Balita Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar rekam medis pemeriksaan balita terkini</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto items-center">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="tableSearch" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs" 
                                   placeholder="Cari Nama atau NIK Anak...">
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-sm font-bold text-slate-700 whitespace-nowrap">Gender:</span>
                            <select wire:model.live="filterGender" 
                                    class="w-full sm:w-36 py-2 px-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                                <option value="">Semua</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Anak</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Unit Posyandu</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Status Gizi</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-250">
                            @forelse($recentRecords as $record)
                            <tr class="hover:bg-slate-50 text-slate-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-700 flex items-center justify-center font-extrabold text-sm border border-teal-100">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'B', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ $record->patient?->full_name }}</span>
                                            <span class="text-xs font-bold text-slate-500 uppercase block">NIK: {{ $record->patient?->id_number ?: '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $record->patient?->posyandu?->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-teal-550/10 text-teal-700 border border-teal-100">
                                        {{ $record->nutrition_status ?: 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-teal-600 hover:text-white transition-all shadow-xs">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 font-bold bg-white">Belum ada pemeriksaan balita</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if($activeTab === 'pregnancy')
        {{-- ================= PREGNANCY TAB ================= --}}
        <div class="space-y-8 animate-fadeIn">
            <livewire:admin.analytics.ibu-hamil-analytics :selected-year="$selectedYear" :selected-month="$selectedMonth" :selected-posyandu="$selectedPosyandu" />

            {{-- Recent Records for Ibu Hamil --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pemeriksaan Ibu Hamil Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar rekam medis pemeriksaan ibu hamil terkini</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto items-center">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="tableSearch" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs" 
                                   placeholder="Cari Nama atau NIK Ibu...">
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Ibu</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Unit Posyandu</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Usia Kehamilan</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-250">
                            @forelse($recentPregnancyRecords as $record)
                            <tr class="hover:bg-slate-50 text-slate-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center font-extrabold text-sm border border-rose-100">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'I', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ $record->patient?->full_name }}</span>
                                            <span class="text-xs font-bold text-slate-500 uppercase block">NIK: {{ $record->patient?->id_number ?: '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $record->patient?->posyandu?->name ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-rose-50 text-rose-750 border border-rose-100">
                                        {{ $record->gestational_age ?: 'N/A' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-rose-600 hover:text-white transition-all shadow-xs">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 font-bold bg-white">Belum ada pemeriksaan ibu hamil</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    @if($activeTab === 'lansia')
        {{-- ================= LANSIA TAB ================= --}}
        <div class="space-y-8 animate-fadeIn">
            <livewire:admin.analytics.lansia-analytics :selected-year="$selectedYear" :selected-month="$selectedMonth" :selected-posyandu="$selectedPosyandu" />

            {{-- Recent Records for Lansia --}}
            <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 bg-slate-50/50">
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Pemeriksaan Lansia Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar rekam medis pemeriksaan lansia terkini</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto items-center">
                        <div class="relative w-full sm:w-64">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-slate-400">
                                <span class="material-symbols-outlined text-[20px]">search</span>
                            </span>
                            <input type="text" wire:model.live.debounce.300ms="tableSearch" 
                                   class="w-full pl-10 pr-4 py-2.5 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 placeholder-slate-400 focus:outline-hidden focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs" 
                                   placeholder="Cari Nama atau NIK Lansia...">
                        </div>
                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <span class="text-sm font-bold text-slate-700 whitespace-nowrap">Gender:</span>
                            <select wire:model.live="filterGender" 
                                    class="w-full sm:w-36 py-2 px-3 bg-white border border-slate-300 rounded-xl text-sm font-semibold text-slate-800 focus:outline-hidden focus:ring-2 focus:ring-teal-500 focus:border-teal-500 shadow-xs">
                                <option value="">Semua</option>
                                <option value="L">Laki-laki</option>
                                <option value="P">Perempuan</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Lansia</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Unit Posyandu</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Tekanan Darah</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Gula Darah</th>
                                <th class="px-6 py-3.5 text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal</th>
                                <th class="px-6 py-3.5 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-250">
                            @forelse($recentLansiaRecords as $record)
                            <tr class="hover:bg-slate-50 text-slate-800">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-700 flex items-center justify-center font-extrabold text-sm border border-indigo-100">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'L', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-bold text-slate-900">{{ $record->patient?->full_name }}</span>
                                            <span class="text-xs font-bold text-slate-500 uppercase block">NIK: {{ $record->patient?->id_number ?: '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $record->patient?->posyandu?->name ?? '-' }}</td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-800">
                                    {{ $record->systolic_bp ?: '-' }}/{{ $record->diastolic_bp ?: '-' }} mmHg
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-800">
                                    {{ $record->blood_sugar ?: '-' }} mg/dL
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-9 h-9 inline-flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-600 hover:text-white transition-all shadow-xs">
                                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-sm text-slate-500 font-bold bg-white">Belum ada pemeriksaan lansia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ── ANA-22: Drill-down Modal ── --}}
    @if($showDrillDown)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs animate-fadeIn">
        <div class="relative w-full max-w-4xl bg-white rounded-3xl shadow-2xl border border-slate-200 overflow-hidden flex flex-col max-h-[85vh] animate-scaleUp">
            {{-- Modal Header --}}
            <div class="px-8 py-6 border-b border-slate-200 flex items-center justify-between bg-slate-50">
                <div>
                    <h3 class="text-xl font-extrabold text-slate-900">{{ $drillDownTitle }}</h3>
                    <p class="text-xs text-slate-500 mt-1 font-bold">Menampilkan hingga 50 rekam medis pencocokan terbaru</p>
                </div>
                <button wire:click="closeDrillDown" class="w-10 h-10 flex items-center justify-center rounded-xl bg-slate-100 text-slate-500 hover:text-slate-900 transition-colors cursor-pointer border border-slate-200">
                    <span class="material-symbols-outlined">close</span>
                </button>
            </div>
            
            {{-- Modal Body --}}
            <div class="flex-1 overflow-y-auto p-6">
                <div class="overflow-x-auto border border-slate-200 rounded-2xl">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-100 border-b border-slate-200">
                                <th class="px-6 py-4 text-xs font-bold text-slate-700 uppercase tracking-wider">Nama Pasien</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-700 uppercase tracking-wider">Unit Posyandu</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-700 uppercase tracking-wider">Status / Keterangan</th>
                                <th class="px-6 py-4 text-xs font-bold text-slate-700 uppercase tracking-wider">Tanggal Kunjungan</th>
                                <th class="px-6 py-4 text-right text-xs font-bold text-slate-700 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200">
                            @forelse($drillDownData as $row)
                            <tr class="hover:bg-slate-50 text-slate-800">
                                <td class="px-6 py-4">
                                    <div>
                                        <span class="block font-bold text-slate-900">{{ $row['name'] }}</span>
                                        <span class="text-[10px] text-slate-500 uppercase block font-semibold">NIK: {{ $row['nik'] }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-700">{{ $row['posyandu'] }}</td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-xs font-bold bg-teal-50 text-teal-700 border border-teal-100">
                                        {{ $row['nutrition_status'] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-xs font-bold text-slate-600">{{ $row['visit_date'] }}</td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $row['patient_id']) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-100 text-slate-500 hover:bg-teal-600 hover:text-white transition-all shadow-xs">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-10 text-center text-sm text-slate-500 font-bold bg-white">Tidak ada data detail untuk periode/filter ini</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Modal Footer --}}
            <div class="px-8 py-5 bg-slate-50 border-t border-slate-200 flex justify-end">
                <button wire:click="closeDrillDown" class="px-6 py-2.5 bg-slate-900 hover:bg-slate-800 text-white rounded-xl text-xs font-bold uppercase tracking-wider shadow-md transition-colors cursor-pointer">
                    Tutup
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

@script
<script>
// ── Global Chart Defaults ──
Chart.defaults.font.family = "'Public Sans', sans-serif";
Chart.defaults.font.weight = '700';
Chart.defaults.color = '#475569';

let visitsTrendChart = null;
let nutritionTrendChart = null;
let nutritionDonutChart = null;
let vaccineChart = null;
let pregnancyRiskChart = null;
let lansiaMetabolicChart = null;

// ── ANA-30: Download Chart as Image ──
window.downloadChart = function(chartInstance, fileName) {
    if (!chartInstance) return;
    const url = chartInstance.toBase64Image();
    const a = document.createElement('a');
    a.href = url;
    a.download = fileName + '.png';
    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);
}

// ── ANA-50: Fallback Error Helpers ──
function showChartError(canvasId) {
    const errorEl = document.getElementById('error-' + canvasId);
    if (errorEl) {
        errorEl.classList.remove('opacity-0', 'pointer-events-none');
    }
}

function hideChartError(canvasId) {
    const errorEl = document.getElementById('error-' + canvasId);
    if (errorEl) {
        errorEl.classList.add('opacity-0', 'pointer-events-none');
    }
}

function initCharts(data = null) {
    // Destroy existing if they exist (safely inside try-catch)
    try { if (visitsTrendChart) { visitsTrendChart.destroy(); visitsTrendChart = null; } } catch (e) {}
    try { if (nutritionTrendChart) { nutritionTrendChart.destroy(); nutritionTrendChart = null; } } catch (e) {}
    try { if (nutritionDonutChart) { nutritionDonutChart.destroy(); nutritionDonutChart = null; } } catch (e) {}
    try { if (vaccineChart) { vaccineChart.destroy(); vaccineChart = null; } } catch (e) {}
    try { if (pregnancyRiskChart) { pregnancyRiskChart.destroy(); pregnancyRiskChart = null; } } catch (e) {}
    try { if (lansiaMetabolicChart) { lansiaMetabolicChart.destroy(); lansiaMetabolicChart = null; } } catch (e) {}

    // Hide error messages
    hideChartError('visitsTrendChart');
    hideChartError('nutritionTrendChart');
    hideChartError('nutritionDonutChart');
    hideChartError('vaccineBarChart');
    hideChartError('pregnancyRiskChart');
    hideChartError('lansiaMetabolicChart');

    // Fetch data arrays from event or blade json variables
    const labels = data ? data.trendLabels : $wire.trendLabels;
    
    // Overview trend
    const visitsBalita = data ? data.trendVisitsBalita : $wire.trendVisitsBalita;
    const visitsIbuHamil = data ? data.trendVisitsIbuHamil : $wire.trendVisitsIbuHamil;
    const visitsLansia = data ? data.trendVisitsLansia : $wire.trendVisitsLansia;

    const viewMode = data ? data.viewMode : $wire.viewMode;
    const compareMode = data ? data.compareMode : $wire.compareMode;
    const trendCompareCurrent = data ? data.trendCompareCurrent : $wire.trendCompareCurrent;
    const trendComparePrevious = data ? data.trendComparePrevious : $wire.trendComparePrevious;
    const trendLabelsPrevious = data ? data.trendLabelsPrevious : $wire.trendLabelsPrevious;

    // Balita trend
    const normal = data ? data.trendNormal : $wire.trendNormal;
    const stunting = data ? data.trendStunting : $wire.trendStunting;
    const risk = data ? data.trendRisk : $wire.trendRisk;
    const nutLabels = data ? data.nutritionLabels : $wire.nutritionLabels;
    const nutData = data ? data.nutritionData : $wire.nutritionData;
    const vaxLabels = data ? data.vaccineLabels : $wire.vaccineLabels;
    const vaxData = data ? data.vaccineData : $wire.vaccineData;

    // Ibu Hamil trend
    const pregHyper = data ? data.trendPregnancyHypertension : $wire.trendPregnancyHypertension;
    const pregFe = data ? data.trendPregnancyFe : $wire.trendPregnancyFe;

    // Lansia trend
    const lansiaBP = data ? data.trendLansiaHypertension : $wire.trendLansiaHypertension;
    const lansiaSugar = data ? data.trendLansiaHyperglycemia : $wire.trendLansiaHyperglycemia;
    const lansiaChol = data ? data.trendLansiaHypercholesterolemia : $wire.trendLansiaHypercholesterolemia;
    const lansiaUric = data ? data.trendLansiaHyperuricemia : $wire.trendLansiaHyperuricemia;

    // 1. Overview Visits Trend Line Chart
    const visitsCtx = document.getElementById('visitsTrendChart');
    if (visitsCtx) {
        try {
            let chartType = 'line';
            let chartLabels = labels;
            let chartDatasets = [];

            if (viewMode === 'yearly') {
                chartType = 'line';
                chartLabels = labels;
                chartDatasets = [
                    {
                        label: 'Tahun Ini',
                        data: trendCompareCurrent,
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13, 148, 136, 0.03)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Tahun Lalu',
                        data: trendComparePrevious,
                        borderColor: '#94a3b8',
                        borderDash: [5, 5],
                        backgroundColor: 'transparent',
                        borderWidth: 2,
                        tension: 0.35,
                        fill: false,
                    }
                ];
            } else if (compareMode && trendLabelsPrevious.length > 0) {
                chartType = 'bar';
                chartLabels = trendLabelsPrevious; // Balita, Ibu Hamil, Lansia
                chartDatasets = [
                    {
                        label: 'Bulan Ini',
                        data: trendCompareCurrent,
                        backgroundColor: '#0d9488',
                        borderRadius: 4
                    },
                    {
                        label: 'Bulan Lalu',
                        data: trendComparePrevious,
                        backgroundColor: '#94a3b8',
                        borderRadius: 4
                    }
                ];
            } else {
                chartType = 'line';
                chartLabels = labels;
                chartDatasets = [
                    {
                        label: 'Balita & Anak',
                        data: visitsBalita,
                        borderColor: '#0d9488', // teal-600
                        backgroundColor: 'rgba(13, 148, 136, 0.03)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Ibu Hamil',
                        data: visitsIbuHamil,
                        borderColor: '#e11d48', // rose-600
                        backgroundColor: 'rgba(225, 29, 72, 0.03)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Lansia',
                        data: visitsLansia,
                        borderColor: '#4f46e5', // indigo-600
                        backgroundColor: 'rgba(79, 70, 229, 0.03)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        fill: true,
                    }
                ];
            }

            visitsTrendChart = new Chart(visitsCtx, {
                type: chartType,
                data: {
                    labels: chartLabels,
                    datasets: chartDatasets
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const firstPoint = activeElements[0];
                            const index = firstPoint.index;
                            const datasetIndex = firstPoint.datasetIndex;
                            const label = chartLabels[index];
                            const month = index + 1;

                            let type = 'balita';
                            let dsLabel = '';
                            if (viewMode === 'yearly' || (compareMode && trendLabelsPrevious.length > 0)) {
                                if (viewMode === 'yearly') {
                                    dsLabel = 'Semua Kunjungan';
                                    type = 'balita'; // fallback
                                } else {
                                    const catName = trendLabelsPrevious[index];
                                    dsLabel = catName;
                                    type = catName === 'Balita' ? 'balita' : (catName === 'Ibu Hamil' ? 'ibu_hamil' : 'lansia');
                                }
                            } else {
                                const datasetLabel = chartDatasets[datasetIndex].label;
                                dsLabel = datasetLabel;
                                if (datasetLabel.includes('Ibu')) type = 'ibu_hamil';
                                else if (datasetLabel.includes('Lansia')) type = 'lansia';
                            }

                            $wire.call('drillDown', `${dsLabel} - ${label}`, type, month);
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }
                    },
                    plugins: {
                        legend: { display: true, position: 'top' }
                    }
                }
            });
        } catch (e) {
            console.error("Error loading visitsTrendChart:", e);
            showChartError('visitsTrendChart');
        }
    }

    // 2. Balita Nutrition Trend Chart
    const trendCtx = document.getElementById('nutritionTrendChart');
    if (trendCtx) {
        try {
            nutritionTrendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Normal',
                            data: normal,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5, 150, 105, 0.03)',
                            borderWidth: 3.5,
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Risiko',
                            data: risk,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245, 158, 11, 0.03)',
                            borderWidth: 3.5,
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Stunting',
                            data: stunting,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239, 68, 68, 0.03)',
                            borderWidth: 3.5,
                            tension: 0.35,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const firstPoint = activeElements[0];
                            const index = firstPoint.index;
                            const datasetIndex = firstPoint.datasetIndex;
                            const label = labels[index];
                            const month = index + 1;
                            const datasetLabel = ['Normal', 'Risiko', 'Stunting'][datasetIndex];

                            let type = 'balita';
                            if (datasetLabel === 'Stunting') type = 'stunting';
                            else if (datasetLabel === 'Risiko') type = 'gizi_buruk';

                            $wire.call('drillDown', `Balita (${datasetLabel}) - ${label}`, type, month);
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                    },
                    plugins: { legend: { display: true } }
                }
            });
        } catch (e) {
            console.error("Error loading nutritionTrendChart:", e);
            showChartError('nutritionTrendChart');
        }
    }

    // 3. Balita Donut Chart
    const donutCtx = document.getElementById('nutritionDonutChart');
    if (donutCtx && nutData && nutData.length > 0 && nutData.some(v => v > 0)) {
        try {
            const colors = nutLabels.map(label => {
                if (label.includes('Normal') || label.includes('Baik')) return '#059669';
                if (label.includes('Kurang') && !label.includes('Sangat')) return '#f59e0b';
                if (label.includes('Risiko') || label.includes('Berisiko')) return '#f59e0b';
                if (label.includes('Sangat') || label.includes('Buruk') || label.includes('Pendek')) return '#ef4444';
                if (label.includes('Lebih') || label.includes('Obesitas')) return '#f59e0b';
                return '#94a3b8';
            });
            nutritionDonutChart = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: nutLabels,
                    datasets: [{
                        data: nutData,
                        backgroundColor: colors,
                        borderWidth: 3,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '75%',
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const firstPoint = activeElements[0];
                            const index = firstPoint.index;
                            const label = nutLabels[index];
                            let type = 'balita';
                            if (label.includes('Buruk') || label.includes('Sangat Kurang')) type = 'gizi_buruk';
                            else if (label.includes('Kurang') || label.includes('Pendek') || label.includes('Stunting')) type = 'stunting';
                            
                            $wire.call('drillDown', `Balita (${label})`, type);
                        }
                    },
                    plugins: { legend: { display: false } }
                }
            });
        } catch (e) {
            console.error("Error loading nutritionDonutChart:", e);
            showChartError('nutritionDonutChart');
        }
    }

    // 4. Vaccine Bar Chart
    const vaxCtx = document.getElementById('vaccineBarChart');
    if (vaxCtx) {
        try {
            vaccineChart = new Chart(vaxCtx, {
                type: 'bar',
                data: {
                    labels: vaxLabels,
                    datasets: [{
                        label: 'Jumlah Anak',
                        data: vaxData,
                        backgroundColor: 'rgba(13, 148, 136, 0.8)',
                        borderRadius: 8,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }
                    }
                }
            });
        } catch (e) {
            console.error("Error loading vaccineChart:", e);
            showChartError('vaccineBarChart');
        }
    }

    // 5. Ibu Hamil Trend Chart
    const pregCtx = document.getElementById('pregnancyRiskChart');
    if (pregCtx) {
        try {
            pregnancyRiskChart = new Chart(pregCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Kepatuhan Pil Fe',
                            data: pregFe,
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.03)',
                            borderWidth: 3.5,
                            tension: 0.35,
                            fill: true,
                        },
                        {
                            label: 'Risiko Hipertensi',
                            data: pregHyper,
                            borderColor: '#f43f5e',
                            backgroundColor: 'rgba(244, 63, 94, 0.03)',
                            borderWidth: 3.5,
                            tension: 0.35,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const firstPoint = activeElements[0];
                            const index = firstPoint.index;
                            const label = labels[index];
                            const month = index + 1;
                            
                            $wire.call('drillDown', `Ibu Hamil - ${label}`, 'ibu_hamil', month);
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                    },
                    plugins: { legend: { display: true } }
                }
            });
        } catch (e) {
            console.error("Error loading pregnancyRiskChart:", e);
            showChartError('pregnancyRiskChart');
        }
    }

    // 6. Lansia Metabolic Risk Chart
    const lansiaCtx = document.getElementById('lansiaMetabolicChart');
    if (lansiaCtx) {
        try {
            lansiaMetabolicChart = new Chart(lansiaCtx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Hipertensi',
                            data: lansiaBP,
                            backgroundColor: '#f43f5e',
                            borderRadius: 6,
                        },
                        {
                            label: 'Hiperglikemia',
                            data: lansiaSugar,
                            backgroundColor: '#eab308',
                            borderRadius: 6,
                        },
                        {
                            label: 'Hiperkolesterolemia',
                            data: lansiaChol,
                            backgroundColor: '#3b82f6',
                            borderRadius: 6,
                        },
                        {
                            label: 'Hiperurisemia',
                            data: lansiaUric,
                            backgroundColor: '#8b5cf6',
                            borderRadius: 6,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    onClick: (event, activeElements) => {
                        if (activeElements.length > 0) {
                            const firstPoint = activeElements[0];
                            const index = firstPoint.index;
                            const label = labels[index];
                            const month = index + 1;
                            
                            $wire.call('drillDown', `Lansia - ${label}`, 'lansia', month);
                        }
                    },
                    scales: {
                        x: { grid: { display: false } },
                        y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                    },
                    plugins: { legend: { display: true } }
                }
            });
        } catch (e) {
            console.error("Error loading lansiaMetabolicChart:", e);
            showChartError('lansiaMetabolicChart');
        }
    }
}

// Initial load
initCharts();

// Listen for Livewire updates
$wire.on('charts-updated', (event) => {
    const data = Array.isArray(event) ? event[0] : event;
    initCharts(data);
});
</script>
@endscript
