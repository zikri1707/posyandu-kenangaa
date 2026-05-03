<div class="max-w-[1440px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col sm:flex-row sm:items-end justify-between gap-4">
        <div>
            <h1 class="text-display text-3xl font-bold">Analytics & Insights</h1>
            <p class="text-sm text-slate-500 mt-1 flex items-center gap-2">
                <span class="material-symbols-outlined text-[14px]">info</span>
                Data agregat periode Januari – Desember {{ $selectedYear }}
                @if(auth()->user()->posyandu)
                    · {{ auth()->user()->posyandu->name }}
                @endif
                @if($lastUpdated)
                    · Diperbarui {{ $lastUpdated }}
                @endif
            </p>
        </div>
        <div class="flex items-center gap-3 flex-wrap">
            {{-- Refresh Button --}}
            <button wire:click="refreshStats" wire:loading.attr="disabled"
                    class="h-10 w-10 flex items-center justify-center bg-white border border-slate-200 rounded-xl text-slate-600 hover:bg-slate-50 transition-all shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[20px] transition-transform duration-700" wire:loading.class="rotate-180">refresh</span>
            </button>

            {{-- Year Selector --}}
            <div class="flex items-center bg-white border border-slate-200 rounded-xl px-3 py-2 gap-2 shadow-sm">
                <span class="material-symbols-outlined text-slate-400 text-[18px]">calendar_today</span>
                <select wire:model.live="selectedYear"
                        class="bg-transparent border-none focus:ring-0 text-sm font-semibold text-slate-700 p-0 pr-6 cursor-pointer">
                    @foreach($years as $y)
                        <option value="{{ $y }}">{{ $y }}</option>
                    @endforeach
                </select>
            </div>
            {{-- Export buttons --}}
            <a href="{{ route('admin.reports.index') }}"
               class="h-10 px-4 flex items-center gap-2 bg-white border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 hover:bg-slate-50 transition-colors shadow-sm">
                <span class="material-symbols-outlined text-teal-600 text-[18px]">file_download</span>
                Export Excel
            </a>
            <a href="{{ route('admin.reports.index') }}"
               class="h-10 px-4 flex items-center gap-2 bg-teal-600 text-white rounded-xl text-sm font-bold hover:bg-teal-700 active:scale-95 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">picture_as_pdf</span>
                Download PDF
            </a>
        </div>
    </div>

    {{-- ── Overview Stats ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

        {{-- Total Balita --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-11 h-11 bg-teal-50 rounded-xl flex items-center justify-center text-teal-600">
                    <span class="material-symbols-outlined text-[22px]">child_care</span>
                </div>
                <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded-full">Terdaftar</span>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Total Balita</p>
            <h4 class="text-4xl font-black text-slate-900">{{ number_format($totalBalita) }}</h4>
            <p class="text-xs text-slate-400 mt-2">
                @if(auth()->user()->isSuperAdmin())
                    Di semua posyandu
                @else
                    Posyandu {{ auth()->user()->posyandu?->name ?? '-' }}
                @endif
            </p>
        </div>

        {{-- Stunting Rate --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-11 h-11 bg-red-50 rounded-xl flex items-center justify-center text-red-500">
                    <span class="material-symbols-outlined text-[22px]" style="font-variation-settings:'FILL' 1;">warning</span>
                </div>
                <span class="text-xs font-bold {{ $stuntingRate >= 14 ? 'text-red-600 bg-red-50' : 'text-green-600 bg-green-50' }} px-2 py-1 rounded-full">
                    Target &lt; 14%
                </span>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Angka Stunting</p>
            <h4 class="text-4xl font-black {{ $stuntingRate >= 14 ? 'text-red-600' : 'text-slate-900' }}">{{ $stuntingRate }}%</h4>
            <p class="text-xs text-slate-400 mt-2">Dari balita yang diperiksa</p>
        </div>

        {{-- Cakupan Imunisasi --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-11 h-11 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600">
                    <span class="material-symbols-outlined text-[22px]">vaccines</span>
                </div>
                <span class="text-xs font-bold {{ $cakupanImunisasi >= 90 ? 'text-green-600 bg-green-50' : 'text-amber-600 bg-amber-50' }} px-2 py-1 rounded-full">
                    {{ $cakupanImunisasi >= 90 ? 'On Track' : 'Perlu Perhatian' }}
                </span>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Cakupan Imunisasi</p>
            <h4 class="text-4xl font-black text-slate-900">{{ $cakupanImunisasi }}%</h4>
            <p class="text-xs text-slate-400 mt-2">Balita dengan catatan imunisasi</p>
        </div>

        {{-- Kader Aktif --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-5 shadow-sm hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start mb-4">
                <div class="w-11 h-11 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600">
                    <span class="material-symbols-outlined text-[22px]">volunteer_activism</span>
                </div>
            </div>
            <p class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-1">Kader Aktif</p>
            <h4 class="text-4xl font-black text-slate-900">{{ $kaderAktif }}</h4>
            <p class="text-xs text-slate-400 mt-2">Admin, staff & tenaga medis</p>
        </div>

    </div>

    {{-- ── Charts Row ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        {{-- Nutrition Trend Line Chart --}}
        <div class="lg:col-span-2 bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h5 class="text-base font-bold text-slate-900">Tren Gizi Balita (12 Bulan)</h5>
                    <p class="text-sm text-slate-500 mt-0.5">Perbandingan Normal vs Stunting — {{ $selectedYear }}</p>
                </div>
                <div class="flex gap-4">
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-teal-600"></span>
                        <span class="text-xs font-semibold text-slate-600">Normal</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="text-xs font-semibold text-slate-600">Stunting</span>
                    </div>
                </div>
            </div>
            <div class="relative h-56">
                <canvas id="nutritionTrendChart" wire:ignore></canvas>
                <div wire:loading wire:target="selectedYear, refreshStats" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-10">
                    <div class="flex flex-col items-center gap-2">
                        <span class="material-symbols-outlined text-teal-600 animate-spin">refresh</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-widest">Loading...</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Nutrition Distribution Donut --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <h5 class="text-base font-bold text-slate-900 mb-1">Distribusi Status Gizi</h5>
            <p class="text-sm text-slate-500 mb-5">Balita tahun {{ $selectedYear }}</p>
            <div class="relative flex justify-center mb-5">
                <canvas id="nutritionDonutChart" width="180" height="180" style="max-width:180px;max-height:180px;" wire:ignore></canvas>
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                    <span class="text-2xl font-black text-slate-900">{{ $totalBalita }}</span>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tight">Total</span>
                </div>
                <div wire:loading wire:target="selectedYear, refreshStats" class="absolute inset-0 bg-white/60 backdrop-blur-[1px] flex items-center justify-center z-10 rounded-full">
                    <span class="material-symbols-outlined text-teal-600 animate-spin text-[20px]">refresh</span>
                </div>
            </div>
            <div class="space-y-2.5">
                @php
                    $donutColors = ['#16A34A','#D97706','#7C3AED','#DC2626','#94a3b8'];
                    $i = 0;
                @endphp
                @foreach($nutritionLabels as $label)
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:{{ $donutColors[$i % count($donutColors)] }}"></span>
                        <span class="text-sm font-medium text-slate-700">{{ $label }}</span>
                    </div>
                    <span class="text-sm font-bold text-slate-900">{{ $nutritionData[$i] ?? 0 }}</span>
                </div>
                @php $i++ @endphp
                @endforeach
                @if(empty($nutritionLabels))
                <p class="text-xs text-slate-400 text-center py-4">Belum ada data gizi</p>
                @endif
            </div>
        </div>

    </div>

    {{-- ── Stunting & Demographics ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Stunting by Posyandu --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6 shadow-sm">
            <div class="flex justify-between items-center mb-6">
                <h5 class="text-base font-bold text-slate-900">Angka Stunting per Posyandu</h5>
                <span class="text-xs text-slate-400 font-medium">{{ $selectedYear }}</span>
            </div>
            <div class="space-y-5">
                @forelse($stuntingByPosyandu as $item)
                <div>
                    <div class="flex justify-between text-sm font-semibold mb-1.5">
                        <span class="text-slate-700 truncate max-w-[200px]">{{ $item['name'] }}</span>
                        <span class="{{ $item['text'] }} font-bold">{{ $item['rate'] }}%</span>
                    </div>
                    <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                        <div class="{{ $item['color'] }} h-full rounded-full transition-all duration-700"
                             style="width: {{ $item['width'] }}%"></div>
                    </div>
                    <p class="text-xs text-slate-400 mt-1">{{ $item['stunting'] }} dari {{ $item['total'] }} balita</p>
                </div>
                @empty
                <div class="flex flex-col items-center gap-3 py-8 text-slate-400">
                    <span class="material-symbols-outlined text-[40px] text-slate-200">bar_chart</span>
                    <p class="text-sm">Belum ada data stunting</p>
                </div>
                @endforelse
            </div>
        </div>

        {{-- Demographics --}}
        <div class="bg-slate-50 border border-slate-200 rounded-2xl p-6 shadow-sm flex flex-col">
            <h5 class="text-base font-bold text-slate-900 mb-5">Demografi Balita</h5>
            <div class="grid grid-cols-3 gap-3 flex-1">
                <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-black text-teal-600 mb-1">{{ number_format($usia0_12) }}</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">0–12 Bln</span>
                    <p class="text-[10px] text-teal-600 mt-1 font-semibold">Bayi</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-black text-teal-600 mb-1">{{ number_format($usia12_24) }}</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">12–24 Bln</span>
                    <p class="text-[10px] text-teal-600 mt-1 font-semibold">Baduta</p>
                </div>
                <div class="bg-white rounded-xl border border-slate-200 p-4 flex flex-col items-center justify-center text-center">
                    <span class="text-3xl font-black text-teal-600 mb-1">{{ number_format($usia24plus) }}</span>
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">24+ Bln</span>
                    <p class="text-[10px] text-teal-600 mt-1 font-semibold">Balita</p>
                </div>
            </div>
            {{-- Insight box --}}
            <div class="mt-5 p-4 bg-teal-700 rounded-xl text-white flex items-start gap-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex-shrink-0 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[18px]">info</span>
                </div>
                <p class="text-sm leading-relaxed">
                    <strong>Insight:</strong>
                    @if($usia12_24 > $usia0_12 && $usia12_24 > $usia24plus)
                        Kelompok Baduta (12–24 bln) mendominasi. Pastikan stok vaksin DPT-HB-Hib mencukupi.
                    @elseif($stuntingRate >= 10)
                        Angka stunting {{ $stuntingRate }}% perlu perhatian khusus. Tingkatkan program PMT.
                    @else
                        Data gizi balita dalam kondisi baik. Pertahankan program posyandu rutin.
                    @endif
                </p>
            </div>
        </div>

    </div>

    {{-- ── Recent Records Table ── --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-200 flex justify-between items-center">
            <h5 class="text-base font-bold text-slate-900">Pemeriksaan Terbaru</h5>
            <a href="{{ route('admin.medical-records.index') }}"
               class="text-sm font-bold text-teal-600 hover:text-teal-800 flex items-center gap-1 transition-colors">
                Lihat Semua
                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
            </a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead class="bg-slate-50 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Nama Balita</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Unit Posyandu</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Status Gizi</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3.5 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentRecords as $record)
                    @php
                        $initials = strtoupper(substr($record->patient?->full_name ?? 'X', 0, 1) .
                                    (strpos($record->patient?->full_name ?? '', ' ') !== false
                                        ? substr(strrchr($record->patient?->full_name ?? '', ' '), 1, 1)
                                        : ''));
                        $status = $record->nutrition_status;
                        $badgeClass = match($status) {
                            'Normal'              => 'bg-green-100 text-green-700',
                            'Gizi Kurang'         => 'bg-amber-100 text-amber-700',
                            'Gizi Lebih'          => 'bg-purple-100 text-purple-700',
                            'Gizi Buruk/Stunting' => 'bg-red-100 text-red-700',
                            default               => 'bg-slate-100 text-slate-500',
                        };
                        $badgeIcon = match($status) {
                            'Normal'              => 'check_circle',
                            'Gizi Buruk/Stunting' => 'error',
                            'Gizi Kurang'         => 'warning',
                            default               => 'remove',
                        };
                    @endphp
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-teal-100 text-teal-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                    {{ $initials }}
                                </div>
                                <span class="font-semibold text-sm text-slate-800">{{ $record->patient?->full_name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600">
                            {{ $record->patient?->posyandu?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            @if($status)
                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                <span class="material-symbols-outlined text-[13px]">{{ $badgeIcon }}</span>
                                {{ $status }}
                            </span>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-slate-600 font-medium">
                            {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <a href="{{ route('admin.patients.show', $record->patient_id) }}"
                               class="text-xs font-bold text-teal-600 hover:text-teal-800 uppercase transition-colors">
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <span class="material-symbols-outlined text-[40px] text-slate-200">search_off</span>
                                <p class="text-sm">Belum ada data pemeriksaan</p>
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
<script>document.addEventListener('DOMContentLoaded', function () {
    // ── Nutrition Trend Line Chart ──────────────────────────────
    const trendCtx = document.getElementById('nutritionTrendChart');
    if (trendCtx) {
        new Chart(trendCtx, {
            type: 'line',
            data: {
                labels: @json($trendLabels),
                datasets: [
                    {
                        label: 'Normal',
                        data: @json($trendNormal),
                        borderColor: '#0d9488',
                        backgroundColor: 'rgba(13,148,136,0.08)',
                        borderWidth: 3,
                        pointBackgroundColor: '#0d9488',
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                    },
                    {
                        label: 'Stunting',
                        data: @json($trendStunting),
                        borderColor: '#ef4444',
                        backgroundColor: 'rgba(239,68,68,0.06)',
                        borderWidth: 3,
                        pointBackgroundColor: '#ef4444',
                        pointRadius: 4,
                        tension: 0.4,
                        fill: true,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1e293b',
                        titleFont: { weight: 'bold' },
                        padding: 10,
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11, weight: '600' }, color: '#94a3b8' }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 11 }, color: '#94a3b8', precision: 0 }
                    }
                }
            }
        });
    }

    // ── Nutrition Donut Chart ───────────────────────────────────
    const donutCtx = document.getElementById('nutritionDonutChart');
    if (donutCtx) {
        const labels = @json($nutritionLabels);
        const data   = @json($nutritionData);
        const colors = ['#16A34A','#D97706','#7C3AED','#DC2626','#94a3b8'];

        if (data.length > 0 && data.some(v => v > 0)) {
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors.slice(0, data.length),
                        borderWidth: 3,
                        borderColor: '#ffffff',
                        hoverOffset: 6,
                    }]
                },
                options: {
                    responsive: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#1e293b',
                            padding: 10,
                        }
                    }
                }
            });
        } else {
            // Empty state
            new Chart(donutCtx, {
                type: 'doughnut',
                data: {
                    datasets: [{ data: [1], backgroundColor: ['#e2e8f0'], borderWidth: 0 }]
                },
                options: { responsive: false, cutout: '72%', plugins: { legend: { display: false }, tooltip: { enabled: false } } }
            });
        }
    }
});

// Re-init charts on Livewire update
document.addEventListener('livewire:updated', function () {
    // Destroy existing charts before re-creating
    ['nutritionTrendChart', 'nutritionDonutChart'].forEach(id => {
        const canvas = document.getElementById(id);
        if (canvas) {
            const existing = Chart.getChart(canvas);
            if (existing) existing.destroy();
        }
    });
    // Re-trigger DOMContentLoaded logic
    document.dispatchEvent(new Event('DOMContentLoaded'));
});
</script>
@endpush
