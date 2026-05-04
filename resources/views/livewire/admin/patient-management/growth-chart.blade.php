@extends('layouts.admin-layout')

@section('admin-title') Buku KIA Digital: {{ $patient->full_name }} @endsection

@section('admin-actions')
    <x-button href="{{ route('admin.patients.index') }}" variant="outline" icon="arrow_back">
        Kembali
    </x-button>
@endsection

@section('admin-content')
<div wire:key="growth-chart-root" class="max-w-7xl mx-auto space-y-6 pb-12">
    
    {{-- ── Patient Header: Premium Bento Style ── --}}
    <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100 relative overflow-hidden group">
        <div class="absolute -right-12 -top-12 w-48 h-48 bg-teal-50 rounded-full opacity-50 transition-transform group-hover:scale-110 duration-700"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row items-center gap-6">
            <div class="w-20 h-20 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-inner border border-teal-100 flex-shrink-0">
                <span class="material-symbols-outlined text-[40px]">child_care</span>
            </div>
            
            <div class="flex-1 text-center lg:text-left">
                <div class="flex flex-wrap items-center justify-center lg:justify-start gap-3 mb-1">
                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $patient->full_name }}</h2>
                    <span class="px-3 py-0.5 rounded-full bg-teal-500 text-white text-[9px] font-black uppercase tracking-widest shadow-sm">
                        {{ str_replace('_', ' ', $patient->category) }}
                    </span>
                </div>
                <div class="flex flex-wrap justify-center lg:justify-start gap-4 text-slate-500 text-[11px] font-bold">
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-teal-500/60">fingerprint</span>
                        {{ $patient->id_number }}
                    </div>
                    <div class="flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[16px] text-teal-500/60">cake</span>
                        {{ \Carbon\Carbon::parse($patient->birth_date)->translatedFormat('d M Y') }}
                    </div>
                </div>
            </div>

            <div class="flex items-center gap-4 bg-slate-50 p-2 rounded-2xl border border-slate-100">
                <div class="px-4 py-2 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Status Gizi</p>
                    <p class="text-sm font-black text-emerald-600">Normal</p>
                </div>
                <div class="w-px h-8 bg-slate-200"></div>
                <div class="px-4 py-2 text-center">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Posyandu</p>
                    <p class="text-sm font-black text-slate-700">{{ $patient->posyandu->name ?? '-' }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Dashboard Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        {{-- Left: Main Chart Card --}}
        <div class="lg:col-span-8 space-y-6">
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4 mb-6">
                    <div>
                        <h3 class="text-md font-black text-slate-800 tracking-tight uppercase">Grafik Pertumbuhan</h3>
                        <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Standar Antropometri WHO</p>
                    </div>
                    
                    <div class="flex items-center p-1 bg-slate-100 rounded-xl">
                        <button wire:click="switchChart('wfa')" 
                            @class([
                                'px-6 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all',
                                'bg-white shadow-sm text-teal-600' => $activeChart === 'wfa',
                                'text-slate-500 hover:text-slate-700' => $activeChart !== 'wfa'
                            ])>BB/U</button>
                        <button wire:click="switchChart('hfa')" 
                            @class([
                                'px-6 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all',
                                'bg-white shadow-sm text-teal-600' => $activeChart === 'hfa',
                                'text-slate-500 hover:text-slate-700' => $activeChart !== 'hfa'
                            ])>TB/U</button>
                    </div>
                </div>

                <div class="relative bg-slate-50/30 rounded-2xl border border-dashed border-slate-200 p-4" 
                     style="height: 400px;"
                     x-data="{ 
                        chart: null,
                        hasData: true,
                        initChart(chartData) {
                            const ctx = document.getElementById('growthChart');
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
                                    animation: { duration: 800 },
                                    interaction: { intersect: false, mode: 'index' },
                                    scales: {
                                        x: { grid: { display: false }, ticks: { font: { size: 10, weight: '700' } } },
                                        y: { grid: { color: '#f8fafc' }, ticks: { font: { size: 10, weight: '700' } } }
                                    },
                                    plugins: {
                                        legend: { position: 'bottom', labels: { boxWidth: 12, font: { weight: '700', size: 10 } } },
                                        tooltip: { backgroundColor: '#1e293b', padding: 12, cornerRadius: 8 }
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
                    <script>var initialGrowthData = @json($chartData);</script>
                    <canvas id="growthChart" wire:ignore x-show="hasData"></canvas>
                    
                    <div x-show="!hasData" class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center">
                        <div class="w-16 h-16 rounded-2xl bg-slate-100 flex items-center justify-center text-slate-300 mb-4">
                            <span class="material-symbols-outlined text-[32px]">query_stats</span>
                        </div>
                        <h4 class="text-sm font-black text-slate-800">Data Belum Lengkap</h4>
                        <p class="text-[10px] text-slate-500 font-bold max-w-[200px]">Referensi standar WHO untuk kategori ini belum tersedia.</p>
                    </div>

                    <div wire:loading class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center z-10 rounded-2xl">
                        <div class="flex flex-col items-center gap-3">
                            <div class="animate-spin rounded-full h-8 w-8 border-3 border-teal-500 border-t-transparent"></div>
                            <p class="text-[9px] font-black text-teal-600 uppercase tracking-widest">Loading...</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- History Mini-Table --}}
            <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-wider">Riwayat Pemeriksaan</h3>
                    <a href="#" class="text-[9px] font-black text-teal-600 uppercase tracking-widest">Selengkapnya →</a>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="border-b border-slate-50">
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Tgl Kunjungan</th>
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Berat</th>
                                <th class="pb-4 text-[9px] font-black text-slate-400 uppercase tracking-widest">Tinggi</th>
                                <th class="pb-4 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($patient->medicalRecords()->latest()->limit(5)->get() as $record)
                            <tr class="group hover:bg-slate-50/50">
                                <td class="py-4 text-[11px] font-bold text-slate-600">
                                    {{ \Carbon\Carbon::parse($record->visit_date)->translatedFormat('d M Y') }}
                                </td>
                                <td class="py-4">
                                    <span class="text-[11px] font-black text-slate-900">{{ $record->weight }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase ml-0.5">kg</span>
                                </td>
                                <td class="py-4">
                                    <span class="text-[11px] font-black text-slate-900">{{ $record->height }}</span>
                                    <span class="text-[8px] font-bold text-slate-400 uppercase ml-0.5">cm</span>
                                </td>
                                <td class="py-4 text-right">
                                    <span @class([
                                        'px-2 py-0.5 rounded-md text-[8px] font-black uppercase tracking-wider',
                                        'bg-emerald-50 text-emerald-600' => str_contains($record->nutrition_status ?? '', 'Normal') || str_contains($record->nutrition_status ?? '', 'Baik'),
                                        'bg-amber-50 text-amber-600' => str_contains($record->nutrition_status ?? '', 'Kurang'),
                                        'bg-red-50 text-red-600' => str_contains($record->nutrition_status ?? '', 'Buruk'),
                                        'bg-slate-50 text-slate-400' => !$record->nutrition_status,
                                    ])>
                                        {{ $record->nutrition_status ?: 'Data N/A' }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="4" class="py-8 text-center text-[10px] text-slate-400 font-bold uppercase tracking-widest">Belum ada riwayat</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right: Quick Stats --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-1 gap-4">
                <div class="p-6 bg-emerald-50 rounded-[2rem] border border-emerald-100 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-emerald-600 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">cake</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest">Usia Anak</p>
                        <p class="text-xl font-black text-emerald-900">{{ $patient->age_in_months }} <span class="text-[10px]">Bln</span></p>
                    </div>
                </div>
                <div class="p-6 bg-blue-50 rounded-[2rem] border border-blue-100 flex items-center gap-4 group">
                    <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center text-blue-600 group-hover:scale-110 transition-transform">
                        <span class="material-symbols-outlined">vaccines</span>
                    </div>
                    <div>
                        <p class="text-[9px] font-black text-blue-600/60 uppercase tracking-widest">Imunisasi</p>
                        <p class="text-xl font-black text-blue-900">8 <span class="text-[10px]">/ 12</span></p>
                    </div>
                </div>
            </div>

            <div class="bg-slate-900 rounded-[2.5rem] p-8 text-white relative overflow-hidden shadow-xl">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-teal-500/10 rounded-full blur-2xl"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <span class="px-2 py-0.5 bg-white/10 rounded-md text-[8px] font-black uppercase tracking-widest text-slate-300">Digital Pass</span>
                        <span class="material-symbols-outlined text-teal-400 text-[24px]">qr_code_2</span>
                    </div>
                    <div class="space-y-4">
                        <div>
                            <p class="text-[8px] font-black text-slate-500 uppercase tracking-widest mb-1">Terakhir Diperiksa</p>
                            <p class="text-sm font-black italic">{{ $patient->medicalRecords()->latest()->first()?->visit_date ? \Carbon\Carbon::parse($patient->medicalRecords()->latest()->first()->visit_date)->translatedFormat('d M Y') : 'Belum Ada' }}</p>
                        </div>
                        <div class="flex items-center justify-between pt-4 border-t border-white/5">
                            <p class="text-[10px] font-bold text-teal-400">Petugas: {{ explode(' ', $patient->medicalRecords()->latest()->first()?->user?->name ?? 'Sistem')[0] }}</p>
                            <div class="w-8 h-8 rounded-lg bg-teal-500 flex items-center justify-center">
                                <i class="fas fa-user-nurse text-white text-[12px]"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="bg-amber-50 border border-amber-100 rounded-[2.5rem] p-8 relative overflow-hidden">
                <div class="absolute -left-10 -bottom-10 w-24 h-24 bg-amber-200/20 rounded-full blur-2xl"></div>
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-xl bg-white shadow-sm text-amber-500 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">lightbulb</span>
                    </div>
                    <h4 class="font-black text-slate-800 text-[10px] uppercase tracking-widest">Tips Kesehatan</h4>
                </div>
                <p class="text-[11px] text-slate-600 leading-relaxed font-bold italic">
                    "Pemberian MP-ASI bergizi setelah usia 6 bulan sangat krusial untuk mencegah keterlambatan pertumbuhan."
                </p>
            </div>
        </div>
    </div>
@endsection
