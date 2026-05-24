<div wire:key="growth-chart-root" class="w-full space-y-12">
    
    @php
        $isMale = strtoupper($patient->gender) === 'L' || strtoupper($patient->gender) === 'M';
        $themeColor = $isMale ? 'bg-sky-600' : 'bg-pink-600';
    @endphp

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
                    @if($patient->category === 'lansia')
                    <tr class="border-b border-slate-50">
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4">Tanggal Kunjungan</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Berat / Tinggi</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tensi (BP)</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Gula Darah</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Asam Urat</th>
                        <th class="pb-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4">Kolesterol</th>
                    </tr>
                    @else
                    <tr class="border-b border-slate-50">
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest pl-4">Tanggal Kunjungan</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Berat Badan</th>
                        <th class="pb-6 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tinggi Badan</th>
                        <th class="pb-6 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest pr-4">Status Gizi</th>
                    </tr>
                    @endif
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($patient->medicalRecords()->latest()->limit(5)->get() as $record)
                    <tr class="group hover:bg-slate-50/50 transition-colors">
                        <td class="py-6 pl-4">
                            <p class="text-[12px] font-black text-slate-700 mb-0.5">{{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d F Y') }}</p>
                            <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Pemeriksaan Rutin</p>
                        </td>
                        @if($patient->category === 'lansia')
                        <td class="py-6">
                            <div class="flex flex-col gap-0.5">
                                <span class="text-sm font-black text-slate-900">{{ $record->weight ?? '-' }} <span class="text-[10px] text-slate-400 font-bold uppercase">kg</span></span>
                                <span class="text-xs font-bold text-slate-500">{{ $record->height ?? '-' }} <span class="text-[9px] text-slate-400 font-bold uppercase">cm</span></span>
                            </div>
                        </td>
                        <td class="py-6">
                            <span class="text-sm font-black text-slate-900">{{ $record->systolic_bp && $record->diastolic_bp ? $record->systolic_bp . '/' . $record->diastolic_bp : '-' }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase">mmHg</span>
                        </td>
                        <td class="py-6">
                            <span class="text-sm font-black text-slate-900">{{ $record->blood_sugar ?? '-' }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase">mg/dL</span>
                        </td>
                        <td class="py-6">
                            <span class="text-sm font-black text-slate-900">{{ $record->uric_acid ?? '-' }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase">mg/dL</span>
                        </td>
                        <td class="py-6 text-right pr-4">
                            <span class="text-sm font-black text-slate-900">{{ $record->cholesterol ?? '-' }}</span>
                            <span class="text-[10px] font-black text-slate-400 uppercase">mg/dL</span>
                        </td>
                        @else
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
                        @endif
                    </tr>
                    @empty
                    <tr><td colspan="{{ $patient->category === 'lansia' ? 6 : 4 }}" class="py-12 text-center text-[11px] text-slate-400 font-bold uppercase tracking-[0.3em]">Belum ada data pemeriksaan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Section 2: Quick Stats Row ── --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if($patient->category === 'lansia')
        @php
            $latestRecord = $patient->medicalRecords()->latest()->first();
        @endphp
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex items-center gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-orange-50 text-orange-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform shrink-0">
                <span class="material-symbols-outlined text-[32px]">favorite</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Tekanan Darah Terakhir</p>
                <p class="text-2xl font-black text-slate-800">
                    {{ $latestRecord && $latestRecord->systolic_bp && $latestRecord->diastolic_bp ? $latestRecord->systolic_bp . '/' . $latestRecord->diastolic_bp : '-' }}
                    <span class="text-xs text-slate-400 uppercase">mmHg</span>
                </p>
            </div>
        </div>

        <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-2xl group">
            <div class="absolute -right-16 -bottom-16 w-48 h-48 bg-orange-500/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 flex items-center justify-between">
                <div class="space-y-4">
                    <div class="px-3 py-1 bg-white/10 backdrop-blur-md rounded-lg border border-white/10 w-fit">
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-orange-400">Posbindu Pass</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-slate-500 uppercase tracking-widest mb-1">Terakhir Diperiksa</p>
                        <p class="text-sm font-black italic text-slate-200">
                            {{ $latestRecord?->visit_date ? \Carbon\Carbon::parse($latestRecord->visit_date)->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-orange-400 text-[40px] opacity-40">qr_code_2</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-orange-50 to-amber-50 border border-orange-100 rounded-[2.5rem] p-8 relative overflow-hidden group shadow-sm flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white text-orange-500 flex items-center justify-center shadow-md shrink-0">
                <span class="material-symbols-outlined text-[32px]">health_and_safety</span>
            </div>
            <div class="min-w-0">
                <p class="text-[10px] font-black text-orange-700 uppercase tracking-[0.2em] mb-1">Penyakit Historis</p>
                <p class="text-xs text-slate-600 font-bold leading-relaxed truncate group-hover:whitespace-normal" title="{{ $patient->historical_diseases }}">{{ $patient->historical_diseases ?: 'Tidak ada riwayat penyakit' }}</p>
            </div>
        </div>
        @else
        <div class="bg-white rounded-[2.5rem] p-8 border border-slate-100 shadow-sm flex items-center gap-6 group">
            <div class="w-16 h-16 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center shadow-inner group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[32px]">vaccines</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-1">Capaian Imunisasi</p>
                <p class="text-2xl font-black text-slate-800">8 <span class="text-sm text-slate-400">/ 12</span></p>
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
                            {{ $patient->medicalRecords()->latest()->first()?->visit_date ? \Carbon\Carbon::parse($patient->medicalRecords()->latest()->first()->visit_date)->translatedFormat('d F Y') : 'N/A' }}
                        </p>
                    </div>
                </div>
                <span class="material-symbols-outlined text-teal-400 text-[40px] opacity-40">qr_code_2</span>
            </div>
        </div>

        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-orange-100 rounded-[2.5rem] p-8 relative overflow-hidden group shadow-sm flex items-center gap-6">
            <div class="w-16 h-16 rounded-2xl bg-white text-orange-500 flex items-center justify-center shadow-md shrink-0">
                <span class="material-symbols-outlined text-[32px]">lightbulb</span>
            </div>
        @endif
    </div>

    @if($patient->category === 'lansia')
    {{-- ── Section 3: Grafik Pemantauan Kesehatan Lansia ── --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden relative group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-orange-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 bg-gradient-to-b from-orange-500 to-amber-600 rounded-full"></div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight uppercase">Grafik Pemantauan Kesehatan Berkala</h3>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.3em] ml-5">Posbindu Lansia - Tren Metrik Fisik & Laboratorium</p>
            </div>
        </div>

        <script>var lansiaChartData = @json($chartData);</script>
        
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 relative z-10"
             x-data="{
                charts: {},
                initCharts() {
                    if (typeof lansiaChartData === 'undefined' || !lansiaChartData.labels || lansiaChartData.labels.length === 0) {
                        return;
                    }
                    
                    const labels = lansiaChartData.labels;
                    const config = {
                        weight: {
                            id: 'chartWeight',
                            label: 'Berat Badan',
                            data: lansiaChartData.weight,
                            color: '#f97316', // orange-500
                            unit: 'kg'
                        },
                        bp: {
                            id: 'chartBP',
                            label: 'Tekanan Darah',
                            systolic: lansiaChartData.systolic,
                            diastolic: lansiaChartData.diastolic,
                            unit: 'mmHg'
                        },
                        sugar: {
                            id: 'chartSugar',
                            label: 'Gula Darah',
                            data: lansiaChartData.blood_sugar,
                            color: '#ef4444', // red-500
                            unit: 'mg/dL'
                        },
                        uric: {
                            id: 'chartUric',
                            label: 'Asam Urat',
                            data: lansiaChartData.uric_acid,
                            color: '#10b981', // emerald-500
                            unit: 'mg/dL'
                        },
                        cholesterol: {
                            id: 'chartCholesterol',
                            label: 'Kolesterol',
                            data: lansiaChartData.cholesterol,
                            color: '#a855f7', // purple-500
                            unit: 'mg/dL'
                        }
                    };

                    // Common line options
                    const commonOptions = {
                        responsive: true,
                        maintainAspectRatio: false,
                        animation: { duration: 1500, easing: 'easeOutQuart' },
                        interaction: { intersect: false, mode: 'index' },
                        scales: {
                            x: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#64748b', font: { size: 10, weight: '700' } }
                            },
                            y: { 
                                grid: { color: 'rgba(15, 23, 42, 0.05)', drawBorder: false }, 
                                ticks: { color: '#64748b', font: { size: 10, weight: '700' } }
                            }
                        },
                        plugins: {
                            legend: { display: false },
                            tooltip: { backgroundColor: 'rgba(15, 23, 42, 1)', padding: 12, cornerRadius: 12 }
                        }
                    };

                    // 1. Weight Chart
                    this.createSingleLineChart(config.weight.id, labels, config.weight.label, config.weight.data, config.weight.color, config.weight.unit, commonOptions);

                    // 2. BP Chart (Double Line: Systolic & Diastolic)
                    this.createBPChart(config.bp.id, labels, config.bp.systolic, config.bp.diastolic, config.bp.unit, commonOptions);

                    // 3. Sugar Chart
                    this.createSingleLineChart(config.sugar.id, labels, config.sugar.label, config.sugar.data, config.sugar.color, config.sugar.unit, commonOptions);

                    // 4. Uric Acid Chart
                    this.createSingleLineChart(config.uric.id, labels, config.uric.label, config.uric.data, config.uric.color, config.uric.unit, commonOptions);

                    // 5. Cholesterol Chart
                    this.createSingleLineChart(config.cholesterol.id, labels, config.cholesterol.label, config.cholesterol.data, config.cholesterol.color, config.cholesterol.unit, commonOptions);
                },
                createSingleLineChart(canvasId, labels, label, data, color, unit, commonOptions) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;
                    
                    if (this.charts[canvasId]) this.charts[canvasId].destroy();
                    
                    const opts = JSON.parse(JSON.stringify(commonOptions));
                    opts.plugins.legend = { display: false };
                    
                    this.charts[canvasId] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [{
                                label: label,
                                data: data,
                                borderColor: color,
                                backgroundColor: color + '15',
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: color,
                                fill: true,
                                tension: 0.3
                            }]
                        },
                        options: opts
                    });
                },
                createBPChart(canvasId, labels, systolic, diastolic, unit, commonOptions) {
                    const ctx = document.getElementById(canvasId);
                    if (!ctx) return;
                    
                    if (this.charts[canvasId]) this.charts[canvasId].destroy();
                    
                    const opts = JSON.parse(JSON.stringify(commonOptions));
                    opts.plugins.legend = { 
                        display: true,
                        position: 'top',
                        labels: { boxWidth: 8, boxHeight: 8, usePointStyle: true, font: { size: 10, weight: '700' } }
                    };
                    
                    this.charts[canvasId] = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: labels,
                            datasets: [
                                {
                                    label: 'Sistolik',
                                    data: systolic,
                                    borderColor: '#ef4444', // red-500
                                    backgroundColor: 'transparent',
                                    borderWidth: 3,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#ef4444',
                                    tension: 0.3
                                },
                                {
                                    label: 'Diastolik',
                                    data: diastolic,
                                    borderColor: '#3b82f6', // blue-500
                                    backgroundColor: 'transparent',
                                    borderWidth: 3,
                                    pointRadius: 4,
                                    pointBackgroundColor: '#3b82f6',
                                    tension: 0.3
                                }
                            ]
                        },
                        options: opts
                    });
                }
             }"
             x-init="setTimeout(() => { initCharts(); }, 300);"
        >
            {{-- Card 1: Weight --}}
            <div class="bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 p-6 flex flex-col h-[320px]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Berat Badan (kg)</span>
                    <span class="text-xs font-black text-orange-600 bg-orange-50 px-3 py-1 rounded-full uppercase tracking-tighter">Posbindu</span>
                </div>
                <div class="flex-1 min-h-0 relative">
                    <canvas id="chartWeight"></canvas>
                </div>
            </div>

            {{-- Card 2: Blood Pressure --}}
            <div class="bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 p-6 flex flex-col h-[320px]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tekanan Darah (mmHg)</span>
                    <span class="text-xs font-black text-red-600 bg-red-50 px-3 py-1 rounded-full uppercase tracking-tighter">Posbindu</span>
                </div>
                <div class="flex-1 min-h-0 relative">
                    <canvas id="chartBP"></canvas>
                </div>
            </div>

            {{-- Card 3: Blood Sugar --}}
            <div class="bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 p-6 flex flex-col h-[320px]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gula Darah (mg/dL)</span>
                    <span class="text-xs font-black text-rose-600 bg-rose-50 px-3 py-1 rounded-full uppercase tracking-tighter">Posbindu</span>
                </div>
                <div class="flex-1 min-h-0 relative">
                    <canvas id="chartSugar"></canvas>
                </div>
            </div>

            {{-- Card 4: Uric Acid --}}
            <div class="bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 p-6 flex flex-col h-[320px]">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Asam Urat (mg/dL)</span>
                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-3 py-1 rounded-full uppercase tracking-tighter">Posbindu</span>
                </div>
                <div class="flex-1 min-h-0 relative">
                    <canvas id="chartUric"></canvas>
                </div>
            </div>

            {{-- Card 5: Cholesterol --}}
            <div class="bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 p-6 flex flex-col h-[320px] lg:col-span-2">
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kolesterol (mg/dL)</span>
                    <span class="text-xs font-black text-purple-600 bg-purple-50 px-3 py-1 rounded-full uppercase tracking-tighter">Posbindu</span>
                </div>
                <div class="flex-1 min-h-0 relative">
                    <canvas id="chartCholesterol"></canvas>
                </div>
            </div>
            
            @if(empty($chartData['labels']))
            <div class="absolute inset-0 bg-white/80 backdrop-blur-md flex flex-col items-center justify-center p-12 text-center z-20 rounded-[3rem]">
                <div class="w-20 h-20 rounded-[2rem] bg-orange-50 flex items-center justify-center text-orange-400 mb-6 shadow-md">
                    <span class="material-symbols-outlined text-[36px]">query_stats</span>
                </div>
                <h4 class="text-lg font-black text-slate-800 tracking-tight">Belum Ada Data Pemantauan</h4>
                <p class="text-xs text-slate-400 font-bold max-w-[280px] mt-2 leading-relaxed">Grafik pemantauan akan otomatis digambar setelah rekam kunjungan lansia ditambahkan.</p>
            </div>
            @endif
        </div>
    </div>
    @else
    {{-- ── Section 3: Grafik Pertumbuhan (Bottom Section) ── --}}
    <div class="bg-white rounded-[3rem] p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] border border-slate-100 overflow-hidden relative group">
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-teal-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-1000"></div>

        <div class="flex flex-col md:flex-row items-center justify-between gap-8 mb-10 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <div class="w-2 h-8 bg-gradient-to-b from-teal-500 to-emerald-600 rounded-full"></div>
                    <h3 class="text-xl font-black text-slate-800 tracking-tight uppercase">Grafik Analisis Pertumbuhan WHO</h3>
                </div>
                <p class="text-[11px] font-bold text-slate-400 uppercase tracking-[0.3em] ml-5">Visualisasi Tren Antropometri Anak</p>
            </div>
            
            <div class="flex items-center p-1.5 bg-slate-50/50 backdrop-blur-md rounded-[2rem] border border-slate-100 shadow-inner">
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

        <script>var initialGrowthData = @json($chartData);</script>
        <div class="relative rounded-[2.5rem] p-8 border border-slate-100 bg-white shadow-sm overflow-hidden h-[550px] transition-all duration-700" 
             x-data="{ 
                chart: null,
                hasData: true,
                initChart(chartData) {
                    const ctx = document.getElementById('growthChartBottom');
                    if (!ctx || !window.Chart) return;
                    if (this.chart) this.chart.destroy();
                    
                    const data = chartData || null;
                    if (!data || !data.datasets || data.datasets.length === 0) {
                        this.hasData = false;
                        return;
                    }

                    this.hasData = true;
                    this.chart = new Chart(ctx, {
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
                setTimeout(() => { if (typeof initialGrowthData !== 'undefined') initChart(initialGrowthData); }, 300);
                $wire.on('chart-updated', (data) => {
                    const rawData = Array.isArray(data) ? data[0] : data;
                    initChart(rawData);
                });
             "
        >
            <div class="relative w-full h-full z-10" wire:ignore>
                <canvas id="growthChartBottom" x-show="hasData"></canvas>
            </div>
            
            <div x-show="!hasData" class="absolute inset-0 flex flex-col items-center justify-center p-12 text-center z-20">
                <div class="w-24 h-24 rounded-[2.5rem] bg-slate-50 flex items-center justify-center text-slate-400 mb-8 border border-slate-100 shadow-sm">
                    <span class="material-symbols-outlined text-[48px]">query_stats</span>
                </div>
                <h4 class="text-xl font-black text-slate-800 tracking-tight">Belum Ada Data Pengukuran</h4>
                <p class="text-xs text-slate-400 font-bold max-w-[300px] mt-3 leading-relaxed">Grafik pertumbuhan akan tersedia setelah data antropometri ditambahkan.</p>
            </div>

            <div wire:loading wire:target="switchChart" class="absolute inset-0 bg-white/60 backdrop-blur-md flex items-center justify-center z-30 rounded-[2.5rem]">
                <div class="flex flex-col items-center gap-5">
                    <div class="w-12 h-12 border-4 border-teal-500 border-t-transparent rounded-full animate-spin"></div>
                    <p class="text-[11px] font-black text-teal-600 uppercase tracking-[0.4em]">Sinkronisasi Analisis...</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
