{{-- ── Analytics & Insights — Premium Senior-Friendly UI ── --}}
<div class="max-w-[1440px] mx-auto space-y-10 pb-20">

    {{-- ── Modern Header with Mesh Gradient ── --}}
    <div class="relative rounded-[3rem] p-8 md:p-12 overflow-hidden bg-slate-900 text-white shadow-2xl">
        {{-- Mesh Gradient Background --}}
        <div class="absolute inset-0 opacity-40 pointer-events-none">
            <div class="absolute -top-24 -left-24 w-96 h-96 bg-teal-500 rounded-full blur-[120px]"></div>
            <div class="absolute top-1/2 -right-24 w-80 h-80 bg-blue-600 rounded-full blur-[100px]"></div>
            <div class="absolute -bottom-24 left-1/3 w-64 h-64 bg-indigo-500 rounded-full blur-[80px]"></div>
        </div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
            <div class="space-y-3">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[11px] font-black uppercase tracking-[0.2em] text-teal-300">
                    <span class="w-2 h-2 rounded-full bg-teal-400 animate-pulse"></span>
                    Live Data Analysis
                </div>
                <h1 class="text-4xl md:text-5xl font-black tracking-tight leading-tight">Analitik & Wawasan Kesehatan</h1>
                <p class="text-lg text-slate-300 font-medium max-w-2xl">
                    Pemantauan agregat periode Januari – Desember {{ $selectedYear }} 
                    @if(auth()->user()->posyandu) · <span class="text-white font-bold">{{ auth()->user()->posyandu->name }}</span> @endif
                </p>
                @if($lastUpdated)
                <p class="text-[11px] font-black uppercase tracking-widest text-slate-500 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[14px]">history</span>
                    Data diperbarui terakhir pada: {{ $lastUpdated }}
                </p>
                @endif
            </div>

            <div class="flex items-center gap-4 flex-wrap">
                <div class="flex items-center bg-white h-14 rounded-2xl px-5 gap-3 shadow-xl shadow-black/20 border border-white/10">
                    <span class="material-symbols-outlined text-slate-400 text-[22px]">calendar_month</span>
                    <select wire:model.live="selectedYear"
                            class="bg-transparent border-none focus:ring-0 text-lg font-black text-slate-900 p-0 pr-8 cursor-pointer">
                        @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                        @endforeach
                    </select>
                </div>
                {{-- Month Selector --}}
            <div class="flex items-center bg-white h-14 rounded-2xl px-5 gap-3 shadow-xl shadow-black/20 border border-white/10">
                <span class="material-symbols-outlined text-slate-400 text-[22px]">format_list_bulleted</span>
                <select wire:model.live="selectedMonth"
                        class="bg-transparent border-none focus:ring-0 text-lg font-black text-slate-900 p-0 pr-8 cursor-pointer">
                    <option value="">Semua Bulan</option>
                    @foreach(range(1, 12) as $m)
                        <option value="{{ $m }}">{{ Carbon\Carbon::create(2000, $m)->translatedFormat('F') }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Export buttons --}}
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.reports.index') }}"
                       class="h-14 px-6 flex items-center gap-3 bg-white/10 hover:bg-white/20 backdrop-blur-xl border border-white/20 rounded-2xl text-sm font-black text-white uppercase tracking-widest transition-all">
                        <span class="material-symbols-outlined text-teal-400 text-[22px]">file_download</span>
                        Excel
                    </a>
                    <a href="{{ route('admin.reports.index') }}"
                       class="h-14 px-6 flex items-center gap-3 bg-teal-600 hover:bg-teal-700 text-white rounded-2xl text-sm font-black uppercase tracking-widest shadow-xl shadow-teal-600/30 transition-all">
                        <span class="material-symbols-outlined text-[22px]">picture_as_pdf</span>
                        PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Key Performance Stats (High Contrast) ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Balita', 'val' => number_format($totalBalita), 'unit' => 'Jiwa', 'icon' => 'child_care', 'color' => 'teal', 'desc' => 'Terdaftar aktif'],
                ['label' => 'Angka Stunting', 'val' => $stuntingRate . '%', 'unit' => 'Prevalensi', 'icon' => 'trending_down', 'color' => 'red', 'desc' => $stuntingRate >= 14 ? 'Perlu Intervensi' : 'Dibawah Target Nasional'],
                ['label' => 'Cakupan Imunisasi', 'val' => $cakupanImunisasi . '%', 'unit' => 'Kelengkapan', 'icon' => 'vaccines', 'color' => 'blue', 'desc' => 'Status Rekam Medis'],
                ['label' => 'Petugas Aktif', 'val' => $kaderAktif, 'unit' => 'Kader', 'icon' => 'groups', 'color' => 'amber', 'desc' => 'Admin & Tenaga Kesehatan'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:shadow-[0_20px_50px_rgba(0,0,0,0.08)] transition-all duration-500 group">
            <div class="flex items-start justify-between mb-6">
                <div @class([
                    'w-14 h-14 rounded-2xl flex items-center justify-center transition-all duration-500 group-hover:scale-110 shadow-lg',
                    'bg-teal-500 text-white shadow-teal-500/20' => $s['color'] === 'teal',
                    'bg-red-500 text-white shadow-red-500/20' => $s['color'] === 'red',
                    'bg-blue-600 text-white shadow-blue-600/20' => $s['color'] === 'blue',
                    'bg-amber-500 text-white shadow-amber-500/20' => $s['color'] === 'amber',
                ])>
                    <span class="material-symbols-outlined text-[32px]">{{ $s['icon'] }}</span>
                </div>
                <span class="text-[9px] font-black uppercase tracking-widest text-slate-400 group-hover:text-slate-900 transition-colors">{{ $s['unit'] }}</span>
            </div>
            <h3 class="text-4xl font-black text-slate-900 tracking-tighter mb-2">{{ $s['val'] }}</h3>
            <p class="text-sm font-black text-slate-800 uppercase tracking-tight mb-1">{{ $s['label'] }}</p>
            <p class="text-[11px] text-slate-400 font-bold">{{ $s['desc'] }}</p>
        </div>
        @endforeach
    </div>

    {{-- ── Main Charts Row (Large & Clear) ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- Nutrition Trend Line Chart --}}
        <div class="lg:col-span-2 bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tren Kesehatan Tahunan</h3>
                    <p class="text-sm text-slate-400 font-medium mt-1">Perbandingan pertumbuhan Normal vs Stunting</p>
                </div>
                <div class="flex items-center gap-6 bg-slate-50 px-6 py-3 rounded-2xl border border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-teal-600 shadow-sm shadow-teal-600/20"></span>
                        <span class="text-xs font-black text-slate-600 uppercase tracking-wider">Normal</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-amber-500 shadow-sm shadow-amber-500/20"></span>
                        <span class="text-xs font-black text-slate-600 uppercase tracking-wider">Risiko</span>
                    </div>
                    <div class="flex items-center gap-3">
                        <span class="w-3 h-3 rounded-full bg-red-500 shadow-sm shadow-red-500/20"></span>
                        <span class="text-xs font-black text-slate-600 uppercase tracking-wider">Stunting</span>
                    </div>
                </div>
            </div>
            <div class="relative h-[400px]">
                <canvas id="nutritionTrendChart" wire:ignore></canvas>
                <div wire:loading wire:target="selectedYear, refreshStats" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-2xl">
                    <div class="flex flex-col items-center gap-4">
                        <div class="w-12 h-12 border-4 border-teal-500/20 border-t-teal-600 rounded-full animate-spin"></div>
                        <span class="text-xs font-black text-slate-900 uppercase tracking-[0.3em]">Memproses Data...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nutrition Distribution Donut --}}
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col">
            <div class="mb-10 text-center">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Kondisi Gizi</h3>
                <p class="text-sm text-slate-400 font-medium mt-1">Status rekapitulasi saat ini</p>
            </div>
            
            <div class="relative flex justify-center mb-12">
                <canvas id="nutritionDonutChart" width="220" height="220" style="max-width:220px;max-height:220px;" wire:ignore></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-4xl font-black text-slate-900 leading-none">{{ $totalBalita }}</span>
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mt-2">Total</span>
                </div>
            </div>

            <div class="space-y-3 flex-1 overflow-y-auto custom-scrollbar">
                @php
                    $donutColors = ['#059669','#d97706','#7c3aed','#dc2626','#64748b'];
                    $i = 0;
                @endphp
                @foreach($nutritionLabels as $label)
                <div class="flex justify-between items-center p-4 rounded-2xl hover:bg-slate-50 transition-all group/item">
                    <div class="flex items-center gap-4">
                        <span class="w-3 h-3 rounded-full flex-shrink-0 shadow-lg" style="background:{{ $donutColors[$i % count($donutColors)] }}"></span>
                        <span class="text-sm font-black text-slate-600 group-hover/item:text-slate-900 transition-colors uppercase tracking-tight">{{ $label }}</span>
                    </div>
                    <span class="text-lg font-black text-slate-900">{{ $nutritionData[$i] ?? 0 }}</span>
                </div>
                @php $i++ @endphp
                @endforeach
                @if(empty($nutritionLabels))
                    <div class="py-10 text-center text-slate-300">
                        <span class="material-symbols-outlined text-[48px]">analytics</span>
                        <p class="text-[10px] font-black uppercase tracking-widest mt-2">Belum ada data</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ── Detailed Analytics Row ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        {{-- Stunting per Posyandu --}}
        <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h3 class="text-2xl font-black text-slate-900 tracking-tight">Kinerja Unit Posyandu</h3>
                    <p class="text-sm text-slate-400 font-medium mt-1">Prevalensi stunting per wilayah</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined">map</span>
                </div>
            </div>

            <div class="space-y-8">
                @forelse($stuntingByPosyandu as $item)
                <div class="group/bar">
                    <div class="flex justify-between items-end mb-3">
                        <div>
                            <span class="text-lg font-black text-slate-900 tracking-tight block group-hover/bar:text-teal-600 transition-colors">{{ $item['name'] }}</span>
                            <span class="text-xs text-slate-400 font-bold uppercase tracking-widest">{{ $item['stunting'] }} dari {{ $item['total'] }} Balita</span>
                        </div>
                        <span class="{{ $item['text'] }} text-2xl font-black">{{ $item['rate'] }}%</span>
                    </div>
                    <div class="h-4 w-full bg-slate-50 rounded-full overflow-hidden border border-slate-100 p-0.5">
                        <div class="{{ $item['color'] }} h-full rounded-full transition-all duration-1000 shadow-sm"
                             style="width: {{ $item['width'] }}%"></div>
                    </div>
                </div>
                @empty
                    <div class="py-20 text-center text-slate-300">
                        <span class="material-symbols-outlined text-[64px]">location_off</span>
                        <p class="text-xs font-black uppercase tracking-[0.3em] mt-4">Data wilayah kosong</p>
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Demographics & Insights --}}
        <div class="flex flex-col gap-8">
            <div class="bg-white rounded-[3rem] p-10 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <h3 class="text-2xl font-black text-slate-900 tracking-tight mb-10 text-center">Segmentasi Usia</h3>
                <div class="grid grid-cols-3 gap-6">
                    @php
                        $demos = [
                            ['label' => 'Bayi', 'range' => '0–12', 'val' => $usia0_12, 'color' => 'teal'],
                            ['label' => 'Baduta', 'range' => '12–24', 'val' => $usia12_24, 'color' => 'indigo'],
                            ['label' => 'Balita', 'range' => '24+', 'val' => $usia24plus, 'color' => 'blue'],
                        ];
                    @endphp
                    @foreach($demos as $d)
                    <div class="bg-slate-50 rounded-3xl p-6 border border-slate-100 flex flex-col items-center text-center group hover:bg-white hover:shadow-xl hover:-translate-y-1 transition-all duration-500">
                        <span class="text-4xl font-black text-slate-900 mb-2">{{ number_format($d['val']) }}</span>
                        <span class="text-[9px] font-black text-slate-400 uppercase tracking-[0.2em] mb-4">{{ $d['range'] }} Bulan</span>
                        <div @class([
                            'px-4 py-1 rounded-full text-[10px] font-black uppercase tracking-widest',
                            'bg-teal-100 text-teal-700' => $d['color'] === 'teal',
                            'bg-indigo-100 text-indigo-700' => $d['color'] === 'indigo',
                            'bg-blue-100 text-blue-700' => $d['color'] === 'blue',
                        ])>{{ $d['label'] }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Smart Insight Card --}}
            <div class="bg-slate-900 rounded-[3rem] p-10 text-white relative overflow-hidden shadow-2xl shadow-slate-900/20 group">
                <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-teal-500 rounded-full blur-[80px] opacity-30 group-hover:scale-150 transition-transform duration-1000"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-4 mb-6">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-xl">
                            <span class="material-symbols-outlined text-teal-400 text-[28px]">tips_and_updates</span>
                        </div>
                        <h4 class="text-xl font-black uppercase tracking-widest">Wawasan Cerdas</h4>
                    </div>
                    <div class="space-y-4">
                        <p class="text-lg text-slate-300 leading-relaxed font-medium">
                            @if($usia12_24 > $usia0_12 && $usia12_24 > $usia24plus)
                                Kelompok <span class="text-white font-black underline decoration-teal-500 decoration-2 underline-offset-4">Baduta (12–24 bln)</span> mendominasi populasi. Pastikan ketersediaan vaksin lanjutan dan edukasi PMT bagi orang tua di kelompok ini.
                            @elseif($stuntingRate >= 14)
                                Angka stunting saat ini sebesar <span class="text-red-400 font-black">{{ $stuntingRate }}%</span>. Disarankan untuk meningkatkan frekuensi posyandu dan pemberian gizi tambahan (PMT) secara intensif.
                            @else
                                Selamat! Kondisi gizi balita berada dalam <span class="text-teal-400 font-black">Status Aman</span>. Terus pertahankan program monitoring rutin dan imunisasi tepat waktu.
                            @endif
                        </p>
                        <div class="pt-6 border-t border-white/5">
                            <a href="{{ route('admin.reports.index') }}" class="inline-flex items-center gap-2 text-[11px] font-black uppercase tracking-[0.3em] text-teal-400 hover:text-white transition-colors">
                                Lihat Laporan Lengkap <span class="material-symbols-outlined text-[16px]">arrow_right_alt</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Recent Activities (Modern Table) ── --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <div class="px-10 py-8 border-b border-slate-50 flex flex-col sm:flex-row justify-between items-center gap-4">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Data Pemeriksaan Terbaru</h3>
                <p class="text-sm text-slate-400 font-medium mt-1">Aktifitas monitoring 5 balita terakhir</p>
            </div>
            <a href="{{ route('admin.medical-records.index') }}"
               class="h-14 px-8 flex items-center gap-3 bg-slate-900 text-white rounded-2xl text-sm font-black uppercase tracking-[0.2em] hover:bg-teal-600 shadow-xl shadow-slate-900/10 transition-all group">
                Semua Data
                <span class="material-symbols-outlined text-[20px] group-hover:translate-x-1 transition-transform">arrow_forward</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas Balita</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Lokasi Unit</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Gizi</th>
                        <th class="px-10 py-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Periksa</th>
                        <th class="px-10 py-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Kontrol</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($recentRecords as $record)
                    @php
                        $initials = strtoupper(substr($record->patient?->full_name ?? 'X', 0, 2));
                        $status = $record->nutrition_status;
                        $badgeStyles = match(true) {
                            str_contains($status, 'Normal') || str_contains($status, 'Baik') => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                            str_contains($status, 'Kurang') && !str_contains($status, 'Sangat') => 'bg-amber-50 text-amber-600 border-amber-100',
                            str_contains($status, 'Risiko') || str_contains($status, 'Berisiko') => 'bg-amber-50 text-amber-600 border-amber-100',
                            str_contains($status, 'Sangat') || str_contains($status, 'Buruk') || str_contains($status, 'Pendek') => 'bg-red-50 text-red-600 border-red-100',
                            str_contains($status, 'Lebih') || str_contains($status, 'Obesitas') => 'bg-indigo-50 text-indigo-600 border-indigo-100',
                            default => 'bg-slate-50 text-slate-400 border-slate-100',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors group">
                        <td class="px-10 py-6">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center font-black text-sm group-hover:bg-slate-900 group-hover:text-white transition-all duration-500">
                                    {{ $initials }}
                                </div>
                                <div>
                                    <span class="block text-base font-black text-slate-900 tracking-tight">{{ $record->patient?->full_name ?? '-' }}</span>
                                    <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: #{{ $record->patient_id }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-sm font-black text-slate-700 tracking-tight">{{ $record->patient?->posyandu?->name ?? '-' }}</span>
                        </td>
                        <td class="px-10 py-6">
                            @if($status)
                            <span @class(["inline-flex items-center px-4 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest border", $badgeStyles])>
                                {{ $status }}
                            </span>
                            @else
                            <span class="text-[10px] text-slate-300 font-black uppercase">Tanpa Data</span>
                            @endif
                        </td>
                        <td class="px-10 py-6">
                            <span class="text-sm font-black text-slate-600 tracking-tight">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}</span>
                        </td>
                        <td class="px-10 py-6 text-right">
                            <a href="{{ route('admin.patients.show', $record->patient_id) }}"
                               class="w-10 h-10 inline-flex items-center justify-center rounded-xl bg-slate-50 text-slate-300 hover:bg-teal-600 hover:text-white transition-all">
                                <span class="material-symbols-outlined">arrow_forward</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-10 py-24 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <span class="material-symbols-outlined text-[48px]">search_off</span>
                                </div>
                                <p class="text-[11px] font-black uppercase tracking-[0.3em] text-slate-300">Belum ada riwayat pemeriksaan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ── Global Chart Defaults ──
    Chart.defaults.font.family = "'Public Sans', sans-serif";
    Chart.defaults.font.weight = '700';
    Chart.defaults.color = '#64748b';

    let trendChart = null;
    let donutChart = null;

    function initCharts(data = null) {
        // Destroy existing
        if (trendChart) trendChart.destroy();
        if (donutChart) donutChart.destroy();

        // Data from event or initial load
        const labels = data ? data.trendLabels : @json($trendLabels);
        const normal = data ? data.trendNormal : @json($trendNormal);
        const stunting = data ? data.trendStunting : @json($trendStunting);
        const risk = data ? data.trendRisk : @json($trendRisk);
        const nutLabels = data ? data.nutritionLabels : @json($nutritionLabels);
        const nutData = data ? data.nutritionData : @json($nutritionData);

        // ── Trend Chart ──
        const trendCtx = document.getElementById('nutritionTrendChart');
        if (trendCtx) {
            trendChart = new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [
                        {
                            label: 'Normal',
                            data: normal,
                            borderColor: '#059669',
                            backgroundColor: 'rgba(5,150,105,0.05)',
                            borderWidth: 4,
                            pointBackgroundColor: '#059669',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Risiko',
                            data: risk,
                            borderColor: '#f59e0b',
                            backgroundColor: 'rgba(245,158,11,0.05)',
                            borderWidth: 4,
                            pointBackgroundColor: '#f59e0b',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            tension: 0.4,
                            fill: true,
                        },
                        {
                            label: 'Stunting',
                            data: stunting,
                            borderColor: '#ef4444',
                            backgroundColor: 'rgba(239,68,68,0.03)',
                            borderWidth: 4,
                            pointBackgroundColor: '#ef4444',
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            pointRadius: 6,
                            tension: 0.4,
                            fill: true,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { font: { size: 12, weight: '800' }, color: '#94a3b8' } },
                        y: { beginAtZero: true, grid: { color: '#f1f5f9' }, ticks: { precision: 0 } }
                    }
                }
            });
        }

        // ── Donut Chart ──
        const donutCtx = document.getElementById('nutritionDonutChart');
        if (donutCtx && nutData.length > 0 && nutData.some(v => v > 0)) {
            donutChart = new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: nutLabels,
                    datasets: [{
                        data: nutData,
                        backgroundColor: ['#059669','#d97706','#7c3aed','#dc2626','#64748b'],
                        borderWidth: 4,
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
    }

    // Initial load
    initCharts();

    // Listen for Livewire updates
    window.addEventListener('charts-updated', event => {
        initCharts(event.detail);
    });
});
</script>
@endpush
