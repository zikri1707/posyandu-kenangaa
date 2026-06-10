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
            'shadow' => 'shadow-teal-500/10',
            'bg-light' => 'bg-teal-50',
            'text' => 'text-teal-600',
            'border' => 'border-teal-100',
        ],
        'lansia' => [
            'name' => 'Lansia',
            'gradient' => 'from-amber-600 to-orange-500',
            'shadow' => 'shadow-amber-500/10',
            'bg-light' => 'bg-amber-50',
            'text' => 'text-amber-600',
            'border' => 'border-amber-100',
        ],
        'ibu_hamil' => [
            'name' => 'Ibu Hamil',
            'gradient' => 'from-rose-500 to-pink-500',
            'shadow' => 'shadow-rose-500/10',
            'bg-light' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'border' => 'border-rose-100',
        ],
        default => [
            'name' => str_replace('_', ' ', ucfirst($cat)),
            'gradient' => 'from-indigo-600 to-slate-500',
            'shadow' => 'shadow-indigo-500/10',
            'bg-light' => 'bg-indigo-50',
            'text' => 'text-indigo-600',
            'border' => 'border-indigo-100',
        ]
    };
@endphp

<div class="max-w-5xl mx-auto space-y-8 pb-16 px-4">

    {{-- Breadcrumbs & Header --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-6">
        <div class="space-y-2">
            <nav class="flex items-center gap-2">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-100 shadow-sm text-[10px] font-black uppercase tracking-widest text-slate-400">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-slate-400 hover:text-teal-600">
                        <span class="material-symbols-outlined text-[14px]">home</span>
                        Beranda
                    </a>
                    <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                    <a href="{{ route('admin.reports.index') }}" class="text-slate-400 hover:text-teal-600">Rekap Laporan</a>
                    <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                    <span class="{{ $theme['text'] }}">Rapor Individu</span>
                </div>
            </nav>
            <h1 class="text-3xl font-black tracking-tight leading-none text-slate-900">
                Rapor Perkembangan: <span class="text-transparent bg-clip-text bg-gradient-to-r {{ $theme['gradient'] }}">{{ $patient['full_name'] }}</span>
            </h1>
        </div>
    </div>

    {{-- ── 1. Filter Rentang Periode (Interactive) ── --}}
    <section class="bg-white rounded-3xl border border-slate-150 p-6 shadow-sm">
        <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest mb-4 ml-1">Rentang Periode Rapor</h3>
        
        <form id="filterForm" method="GET" action="{{ route('admin.reports.individual', $patient['id']) }}">
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
                
                {{-- Mulai Bulan --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Bulan Mulai</label>
                    <select name="start_month" id="start_month" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-sm font-semibold bg-slate-50 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($startMonth == $m)>{{ Carbon\Carbon::create(2020, $m, 1)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Mulai Tahun --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Tahun Mulai</label>
                    <select name="start_year" id="start_year" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-sm font-semibold bg-slate-50 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" @selected($startYear == $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Selesai Bulan --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Bulan Selesai</label>
                    <select name="end_month" id="end_month" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-sm font-semibold bg-slate-50 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        @foreach(range(1, 12) as $m)
                            <option value="{{ $m }}" @selected($endMonth == $m)>{{ Carbon\Carbon::create(2020, $m, 1)->translatedFormat('F') }}</option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Selesai Tahun --}}
                <div class="md:col-span-1">
                    <label class="block text-[10px] font-bold text-slate-500 uppercase tracking-widest mb-2 ml-1">Tahun Selesai</label>
                    <select name="end_year" id="end_year" class="w-full h-12 px-4 rounded-xl border border-slate-200 text-sm font-semibold bg-slate-50 focus:bg-white focus:ring-2 focus:ring-teal-500/20 focus:border-teal-500 transition-all">
                        @for($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}" @selected($endYear == $y)>{{ $y }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Action --}}
                <div>
                    <button type="submit" class="w-full h-12 bg-teal-600 hover:bg-teal-700 active:scale-98 text-white rounded-xl text-xs font-black uppercase tracking-widest transition-all shadow-md shadow-teal-600/10 flex items-center justify-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">sync</span>
                        Terapkan
                    </button>
                </div>
            </div>
            
            {{-- Presets --}}
            <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-slate-100 items-center">
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mr-2">Cepat:</span>
                <button type="button" onclick="setPeriodPreset(1)" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-600 border border-slate-150 transition-colors">1 Bulan Saja</button>
                <button type="button" onclick="setPeriodPreset(3)" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-600 border border-slate-150 transition-colors">3 Bulan Terakhir</button>
                <button type="button" onclick="setPeriodPreset(6)" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-600 border border-slate-150 transition-colors">6 Bulan Terakhir</button>
                <button type="button" onclick="setPeriodPreset(12)" class="px-4 py-2 bg-slate-50 hover:bg-slate-100 rounded-xl text-xs font-bold text-slate-600 border border-slate-150 transition-colors">1 Tahun Terakhir</button>
            </div>
        </form>
    </section>

    {{-- Bento Grid layout --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- ── 2. Identitas Warga Bento-Card (Left Col - 4 span) ── --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2.5rem] border border-slate-150 p-8 flex flex-col items-center text-center relative overflow-hidden group shadow-sm">
                <div class="absolute -right-10 -top-10 w-24 h-24 bg-teal-500/5 rounded-full blur-xl"></div>
                
                {{-- Profile icon/photo --}}
                <div class="w-32 h-32 rounded-3xl bg-linear-to-br {{ $theme['gradient'] }} p-1 mb-6 shadow-md">
                    <div class="w-full h-full bg-white rounded-[1.35rem] flex items-center justify-center overflow-hidden">
                        <span class="material-symbols-outlined text-[64px] {{ $theme['text'] }}">account_circle</span>
                    </div>
                </div>

                <h2 class="text-xl font-black text-slate-900 leading-tight mb-1">{{ $patient['full_name'] }}</h2>
                <span class="text-[10px] font-black px-3 py-1 bg-slate-100 rounded-full text-slate-500 uppercase tracking-widest">NIK: {{ $patient['id_number'] }}</span>

                <div class="w-full mt-6 pt-6 border-t border-slate-100 grid grid-cols-2 gap-2 text-left">
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Kategori</span>
                        <span class="text-xs font-black text-slate-800 uppercase">{{ str_replace('_', ' ', $patient['category']) }}</span>
                    </div>
                    <div>
                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider block">Usia</span>
                        <span class="text-xs font-black text-slate-800">{{ $patient['age'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Detail Keluarga --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-150 p-8 space-y-4 shadow-sm">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                    <span class="material-symbols-outlined text-teal-600 text-[18px]">family_restroom</span>
                    Informasi Keluarga
                </h4>
                
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-xs text-slate-400 font-bold">Nama Ibu</span>
                        <span class="font-black text-slate-700">{{ $patient['mother_name'] }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-xs text-slate-400 font-bold">Nama Ayah</span>
                        <span class="font-black text-slate-700">{{ $patient['father_name'] }}</span>
                    </div>
                    <div class="flex justify-between py-1.5 border-b border-slate-50">
                        <span class="text-xs text-slate-400 font-bold">No. Telp</span>
                        <span class="font-black text-slate-700">{{ $patient['phone_number'] }}</span>
                    </div>
                    <div class="py-1.5">
                        <span class="text-xs text-slate-400 font-bold block mb-1">Alamat Domisili</span>
                        <span class="font-bold text-slate-600 text-xs leading-relaxed block">{{ $patient['address'] }}</span>
                    </div>
                </div>
            </div>

            {{-- Actions / Exports --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-150 p-6 space-y-3 shadow-sm">
                <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider mb-2 ml-1">Ekspor Laporan Perkembangan</h4>
                
                {{-- PDF Export Form --}}
                <form action="{{ route('admin.reports.individual.pdf', $patient['id']) }}" method="POST" target="_blank">
                    @csrf
                    <input type="hidden" name="start_month" value="{{ $startMonth }}">
                    <input type="hidden" name="start_year" value="{{ $startYear }}">
                    <input type="hidden" name="end_month" value="{{ $endMonth }}">
                    <input type="hidden" name="end_year" value="{{ $endYear }}">
                    <button type="submit" class="w-full h-12 bg-red-600 hover:bg-red-700 active:scale-98 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                        Unduh Rapor PDF (Resmi)
                    </button>
                </form>

                {{-- Excel Export Form --}}
                <form action="{{ route('admin.reports.individual.excel', $patient['id']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="start_month" value="{{ $startMonth }}">
                    <input type="hidden" name="start_year" value="{{ $startYear }}">
                    <input type="hidden" name="end_month" value="{{ $endMonth }}">
                    <input type="hidden" name="end_year" value="{{ $endYear }}">
                    <button type="submit" class="w-full h-12 bg-green-600 hover:bg-green-700 active:scale-98 text-white rounded-xl text-xs font-bold flex items-center justify-center gap-2 transition-all shadow-sm">
                        <span class="material-symbols-outlined text-[18px]">description</span>
                        Unduh Rapor Excel (Data)
                    </button>
                </form>
            </div>
        </div>

        {{-- ── 3. Chart Analytics & History (Right Col - 8 span) ── --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- 3.1. Grafik Tumbuh Kembang (Hanya untuk Balita) --}}
            @if(in_array($cat, ['bayi', 'baduta', 'balita']))
            <div class="bg-white rounded-[2.5rem] border border-slate-150 p-8 shadow-sm space-y-6">
                <div class="flex items-center justify-between">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600">monitoring</span>
                        Visualisasi Grafik Tren Pertumbuhan
                    </h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Weight Chart --}}
                    <div class="space-y-2">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-wider block text-center">Tren Berat Badan (kg)</span>
                        <div class="h-48 w-full relative">
                            <canvas id="weightChartCanvas"></canvas>
                        </div>
                    </div>
                    {{-- Height Chart --}}
                    <div class="space-y-2">
                        <span class="text-xs font-black text-slate-500 uppercase tracking-wider block text-center">Tren Tinggi Badan (cm)</span>
                        <div class="h-48 w-full relative">
                            <canvas id="heightChartCanvas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- 3.2. Tabel Detail Pengukuran Bulanan --}}
            <div class="bg-white rounded-[2.5rem] border border-slate-150 shadow-sm overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100">
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest flex items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600">table_rows</span>
                        Riwayat Kunjungan & Antropometri Bulanan
                    </h3>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs">
                        <thead class="bg-slate-50 border-b border-slate-200">
                            <tr>
                                <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">Periode</th>
                                <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">Tgl Kunjungan</th>
                                <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">BB (kg)</th>
                                <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">TB (cm)</th>
                                @if(in_array($cat, ['bayi', 'baduta', 'balita']))
                                    <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">LILA / LK</th>
                                    <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">Status Gizi</th>
                                @else
                                    <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">Tensi / Gula</th>
                                    <th class="px-5 py-3 font-bold text-slate-500 uppercase tracking-wider">Catatan</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($reportData['monthly_records'] as $slot)
                                @php $record = $slot['record']; @endphp
                                <tr class="hover:bg-slate-50/50 transition-colors">
                                    <td class="px-5 py-3.5 font-bold text-slate-800">{{ $slot['period']['label'] }}</td>
                                    @if($record)
                                        <td class="px-5 py-3.5 text-slate-600 font-semibold">{{ $record['visit_date'] }}</td>
                                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $record['weight'] ?? '-' }}</td>
                                        <td class="px-5 py-3.5 font-bold text-slate-900">{{ $record['height'] ?? '-' }}</td>
                                        
                                        @if(in_array($cat, ['bayi', 'baduta', 'balita']))
                                            <td class="px-5 py-3.5 text-slate-600">
                                                {{ $record['upper_arm_circumference'] ?? '-' }} / {{ $record['head_circumference'] ?? '-' }} cm
                                            </td>
                                            <td class="px-5 py-3.5">
                                                @php
                                                    $st = $record['nutrition_status'] ?? null;
                                                    $badge = match($st) {
                                                        'Normal', 'Gizi Baik' => 'bg-green-50 text-green-700 border-green-150',
                                                        'Gizi Kurang', 'Kurang' => 'bg-amber-50 text-amber-700 border-amber-150',
                                                        'Gizi Buruk/Stunting', 'Gizi Buruk' => 'bg-red-50 text-red-700 border-red-150',
                                                        default => 'bg-slate-50 text-slate-500 border-slate-150',
                                                    };
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold border {{ $badge }}">{{ $st ?? '-' }}</span>
                                            </td>
                                        @else
                                            <td class="px-5 py-3.5 text-slate-600">
                                                {{ $record['blood_pressure'] ?? '-' }} / {{ $record['blood_sugar'] ?? '-' }}
                                            </td>
                                            <td class="px-5 py-3.5 text-slate-500 max-w-xs truncate">{{ $record['health_note'] ?? $record['complaint'] ?? '-' }}</td>
                                        @endif
                                    @else
                                        <td colspan="5" class="px-5 py-3.5 text-center text-slate-400 italic">Tidak Hadir</td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 3.3. Imunisasi & Vitamin A --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Riwayat Vitamin --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-150 p-6 shadow-sm space-y-4">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-amber-500">medication</span>
                        Pemberian Vitamin A & Obat Cacing
                    </h4>
                    
                    <div class="space-y-3">
                        @forelse($reportData['vitamins_in_period'] as $vit)
                            <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                                <div class="flex flex-col">
                                    <span class="text-xs font-black text-slate-700">{{ $vit['note'] }}</span>
                                    <span class="text-[9px] font-bold text-slate-400 uppercase">Diberikan: {{ $vit['date'] }}</span>
                                </div>
                                <span class="text-[9px] font-black uppercase px-2.5 py-1 rounded-md border bg-white @if($vit['color']=='red') text-red-600 border-red-100 @else text-blue-600 border-blue-100 @endif">{{ $vit['color'] }}</span>
                            </div>
                        @empty
                            <p class="text-xs text-slate-400 italic text-center py-6">Tidak ada pemberian vitamin di rentang periode ini</p>
                        @endforelse
                    </div>
                </div>

                {{-- Riwayat Imunisasi Wajib --}}
                <div class="bg-white rounded-[2.5rem] border border-slate-150 p-6 shadow-sm space-y-4">
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-wider border-b border-slate-100 pb-3 flex items-center gap-2">
                        <span class="material-symbols-outlined text-indigo-500">vaccines</span>
                        Kelengkapan Imunisasi Anak
                    </h4>
                    
                    <div class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                        @foreach($reportData['immunization_status'] as $group)
                            @foreach($group['vaccines'] as $vax)
                                <div class="flex items-center justify-between p-2 rounded-xl {{ $vax['received'] ? 'bg-emerald-50/50' : 'bg-slate-50' }}">
                                    <div class="flex flex-col min-w-0">
                                        <span class="text-xs font-bold text-slate-700 truncate">{{ $vax['name'] }}</span>
                                        <span class="text-[9px] font-semibold text-slate-400 truncate">{{ $group['label'] }}</span>
                                    </div>
                                    
                                    @if($vax['received'])
                                        <span class="material-symbols-outlined text-emerald-500 text-[18px]">check_circle</span>
                                    @elseif($vax['is_due'])
                                        <span class="text-[8px] font-black uppercase px-2 py-0.5 bg-amber-50 border border-amber-100 text-amber-700 rounded-md">Due</span>
                                    @else
                                        <span class="material-symbols-outlined text-slate-300 text-[18px]">cancel</span>
                                    @endif
                                </div>
                            @endforeach
                        @endforeach
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

{{-- Preset JavaScript --}}
<script>
    function setPeriodPreset(months) {
        const endM = parseInt(document.getElementById('end_month').value);
        const endY = parseInt(document.getElementById('end_year').value);
        
        let startM = endM - months + 1;
        let startY = endY;
        
        while (startM <= 0) {
            startM += 12;
            startY -= 1;
        }
        
        document.getElementById('start_month').value = startM;
        document.getElementById('start_year').value = startY;
        
        document.getElementById('filterForm').submit();
    }
</script>

{{-- Chart.js Rendering (browser only) --}}
@if(in_array($cat, ['bayi', 'baduta', 'balita']))
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        if (typeof Chart === 'undefined') return;

        const records = @json($reportData['raw_records']);
        
        // Sort ascending by date
        records.sort((a, b) => new Date(a.visit_date) - new Date(b.visit_date));

        const labels = records.map(r => {
            const d = new Date(r.visit_date);
            return d.toLocaleDateString('id-ID', { month: 'short', year: '2-digit' });
        });

        // 1. Weight Chart
        const weightData = records.map(r => parseFloat(r.weight) || null);
        new Chart(document.getElementById('weightChartCanvas'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Berat Badan (kg)',
                    data: weightData,
                    borderColor: '#0d9488',
                    backgroundColor: 'rgba(13, 148, 136, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#0d9488'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { suggestedMin: 0, grid: { color: 'rgba(15, 23, 42, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });

        // 2. Height Chart
        const heightData = records.map(r => parseFloat(r.height) || null);
        new Chart(document.getElementById('heightChartCanvas'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Tinggi Badan (cm)',
                    data: heightData,
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#2563eb'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { suggestedMin: 40, grid: { color: 'rgba(15, 23, 42, 0.05)' } },
                    x: { grid: { display: false } }
                }
            }
        });
    });
</script>
@endpush
@endif
@endsection
