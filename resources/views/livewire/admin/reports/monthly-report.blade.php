<div class="space-y-8 p-6 md:p-8 pt-4">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6 mb-8 relative">
        <div class="relative pl-6 z-10">
            {{-- Vertical Glowing Bar --}}
            <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-linear-to-b from-teal-400 to-emerald-300 rounded-lg shadow-[0_0_12px_rgba(45,212,191,0.6)]"></div>
            
            <div class="flex flex-col gap-3">
                <div>
                    <h1 class="font-display-sm md:font-display-lg text-display-sm-mobile md:text-display-lg text-teal-700 mb-2 tracking-tight">
                        Rekap & Laporan
                    </h1>
                    <p class="text-sm font-medium text-outline mt-3">Analisis data kunjungan dan status kesehatan warga secara komprehensif.</p>
                </div>
            </div>
        </div>
        
        {{-- Decorative Background Glow --}}
        <div class="absolute right-0 top-0 w-64 h-64 bg-teal-400/5 rounded-lg blur-3xl -z-10 pointer-events-none"></div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="flex items-center gap-3 px-5 py-4 bg-emerald-50/80 backdrop-blur-sm border border-emerald-100 text-emerald-800 rounded-2xl text-sm font-medium shadow-sm animate-fade-in">
            <span class="material-symbols-outlined text-emerald-500 text-[22px]">check_circle</span>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="flex items-center gap-3 px-5 py-4 bg-error-container/80 backdrop-blur-sm border border-rose-100 text-rose-800 rounded-2xl text-sm font-medium shadow-sm animate-fade-in">
            <span class="material-symbols-outlined text-error text-[22px]">error</span>
            {{ session('error') }}
        </div>
    @endif

    {{-- ── Filter Section (Glassmorphism & Floating Style) ── --}}
    <section class="bg-white/80 backdrop-blur-xl rounded-2xl border border-white/40 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] p-6 relative overflow-hidden">
        {{-- Inner subtle glow --}}
        <div class="absolute -top-24 -right-24 w-48 h-48 bg-primary/10 rounded-lg blur-3xl pointer-events-none"></div>
        
        <div class="flex items-center gap-2 mb-5">
            <span class="material-symbols-outlined text-primary text-[20px]">filter_list</span>
            <h3 class="text-xs font-black text-on-surface-variant uppercase tracking-widest">Filter Laporan</h3>
        </div>
        
        <div class="flex flex-wrap items-end gap-5 relative z-10">
            {{-- Pilih Posyandu (superadmin only) --}}
            @if(auth()->user()->isSuperAdmin())
            <div class="flex-1 min-w-50">
                <label class="block text-[10px] font-bold text-outline-variant uppercase tracking-widest ml-1 mb-2">Posyandu</label>
                <x-forms.select-input wire:model="selectedPosyanduId" class="rounded-2xl! bg-surface-container-low/50! focus:bg-white! border-outline-variant/60! shadow-inner-sm transition-all" value="{{ $selectedPosyanduId }}">
                    @foreach($posyandus as $pos)
                        <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                    @endforeach
                </x-forms.select-input>
            </div>
            @endif

            {{-- Tanggal Mulai --}}
            <div class="flex-1 min-w-35">
                <label class="block text-[10px] font-bold text-outline-variant uppercase tracking-widest ml-1 mb-2">Periode Mulai</label>
                <input type="month" wire:model.live="startPeriod" class="w-full h-12 px-4 rounded-2xl border border-outline-variant bg-surface-container-low/50 text-sm font-medium focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-400/10 transition-all shadow-inner-sm">
            </div>

            {{-- Divider --}}
            <div class="hidden md:flex items-center justify-center w-6 h-12 pb-2">
                <span class="material-symbols-outlined text-slate-300">arrow_right_alt</span>
            </div>

            {{-- Tanggal Akhir --}}
            <div class="flex-1 min-w-35">
                <label class="block text-[10px] font-bold text-outline-variant uppercase tracking-widest ml-1 mb-2">Periode Selesai</label>
                <input type="month" wire:model.live="endPeriod" class="w-full h-12 px-4 rounded-2xl border border-outline-variant bg-surface-container-low/50 text-sm font-medium focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-400/10 transition-all shadow-inner-sm">
            </div>

            {{-- Tombol Tampilkan --}}
            <button wire:click="generateReport"
                    wire:loading.attr="disabled"
                    class="h-12 px-8 bg-linear-to-r from-teal-600 to-emerald-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest hover:scale-[1.02] active:scale-95 transition-all flex items-center gap-2 shadow-[0_8px_16px_-6px_rgba(13,148,136,0.4)]">
                <span wire:loading.remove wire:target="generateReport" class="material-symbols-outlined text-[18px]">magic_button</span>
                <svg wire:loading wire:target="generateReport" class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                </svg>
                <span wire:loading.remove wire:target="generateReport">Analisis</span>
                <span wire:loading wire:target="generateReport">Proses...</span>
            </button>
        </div>
    </section>

    {{-- ── Stats Cards ── --}}
    @if($reportGenerated)
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

        {{-- Total Kunjungan --}}
        <div class="group bg-white rounded-2xl border border-outline-variant p-6 shadow-sm transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 flex items-center justify-center border border-primary rounded-lg text-primary bg-primary-container/30">
                    <span class="material-symbols-outlined text-[24px]">group</span>
                </div>
                <span class="text-[9px] font-black text-primary bg-primary-container/50 border border-primary px-3 py-1 rounded-lg uppercase tracking-wider">Bulan Ini</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-outline-variant uppercase tracking-widest">Total Kunjungan</p>
                <h3 class="text-4xl font-black text-on-surface mt-2 mb-1 tracking-tight">{{ $totalKunjungan }}</h3>
                <p class="text-[11px] text-outline-variant font-semibold mt-1">{{ $periodLabel }}</p>
            </div>
        </div>

        {{-- Balita Stunting --}}
        <div class="group bg-white rounded-2xl border border-outline-variant p-6 shadow-sm transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 flex items-center justify-center border border-error rounded-lg text-error bg-error-container/30">
                    <span class="material-symbols-outlined text-[24px]" style="font-variation-settings:'FILL' 1;">warning</span>
                </div>
                <span class="text-[9px] font-black text-error bg-error-container/50 border border-error px-3 py-1 rounded-lg uppercase tracking-wider">Perhatian</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-outline-variant uppercase tracking-widest">Balita Stunting</p>
                <h3 class="text-4xl font-black text-on-surface mt-2 mb-1 tracking-tight">{{ $balitaStunting }}</h3>
                <p class="text-[11px] text-outline-variant font-semibold mt-1">Ditemukan bulan ini</p>
            </div>
        </div>

        {{-- Ibu Hamil --}}
        <div class="group bg-white rounded-2xl border border-outline-variant p-6 shadow-sm transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 flex items-center justify-center border border-blue-200 rounded-lg text-secondary bg-blue-50/30">
                    <span class="material-symbols-outlined text-[24px]">female</span>
                </div>
                <span class="text-[9px] font-black text-secondary bg-blue-50/50 border border-blue-200 px-3 py-1 rounded-lg uppercase tracking-wider">Terdaftar</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-outline-variant uppercase tracking-widest">Ibu Hamil</p>
                <h3 class="text-4xl font-black text-on-surface mt-2 mb-1 tracking-tight">{{ $totalIbuHamil }}</h3>
                <p class="text-[11px] text-outline-variant font-semibold mt-1">Terdaftar aktif</p>
            </div>
        </div>

        {{-- Cakupan Vitamin A --}}
        <div class="group bg-white rounded-2xl border border-outline-variant p-6 shadow-sm transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 flex items-center justify-center border border-amber-200 rounded-lg text-amber-500 bg-amber-50/30">
                    <span class="material-symbols-outlined text-[24px]">medication</span>
                </div>
                <span class="text-[9px] font-black text-amber-600 bg-amber-50/50 border border-amber-200 px-3 py-1 rounded-lg uppercase tracking-wider">Target: 95%</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-outline-variant uppercase tracking-widest">Cakupan Vitamin A</p>
                <h3 class="text-4xl font-black text-on-surface mt-2 mb-1 tracking-tight">{{ $cakupanVitaminA }}%</h3>
                <p class="text-[11px] text-outline-variant font-semibold mt-1">Capaian posyandu</p>
            </div>
        </div>

        {{-- Lansia --}}
        <div class="group bg-white rounded-2xl border border-outline-variant p-6 shadow-sm transition-all duration-300 relative overflow-hidden">
            <div class="flex justify-between items-start mb-6">
                <div class="w-12 h-12 flex items-center justify-center border border-secondary rounded-lg text-secondary bg-secondary-container/30">
                    <span class="material-symbols-outlined text-[24px]">elderly</span>
                </div>
                <span class="text-[9px] font-black text-secondary bg-secondary-container/50 border border-secondary px-3 py-1 rounded-lg uppercase tracking-wider">Terdaftar</span>
            </div>
            <div>
                <p class="text-[11px] font-bold text-outline-variant uppercase tracking-widest">Lansia</p>
                <h3 class="text-4xl font-black text-on-surface mt-2 mb-1 tracking-tight">{{ $totalLansia }}</h3>
                <p class="text-[11px] text-outline-variant font-semibold mt-1">Terdaftar aktif</p>
            </div>
        </div>

    </section>

    {{-- ── Tabel Detail Kunjungan (Redesigned) ── --}}
    <section class="bg-white rounded-2xl border border-slate-100 shadow-[0_4px_24px_-8px_rgba(0,0,0,0.05)] overflow-hidden flex flex-col">

        {{-- Header Tabel --}}
        <div class="px-8 py-6 border-b border-slate-100/60 bg-surface-container-low/30 flex flex-wrap justify-between items-center gap-6">
            <div>
                <h2 class="text-headline-sm font-black text-on-surface tracking-tight">Detail Kunjungan</h2>
                <p class="text-[13px] text-outline font-medium mt-1"><span class="font-bold text-primary">{{ $posyanduName }}</span> • {{ $periodLabel }}</p>
            </div>
            <div class="flex flex-wrap gap-3">
                {{-- Ekspor Excel --}}
                <button wire:click="exportExcel"
                        wire:loading.attr="disabled"
                        wire:target="exportExcel"
                        class="h-11 px-5 bg-white border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:border-emerald-500 hover:text-primary active:scale-95 transition-all flex items-center gap-2 shadow-sm group">
                    <span wire:loading.remove wire:target="exportExcel" class="material-symbols-outlined text-[18px] text-emerald-500 group-hover:scale-110 transition-transform">description</span>
                    <svg wire:loading wire:target="exportExcel" class="animate-spin h-4 w-4 text-emerald-500" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>Excel</span>
                </button>

                {{-- Ekspor PDF --}}
                <button wire:click="exportPdf"
                        wire:loading.attr="disabled"
                        wire:target="exportPdf"
                        class="h-11 px-5 bg-white border border-outline-variant text-on-surface-variant rounded-xl text-sm font-bold hover:border-error hover:text-error active:scale-95 transition-all flex items-center gap-2 shadow-sm group">
                    <span wire:loading.remove wire:target="exportPdf" class="material-symbols-outlined text-[18px] text-error group-hover:scale-110 transition-transform">picture_as_pdf</span>
                    <svg wire:loading wire:target="exportPdf" class="animate-spin h-4 w-4 text-error" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path>
                    </svg>
                    <span>PDF</span>
                </button>
            </div>
        </div>

        {{-- Search & Sort Section --}}
        <div class="px-6 lg:px-8 py-5 border-b border-slate-100/60 flex flex-col gap-4">
            {{-- Search Input --}}
            <div class="relative w-full max-w-100 shrink-0 group">
                <div class="absolute inset-y-0 left-0 w-10 flex items-center justify-center pointer-events-none">
                    <span class="material-symbols-outlined text-outline-variant group-focus-within:text-teal-500 transition-colors text-[20px]">search</span>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" 
                       placeholder="Cari Nama/NIK..."
                       class="w-full pl-10 pr-4 h-10 rounded-xl border border-outline-variant bg-surface-container-low/50 text-[13px] font-medium focus:bg-white focus:border-teal-400 focus:ring-4 focus:ring-teal-400/10 transition-all shadow-sm">
            </div>

            {{-- Sort Options Row --}}
            <div class="flex items-center gap-3 overflow-x-auto hide-scrollbar w-full pb-1">
                <span class="text-[10px] font-black text-outline-variant uppercase tracking-widest whitespace-nowrap shrink-0">Urutkan:</span>
            
                {{-- Sort by Patient Name --}}
                <div class="flex bg-surface-container/80 p-1 rounded-xl">
                    <button wire:click="$set('sortBy', 'patient_name_asc')"
                            @class(['px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-1', 
                                    'bg-white text-primary shadow-sm' => $sortBy === 'patient_name_asc',
                                    'text-outline hover:text-on-surface-variant' => $sortBy !== 'patient_name_asc'])>
                        <span class="material-symbols-outlined text-[14px]">sort_by_alpha</span> A-Z
                    </button>
                    <button wire:click="$set('sortBy', 'patient_name_desc')"
                            @class(['px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-1', 
                                    'bg-white text-primary shadow-sm' => $sortBy === 'patient_name_desc',
                                    'text-outline hover:text-on-surface-variant' => $sortBy !== 'patient_name_desc'])>
                        <span class="material-symbols-outlined text-[14px]">sort_by_alpha</span> Z-A
                    </button>
                </div>

                {{-- Sort by Visit Date --}}
                <div class="flex bg-surface-container/80 p-1 rounded-xl">
                    <button wire:click="$set('sortBy', 'visit_date_desc')"
                            @class(['px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-1', 
                                    'bg-white text-primary shadow-sm' => $sortBy === 'visit_date_desc',
                                    'text-outline hover:text-on-surface-variant' => $sortBy !== 'visit_date_desc'])>
                        <span class="material-symbols-outlined text-[14px]">event_available</span> Terbaru
                    </button>
                    <button wire:click="$set('sortBy', 'visit_date_asc')"
                            @class(['px-4 py-1.5 rounded-lg text-[10px] font-black uppercase tracking-widest transition-all whitespace-nowrap flex items-center gap-1', 
                                    'bg-white text-primary shadow-sm' => $sortBy === 'visit_date_asc',
                                    'text-outline hover:text-on-surface-variant' => $sortBy !== 'visit_date_asc'])>
                        <span class="material-symbols-outlined text-[14px]">history_toggle_off</span> Terlama
                    </button>
                </div>
            </div>
        </div>

        {{-- Tabel --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse whitespace-nowrap">
                <thead>
                    <tr>
                        <th class="px-8 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest w-16">No</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Kategori</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Nama & NIK</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Usia</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Kunjungan</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Pengukuran</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest">Status Gizi</th>
                        <th class="px-6 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest text-center">Tindakan</th>
                        <th class="px-8 py-4 text-[10px] font-black text-outline-variant uppercase tracking-widest text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100/50">
                    @forelse($records as $index => $record)
                    <tr class="hover:bg-surface-container-low/80 transition-colors group">
                        <td class="px-8 py-5 text-sm font-bold text-outline-variant">
                            {{ ($records->currentPage() - 1) * $records->perPage() + $index + 1 }}
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $cat = $record->patient->category ?? 'Lainnya';
                                $catColor = match(strtolower($cat)) {
                                    'bayi', 'baduta', 'balita' => 'text-emerald-700 bg-emerald-50 border-emerald-200',
                                    'ibu hamil', 'ibu_hamil' => 'text-blue-700 bg-blue-50 border-blue-200',
                                    'lansia' => 'text-amber-700 bg-amber-50 border-amber-200',
                                    'pua', 'wus', 'remaja' => 'text-purple-700 bg-purple-50 border-purple-200',
                                    default => 'text-on-surface-variant bg-surface-container-low border-outline-variant'
                                };
                            @endphp
                            <span class="inline-flex px-2 py-1 text-[10px] font-bold uppercase tracking-wider rounded border {{ $catColor }}">
                                {{ str_replace('_', ' ', $cat) }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                @if($record->patient?->profile_photo)
                                    <img src="{{ asset('storage/' . $record->patient->profile_photo) }}" class="h-9 w-9 rounded-xl object-cover border border-outline-variant flex-shrink-0">
                                @else
                                    @php
                                        $initials = $record->patient ? strtoupper(substr($record->patient->full_name, 0, 2)) : '-';
                                    @endphp
                                    <div class="h-9 w-9 rounded-xl bg-primary-container text-on-primary-container flex items-center justify-center font-bold text-xs flex-shrink-0 font-sans">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-bold text-sm text-on-surface">{{ $record->patient->full_name ?? '-' }}</div>
                                    <div class="text-[11px] font-medium text-outline font-mono mt-0.5">{{ $record->patient->id_number ?? '' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @if($record->patient?->birth_date)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-surface-container text-on-surface-variant text-xs font-bold">
                                    {{ floor($record->patient->birth_date->diffInMonths(now())) }} Bln
                                </span>
                            @else
                                <span class="text-outline-variant">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5 text-sm font-semibold text-on-surface-variant">
                            {{ \Carbon\Carbon::parse($record->visit_date)->format('d M Y') }}
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3">
                                <div title="Berat Badan">
                                    <span class="text-[10px] font-bold text-outline-variant uppercase">BB</span>
                                    <div class="text-sm font-bold text-on-surface">{{ $record->weight ? number_format($record->weight, 1) : '-' }} <span class="text-xs text-outline-variant font-medium">kg</span></div>
                                </div>
                                <div class="w-px h-6 bg-surface-container-high"></div>
                                <div title="Tinggi Badan">
                                    <span class="text-[10px] font-bold text-outline-variant uppercase">TB</span>
                                    <div class="text-sm font-bold text-on-surface">{{ $record->height ? number_format($record->height, 1) : '-' }} <span class="text-xs text-outline-variant font-medium">cm</span></div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            @php
                                $status = $record->nutrition_status ?? null;
                                $badgeStyle = match($status ? strtolower(trim($status)) : null) {
                                    'normal', 'gizi baik', 'baik' => 'bg-emerald-50 text-emerald-700 ring-1 ring-emerald-600/20',
                                    'gizi kurang', 'kurang' => 'bg-amber-50 text-amber-700 ring-1 ring-amber-600/20',
                                    'gizi lebih', 'lebih', 'berisiko gizi lebih', 'obesitas' => 'bg-orange-50 text-orange-700 ring-1 ring-orange-600/20',
                                    'gizi buruk/stunting', 'gizi buruk', 'buruk', 'stunting' => 'bg-error-container text-rose-700 ring-1 ring-rose-600/20',
                                    default => 'bg-surface-container text-outline ring-1 ring-slate-400/20',
                                };
                                $icon = match($status ? strtolower(trim($status)) : null) {
                                    'gizi buruk/stunting', 'gizi buruk', 'buruk', 'stunting' => 'trending_down',
                                    'gizi kurang', 'kurang' => 'trending_down',
                                    'gizi lebih', 'lebih', 'berisiko gizi lebih', 'obesitas' => 'trending_up',
                                    'normal', 'gizi baik', 'baik' => 'check_circle',
                                    default => 'horizontal_rule',
                                };
                            @endphp
                            @if($status)
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-[11px] font-bold {{ $badgeStyle }}">
                                <span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">{{ $icon }}</span>
                                {{ $status }}
                            </span>
                            @else
                            <span class="text-xs text-outline-variant">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center justify-center gap-2">
                                <div class="flex flex-col items-center gap-1" title="Vitamin A">
                                    <span class="text-[9px] font-bold text-outline-variant uppercase">Vit. A</span>
                                    @if($record->vitamin_a)
                                        <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center text-primary"><span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">check</span></div>
                                    @else
                                        <div class="w-6 h-6 rounded-lg bg-surface-container flex items-center justify-center text-outline-variant"><span class="material-symbols-outlined text-[14px]">close</span></div>
                                    @endif
                                </div>
                                <div class="w-px h-6 bg-surface-container"></div>
                                <div class="flex flex-col items-center gap-1" title="Pil FE">
                                    <span class="text-[9px] font-bold text-outline-variant uppercase">Pil FE</span>
                                    @if($record->pill_fe)
                                        <div class="w-6 h-6 rounded-lg bg-emerald-100 flex items-center justify-center text-primary"><span class="material-symbols-outlined text-[14px]" style="font-variation-settings:'FILL' 1;">check</span></div>
                                    @else
                                        <div class="w-6 h-6 rounded-lg bg-surface-container flex items-center justify-center text-outline-variant"><span class="material-symbols-outlined text-[14px]">close</span></div>
                                    @endif
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-center">
                            <a href="{{ route('admin.reports.individual', ['patient' => $record->patient_id, 'start_month' => $startMonth, 'start_year' => $startYear, 'end_month' => $endMonth, 'end_year' => $endYear]) }}"
                               class="inline-flex items-center justify-center rounded-xl bg-white border border-outline-variant shadow-sm px-4 py-2.5 text-xs font-black text-on-surface-variant hover:bg-primary-container hover:text-on-primary-container hover:border-primary active:scale-95 transition-all">
                                <span class="material-symbols-outlined text-[18px]">assignment_ind</span>
                                <span class="ml-1.5">Rapor</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4 text-outline-variant">
                                <div class="w-20 h-20 rounded-2xl bg-surface-container-low flex items-center justify-center border border-slate-100">
                                    <span class="material-symbols-outlined text-[40px] text-slate-300">search_off</span>
                                </div>
                                <div>
                                    <p class="text-base font-bold text-on-surface-variant">Tidak ada data kunjungan</p>
                                    <p class="text-sm mt-1">Tidak ditemukan rekam medis untuk periode {{ $periodLabel }}</p>
                                </div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($records instanceof \Illuminate\Pagination\LengthAwarePaginator && $records->hasPages())
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            <x-layouts.ui.pagination :paginator="$records" label="data" />
        </div>
        @elseif($records->count() > 0)
        <div class="p-4 border-t border-outline-variant">
            <p class="text-sm font-medium text-outline">Menampilkan total <span class="font-bold text-on-surface-variant">{{ $total }}</span> data</p>
        </div>
        @endif

    </section>
    @else
    {{-- Empty State --}}
    {{-- Empty State --}}
    <section class="bg-white rounded-2xl border border-outline-variant shadow-sm p-12 mt-8 text-center">
        <div class="flex flex-col items-center justify-center gap-4">
            <div class="w-20 h-20 rounded-2xl bg-primary-container flex items-center justify-center border border-teal-100 mb-2">
                <span class="material-symbols-outlined text-[40px] text-teal-500" style="font-variation-settings:'FILL' 1;">analytics</span>
            </div>
            <div>
                <h2 class="text-headline-md font-black text-on-surface tracking-tight">Pilih Periode Laporan</h2>
                <p class="text-sm font-medium text-outline mt-2 max-w-md mx-auto leading-relaxed">Pilih posyandu, bulan, dan tahun pada filter di atas lalu klik <span class="font-bold text-primary">Analisis</span> untuk menghasilkan rekap dan laporan detail.</p>
            </div>
        </div>
    </section>
    @endif

</div>

@push('scripts')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('download-file', (data) => {
            const link = document.createElement('a');
            link.href = data.url;
            link.download = '';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        });
    });
</script>
@endpush
