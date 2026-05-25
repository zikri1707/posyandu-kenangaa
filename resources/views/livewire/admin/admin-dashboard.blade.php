<div class="space-y-6">
    {{-- Material Symbols & Fonts --}}
    @push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #134e4a 100%);
        }
        .hero-orb-1 {
            background: radial-gradient(circle, rgba(20,184,166,0.25) 0%, transparent 70%);
        }
        .hero-orb-2 {
            background: radial-gradient(circle, rgba(99,102,241,0.2) 0%, transparent 70%);
        }
        .kpi-card {
            background: white;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 16px;
            padding: 1.25rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            transition: all 200ms ease-out;
            cursor: default;
        }
        .kpi-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 24px -4px rgba(0,104,73,0.12);
            border-color: rgba(0,104,73,0.15);
        }
        .kpi-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            transition: transform 300ms ease;
        }
        .kpi-card:hover .kpi-icon {
            transform: scale(1.08);
        }
        .widget-card {
            background: white;
            border: 1px solid rgba(0,0,0,0.06);
            border-radius: 20px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            overflow: hidden;
        }
        .widget-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .table-row-hover:hover {
            background: #f8fafb;
        }
        @keyframes ping-slow {
            0%, 100% { transform: scale(1); opacity: 0.8; }
            50% { transform: scale(1.8); opacity: 0; }
        }
        .animate-ping-slow {
            animation: ping-slow 2s ease-in-out infinite;
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
    <section class="relative rounded-2xl overflow-hidden" style="background:#0f172a;">
        {{-- Background layers --}}
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute top-0 left-1/4 w-72 h-72 hero-orb-1 rounded-full filter blur-[60px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-72 h-72 hero-orb-2 rounded-full filter blur-[60px]" style="animation:pulse 4s ease-in-out 1.5s infinite;"></div>
        {{-- Grid overlay --}}
        <div class="absolute inset-0 opacity-[0.04]"
             style="background-image:url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjAuNSIgZmlsbD0ibm9uZSI+PHBhdGggZD0iTTQwIDBMMCA0ME0wIDBsNDAgNDAiLz48L2c+PC9zdmc+');"></div>

        <div class="relative z-10 px-8 py-10 md:px-12 md:py-12 max-w-3xl">
            {{-- Live badge --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full mb-6"
                 style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);">
                <span class="relative flex h-2 w-2">
                    <span class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-teal-400"></span>
                </span>
                <span class="text-xs font-semibold text-teal-100 tracking-wide">Sistem Informasi Posyandu Aktif</span>
            </div>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight" style="letter-spacing:-0.02em;">
                {{ $sapa }},
                <span class="text-transparent bg-clip-text" style="background-image:linear-gradient(135deg,#5eead4,#a7f3d0);">
                    {{ explode(' ', $user->name)[0] }}!
                </span>
            </h1>

            <p class="text-slate-300 text-base max-w-xl leading-relaxed mb-8 font-normal" style="opacity:0.85;">
                Pantau indikator kesehatan masyarakat secara real-time dan tingkatkan kualitas pelayanan Posyandu di wilayah Anda.
            </p>

            <div class="flex flex-wrap items-center gap-3">
                @can('create', App\Models\Patient::class)
                <a href="{{ route('admin.patients.create') }}"
                   class="inline-flex items-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5 hover:shadow-lg"
                   style="background:rgba(20,184,166,1); box-shadow:0 4px 14px rgba(20,184,166,0.35);">
                    <span class="material-symbols-outlined text-[20px]">person_add</span>
                    Registrasi Warga
                </a>
                @endcan

                @can('create', App\Models\MedicalRecord::class)
                <a href="{{ route('admin.medical-records.create') }}"
                   class="inline-flex items-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5"
                   style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); backdrop-filter:blur(8px);">
                    <span class="material-symbols-outlined text-[20px]">add_circle</span>
                    Input Rekam Medis
                </a>
                @endcan
            </div>
        </div>
    </section>

    {{-- ── KPI Cards ── --}}
    @php
    $stats = [
        ['label' => 'Balita',       'value' => $totalBalita,   'icon' => 'child_care',      'color' => 'blue',    'bg' => '#eff6ff', 'fg' => '#2563eb'],
        ['label' => 'Ibu Hamil',    'value' => $totalIbuHamil, 'icon' => 'pregnant_woman',  'color' => 'pink',    'bg' => '#fdf2f8', 'fg' => '#be185d'],
        ['label' => 'Remaja',       'value' => $totalRemaja,   'icon' => 'groups',           'color' => 'indigo',  'bg' => '#eef2ff', 'fg' => '#4338ca'],
        ['label' => 'Lansia',       'value' => $totalLansia,   'icon' => 'elderly',          'color' => 'orange',  'bg' => '#fff7ed', 'fg' => '#c2410c'],
        ['label' => 'Kunjungan',    'value' => $kunjunganBaru, 'icon' => 'analytics',        'color' => 'emerald', 'bg' => '#ecfdf5', 'fg' => '#059669'],
        ['label' => 'Jadwal Aktif', 'value' => $jadwalAktif,  'icon' => 'calendar_today',   'color' => 'teal',    'bg' => '#f0fdfa', 'fg' => '#0f766e'],
    ];
    @endphp

    <section class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
        @foreach($stats as $s)
        <div class="kpi-card">
            <div class="kpi-icon" style="background:{{ $s['bg'] }}; color:{{ $s['fg'] }};">
                <span class="material-symbols-outlined text-[22px]">{{ $s['icon'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">{{ $s['label'] }}</p>
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-slate-900" style="letter-spacing:-0.03em; font-variant-numeric:tabular-nums;">{{ number_format($s['value']) }}</span>
                <span class="text-xs font-medium text-slate-400">org</span>
            </div>
        </div>
        @endforeach
    </section>

    {{-- ── Main Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

        {{-- Left: 8 Columns --}}
        <div class="lg:col-span-8 space-y-6">

            {{-- Stunting Alert Table --}}
            <div class="widget-card">
                <div class="widget-header">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">warning</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Prioritas Atensi Gizi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Status stunting & gizi buruk terdeteksi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-red-50 border border-red-100">
                        <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                        <span class="text-xs font-semibold text-red-600">{{ count($balitaStunting) }} Kasus</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid rgba(0,0,0,0.05);">
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Balita</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Usia</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">Status</th>
                                <th class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($balitaStunting as $balita)
                            <tr class="table-row-hover transition-colors" style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($balita->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-semibold text-slate-800">{{ $balita->full_name }}</span>
                                            <span class="text-xs text-slate-400">ID: {{ $balita->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 text-center">{{ $balita->age }}</td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $latestRecord = $balita->medicalRecords->first();
                                        $displayStatus = 'Atensi Gizi';
                                        if ($latestRecord) {
                                            $possibleStatuses = [
                                                $latestRecord->stunting_status,
                                                $latestRecord->nutrition_status,
                                                $latestRecord->wasting_status
                                            ];
                                            foreach ($possibleStatuses as $ps) {
                                                if (str_contains($ps, 'Sangat') || str_contains($ps, 'Buruk') || str_contains($ps, 'Pendek')) {
                                                    $displayStatus = $ps;
                                                    break;
                                                }
                                            }
                                        }
                                    @endphp
                                    <span class="badge badge-red">{{ $displayStatus }}</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $balita->id) }}"
                                       class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-teal-500 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-16 text-center">
                                    <div class="flex flex-col items-center gap-3">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined text-[28px]">verified_user</span>
                                        </div>
                                        <p class="text-sm font-medium text-slate-400">Semua data terpantau normal</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Recent Activity --}}
            <div class="widget-card">
                <div class="widget-header">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center flex-shrink-0">
                            <span class="material-symbols-outlined text-[20px]">history</span>
                        </div>
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Aktivitas Pemeriksaan</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Kunjungan terbaru</p>
                        </div>
                    </div>
                    <a href="{{ route('admin.medical-records.index') }}"
                       class="text-xs font-semibold text-teal-600 hover:text-teal-700 hover:underline transition-colors">
                        Lihat Semua →
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr style="background:#f9fafb; border-bottom:1px solid rgba(0,0,0,0.05);">
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Pasien</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu Visit</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Unit Posyandu</th>
                                <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Petugas</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentActivities as $activity)
                            <tr class="table-row-hover transition-colors" style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                            {{ strtoupper(substr($activity->patient->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-semibold text-slate-800">{{ $activity->patient->full_name }}</span>
                                            <span class="text-xs text-slate-400">{{ $activity->patient->category }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $activity->visit_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="badge badge-blue">{{ $activity->patient->posyandu->name }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $activity->user->name ?? '-' }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- Right Side: 4 Columns --}}
        <div class="lg:col-span-4 flex flex-col gap-5">

            {{-- Upcoming Schedule Widget --}}
            <div class="widget-card p-5 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-teal-50 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-teal-600 text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">event</span>
                            </div>
                            <span class="font-bold text-slate-900 text-sm">Agenda Terdekat</span>
                        </div>
                        <span class="badge badge-teal">Live</span>
                    </div>

                    @if($upcomingSchedule)
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-slate-900 flex flex-col items-center justify-center text-white shadow-lg flex-shrink-0">
                                <span class="text-[9px] font-bold uppercase opacity-50">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->translatedFormat('M') }}</span>
                                <span class="text-xl font-bold leading-none">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-sm leading-tight truncate">{{ $upcomingSchedule->title }}</h4>
                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[14px] text-teal-500">schedule</span>
                                    {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span class="material-symbols-outlined text-[18px] text-teal-500 flex-shrink-0">location_on</span>
                            <span class="text-sm text-slate-700 font-medium truncate">{{ $upcomingSchedule->location ?: 'Pusat Posyandu' }}</span>
                        </div>

                        <a href="{{ route('admin.schedules.index') }}"
                           class="w-full h-11 bg-teal-600 text-white rounded-xl font-semibold text-sm flex items-center justify-center hover:bg-teal-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            Buka Kalender
                        </a>
                    </div>
                    @else
                    <div class="flex flex-col items-center py-6 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                            <span class="material-symbols-outlined text-[24px]">event_busy</span>
                        </div>
                        <p class="text-sm font-medium text-slate-400">Tidak ada jadwal terdekat</p>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Missing Immunizations Widget --}}
            <div class="widget-card p-5 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-50 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div class="flex items-center gap-2.5">
                            <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center">
                                <span class="material-symbols-outlined text-[18px]">vaccines</span>
                            </div>
                            <span class="font-bold text-slate-900 text-sm">Atensi Imunisasi</span>
                        </div>
                        @if(count($missingImmunizations) > 0)
                        <span class="badge badge-amber">{{ count($missingImmunizations) }} Anak</span>
                        @endif
                    </div>

                    <div class="space-y-2">
                        @forelse($missingImmunizations as $item)
                        <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 hover:bg-white hover:shadow-sm transition-all">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-700 flex-shrink-0 flex items-center justify-center font-bold text-[11px]">
                                    {{ strtoupper(substr($item['patient']->full_name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-slate-900 truncate">{{ $item['patient']->full_name }}</p>
                                    <p class="text-xs text-slate-400 truncate">Target: {{ $item['next_vaccine'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.patients.show', $item['patient']->id) }}"
                               class="w-7 h-7 flex-shrink-0 flex items-center justify-center rounded-lg bg-white border border-slate-200 text-slate-400 hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-all">
                                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                            </a>
                        </div>
                        @empty
                        <div class="flex flex-col items-center py-6 text-center">
                            <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                                <span class="material-symbols-outlined text-[24px]">verified</span>
                            </div>
                            <p class="text-sm font-medium text-slate-400">Semua Imunisasi Terpenuhi</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Nutrition Status Widget --}}
            <div class="widget-card p-5 relative overflow-hidden">
                <div class="absolute -right-8 -top-8 w-32 h-32 bg-indigo-50 rounded-full blur-3xl pointer-events-none"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-5">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm">Kondisi Gizi</h3>
                            <p class="text-xs text-slate-500 mt-0.5">Distribusi status gizi balita</p>
                        </div>
                        <div class="w-9 h-9 rounded-xl bg-indigo-50 flex items-center justify-center text-indigo-600">
                            <span class="material-symbols-outlined text-[18px]">donut_large</span>
                        </div>
                    </div>

                    @php
                        $ndLabels = $nutritionStatusDistribution['labels'];
                        $ndData = $nutritionStatusDistribution['data'];
                        $chartTotal = array_sum($ndData);
                    @endphp

                    {{-- Chart --}}
                    <div x-data="{
                        chart: null,
                        init() {
                            const nd = $wire.nutritionStatusDistribution;
                            if (!nd || !nd.labels || !nd.data || nd.data.length === 0) return;
                            
                            const colors = nd.labels.map(label => {
                                if (label.includes('Normal') || label.includes('Baik')) return '#059669';
                                if (label.includes('Kurang') && !label.includes('Sangat')) return '#f59e0b';
                                if (label.includes('Risiko') || label.includes('Berisiko')) return '#f59e0b';
                                if (label.includes('Sangat') || label.includes('Buruk') || label.includes('Pendek')) return '#ef4444';
                                if (label.includes('Lebih') || label.includes('Obesitas')) return '#f59e0b';
                                return '#94a3b8';
                            });

                            this.chart = new Chart(this.$refs.canvas, {
                                type: 'doughnut',
                                data: {
                                    labels: nd.labels,
                                    datasets: [{
                                        data: nd.data,
                                        backgroundColor: colors,
                                        borderWidth: 3,
                                        borderColor: '#ffffff',
                                        hoverOffset: 8
                                    }]
                                },
                                options: {
                                    responsive: true,
                                    maintainAspectRatio: false,
                                    cutout: '80%',
                                    plugins: {
                                        legend: { display: false },
                                        tooltip: {
                                            cornerRadius: 10,
                                            padding: 10,
                                            bodyFont: { family: '\'Public Sans\', sans-serif', size: 12 },
                                            titleFont: { family: '\'Public Sans\', sans-serif', size: 12, weight: 'bold' }
                                        }
                                    }
                                }
                            });
                        }
                    }" wire:ignore class="relative flex justify-center mb-5">
                        <canvas x-ref="canvas" width="180" height="180" style="max-width:180px;max-height:180px;"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <span class="text-2xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">{{ $chartTotal }}</span>
                            <span class="text-xs font-medium text-slate-400 mt-1">Total</span>
                        </div>
                    </div>

                    {{-- Legend --}}
                    <div class="space-y-2.5 pt-4 border-t border-slate-100">
                        @foreach($ndLabels as $index => $label)
                        @php
                            $count = $ndData[$index] ?? 0;
                            $percentage = $chartTotal > 0 ? round(($count / $chartTotal) * 100, 1) : 0;
                            $color = match(true) {
                                str_contains($label, 'Normal') || str_contains($label, 'Baik') => '#059669',
                                str_contains($label, 'Kurang') && !str_contains($label, 'Sangat') => '#f59e0b',
                                str_contains($label, 'Risiko') || str_contains($label, 'Berisiko') => '#f59e0b',
                                str_contains($label, 'Sangat') || str_contains($label, 'Buruk') || str_contains($label, 'Pendek') => '#ef4444',
                                str_contains($label, 'Lebih') || str_contains($label, 'Obesitas') => '#f59e0b',
                                default => '#94a3b8',
                            };
                        @endphp
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $color }}"></span>
                            <span class="text-xs text-slate-600 flex-1 truncate">{{ $label }}</span>
                            <div class="flex items-center gap-2 flex-shrink-0">
                                <div class="w-16 h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full rounded-full" style="background:{{ $color }}; width:{{ $percentage }}%;"></div>
                                </div>
                                <span class="text-xs font-semibold text-slate-700 w-8 text-right" style="font-variant-numeric:tabular-nums;">{{ $percentage }}%</span>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Trend Analysis Section ── --}}
    <section class="widget-card p-6 md:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
            <div>
                <div class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 mb-3">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-emerald-700">Analitik Tahunan</span>
                </div>
                <h3 class="font-bold text-slate-900 text-lg" style="letter-spacing:-0.02em;">Tren Aktivitas Pemeriksaan</h3>
                <p class="text-sm text-slate-500 mt-1">Frekuensi penimbangan kumulatif selama 12 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-3 sm:flex-shrink-0">
                <div class="text-right">
                    <p class="text-xs font-medium text-slate-400 mb-0.5">Rata-rata Bulanan</p>
                    <p class="font-bold text-slate-900 text-lg" style="font-variant-numeric:tabular-nums;">
                        {{ count($monthlyWeighingData['data']) > 0 ? round(array_sum($monthlyWeighingData['data']) / count($monthlyWeighingData['data'])) : 0 }}
                        <span class="text-sm font-medium text-slate-400">Sesi</span>
                    </p>
                </div>
                <div class="w-10 h-10 rounded-xl bg-slate-900 text-white flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">trending_up</span>
                </div>
            </div>
        </div>
        <div x-data="{
            chart: null,
            init() {
                const wd = $wire.monthlyWeighingData;
                if (!wd || !wd.labels || !wd.data || wd.data.length === 0) return;

                const ctx = this.$refs.canvas.getContext('2d');
                const gradient = ctx.createLinearGradient(0, 0, 0, 280);
                gradient.addColorStop(0, 'rgba(0, 108, 73, 0.18)');
                gradient.addColorStop(1, 'rgba(0, 108, 73, 0)');

                this.chart = new Chart(this.$refs.canvas, {
                    type: 'line',
                    data: {
                        labels: wd.labels,
                        datasets: [{
                            label: 'Penimbangan',
                            data: wd.data,
                            borderColor: '#006c49',
                            borderWidth: 2.5,
                            fill: true,
                            backgroundColor: gradient,
                            tension: 0.4,
                            pointRadius: 0,
                            pointHoverRadius: 5,
                            pointHoverBackgroundColor: '#006c49',
                            pointHoverBorderColor: '#fff',
                            pointHoverBorderWidth: 2
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                cornerRadius: 10,
                                padding: 12,
                                mode: 'index',
                                intersect: false,
                                backgroundColor: '#1e293b',
                                titleColor: '#94a3b8',
                                bodyColor: '#f1f5f9',
                                titleFont: { family: '\'Public Sans\', sans-serif', size: 11 },
                                bodyFont: { family: '\'Public Sans\', sans-serif', size: 13, weight: 'bold' }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: 'rgba(0,0,0,0.04)', drawTicks: false },
                                border: { display: false },
                                ticks: {
                                    font: { family: '\'Public Sans\', sans-serif', size: 12 },
                                    color: '#94a3b8',
                                    padding: 8
                                }
                            },
                            x: {
                                grid: { display: false },
                                border: { display: false },
                                ticks: {
                                    font: { family: '\'Public Sans\', sans-serif', size: 12 },
                                    color: '#94a3b8',
                                    padding: 6
                                }
                            }
                        }
                    }
                });
            }
        }" wire:ignore class="h-72 -mx-2">
            <canvas x-ref="canvas"></canvas>
        </div>
    </section>


</div>
