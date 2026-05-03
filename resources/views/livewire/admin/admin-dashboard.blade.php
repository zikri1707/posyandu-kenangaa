<div>
    @section('admin-title') Dashboard @endsection

    {{-- Material Symbols CDN (for icons used in this design) --}}
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .material-symbols-outlined { 
            font-variation-settings: 'FILL' 1, 'wght' 500;
            font-size: 24px;
        }
        /* Responsive utilities untuk ibu-ibu dan usia 50+ */
        .text-xxl { font-size: 1.375rem; line-height: 1.4; }
        .text-xl-base { font-size: 1.25rem; line-height: 1.5; }
        .text-lg-base { font-size: 1.125rem; line-height: 1.6; }
        
        @media (max-width: 768px) {
            .text-xxl { font-size: 1.25rem; }
            .text-xl-base { font-size: 1.125rem; }
            .text-lg-base { font-size: 1rem; }
            .btn-mobile { width: 100%; justify-content: center; }
            .card-mobile { padding: 1.25rem !important; }
            .stats-grid-mobile { grid-template-columns: 1fr 1fr !important; }
            .table-responsive-mobile { overflow-x: auto; -webkit-overflow-scrolling: touch; }
        }
    </style>
    @endpush

<div>
    @section('admin-title') Dashboard @endsection

    @php
        $hour = now()->hour;
        $sapa = $hour < 11 ? 'Selamat Pagi' : ($hour < 15 ? 'Selamat Siang' : ($hour < 18 ? 'Selamat Sore' : 'Selamat Malam'));
        $user = Auth::user();
        $posyanduName = $user->posyandu?->name ?? 'Posyandu';
    @endphp

    {{-- ── Hero Banner (Modernized) ── --}}
    <section class="premium-card relative overflow-hidden mb-8 group p-0!">
        <div class="bg-premium-gradient p-8 md:p-10 text-white min-h-[240px] flex flex-col justify-center">
            {{-- Decorative Animated Elements --}}
            <div class="absolute right-0 top-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -mr-32 -mt-32 animate-pulse"></div>
            <div class="absolute left-0 bottom-0 w-64 h-64 bg-teal-400/20 rounded-full blur-3xl -ml-32 -mb-32"></div>

            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-8">
                <div class="space-y-4">
                    <div class="inline-flex items-center px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-md border border-white/30 text-[10px] font-bold tracking-widest uppercase">
                        <span class="material-symbols-outlined text-[14px] mr-1.5">verified_user</span>
                        {{ $posyanduName }}
                    </div>
                    <div>
                        <h2 class="text-display text-4xl md:text-5xl text-white mb-2">
                            {{ $sapa }}, <span class="text-teal-300">{{ explode(' ', $user->name)[0] }}</span>
                        </h2>
                        @if($user->posyandu?->pedukuhan)
                        <p class="text-teal-50/80 font-medium text-lg flex items-center gap-2">
                            <span class="material-symbols-outlined text-teal-300" style="font-size:20px;">location_on</span>
                            {{ $user->posyandu->pedukuhan->name }}
                        </p>
                        @endif
                    </div>
                </div>
                
                <div class="flex flex-col sm:flex-row gap-4 flex-shrink-0">
                    @can('create', App\Models\Patient::class)
                    <a href="{{ route('admin.patients.create') }}"
                       class="btn-premium bg-white text-teal-900 font-bold px-8 py-4 rounded-2xl flex items-center justify-center gap-2 text-sm tracking-wide shadow-2xl">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        Tambah Warga
                    </a>
                    @endcan
                    @can('create', App\Models\Patient::class)
                    <a href="{{ route('admin.medical-records.create') }}"
                       class="btn-premium bg-teal-800/40 backdrop-blur-xl border border-white/30 text-white font-bold px-8 py-4 rounded-2xl flex items-center justify-center gap-2 text-sm tracking-wide">
                        <span class="material-symbols-outlined text-[20px]">note_add</span>
                        Input Rekam Medis
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </section>

    {{-- ── KPI Stats Grid ── --}}
    <section class="grid grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-6 mb-10">
        @php
        $stats = [
            ['label' => 'Balita',      'value' => $totalBalita,    'icon' => 'child_care',      'color' => 'blue'],
            ['label' => 'Ibu Hamil',   'value' => $totalIbuHamil,  'icon' => 'pregnant_woman',  'color' => 'pink'],
            ['label' => 'Remaja',      'value' => $totalRemaja,    'icon' => 'groups',          'color' => 'indigo'],
            ['label' => 'Lansia',      'value' => $totalLansia,    'icon' => 'elderly',         'color' => 'orange'],
            ['label' => 'Kunjungan',   'value' => $kunjunganBaru,  'icon' => 'how_to_reg',      'color' => 'emerald'],
            ['label' => 'Jadwal Aktif','value' => $jadwalAktif,    'icon' => 'event_available', 'color' => 'green'],
        ];
        @endphp

        @foreach($stats as $s)
        <div class="bento-card p-6 relative overflow-hidden group">
            <div class="relative z-10">
                <div @class([
                    'w-12 h-12 rounded-2xl flex items-center justify-center mb-5 transition-transform group-hover:scale-110 duration-500',
                    'bg-blue-50 text-blue-600' => $s['color'] === 'blue',
                    'bg-pink-50 text-pink-600' => $s['color'] === 'pink',
                    'bg-indigo-50 text-indigo-600' => $s['color'] === 'indigo',
                    'bg-orange-50 text-orange-600' => $s['color'] === 'orange',
                    'bg-emerald-50 text-emerald-600' => $s['color'] === 'emerald',
                    'bg-green-50 text-green-600' => $s['color'] === 'green',
                ])>
                    <span class="material-symbols-outlined text-[26px]">{{ $s['icon'] }}</span>
                </div>
                <p class="text-label-lg text-slate-400 uppercase tracking-widest mb-1.5">{{ $s['label'] }}</p>
                <h3 class="text-headline-lg text-4xl!">{{ number_format($s['value']) }}</h3>
            </div>
            {{-- Subtle bg glow --}}
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-100 rounded-full opacity-0 group-hover:opacity-40 transition-opacity duration-700"></div>
        </div>
        @endforeach
    </section>

    {{-- ── Main Content Area ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">

        {{-- Left Side: Lists --}}
        <div class="lg:col-span-2 space-y-8">
            {{-- Stunting Alert Card --}}
            <div class="premium-card overflow-hidden">
                <div class="px-8 py-6 border-b border-slate-100 bg-red-50/30 flex justify-between items-center">
                    <div>
                        <h3 class="text-headline-md flex items-center gap-3 text-red-900">
                            <span class="material-symbols-outlined text-red-500 text-[28px]" style="font-variation-settings: 'FILL' 1;">warning</span>
                            Status Gizi Perlu Perhatian
                        </h3>
                        <p class="text-sm text-red-700/70 font-medium mt-1">Daftar balita dengan indikasi stunting atau gizi buruk terbaru</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Nama Balita</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Usia</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Status</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($balitaStunting as $balita)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-8 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-red-100 text-red-700 flex items-center justify-center font-bold text-sm">
                                            {{ strtoupper(substr($balita->full_name, 0, 2)) }}
                                        </div>
                                        <span class="text-body-md font-bold text-slate-900">{{ $balita->full_name }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-body-md text-slate-600 font-medium">{{ $balita->age }}</td>
                                <td class="px-8 py-5">
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700 border border-red-200">
                                        <span class="material-symbols-outlined text-[14px]">error</span>
                                        Stunting
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-right">
                                    <a href="{{ route('admin.patients.show', $balita->id) }}"
                                       class="text-sm font-bold text-teal-600 hover:text-teal-800 flex items-center justify-end gap-1 group-hover:translate-x-1 transition-transform">
                                       Detail
                                       <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400 font-medium">
                                    <div class="flex flex-col items-center gap-3">
                                        <span class="material-symbols-outlined text-slate-200 text-5xl">task_alt</span>
                                        <p>Semua data gizi terpantau aman.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="premium-card">
                <div class="px-8 py-6 border-b border-slate-100 flex justify-between items-center">
                    <h3 class="text-headline-md flex items-center gap-3">
                        <span class="material-symbols-outlined text-teal-600 text-[28px]">history</span>
                        Aktivitas Terkini
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Warga</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Tanggal</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Unit</th>
                                <th class="px-8 py-4 text-label-lg text-slate-500 uppercase">Kader</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($recentActivities as $activity)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-8 py-5">
                                    <div class="flex flex-col">
                                        <span class="text-body-md font-bold text-slate-900">{{ $activity->patient->full_name }}</span>
                                        <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">{{ $activity->patient->category }}</span>
                                    </div>
                                </td>
                                <td class="px-8 py-5 text-body-md text-slate-600">{{ $activity->visit_date->format('d M Y') }}</td>
                                <td class="px-8 py-5">
                                    <span class="px-2.5 py-1 rounded-lg bg-slate-100 text-slate-600 text-xs font-bold">
                                        {{ $activity->patient->posyandu->name }}
                                    </span>
                                </td>
                                <td class="px-8 py-5 text-body-md text-slate-600">{{ $activity->user->name ?? '-' }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-8 py-12 text-center text-slate-400">Belum ada aktivitas.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Side: Widgets --}}
        <div class="space-y-8">
            {{-- Upcoming Schedule Widget --}}
            <div class="premium-card p-8 bg-slate-900 text-white">
                <h3 class="text-headline-md text-white mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-teal-400 text-[28px]">calendar_today</span>
                    Jadwal Terdekat
                </h3>

                @if($upcomingSchedule)
                <div class="space-y-6">
                    <div class="flex items-start gap-5">
                        <div class="w-16 h-16 rounded-2xl bg-teal-500 flex flex-col items-center justify-center text-white shadow-lg shadow-teal-500/20">
                            <span class="text-[10px] font-black uppercase opacity-80 leading-none mb-1">
                                {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->translatedFormat('M') }}
                            </span>
                            <span class="text-2xl font-black leading-none">
                                {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('d') }}
                            </span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-body-md font-bold text-white mb-1 line-clamp-2 leading-snug">{{ $upcomingSchedule->title }}</h4>
                            <p class="text-xs text-teal-100/60 flex items-center gap-1.5 font-medium">
                                <span class="material-symbols-outlined text-[16px]">schedule</span>
                                {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('H:i') }} WIB
                            </p>
                        </div>
                    </div>
                    
                    @if($upcomingSchedule->location)
                    <div class="flex items-start gap-3 p-4 rounded-xl bg-white/5 border border-white/10">
                        <span class="material-symbols-outlined text-teal-400 text-[18px] mt-0.5">location_on</span>
                        <p class="text-xs text-slate-300 font-medium leading-relaxed">{{ $upcomingSchedule->location }}</p>
                    </div>
                    @endif

                    <a href="{{ route('admin.schedules.index') }}" 
                       class="w-full btn-premium bg-white text-slate-900 py-3.5 rounded-xl font-bold text-sm text-center">
                        Lihat Jadwal Lengkap
                    </a>
                </div>
                @else
                <div class="text-center py-10 opacity-40">
                    <span class="material-symbols-outlined text-5xl mb-4">event_busy</span>
                    <p class="text-sm font-bold uppercase tracking-widest">Belum ada jadwal</p>
                </div>
                @endif
            </div>

            {{-- Nutrition Distribution Chart --}}
            <div class="premium-card p-8">
                <h3 class="text-headline-md mb-2">Status Gizi</h3>
                <p class="text-xs text-slate-400 font-bold uppercase tracking-widest mb-8">Distribusi Kumulatif</p>
                <div class="relative h-64 mb-6">
                    <canvas id="nutritionStatusChart"></canvas>
                </div>
                {{-- Custom Legend --}}
                <div class="space-y-3">
                    @php $colors = ['#10b981', '#f59e0b', '#f97316', '#ef4444', '#94a3b8']; @endphp
                    @foreach($nutritionStatusDistribution['labels'] as $index => $label)
                    <div class="flex justify-between items-center text-xs font-bold">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full" style="background-color: {{ $colors[$index] ?? '#94a3b8' }}"></span>
                            <span class="text-slate-600">{{ $label }}</span>
                        </div>
                        <span class="text-slate-900">{{ $nutritionStatusDistribution['data'][$index] }}</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── Analytics Section ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-10">
        <div class="lg:col-span-2 premium-card p-8!">
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-8 px-8 pt-8">
                <div>
                    <h3 class="text-headline-md">Tren Penimbangan Bulanan</h3>
                    <p class="text-sm text-slate-500 font-medium mt-1">Volume pemeriksaan rekam medis warga dalam setahun terakhir</p>
                </div>
                <div class="flex items-center gap-2 px-4 py-2 bg-slate-50 rounded-xl border border-slate-200">
                    <span class="w-2.5 h-2.5 bg-teal-600 rounded-full animate-pulse"></span>
                    <span class="text-xs font-bold text-slate-600 uppercase tracking-widest">Live Updates</span>
                </div>
            </div>
            <div class="h-[350px] px-8 pb-8">
                <canvas id="monthlyWeighingChart"></canvas>
            </div>
        </div>

        {{-- Posyandu Stats (SuperAdmin only) --}}
        <div class="space-y-8">
            @if(auth()->user()->isSuperAdmin())
            <div class="premium-card p-8">
                <h3 class="text-headline-md mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-indigo-600 text-[28px]">domain</span>
                    Sasaran per Unit
                </h3>
                <div class="space-y-4">
                    @foreach($posyanduStats as $pStat)
                    <div class="flex items-center justify-between p-4 bg-slate-50 rounded-2xl border border-slate-100 group hover:border-indigo-200 transition-colors">
                        <div>
                            <p class="text-body-md font-bold text-slate-900">{{ $pStat->name }}</p>
                            <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">{{ $pStat->pedukuhan->name ?? 'Wilayah Luar' }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xl font-black text-slate-900">{{ $pStat->patients_count }}</span>
                            <p class="text-[9px] uppercase font-black text-slate-400 tracking-widest">Warga</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Quick Access (Mobile/Sidebar fallback) --}}
            <div class="bento-card p-8 bg-teal-900 text-white flex flex-col justify-center items-center text-center">
                <div class="w-16 h-16 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center mb-4">
                    <span class="material-symbols-outlined text-teal-300 text-3xl">help_outline</span>
                </div>
                <h4 class="text-headline-sm text-white mb-2">Butuh Bantuan?</h4>
                <p class="text-xs text-teal-100/60 mb-6 font-medium">Baca panduan penggunaan sistem Posyandu Admin Dashboard</p>
                <a href="#" class="w-full py-3 bg-white text-teal-900 rounded-xl font-bold text-sm">Buka Panduan</a>
            </div>
        </div>
    </div>

    {{-- ── Final Footer Quick Access ── --}}
    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
        $shortcuts = [
            ['href' => route('admin.patients.index'),        'icon' => 'groups',         'label' => 'Data Warga',   'color' => 'blue'],
            ['href' => route('admin.schedules.index'),       'icon' => 'calendar_month',  'label' => 'Jadwal Aktif', 'color' => 'emerald'],
            ['href' => route('admin.medical-records.index'), 'icon' => 'clinical_notes',  'label' => 'Rekam Medis',  'color' => 'indigo'],
            ['href' => route('admin.articles.index'),        'icon' => 'newspaper',       'label' => 'Edukasi KIA',  'color' => 'pink'],
        ];
        @endphp
        @foreach($shortcuts as $s)
        <a href="{{ $s['href'] }}" class="bento-card p-6 flex items-center gap-5 hover-lift">
            <div @class([
                'w-12 h-12 rounded-2xl flex items-center justify-center flex-shrink-0',
                'bg-blue-100 text-blue-600' => $s['color'] === 'blue',
                'bg-emerald-100 text-emerald-600' => $s['color'] === 'emerald',
                'bg-indigo-100 text-indigo-600' => $s['color'] === 'indigo',
                'bg-pink-100 text-pink-600' => $s['color'] === 'pink',
            ])>
                <span class="material-symbols-outlined text-[24px]">{{ $s['icon'] }}</span>
            </div>
            <div class="min-w-0">
                <h4 class="text-body-md font-bold text-slate-900">{{ $s['label'] }}</h4>
                <p class="text-[10px] text-slate-400 font-black uppercase tracking-tighter mt-0.5">Buka Halaman</p>
            </div>
        </a>
        @endforeach
    </section>
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Donut Chart - Status Gizi
        const nutritionCtx = document.getElementById('nutritionStatusChart');
        if (nutritionCtx) {
            const nd = @json($nutritionStatusDistribution);
            const cMap = {
                'Normal':'#10b981',
                'Gizi Kurang':'#f59e0b',
                'Gizi Lebih':'#f97316',
                'Gizi Buruk/Stunting':'#ef4444',
                'Tidak Dapat Dihitung':'#94a3b8'
            };
            new Chart(nutritionCtx, {
                type: 'doughnut',
                data: {
                    labels: nd.labels,
                    datasets: [{ 
                        data: nd.data, 
                        backgroundColor: nd.labels.map(l => cMap[l] || '#94a3b8'), 
                        borderWidth: 2, 
                        borderColor: '#fff' 
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false, 
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: { 
                            backgroundColor: '#1e293b',
                            padding: 12,
                            cornerRadius: 8,
                            callbacks: { 
                                label: c => {
                                    const t = c.dataset.data.reduce((a, b) => a + b, 0);
                                    return `${c.label}: ${c.parsed} (${t > 0 ? ((c.parsed / t) * 100).toFixed(1) : 0}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Line Chart - Tren Penimbangan
        const weighCtx = document.getElementById('monthlyWeighingChart');
        if (weighCtx) {
            const wd = @json($monthlyWeighingData);
            const g = weighCtx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            g.addColorStop(0, 'rgba(13, 148, 136, 0.15)');
            g.addColorStop(1, 'rgba(13, 148, 136, 0)');
            
            new Chart(weighCtx, {
                type: 'line',
                data: {
                    labels: wd.labels,
                    datasets: [{
                        label: 'Penimbangan',
                        data: wd.data,
                        borderColor: '#0d9488', 
                        backgroundColor: g,
                        borderWidth: 3, 
                        fill: true, 
                        tension: 0.4,
                        pointRadius: 4, 
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#fff', 
                        pointBorderColor: '#0d9488', 
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true, 
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: { 
                            mode: 'index', 
                            intersect: false, 
                            backgroundColor: '#1e293b', 
                            padding: 12, 
                            cornerRadius: 8 
                        }
                    },
                    scales: {
                        y: { 
                            beginAtZero: true, 
                            grid: { color: '#f1f5f9' }, 
                            border: { display: false },
                            ticks: { font: { size: 11 }, color: '#94a3b8' }
                        },
                        x: { 
                            grid: { display: false }, 
                            border: { display: false },
                            ticks: { font: { size: 11, weight: '600' }, color: '#64748b' }
                        }
                    },
                    interaction: { mode: 'nearest', axis: 'x', intersect: false }
                }
            });
        }
    });
    </script>
    @endpush
</div>
