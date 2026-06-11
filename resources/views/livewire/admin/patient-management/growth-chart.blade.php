@php
    $isBalitaGrowthCategory = in_array($patient->category, ['bayi', 'baduta', 'balita']);
    $isMale = strtoupper($patient->gender) === 'L' || strtoupper($patient->gender) === 'M';
    $latestRecord = $patient->medicalRecords()->latest()->first();

    $receivedCount = 0;
    $totalCount = 0;
    if ($isBalitaGrowthCategory) {
        $immunizationStatus = $patient->getImmunizationStatus();
        foreach ($immunizationStatus as $group) {
            foreach ($group['vaccines'] as $vax) {
                if ($vax['is_due']) {
                    $totalCount++;
                }
                if ($vax['received']) {
                    $receivedCount++;
                }
            }
        }
        if ($totalCount === 0) {
            $totalCount = 12;
        }
    }
@endphp

<div wire:key="growth-chart-root" class="w-full max-w-none space-y-12">
@if($isBalitaGrowthCategory)

    {{-- ── Section 1: Riwayat Pemeriksaan (Top Section) ── --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-slate-100">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-400">
                    <span class="material-symbols-outlined text-[24px]">history</span>
                </div>
                <div>
                    <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Riwayat Pemeriksaan</h3>
                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">Data Antropometri Berkala</p>
                </div>
            </div>
            <a href="#" class="px-6 py-2.5 bg-slate-50 hover:bg-slate-100 rounded-full text-[10px] font-black text-slate-500 uppercase tracking-widest transition-all">Lihat Selengkapnya →</a>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-50">
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4">Tanggal Kunjungan</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Berat Badan</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tinggi Badan</th>
                        <th class="pb-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4">Status Gizi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($patient->medicalRecords()->latest()->limit(5)->get() as $record)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="py-6 pl-4">
                            <p class="text-[12px] font-black text-slate-700 mb-0.5">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d F Y') }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pemeriksaan Rutin</p>
                        </td>
                        <td class="py-6">
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-slate-900">{{ $record->weight }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase">kg</span>
                            </div>
                        </td>
                        <td class="py-6">
                            <div class="flex items-baseline gap-1">
                                <span class="text-lg font-black text-slate-900">{{ $record->height }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase">cm</span>
                            </div>
                        </td>
                        <td class="py-6 text-right pr-4">
                            <span @class([
                                'px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest inline-block shadow-sm',
                                'bg-emerald-50 text-emerald-600 border border-emerald-100' => str_contains($record->nutrition_status ?? '', 'Normal') || str_contains($record->nutrition_status ?? '', 'Baik'),
                                'bg-amber-50 text-amber-600 border border-amber-100' => str_contains($record->nutrition_status ?? '', 'Kurang'),
                                'bg-red-50 text-red-600 border border-red-100' => str_contains($record->nutrition_status ?? '', 'Buruk'),
                                'bg-slate-50 text-slate-400 border border-slate-100' => !$record->nutrition_status,
                            ])>
                                {{ $record->nutrition_status ?: 'Data N/A' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="py-12 text-center text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em]">Belum ada data pemeriksaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Section 2: Quick Stats Row ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex items-center gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[32px]">vaccines</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Capaian Imunisasi</p>
                <p class="text-2xl font-black text-slate-800">{{ $receivedCount }} <span class="text-sm text-slate-400">/ {{ $totalCount }}</span></p>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl group">
            <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-teal-500/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-4">
                    <div class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-lg border border-white/10 w-fit">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-teal-400">Digital Pass</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Terakhir Diperiksa</p>
                        <p class="text-sm font-black italic text-slate-200">
                            {{ $latestRecord?->visit_date ? \Carbon\Carbon::parse($latestRecord->visit_date)->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-teal-400 text-[40px] opacity-40">qr_code_2</span>
            </div>
        </div>

        <div class="bg-linear-to-br from-amber-50 to-orange-50 border border-orange-100 rounded-[2.5rem] p-8 relative overflow-hidden group shadow-sm flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white text-orange-500 flex items-center justify-center shadow-md shrink-0">
                <span class="material-symbols-outlined text-[32px]">lightbulb</span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black text-orange-700 uppercase tracking-[0.2em] mb-1">Insight Pertumbuhan</p>
                <p class="text-xs text-slate-600 font-bold leading-relaxed">
                    {{ $latestRecord?->nutrition_status ? 'Status terakhir: ' . $latestRecord->nutrition_status : 'Tambahkan pemeriksaan untuk melihat insight terbaru.' }}
                </p>
            </div>
        </div>
    </div>

    {{-- ── Section 3: Grafik Pertumbuhan (Bottom Section) ── --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden relative group"
         x-data="{ 
            chart: null,
            hasData: true,
            initChart(chartData) {
                const ctx = document.getElementById('growthChartBottom');
                if (!ctx) return;
                
                if (!window.Chart) {
                    setTimeout(() => this.initChart(chartData), 50);
                    return;
                }
                
                // Destroy existing chart on canvas using Chart.getChart to prevent canvas reuse error
                const existingChart = window.Chart.getChart(ctx);
                if (existingChart) {
                    existingChart.destroy();
                }
                
                if (this.chart) {
                    this.chart.destroy();
                    this.chart = null;
                }
                
                const data = chartData || null;
                if (!data || !data.datasets || data.datasets.length === 0) {
                    this.hasData = false;
                    return;
                }

                this.hasData = true;

                // Intercept and style datasets for soft-colored light-mode reference bands
                const rawDatasets = data.datasets;
                const medianDataset = rawDatasets.find(d => d.label === 'Median');
                const plus2Dataset = rawDatasets.find(d => d.label === '+2 SD');
                const minus2Dataset = rawDatasets.find(d => d.label === '-2 SD');
                const plus3Dataset = rawDatasets.find(d => d.label === '+3 SD');
                const minus3Dataset = rawDatasets.find(d => d.label === '-3 SD');
                const childDataset = rawDatasets.find(d => d.label.includes('Anak') || d.label.includes('Badan'));

                const styledDatasets = [];

                // 1. +3 SD (index 0) - Fills to +2 SD (index 1) with amber warning
                if (plus3Dataset) {
                    plus3Dataset.borderColor = '#f43f5e';
                    plus3Dataset.borderWidth = 1.5;
                    plus3Dataset.pointRadius = 0;
                    plus3Dataset.pointHoverRadius = 0;
                    plus3Dataset.fill = 1;
                    plus3Dataset.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                    styledDatasets.push(plus3Dataset);
                }

                // 2. +2 SD (index 1) - Fills to -2 SD (index 3) with healthy emerald green
                if (plus2Dataset) {
                    plus2Dataset.borderColor = '#f59e0b';
                    plus2Dataset.borderWidth = 1.5;
                    plus2Dataset.pointRadius = 0;
                    plus2Dataset.pointHoverRadius = 0;
                    plus2Dataset.fill = 3;
                    plus2Dataset.backgroundColor = 'rgba(16, 185, 129, 0.12)';
                    styledDatasets.push(plus2Dataset);
                }

                // 3. Median (index 2) - No fill, drawn clearly in the center
                if (medianDataset) {
                    medianDataset.borderColor = '#10b981';
                    medianDataset.borderWidth = 2.5;
                    medianDataset.pointRadius = 0;
                    medianDataset.pointHoverRadius = 0;
                    medianDataset.fill = false;
                    styledDatasets.push(medianDataset);
                }

                // 4. -2 SD (index 3) - Fills to -3 SD (index 4) with amber warning
                if (minus2Dataset) {
                    minus2Dataset.borderColor = '#f59e0b';
                    minus2Dataset.borderWidth = 1.5;
                    minus2Dataset.pointRadius = 0;
                    minus2Dataset.pointHoverRadius = 0;
                    minus2Dataset.fill = 4;
                    minus2Dataset.backgroundColor = 'rgba(245, 158, 11, 0.08)';
                    styledDatasets.push(minus2Dataset);
                }

                // 5. -3 SD (index 4) - Fills to origin (bottom) with rose danger
                if (minus3Dataset) {
                    minus3Dataset.borderColor = '#ef4444';
                    minus3Dataset.borderWidth = 1.5;
                    minus3Dataset.pointRadius = 0;
                    minus3Dataset.pointHoverRadius = 0;
                    minus3Dataset.fill = 'origin';
                    minus3Dataset.backgroundColor = 'rgba(239, 68, 68, 0.08)';
                    styledDatasets.push(minus3Dataset);
                }

                // 6. Child dataset (index 5) - High contrast, styled by gender theme
                if (childDataset) {
                    const isMaleGender = {{ $isMale ? 'true' : 'false' }};
                    const lineCol = isMaleGender ? '#0d9488' : '#be185d';
                    childDataset.borderColor = lineCol;
                    childDataset.borderWidth = 4;
                    childDataset.pointRadius = 6;
                    childDataset.pointHoverRadius = 9;
                    childDataset.pointBackgroundColor = lineCol;
                    childDataset.pointBorderColor = '#ffffff';
                    childDataset.pointBorderWidth = 2;
                    childDataset.fill = false;
                    childDataset.tension = 0.25;
                    styledDatasets.push(childDataset);
                }

                data.datasets = styledDatasets;

                this.chart = new window.Chart(ctx, {
                    type: 'line',
                    data: data,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        interaction: { intersect: false, mode: 'index' },
                        scales: {
                            x: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#475569', font: { size: 10, weight: '700' } },
                                title: { display: true, text: 'UMUR (BULAN)', color: '#1e293b', font: { weight: '800', size: 11, family: 'Inter' } }
                            },
                            y: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#475569', font: { size: 10, weight: '700' } },
                                suggestedMin: data.datasets.some(d => d.label.includes('Tinggi')) ? 40 : 0,
                                title: { display: true, text: data.datasets.some(d => d.label.includes('Tinggi')) ? 'TINGGI (CM)' : 'BERAT (KG)', color: '#1e293b', font: { weight: '800', size: 11, family: 'Inter' } }
                            }
                        },
                        plugins: {
                            legend: { 
                                position: 'bottom', 
                                labels: { 
                                    boxWidth: 8, 
                                    boxHeight: 8, 
                                    usePointStyle: true, 
                                    pointStyle: 'circle', 
                                    color: '#475569', 
                                    padding: 25, 
                                    font: { weight: '700', size: 10 } 
                                } 
                            },
                            tooltip: { 
                                backgroundColor: 'rgba(15, 23, 42, 0.95)', 
                                padding: 16, 
                                cornerRadius: 16, 
                                usePointStyle: true, 
                                titleFont: { size: 13, weight: '800' }, 
                                bodyFont: { size: 12, weight: '600' },
                                filter: function(tooltipItem) {
                                    const label = tooltipItem.dataset.label || '';
                                    return label.includes('Anak') || label.includes('Badan') || label === 'Median';
                                },
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            const isHeight = context.chart.options.scales.y.title.text.includes('TINGGI') || context.dataset.label.includes('Tinggi');
                                            label += context.parsed.y + (isHeight ? ' cm' : ' kg');
                                        }
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            }
         }"
         x-init="
            const init = () => {
                if (window.Chart) {
                    initChart($wire.chartData);
                } else {
                    setTimeout(init, 50);
                }
            };
            setTimeout(init, 100);
            
            $wire.on('chart-updated', (data) => {
                const rawData = Array.isArray(data) ? data[0] : data;
                initChart(rawData);
            });
         "
    >
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 bg-linear-to-b from-teal-500 to-emerald-600 rounded-full"></div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight uppercase">Grafik Analisis Pertumbuhan WHO</h3>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.3em] ml-5">Visualisasi Tren Antropometri Anak</p>
            </div>
            
            <div class="flex items-center p-1.5 bg-slate-50/50 backdrop-blur-md rounded-4xl border border-slate-100 shadow-inner">
                <button wire:click="switchChart('wfa')" 
                    @class([
                        'flex items-center gap-3 px-8 py-3 text-[11px] font-black uppercase tracking-widest rounded-full transition-all duration-500',
                        'bg-white shadow-[0_4px_15px_rgba(0,0,0,0.05)] text-teal-600 ring-1 ring-slate-100 scale-100' => $activeChart === 'wfa',
                        'text-slate-400 hover:text-slate-600 hover:bg-white/40 scale-95 opacity-70' => $activeChart !== 'wfa'
                    ])>
                    <span @class([
                        'w-8 h-8 rounded-xl flex items-center justify-center transition-colors',
                        'bg-teal-50 text-teal-600' => $activeChart === 'wfa',
                        'bg-slate-100 text-slate-400' => $activeChart !== 'wfa'
                    ])>
                        <span class="material-symbols-outlined text-[20px]">monitor_weight</span>
                    </span>
                    BB/U
                </button>
                <button wire:click="switchChart('hfa')" 
                    @class([
                        'flex items-center gap-3 px-8 py-3 text-[11px] font-black uppercase tracking-widest rounded-full transition-all duration-500',
                        'bg-white shadow-[0_4px_15px_rgba(0,0,0,0.05)] text-teal-600 ring-1 ring-slate-100 scale-100' => $activeChart === 'hfa',
                        'text-slate-400 hover:text-slate-600 hover:bg-white/40 scale-95 opacity-70' => $activeChart !== 'hfa'
                    ])>
                    <span @class([
                        'w-8 h-8 rounded-xl flex items-center justify-center transition-colors',
                        'bg-teal-50 text-teal-600' => $activeChart === 'hfa',
                        'bg-slate-100 text-slate-400' => $activeChart !== 'hfa'
                    ])>
                        <span class="material-symbols-outlined text-[20px]">straighten</span>
                    </span>
                    TB/U
                </button>
            </div>
        </div>
        
        <div class="relative z-10 min-h-162.5 h-[75vh] w-full" wire:ignore>
            <canvas id="growthChartBottom" class="h-full! w-full!" x-show="hasData"></canvas>
        </div>
        
        <div x-show="!hasData" class="absolute inset-0 flex flex-col items-center justify-center p-12 text-center z-20 bg-white/95 rounded-[3rem]">
            <div class="w-24 h-24 rounded-[2.5rem] bg-slate-50 flex items-center justify-center text-slate-400 mb-8 border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-[48px]">query_stats</span>
            </div>
            <h4 class="text-xl font-black text-slate-800 tracking-tight">Belum Ada Data Pengukuran</h4>
            <p class="text-xs text-slate-400 font-bold max-w-75 mt-3 leading-relaxed">Grafik pertumbuhan akan tersedia setelah data antropometri ditambahkan.</p>
        </div>

        <div wire:loading wire:target="switchChart" class="absolute inset-0 bg-white/60 backdrop-blur-md flex items-center justify-center z-30 rounded-[3rem]">
            <div class="flex flex-col items-center gap-5">
                <div class="w-12 h-12 border-4 border-teal-500 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-[11px] font-black text-teal-600 uppercase tracking-[0.4em]">Sinkronisasi Analisis...</p>
            </div>
        </div>
    </div>
@endif
</div>
