@push('styles')
<style>
    .hero-mesh {
        background-color: #0f172a;
        background-image: 
            radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.3) 0px, transparent 50%),
            radial-gradient(at 50% 0%, hsla(244, 76%, 36%, 0.3) 0px, transparent 50%),
            radial-gradient(at 100% 0%, hsla(339, 66%, 50%, 0.2) 0px, transparent 50%);
    }
</style>
@endpush

<div class="max-w-[1440px] mx-auto space-y-8 pb-20">

    {{-- ── Modern Header with Mesh Gradient ── --}}
    <div class="relative rounded-[2rem] p-6 md:p-8 overflow-hidden hero-mesh text-white shadow-2xl">
        {{-- Decorative Glow Elements --}}
        <div class="absolute inset-0 opacity-20 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-white/10 rounded-full blur-[120px]"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-teal-500/20 rounded-full blur-[100px]"></div>
        </div>

        <div class="relative z-10 flex flex-col xl:flex-row justify-between gap-6">
            {{-- Left Side: Identity --}}
            <div class="space-y-4 max-w-2xl">
                <div class="inline-flex items-center gap-3 px-4 py-1.5 rounded-full bg-white/10 text-teal-300 border border-white/10 text-xs font-black uppercase tracking-widest shadow-xl">
                    <span class="w-2.5 h-2.5 rounded-full bg-teal-400 animate-pulse"></span>
                    Sistem Analitik Terintegrasi
                </div>
                
                <div class="space-y-2">
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-black tracking-tight leading-none">
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-teal-300 via-emerald-300 to-indigo-300 drop-shadow-sm">
                            Dashboard Analitik & Wawasan
                        </span>
                    </h1>
                    <p class="text-base text-slate-300 font-bold leading-relaxed">
                        Data Wawasan Klinis & Keaktifan Posyandu Periode Tahun {{ $selectedYear }} 
                        @if(auth()->user()->posyandu) <br> <span class="text-teal-400">Unit Kerja: {{ auth()->user()->posyandu->name }}</span> @endif
                    </p>
                </div>
            </div>

            {{-- Right Side: Actions & Status --}}
            <div class="flex flex-col items-start xl:items-end gap-4 w-full xl:w-auto">
                <div class="flex flex-wrap items-center justify-start xl:justify-end gap-4 w-full">
                    {{-- Selectors Group --}}
                    <div class="flex flex-1 sm:flex-initial items-center bg-slate-900/80 backdrop-blur-md p-1 rounded-2xl border-2 border-slate-700 shadow-xl">
                        <div class="px-4 py-2 hover:bg-slate-800/50 transition-colors rounded-l-xl">
                            <label class="block text-[9px] font-black text-teal-400 uppercase tracking-widest mb-1">Tahun</label>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-teal-400 text-[18px]">calendar_month</span>
                                <div class="w-24">
                                    <x-forms.select-input wire:model.live="selectedYear" placeholder="" value="{{ $selectedYear }}" class="!bg-transparent !border-none focus:!ring-0 !text-base !font-black !text-white !p-0 !shadow-none !h-auto pr-6">
                                        @foreach($years as $y) <option value="{{ $y }}" class="bg-slate-800">{{ $y }}</option> @endforeach
                                    </x-forms.select-input>
                                </div>
                            </div>
                        </div>
                        <div class="w-[1px] h-8 bg-slate-700"></div>
                        <div class="px-4 py-2 hover:bg-slate-800/50 transition-colors rounded-r-xl">
                            <label class="block text-[9px] font-black text-teal-400 uppercase tracking-widest mb-1">Bulan</label>
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-teal-400 text-[18px]">explore</span>
                                <div class="w-32">
                                    <x-forms.select-input wire:model.live="selectedMonth" placeholder="Semua" :placeholderDisabled="false" value="{{ $selectedMonth }}" class="!bg-transparent !border-none focus:!ring-0 !text-base !font-black !text-white !p-0 !shadow-none !h-auto pr-6">
                                        @foreach(range(1, 12) as $m) <option value="{{ $m }}" class="bg-slate-800">{{ Carbon\Carbon::create(2000, $m)->translatedFormat('F') }}</option> @endforeach
                                    </x-forms.select-input>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Update button --}}
                    <button wire:click="refreshStats" 
                            wire:loading.attr="disabled"
                            class="h-16 px-6 flex items-center gap-3 bg-teal-600 hover:bg-teal-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg transition-all border border-teal-500 hover:-translate-y-0.5 active:scale-95 whitespace-nowrap">
                        <span class="material-symbols-outlined text-[20px]" wire:loading.class="animate-spin" wire:target="refreshStats">sync</span>
                        Refresh Data
                    </button>
                </div>

                {{-- Status --}}
                <div class="text-xs font-bold text-slate-400">
                    Terakhir diperbarui: <span class="text-slate-200">{{ $lastUpdated }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Tab Navigation (Capsules sliding design) ── --}}
    <div class="flex items-center bg-slate-100 dark:bg-slate-900 p-1.5 rounded-[2rem] border border-slate-200 dark:border-slate-800 shadow-sm overflow-x-auto whitespace-nowrap max-w-full">
        <button wire:click="$set('activeTab', 'overview')" @class([
            'flex-1 py-3 px-6 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2',
            'bg-slate-950 text-white shadow-lg' => $activeTab === 'overview',
            'text-slate-600 dark:text-slate-400 hover:text-slate-900 hover:bg-slate-200/50' => $activeTab !== 'overview'
        ])>
            <span class="material-symbols-outlined text-[18px]">dashboard</span>
            Overview
        </button>
        <button wire:click="$set('activeTab', 'balita')" @class([
            'flex-1 py-3 px-6 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2',
            'bg-teal-600 text-white shadow-lg' => $activeTab === 'balita',
            'text-slate-600 dark:text-slate-400 hover:text-slate-900 hover:bg-teal-200/50' => $activeTab !== 'balita'
        ])>
            <span class="material-symbols-outlined text-[18px]">child_care</span>
            Balita & Anak
        </button>
        <button wire:click="$set('activeTab', 'pregnancy')" @class([
            'flex-1 py-3 px-6 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2',
            'bg-rose-600 text-white shadow-lg' => $activeTab === 'pregnancy',
            'text-slate-600 dark:text-slate-400 hover:text-slate-900 hover:bg-rose-200/50' => $activeTab !== 'pregnancy'
        ])>
            <span class="material-symbols-outlined text-[18px]">pregnant_woman</span>
            Ibu Hamil
        </button>
        <button wire:click="$set('activeTab', 'lansia')" @class([
            'flex-1 py-3 px-6 rounded-[1.5rem] text-xs font-black uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2',
            'bg-indigo-600 text-white shadow-lg' => $activeTab === 'lansia',
            'text-slate-600 dark:text-slate-400 hover:text-slate-900 hover:bg-indigo-200/50' => $activeTab !== 'lansia'
        ])>
            <span class="material-symbols-outlined text-[18px]">elderly</span>
            Lansia
        </button>
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
                    'relative overflow-hidden bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border-2 transition-all duration-300 group hover:-translate-y-1',
                    'border-slate-100 hover:border-slate-900 shadow-lg shadow-slate-900/5' => $c['color'] === 'slate',
                    'border-teal-50 hover:border-teal-500 shadow-lg shadow-teal-900/5' => $c['color'] === 'teal',
                    'border-rose-50 hover:border-rose-500 shadow-lg shadow-rose-900/5' => $c['color'] === 'rose',
                    'border-indigo-50 hover:border-indigo-500 shadow-lg shadow-indigo-900/5' => $c['color'] === 'indigo',
                ])>
                    <div class="relative z-10 flex items-center justify-between mb-4">
                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center',
                            'bg-slate-900 text-white' => $c['color'] === 'slate',
                            'bg-teal-500 text-white' => $c['color'] === 'teal',
                            'bg-rose-500 text-white' => $c['color'] === 'rose',
                            'bg-indigo-600 text-white' => $c['color'] === 'indigo',
                        ])>
                            <span class="material-symbols-outlined text-[28px]">{{ $c['icon'] }}</span>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ $c['unit'] }}</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $c['val'] }}</h3>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight">{{ $c['label'] }}</p>
                        <p class="text-xs text-slate-400 mt-2 border-t border-slate-50 dark:border-slate-700 pt-2">{{ $c['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Combined Trend Line Chart --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Tren Kunjungan Bulanan Gabungan</h3>
                        <p class="text-xs text-slate-400 font-semibold mt-1">Perbandingan tren frekuensi kunjungan pasien per kategori di posyandu</p>
                    </div>
                </div>
                <div class="relative h-[380px]">
                    <canvas id="visitsTrendChart" wire:ignore></canvas>
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
                    'relative overflow-hidden bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border-2 transition-all duration-300 group hover:-translate-y-1',
                    'border-teal-50 hover:border-teal-500 shadow-lg shadow-teal-900/5' => $c['color'] === 'teal',
                    'border-red-50 hover:border-red-500 shadow-lg shadow-red-900/5' => $c['color'] === 'red',
                    'border-blue-50 hover:border-blue-500 shadow-lg shadow-blue-900/5' => $c['color'] === 'blue',
                    'border-amber-50 hover:border-amber-500 shadow-lg shadow-amber-900/5' => $c['color'] === 'amber',
                ])>
                    <div class="relative z-10 flex items-center justify-between mb-4">
                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center',
                            'bg-teal-500 text-white' => $c['color'] === 'teal',
                            'bg-red-500 text-white' => $c['color'] === 'red',
                            'bg-blue-500 text-white' => $c['color'] === 'blue',
                            'bg-amber-500 text-white' => $c['color'] === 'amber',
                        ])>
                            <span class="material-symbols-outlined text-[28px]">{{ $c['icon'] }}</span>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ $c['unit'] }}</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $c['val'] }}</h3>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight">{{ $c['label'] }}</p>
                        <p class="text-xs text-slate-400 mt-2 border-t border-slate-50 dark:border-slate-700 pt-2">{{ $c['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Charts Row --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                {{-- Nutrition Trend Line Chart --}}
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Prevalensi Pertumbuhan Balita</h3>
                    <div class="relative h-[340px]">
                        <canvas id="nutritionTrendChart" wire:ignore></canvas>
                    </div>
                </div>

                {{-- Nutrition Donut Chart --}}
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Status Gizi Balita</h3>
                        <p class="text-xs text-slate-400 mt-1">Distribusi data timbang kunjungan terbaru</p>
                    </div>
                    <div class="relative flex justify-center my-6">
                        <canvas id="nutritionDonutChart" width="200" height="200" style="max-width:200px;max-height:200px;" wire:ignore></canvas>
                    </div>
                    <div class="space-y-2 max-h-[160px] overflow-y-auto pr-1">
                        @php $i = 0; @endphp
                        @foreach($nutritionLabels as $label)
                        @php
                            $val = $nutritionData[$i] ?? 0;
                            $sum = array_sum($nutritionData);
                            $pct = $sum > 0 ? round(($val / $sum) * 100, 1) : 0;
                        @endphp
                        <div class="flex items-center justify-between text-xs font-bold text-slate-700 dark:text-slate-300">
                            <span>{{ $label }}</span>
                            <span>{{ $val }} ({{ $pct }}%)</span>
                        </div>
                        @php $i++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- Vaccine Bar Chart --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Capaian Imunisasi Per Jenis</h3>
                <div class="relative h-[280px]">
                    <canvas id="vaccineBarChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- Detailed Stunting per Posyandu --}}
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                    <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6">Prevalensi Stunting per Wilayah Posyandu</h3>
                    <div class="space-y-6">
                        @forelse($stuntingByPosyandu as $item)
                        <div>
                            <div class="flex justify-between items-end mb-2 text-xs font-black text-slate-700 dark:text-slate-300">
                                <span>{{ $item['name'] }} ({{ $item['stunting'] }}/{{ $item['total'] }} Balita)</span>
                                <span>{{ $item['rate'] }}%</span>
                            </div>
                            <div class="h-3 w-full bg-slate-100 dark:bg-slate-700 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $item['color'] }}" style="width: {{ $item['width'] }}%"></div>
                            </div>
                        </div>
                        @empty
                        <p class="text-xs text-slate-400 text-center py-6">Tidak ada data posyandu</p>
                        @endforelse
                    </div>
                </div>

                {{-- Demographic segments and insights --}}
                <div class="space-y-6">
                    <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                        <h3 class="text-lg font-black text-slate-900 dark:text-white mb-6 text-center">Segmentasi Usia Terpantau</h3>
                        <div class="grid grid-cols-3 gap-4">
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900 dark:text-white">{{ $usia0_12 }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">0–12 Bulan</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900 dark:text-white">{{ $usia12_24 }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">12–24 Bulan</span>
                            </div>
                            <div class="p-4 bg-slate-50 dark:bg-slate-900/50 rounded-2xl text-center">
                                <span class="block text-2xl font-black text-slate-900 dark:text-white">{{ $usia24plus }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider">>24 Bulan</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-slate-950 rounded-[2.5rem] p-8 text-white relative overflow-hidden">
                        <div class="relative z-10 space-y-4">
                            <div class="flex items-center gap-3">
                                <span class="material-symbols-outlined text-teal-400 text-[24px]">insights</span>
                                <h4 class="text-sm font-black uppercase tracking-widest">Wawasan Balita</h4>
                            </div>
                            <p class="text-xs text-slate-400 leading-relaxed font-bold">
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
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-lg overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Pemeriksaan Balita Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar pemeriksaan balita terkini</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Anak</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Posyandu</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Gizi</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                            @forelse($recentRecords as $record)
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 text-slate-700 dark:text-slate-300">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 flex items-center justify-center font-black text-xs">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'B', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-black text-slate-900 dark:text-white">{{ $record->patient?->full_name }}</span>
                                            <span class="text-[9px] text-slate-400 uppercase block">NIK: {{ $record->patient?->id_number ?: '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4 text-xs font-bold">{{ $record->patient?->posyandu?->name ?? '-' }}</td>
                                <td class="px-8 py-4">
                                    <span class="inline-flex px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-wider bg-teal-50 dark:bg-teal-950/20 text-teal-600 dark:text-teal-400 border border-teal-100 dark:border-teal-900/55">
                                        {{ $record->nutrition_status ?: 'Normal' }}
                                    </span>
                                </td>
                                <td class="px-8 py-4 text-xs font-semibold">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-8 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-400 hover:bg-teal-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-xs text-slate-400 font-bold">Belum ada pemeriksaan balita</td>
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
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @php
                    $pregnancyCards = [
                        ['label' => 'Ibu Hamil Terdaftar', 'val' => number_format($totalIbuHamil), 'unit' => 'Jiwa', 'icon' => 'pregnant_woman', 'color' => 'rose', 'desc' => 'Jumlah ibu mengandung terpantau aktif'],
                        ['label' => 'Kepatuhan Pil Fe', 'val' => $feComplianceRate . '%', 'unit' => 'Target 100%', 'icon' => 'pill', 'color' => 'emerald', 'desc' => 'Persentase konsumsi zat besi rutin'],
                        ['label' => 'Risiko Hipertensi', 'val' => $hypertensionRiskRate . '%', 'unit' => 'Prevalensi', 'icon' => 'heart_broken', 'color' => 'rose', 'desc' => 'Tingkat tekanan darah sistolik >= 140 atau diastolik >= 90'],
                    ];
                @endphp

                @foreach($pregnancyCards as $c)
                <div @class([
                    'relative overflow-hidden bg-white dark:bg-slate-800 rounded-[2.5rem] p-6 border-2 transition-all duration-300 group hover:-translate-y-1',
                    'border-rose-50 hover:border-rose-500 shadow-lg shadow-rose-900/5' => $c['color'] === 'rose',
                    'border-emerald-50 hover:border-emerald-500 shadow-lg shadow-emerald-900/5' => $c['color'] === 'emerald',
                ])>
                    <div class="relative z-10 flex items-center justify-between mb-4">
                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center',
                            'bg-rose-500 text-white' => $c['color'] === 'rose',
                            'bg-emerald-500 text-white' => $c['color'] === 'emerald',
                        ])>
                            <span class="material-symbols-outlined text-[28px]">{{ $c['icon'] }}</span>
                        </div>
                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400">{{ $c['unit'] }}</span>
                    </div>

                    <div class="space-y-1">
                        <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $c['val'] }}</h3>
                        <p class="text-sm font-black text-slate-800 dark:text-slate-200 uppercase tracking-tight">{{ $c['label'] }}</p>
                        <p class="text-xs text-slate-400 mt-2 border-t border-slate-50 dark:border-slate-700 pt-2">{{ $c['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Pregnancy Trend Chart --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Tren Indikator Klinis Ibu Hamil</h3>
                <div class="relative h-[340px]">
                    <canvas id="pregnancyRiskChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- Recent Records for Pregnancy --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-lg overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Pemeriksaan Kehamilan Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar pemeriksaan ibu hamil terkini</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ibu</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tekanan Darah</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Zat Besi (Pil Fe)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Periksa</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                            @forelse($recentPregnancyRecords as $record)
                            @php
                                $sys = $record->systolic_bp;
                                $dia = $record->diastolic_bp;
                                $bpHigh = ($sys >= 140 || $dia >= 90);
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 text-slate-700 dark:text-slate-300">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-rose-50 dark:bg-rose-950/20 text-rose-600 dark:text-rose-400 flex items-center justify-center font-black text-xs">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'I', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-black text-slate-900 dark:text-white">{{ $record->patient?->full_name }}</span>
                                            <span class="text-[9px] text-slate-400 block">Unit: {{ $record->patient?->posyandu?->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    @if($sys && $dia)
                                        <span @class([
                                            'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border',
                                            'bg-red-50 text-red-600 border-red-100' => $bpHigh,
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => !$bpHigh
                                        ])>
                                            {{ $sys }}/{{ $dia }} mmHg
                                            @if($bpHigh)
                                                <span class="ml-1 text-[9px] font-bold text-red-500">(Risiko Hipertensi)</span>
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    @if($record->pill_fe !== null)
                                        <span @class([
                                            'inline-flex items-center px-3 py-1 rounded-full text-[10px] font-black border',
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => $record->pill_fe === 1,
                                            'bg-slate-50 text-slate-500 border-slate-200' => $record->pill_fe === 0,
                                        ])>
                                            {{ $record->pill_fe === 1 ? 'Konsumsi' : 'Tidak Konsumsi' }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-xs font-semibold">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-8 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-400 hover:bg-rose-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-8 py-10 text-center text-xs text-slate-400 font-bold">Belum ada pemeriksaan ibu hamil</td>
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
            {{-- Stats Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-md">
                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-wider block">Lansia Terdaftar</span>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter mt-1">{{ $totalLansia }}</h3>
                    <p class="text-[10px] text-slate-400 mt-2">Jiwa yang aktif dipantau</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-md border-l-4 border-l-rose-500">
                    <span class="text-[9px] font-black text-rose-500 uppercase tracking-wider block">Hipertensi (BP)</span>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter mt-1">{{ $lansiaHypertensionRate }}%</h3>
                    <p class="text-[10px] text-slate-400 mt-2">Sistolik >= 140 / Diastolik >= 90</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-md border-l-4 border-l-yellow-500">
                    <span class="text-[9px] font-black text-yellow-600 uppercase tracking-wider block">Gula Darah Tinggi</span>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter mt-1">{{ $lansiaHyperglycemiaRate }}%</h3>
                    <p class="text-[10px] text-slate-400 mt-2">Kadar gula sewaktu >= 200</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-md border-l-4 border-l-blue-500">
                    <span class="text-[9px] font-black text-blue-600 uppercase tracking-wider block">Kolesterol Tinggi</span>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter mt-1">{{ $lansiaHypercholesterolemiaRate }}%</h3>
                    <p class="text-[10px] text-slate-400 mt-2">Kadar kolesterol >= 200</p>
                </div>
                <div class="bg-white dark:bg-slate-800 rounded-[2rem] p-5 border border-slate-100 dark:border-slate-700 shadow-md border-l-4 border-l-purple-500">
                    <span class="text-[9px] font-black text-purple-600 uppercase tracking-wider block">Asam Urat Tinggi</span>
                    <h3 class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter mt-1">{{ $lansiaHyperuricemiaRate }}%</h3>
                    <p class="text-[10px] text-slate-400 mt-2">Kadar asam urat >= 7.0</p>
                </div>
            </div>

            {{-- Lansia Trend Chart --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] p-8 border border-slate-100 dark:border-slate-700 shadow-lg">
                <h3 class="text-lg font-black text-slate-900 dark:text-white mb-4">Tren Prevalensi Faktor Risiko Metabolik Lansia</h3>
                <div class="relative h-[340px]">
                    <canvas id="lansiaMetabolicChart" wire:ignore></canvas>
                </div>
            </div>

            {{-- Recent Records for Lansia --}}
            <div class="bg-white dark:bg-slate-800 rounded-[2.5rem] border border-slate-100 dark:border-slate-700 shadow-lg overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-50 dark:border-slate-700 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-black text-slate-900 dark:text-white">Pemeriksaan Lansia Terbaru</h3>
                        <p class="text-xs text-slate-400 mt-0.5">Daftar pemeriksaan metabolik lansia terkini</p>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50 dark:bg-slate-900/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lansia</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tekanan Darah (mmHg)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gula Darah (mg/dL)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Kolesterol (mg/dL)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asam Urat (mg/dL)</th>
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-8 py-4 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 dark:divide-slate-700">
                            @forelse($recentLansiaRecords as $record)
                            @php
                                $sys = $record->systolic_bp;
                                $dia = $record->diastolic_bp;
                                $bpHigh = ($sys >= 140 || $dia >= 90);
                                $sugarHigh = ($record->blood_sugar >= 200);
                                $cholHigh = ($record->cholesterol >= 200);
                                $uricHigh = ($record->uric_acid >= 7.0);
                            @endphp
                            <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/50 text-slate-700 dark:text-slate-300">
                                <td class="px-8 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-indigo-50 dark:bg-indigo-950/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center font-black text-xs">
                                            {{ strtoupper(substr($record->patient?->full_name ?? 'L', 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block font-black text-slate-900 dark:text-white">{{ $record->patient?->full_name }}</span>
                                            <span class="text-[9px] text-slate-400 block">Unit: {{ $record->patient?->posyandu?->name ?? '-' }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-4">
                                    @if($sys && $dia)
                                        <span @class([
                                            'px-2 py-1 rounded-lg text-xs font-bold border block w-max',
                                            'bg-red-50 text-red-600 border-red-100' => $bpHigh,
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => !$bpHigh
                                        ])>
                                            {{ $sys }}/{{ $dia }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    @if($record->blood_sugar)
                                        <span @class([
                                            'px-2 py-1 rounded-lg text-xs font-bold border block w-max',
                                            'bg-red-50 text-red-600 border-red-100' => $sugarHigh,
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => !$sugarHigh
                                        ])>
                                            {{ $record->blood_sugar }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    @if($record->cholesterol)
                                        <span @class([
                                            'px-2 py-1 rounded-lg text-xs font-bold border block w-max',
                                            'bg-red-50 text-red-600 border-red-100' => $cholHigh,
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => !$cholHigh
                                        ])>
                                            {{ $record->cholesterol }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4">
                                    @if($record->uric_acid)
                                        <span @class([
                                            'px-2 py-1 rounded-lg text-xs font-bold border block w-max',
                                            'bg-red-50 text-red-600 border-red-100' => $uricHigh,
                                            'bg-emerald-50 text-emerald-600 border-emerald-100' => !$uricHigh
                                        ])>
                                            {{ $record->uric_acid }}
                                        </span>
                                    @else
                                        <span class="text-slate-300 dark:text-slate-600 text-xs">-</span>
                                    @endif
                                </td>
                                <td class="px-8 py-4 text-xs font-semibold">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</td>
                                <td class="px-8 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $record->patient_id) }}" class="w-8 h-8 inline-flex items-center justify-center rounded-lg bg-slate-50 dark:bg-slate-900 text-slate-400 hover:bg-indigo-600 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="px-8 py-10 text-center text-xs text-slate-400 font-bold">Belum ada pemeriksaan lansia</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
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
Chart.defaults.color = '#64748b';

let visitsTrendChart = null;
let nutritionTrendChart = null;
let nutritionDonutChart = null;
let vaccineChart = null;
let pregnancyRiskChart = null;
let lansiaMetabolicChart = null;

function initCharts(data = null) {
    // Destroy existing if they exist
    if (visitsTrendChart) { visitsTrendChart.destroy(); visitsTrendChart = null; }
    if (nutritionTrendChart) { nutritionTrendChart.destroy(); nutritionTrendChart = null; }
    if (nutritionDonutChart) { nutritionDonutChart.destroy(); nutritionDonutChart = null; }
    if (vaccineChart) { vaccineChart.destroy(); vaccineChart = null; }
    if (pregnancyRiskChart) { pregnancyRiskChart.destroy(); pregnancyRiskChart = null; }
    if (lansiaMetabolicChart) { lansiaMetabolicChart.destroy(); lansiaMetabolicChart = null; }

    // Fetch data arrays from event or blade json variables
    const labels = data ? data.trendLabels : $wire.trendLabels;
    
    // Overview trend
    const visitsBalita = data ? data.trendVisitsBalita : $wire.trendVisitsBalita;
    const visitsIbuHamil = data ? data.trendVisitsIbuHamil : $wire.trendVisitsIbuHamil;
    const visitsLansia = data ? data.trendVisitsLansia : $wire.trendVisitsLansia;

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
        visitsTrendChart = new Chart(visitsCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
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
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { display: true, position: 'top' }
                }
            }
        });
    }

    // 2. Balita Nutrition Trend Chart
    const trendCtx = document.getElementById('nutritionTrendChart');
    if (trendCtx) {
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
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                },
                plugins: { legend: { display: true } }
            }
        });
    }

    // 3. Balita Donut Chart
    const donutCtx = document.getElementById('nutritionDonutChart');
    if (donutCtx && nutData && nutData.length > 0 && nutData.some(v => v > 0)) {
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
                plugins: { legend: { display: false } }
            }
        });
    }

    // 4. Vaccine Bar Chart
    const vaxCtx = document.getElementById('vaccineBarChart');
    if (vaxCtx) {
        vaccineChart = new Chart(vaxCtx, {
            type: 'bar',
            data: {
                labels: vaxLabels,
                datasets: [{
                    label: 'Jumlah Anak',
                    data: vaxData,
                    backgroundColor: 'rgba(13, 148, 136, 0.8)', // teal-600
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
    }

    // 5. Ibu Hamil Trend Chart
    const pregCtx = document.getElementById('pregnancyRiskChart');
    if (pregCtx) {
        pregnancyRiskChart = new Chart(pregCtx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Kepatuhan Pil Fe',
                        data: pregFe,
                        borderColor: '#10b981', // emerald-500
                        backgroundColor: 'rgba(16, 185, 129, 0.03)',
                        borderWidth: 3.5,
                        tension: 0.35,
                        fill: true,
                    },
                    {
                        label: 'Risiko Hipertensi',
                        data: pregHyper,
                        borderColor: '#f43f5e', // rose-500
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
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                },
                plugins: { legend: { display: true } }
            }
        });
    }

    // 6. Lansia Metabolic Risk Chart (Grouped Bar Chart)
    const lansiaCtx = document.getElementById('lansiaMetabolicChart');
    if (lansiaCtx) {
        lansiaMetabolicChart = new Chart(lansiaCtx, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Hipertensi',
                        data: lansiaBP,
                        backgroundColor: '#f43f5e', // rose-500
                        borderRadius: 6,
                    },
                    {
                        label: 'Hiperglikemia',
                        data: lansiaSugar,
                        backgroundColor: '#eab308', // yellow-500
                        borderRadius: 6,
                    },
                    {
                        label: 'Hiperkolesterolemia',
                        data: lansiaChol,
                        backgroundColor: '#3b82f6', // blue-500
                        borderRadius: 6,
                    },
                    {
                        label: 'Hiperurisemia',
                        data: lansiaUric,
                        backgroundColor: '#8b5cf6', // purple-500
                        borderRadius: 6,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: { grid: { display: false } },
                    y: { beginAtZero: true, max: 100, grid: { color: '#f1f5f9' }, ticks: { callback: function(value) { return value + '%'; } } }
                },
                plugins: { legend: { display: true } }
            }
        });
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
