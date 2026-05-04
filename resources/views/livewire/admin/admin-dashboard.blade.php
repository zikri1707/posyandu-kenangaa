<div class="space-y-8">
    {{-- Material Symbols & Fonts --}}
    @push('styles')
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #00685f 0%, #004d46 100%);
        }
        .glass-btn {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.25);
        }
    </style>
    @endpush

    @php
        $hour = now()->hour;
        $sapa = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $user = Auth::user();
        $posyanduName = $user->posyandu?->name ?? 'Posyandu';
    @endphp

    {{-- ── Hero Section ── --}}
    <section class="premium-card relative overflow-hidden group p-0!">
        <div class="hero-gradient p-10 md:p-14 text-white relative min-h-[300px] flex flex-col justify-center">
            {{-- Abstract Shapes --}}
            <div class="absolute right-0 top-0 w-[500px] h-[500px] bg-emerald-400/10 rounded-full blur-[120px] -mr-48 -mt-48 animate-pulse"></div>
            <div class="absolute left-1/4 bottom-0 w-[300px] h-[300px] bg-teal-500/20 rounded-full blur-[80px] -ml-24 -mb-24"></div>

            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 text-[11px] font-black tracking-[0.2em] uppercase mb-6">
                        <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                        {{ $posyanduName }} • Pusat Informasi
                    </div>
                    <h1 class="text-display text-5xl md:text-6xl text-white mb-4 leading-[1.1]">
                        {{ $sapa }}, <br/>
                        <span class="text-emerald-300">{{ explode(' ', $user->name)[0] }}!</span>
                    </h1>
                    <p class="text-teal-50/70 text-lg font-medium max-w-lg">
                        Selamat datang kembali di dashboard kendali Posyandu. Pantau kesehatan warga dan kelola jadwal dengan efisiensi tinggi.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-4">
                    @can('create', App\Models\Patient::class)
                    <a href="{{ route('admin.patients.create') }}"
                       class="h-16 px-8 rounded-2xl bg-white text-teal-950 font-black text-sm uppercase tracking-widest flex items-center gap-3 shadow-2xl shadow-teal-950/20 hover:-translate-y-1 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[22px]">person_add</span>
                        Tambah Warga
                    </a>
                    @endcan
                    @can('create', App\Models\MedicalRecord::class)
                    <a href="{{ route('admin.medical-records.create') }}"
                       class="h-16 px-8 rounded-2xl glass-btn text-white font-black text-sm uppercase tracking-widest flex items-center gap-3 hover:-translate-y-1 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[22px]">edit_note</span>
                        Input Rekam Medis
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    {{-- ── KPI Cards ── --}}
    <section class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6">
        @php
        $stats = [
            ['label' => 'Balita',      'value' => $totalBalita,    'icon' => 'child_care',      'color' => 'blue'],
            ['label' => 'Ibu Hamil',   'value' => $totalIbuHamil,  'icon' => 'pregnant_woman',  'color' => 'pink'],
            ['label' => 'Remaja',      'value' => $totalRemaja,    'icon' => 'groups',          'color' => 'indigo'],
            ['label' => 'Lansia',      'value' => $totalLansia,    'icon' => 'elderly',         'color' => 'orange'],
            ['label' => 'Kunjungan',   'value' => $kunjunganBaru,  'icon' => 'analytics',       'color' => 'emerald'],
            ['label' => 'Jadwal Aktif','value' => $jadwalAktif,    'icon' => 'calendar_today',  'color' => 'teal'],
        ];
        @endphp

        @foreach($stats as $s)
        <div class="bento-card p-7 group hover-lift border-slate-100!">
            <div @class([
                'w-14 h-14 rounded-2xl flex items-center justify-center mb-6 transition-all duration-500 group-hover:rotate-6 shadow-sm',
                'bg-blue-50 text-blue-600' => $s['color'] === 'blue',
                'bg-pink-50 text-pink-600' => $s['color'] === 'pink',
                'bg-indigo-50 text-indigo-600' => $s['color'] === 'indigo',
                'bg-orange-50 text-orange-600' => $s['color'] === 'orange',
                'bg-emerald-50 text-emerald-600' => $s['color'] === 'emerald',
                'bg-teal-50 text-teal-600' => $s['color'] === 'teal',
            ])>
                <span class="material-symbols-outlined text-[28px]">{{ $s['icon'] }}</span>
            </div>
            <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mb-2">{{ $s['label'] }}</p>
            <div class="flex items-end gap-2">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">{{ number_format($s['value']) }}</h3>
                <span class="text-[10px] font-bold text-slate-400 uppercase mb-1">Jiwa</span>
            </div>
        </div>
        @endforeach
    </section>

    {{-- ── Main Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left Content: 8 Columns --}}
        <div class="lg:col-span-8 space-y-8">
            {{-- Stunting Alert Table --}}
            <div class="premium-card overflow-hidden border-red-100!">
                <div class="px-8 py-6 bg-red-50/40 border-b border-red-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-red-950 flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-500 text-[24px]">priority_high</span>
                            Perhatian Gizi Balita
                        </h3>
                        <p class="text-xs text-red-700/60 font-bold uppercase tracking-widest mt-1">Indikasi Stunting & Gizi Buruk</p>
                    </div>
                    <span class="px-4 py-1.5 rounded-xl bg-red-100 text-red-700 text-[10px] font-black uppercase tracking-widest">
                        {{ count($balitaStunting) }} Kasus
                    </span>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Identitas Balita</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Usia</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Terakhir</th>
                                <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($balitaStunting as $balita)
                            <tr class="hover:bg-red-50/20 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-11 h-11 rounded-2xl bg-white border border-red-100 text-red-600 flex items-center justify-center font-black text-sm shadow-sm group-hover:bg-red-500 group-hover:text-white transition-all">
                                            {{ strtoupper(substr($balita->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-black text-slate-900">{{ $balita->full_name }}</span>
                                            <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">ID: #{{ $balita->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm font-black text-slate-600">{{ $balita->age }}</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl text-[10px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-600 animate-pulse"></span>
                                        Stunting
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.patients.show', $balita->id) }}" class="inline-flex h-10 px-5 items-center justify-center rounded-xl bg-white border border-slate-100 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-teal-600 hover:border-teal-100 hover:shadow-lg transition-all">
                                        Detail
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-20 text-center">
                                    <div class="flex flex-col items-center gap-4 text-slate-300">
                                        <span class="material-symbols-outlined text-[56px]">check_circle</span>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em]">Semua data gizi dalam kondisi baik</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="premium-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                        <span class="material-symbols-outlined text-teal-600 text-[24px]">history</span>
                        Aktivitas Pemeriksaan
                    </h3>
                    <a href="{{ route('admin.medical-records.index') }}" class="text-[10px] font-black text-teal-600 uppercase tracking-widest hover:underline">Lihat Semua</a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pasien</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Waktu Visit</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Posyandu</th>
                                <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Petugas</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($recentActivities as $activity)
                            <tr class="hover:bg-slate-50/50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-sm font-black text-slate-900 group-hover:text-teal-600 transition-colors">{{ $activity->patient->full_name }}</span>
                                        <span class="text-[10px] text-slate-400 font-bold uppercase tracking-widest">{{ $activity->patient->category }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-sm font-black text-slate-600">{{ $activity->visit_date->translatedFormat('d M Y') }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-3 py-1 rounded-xl bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest">
                                        {{ $activity->patient->posyandu->name }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-sm font-bold text-slate-700">{{ $activity->user->name ?? '-' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Side: 4 Columns Widgets --}}
        {{-- Right Side: Side Widgets with better separation ── --}}
        <div class="lg:col-span-4 flex flex-col gap-10">
            
            {{-- Upcoming Schedule Widget (Refined Compact) --}}
            <div class="relative group rounded-[2.5rem] p-6 bg-white border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.02)] hover:shadow-[0_30px_60px_rgba(20,184,166,0.1)] transition-all duration-500 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-500/[0.03] rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-teal-500 text-white flex items-center justify-center shadow-lg shadow-teal-500/20">
                                <i class="fas fa-calendar-alt text-[16px]"></i>
                            </div>
                            <span class="text-lg font-black text-slate-900 tracking-tight">Jadwal</span>
                        </div>
                        <span class="px-3 py-1 bg-teal-50 text-teal-600 text-[8px] font-black rounded-full uppercase tracking-widest border border-teal-100">Aktif</span>
                    </div>

                    @if($upcomingSchedule)
                    <div class="space-y-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-teal-500 to-emerald-600 flex flex-col items-center justify-center text-white shadow-xl shadow-teal-500/20">
                                <span class="text-[9px] font-black uppercase opacity-80 mb-0.5">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->translatedFormat('M') }}</span>
                                <span class="text-2xl font-black">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-lg font-black text-slate-900 mb-1 leading-tight truncate">{{ $upcomingSchedule->title }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-2">
                                    <i class="far fa-clock text-teal-500"></i>
                                    {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="p-4 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-white shadow-sm flex items-center justify-center text-teal-500 flex-shrink-0">
                                <i class="fas fa-location-dot text-[14px]"></i>
                            </div>
                            <span class="text-[13px] text-slate-700 font-black truncate">{{ $upcomingSchedule->location ?: 'Posyandu Anggrek' }}</span>
                        </div>

                        <a href="{{ route('admin.schedules.index') }}" 
                           class="w-full h-12 bg-slate-900 text-white rounded-xl font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center hover:bg-teal-600 transition-all">
                            Detail Jadwal
                        </a>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Nutrition Status Widget (Distinct Background) --}}
            <div class="relative group rounded-[2.5rem] p-7 bg-slate-50 border border-slate-100 shadow-sm hover:shadow-xl hover:bg-white transition-all duration-500 overflow-hidden">
                <div class="relative z-10">
                    <h3 class="text-lg font-black text-slate-900 mb-1 tracking-tight">Status Gizi</h3>
                    <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em] mb-8">Proporsi Kumulatif Balita</p>
                    
                    <div class="relative h-56 mb-8">
                        <canvas id="nutritionStatusChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                            <span class="text-3xl font-black text-slate-900">{{ $totalBalita }}</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Jiwa</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 pt-6 border-t border-slate-200/50">
                        <div class="p-3 rounded-xl bg-white border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Gizi Baik</p>
                            <p class="text-sm font-black text-emerald-600">{{ $nutritionStats['Normal'] ?? 0 }}</p>
                        </div>
                        <div class="p-3 rounded-xl bg-white border border-slate-100">
                            <p class="text-[8px] font-black text-slate-400 uppercase mb-1">Perlu Atensi</p>
                            <p class="text-sm font-black text-red-500">{{ ($nutritionStats['Severely Stunted'] ?? 0) + ($nutritionStats['Stunted'] ?? 0) }}</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-3 pt-6 border-t border-slate-200/50">
                        @php 
                            $ndLabels = $nutritionStatusDistribution['labels'];
                            $ndData = $nutritionStatusDistribution['data'];
                            $ndColors = ['#0d9488', '#fbbf24', '#f97316', '#ef4444', '#cbd5e1']; 
                        @endphp
                        @foreach($ndLabels as $index => $label)
                        <div class="flex justify-between items-center p-3 rounded-xl bg-white border border-slate-100 hover:shadow-sm transition-all">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full" style="background-color: {{ $ndColors[$index] ?? '#94a3b8' }}"></span>
                                <span class="text-[10px] font-bold text-slate-600 truncate max-w-[120px]">{{ $label }}</span>
                            </div>
                            <span class="text-xs font-black text-slate-900">{{ $ndData[$index] ?? 0 }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Trend Analysis Section ── --}}
    <section class="premium-card p-0! overflow-hidden">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-8 p-8 md:p-12 pb-0!">
            <div>
                <h3 class="text-2xl font-black text-slate-900 tracking-tight">Tren Penimbangan & Pemeriksaan</h3>
                <p class="text-sm text-slate-500 font-medium mt-1">Aktivitas rekam medis kumulatif warga dalam periode 12 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-3 px-5 py-2.5 bg-emerald-50 rounded-2xl border border-emerald-100">
                <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                <span class="text-[10px] font-black text-emerald-700 uppercase tracking-widest">Real-time Data Analytics</span>
            </div>
        </div>
        <div class="h-[400px]">
            <canvas id="monthlyWeighingChart"></canvas>
        </div>
    </section>

    {{-- Scripts --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const nd = @json($nutritionStatusDistribution);
        const wd = @json($monthlyWeighingData);
        
        // 1. Nutrition Status (Doughnut)
        const nutritionCtx = document.getElementById('nutritionStatusChart');
        if (nutritionCtx) {
            new Chart(nutritionCtx, {
                type: 'doughnut',
                data: {
                    labels: nd.labels,
                    datasets: [{ 
                        data: nd.data, 
                        backgroundColor: ['#0d9488', '#fbbf24', '#f97316', '#ef4444', '#cbd5e1'], 
                        borderWidth: 0,
                        hoverOffset: 15
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '82%',
                    plugins: { legend: { display: false }, tooltip: { cornerRadius: 12, padding: 12 } }
                }
            });
        }

        // 2. Monthly Trend (Line Area)
        const weighCtx = document.getElementById('monthlyWeighingChart');
        if (weighCtx) {
            const gradient = weighCtx.getContext('2d').createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, 'rgba(13, 148, 136, 0.2)');
            gradient.addColorStop(1, 'rgba(13, 148, 136, 0)');

            new Chart(weighCtx, {
                type: 'line',
                data: {
                    labels: wd.labels,
                    datasets: [{
                        label: 'Penimbangan',
                        data: wd.data,
                        borderColor: '#0d9488',
                        borderWidth: 4,
                        fill: true,
                        backgroundColor: gradient,
                        tension: 0.45,
                        pointRadius: 0,
                        pointHoverRadius: 8,
                        pointHoverBackgroundColor: '#0d9488',
                        pointHoverBorderColor: '#fff',
                        pointHoverBorderWidth: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { cornerRadius: 12, padding: 12, mode: 'index', intersect: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: 'rgba(0,0,0,0.03)' }, border: { display: false }, ticks: { font: { weight: 'bold' }, color: '#94a3b8' } },
                        x: { grid: { display: false }, border: { display: false }, ticks: { font: { weight: 'black' }, color: '#64748b' } }
                    }
                }
            });
        }
    });
    </script>
    @endpush
</div>
