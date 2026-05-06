<div class="space-y-8">
    {{-- Material Symbols & Fonts --}}
    @push('styles')
    <style>
        .hero-mesh {
            background-color: #064e3b;
            background-image: 
                radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.5) 0px, transparent 50%),
                radial-gradient(at 50% 0%, hsla(168, 76%, 36%, 0.5) 0px, transparent 50%),
                radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.3) 0px, transparent 50%);
        }
        .neo-glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(20px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.07);
        }
        .stat-card-glow {
            position: relative;
            overflow: hidden;
        }
        .stat-card-glow::after {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            opacity: 0;
            transition: opacity 0.6s ease;
        }
        .stat-card-glow:hover::after {
            opacity: 1;
        }
        .premium-shadow {
            box-shadow: 0 10px 40px -10px rgba(0,0,0,0.05);
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
    <section class="rounded-[3rem] overflow-hidden group premium-shadow">
        <div class="hero-mesh p-10 md:p-14 text-white relative min-h-[320px] flex flex-col justify-center">
            {{-- Decorative Elements --}}
            <div class="absolute right-0 top-0 w-96 h-96 bg-white/5 rounded-full blur-3xl -mr-20 -mt-20 animate-pulse"></div>
            
            <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12">
                <div class="max-w-2xl">
                    <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[9px] font-black tracking-[0.3em] uppercase mb-8">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                        Sistem Kendali Utama • {{ $posyanduName }}
                    </div>
                    <h1 class="text-display text-5xl md:text-7xl text-white mb-6 leading-none">
                        {{ $sapa }}, <br/>
                        <span class="text-emerald-300 drop-shadow-sm">{{ explode(' ', $user->name)[0] }}!</span>
                    </h1>
                    <p class="text-teal-50/60 text-lg font-medium max-w-md leading-relaxed">
                        Pantau indikator kesehatan masyarakat secara real-time dan tingkatkan kualitas pelayanan Posyandu.
                    </p>
                </div>
                
                <div class="flex flex-wrap gap-4">
                    @can('create', App\Models\Patient::class)
                    <a href="{{ route('admin.patients.create') }}"
                       class="h-14 px-8 rounded-2xl bg-white text-emerald-950 font-black text-xs uppercase tracking-widest flex items-center gap-3 shadow-xl hover:-translate-y-1 hover:shadow-2xl hover:shadow-white/20 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[20px]">person_add</span>
                        Registrasi Warga
                    </a>
                    @endcan
                    @can('create', App\Models\MedicalRecord::class)
                    <a href="{{ route('admin.medical-records.create') }}"
                       class="h-14 px-8 rounded-2xl bg-white/10 backdrop-blur-xl border border-white/20 text-white font-black text-xs uppercase tracking-widest flex items-center gap-3 hover:bg-white/20 hover:-translate-y-1 transition-all active:scale-95">
                        <span class="material-symbols-outlined text-[20px]">add_circle</span>
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
        <div class="bg-white rounded-[2.5rem] p-7 group hover:-translate-y-2 transition-all duration-500 border border-slate-100/50 premium-shadow stat-card-glow cursor-default">
            <div @class([
                'w-12 h-12 rounded-2xl flex items-center justify-center mb-6 transition-all duration-700 group-hover:scale-110 group-hover:rotate-6',
                'bg-blue-50 text-blue-500' => $s['color'] === 'blue',
                'bg-rose-50 text-rose-500' => $s['color'] === 'pink',
                'bg-indigo-50 text-indigo-500' => $s['color'] === 'indigo',
                'bg-amber-50 text-amber-500' => $s['color'] === 'orange',
                'bg-emerald-50 text-emerald-500' => $s['color'] === 'emerald',
                'bg-teal-50 text-teal-500' => $s['color'] === 'teal',
            ])>
                <span class="material-symbols-outlined text-[24px]">{{ $s['icon'] }}</span>
            </div>
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">{{ $s['label'] }}</p>
            <div class="flex items-baseline gap-2">
                <h3 class="text-4xl font-black text-slate-900 leading-none tracking-tighter">{{ number_format($s['value']) }}</h3>
                <span class="text-[9px] font-bold text-slate-300 uppercase tracking-tighter">Org</span>
            </div>
        </div>
        @endforeach
    </section>

    {{-- ── Main Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Left Content: 8 Columns --}}
        <div class="lg:col-span-8 space-y-8">
            {{-- Stunting Alert Table --}}
            <div class="bg-white rounded-[3rem] overflow-hidden border border-slate-100 premium-shadow">
                <div class="px-10 py-8 bg-slate-50/50 border-b border-slate-100 flex justify-between items-center">
                    <div>
                        <h3 class="text-xl font-black text-slate-900 flex items-center gap-3">
                            <span class="material-symbols-outlined text-red-500">warning</span>
                            Prioritas Atensi Gizi
                        </h3>
                        <p class="text-[9px] text-slate-400 font-black uppercase tracking-widest mt-1">Status Stunting & Gizi Buruk Terdeteksi</p>
                    </div>
                    <div class="flex items-center gap-2 px-4 py-2 rounded-2xl bg-white border border-slate-100 shadow-sm">
                        <span class="w-2 h-2 rounded-full bg-red-500 animate-ping"></span>
                        <span class="text-[10px] font-black text-slate-600 uppercase">{{ count($balitaStunting) }} Kasus</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/30">
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest">Balita</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Usia</th>
                                <th class="px-10 py-5 text-[9px] font-black text-slate-400 uppercase tracking-widest text-center">Status</th>
                                <th class="px-10 py-5 text-right text-[9px] font-black text-slate-400 uppercase tracking-widest">Kendali</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($balitaStunting as $balita)
                            <tr class="hover:bg-slate-50 transition-colors group">
                                <td class="px-10 py-5">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-400 flex items-center justify-center font-black text-xs group-hover:bg-red-500 group-hover:text-white transition-all duration-500">
                                            {{ strtoupper(substr($balita->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-black text-slate-900 group-hover:text-teal-600 transition-colors">{{ $balita->full_name }}</span>
                                            <span class="text-[9px] text-slate-300 font-bold uppercase tracking-tighter">ID: {{ $balita->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-10 py-5 text-xs font-black text-slate-600 text-center">{{ $balita->age }}</td>
                                <td class="px-10 py-5 text-center">
                                    @php
                                        $latestRecord = $balita->medicalRecords->first();
                                        // Find which status is problematic
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
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-tighter bg-red-50 text-red-600 border border-red-100">
                                        {{ $displayStatus }}
                                    </span>
                                </td>
                                <td class="px-10 py-5 text-right">
                                    <a href="{{ route('admin.patients.show', $balita->id) }}" class="inline-flex w-8 h-8 items-center justify-center rounded-xl bg-slate-50 text-slate-300 hover:bg-teal-500 hover:text-white hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-10 py-24 text-center">
                                    <div class="flex flex-col items-center gap-4">
                                        <div class="w-16 h-16 rounded-3xl bg-slate-50 flex items-center justify-center text-slate-200">
                                            <span class="material-symbols-outlined text-[32px]">verified_user</span>
                                        </div>
                                        <p class="text-[10px] font-black text-slate-300 uppercase tracking-widest">Semua data terpantau normal</p>
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
            
            {{-- Upcoming Schedule Widget (Premium) --}}
            <div class="relative group rounded-[3rem] p-8 bg-white border border-slate-100 premium-shadow hover:border-teal-200 transition-all duration-500 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-teal-50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-500/20">
                                <span class="material-symbols-outlined text-[20px]">event</span>
                            </div>
                            <span class="text-lg font-black text-slate-900 tracking-tight">Agenda</span>
                        </div>
                        <span class="px-3 py-1 bg-teal-50 text-teal-600 text-[8px] font-black rounded-full uppercase tracking-widest border border-teal-100">Live</span>
                    </div>

                    @if($upcomingSchedule)
                    <div class="space-y-6">
                        <div class="flex items-center gap-5">
                            <div class="w-16 h-16 rounded-[1.5rem] bg-gradient-to-br from-slate-900 to-slate-800 flex flex-col items-center justify-center text-white shadow-xl">
                                <span class="text-[9px] font-black uppercase opacity-50 mb-0.5">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->translatedFormat('M') }}</span>
                                <span class="text-2xl font-black">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="text-lg font-black text-slate-900 mb-1 leading-tight truncate group-hover:text-teal-600 transition-colors">{{ $upcomingSchedule->title }}</h4>
                                <p class="text-[10px] text-slate-400 font-bold uppercase tracking-wider flex items-center gap-2">
                                    <span class="material-symbols-outlined text-[14px] text-teal-500">schedule</span>
                                    {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center gap-4 group/loc">
                            <div class="w-10 h-10 rounded-xl bg-white shadow-sm flex items-center justify-center text-teal-500 flex-shrink-0 group-hover/loc:bg-teal-500 group-hover/loc:text-white transition-all">
                                <span class="material-symbols-outlined text-[20px]">location_on</span>
                            </div>
                            <span class="text-[13px] text-slate-700 font-black truncate">{{ $upcomingSchedule->location ?: 'Pusat Posyandu' }}</span>
                        </div>

                        <a href="{{ route('admin.schedules.index') }}" 
                           class="w-full h-14 bg-teal-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] flex items-center justify-center hover:bg-slate-900 shadow-xl shadow-teal-600/10 transition-all">
                            Buka Kalender
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            {{-- Missing Immunizations Widget --}}
            <div class="relative group rounded-[3rem] p-8 bg-white border border-slate-100 premium-shadow hover:border-orange-200 transition-all duration-500 overflow-hidden">
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-orange-50 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-2xl bg-orange-600 text-white flex items-center justify-center shadow-lg shadow-orange-500/20">
                                <span class="material-symbols-outlined text-[20px]">vaccines</span>
                            </div>
                            <span class="text-lg font-black text-slate-900 tracking-tight">Atensi Imunisasi</span>
                        </div>
                        @if(count($missingImmunizations) > 0)
                        <span class="px-3 py-1 bg-orange-50 text-orange-600 text-[8px] font-black rounded-full uppercase tracking-widest border border-orange-100">{{ count($missingImmunizations) }} Anak</span>
                        @endif
                    </div>

                    @forelse($missingImmunizations as $item)
                    <div class="space-y-4 mb-4 last:mb-0">
                        <div class="flex items-center justify-between p-4 rounded-2xl bg-slate-50 border border-slate-100 group/item hover:bg-white hover:shadow-md transition-all">
                            <div class="flex items-center gap-3 overflow-hidden">
                                <div class="w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex-shrink-0 flex items-center justify-center font-black text-[10px]">
                                    {{ strtoupper(substr($item['patient']->full_name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-xs font-black text-slate-900 truncate">{{ $item['patient']->full_name }}</p>
                                    <p class="text-[9px] text-slate-400 font-bold uppercase truncate">Target: {{ $item['next_vaccine'] }}</p>
                                </div>
                            </div>
                            <a href="{{ route('admin.patients.show', $item['patient']->id) }}" class="w-8 h-8 flex-shrink-0 flex items-center justify-center rounded-xl bg-white text-slate-300 hover:bg-orange-500 hover:text-white transition-all">
                                <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                    @empty
                    <div class="flex flex-col items-center py-6 text-center">
                        <div class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-200 mb-3">
                            <span class="material-symbols-outlined text-[24px]">verified</span>
                        </div>
                        <p class="text-[9px] font-black text-slate-300 uppercase tracking-widest">Semua Imunisasi Terpenuhi</p>
                    </div>
                    @endforelse
                </div>
            </div>

            {{-- Nutrition Status Widget (Neo-Glass) --}}
            <div class="relative group rounded-[3rem] p-8 bg-white border border-slate-100 premium-shadow hover:border-indigo-100 transition-all duration-500 overflow-hidden">
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-8">
                        <div>
                            <h3 class="text-lg font-black text-slate-900 tracking-tight">Kondisi Gizi</h3>
                            <p class="text-[9px] text-slate-400 font-black uppercase tracking-[0.2em]">Distribusi Balita</p>
                        </div>
                        <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-500 flex items-center justify-center">
                            <span class="material-symbols-outlined text-[20px]">donut_large</span>
                        </div>
                    </div>
                    
                    <div class="relative h-60 mb-8">
                        <canvas id="nutritionStatusChart"></canvas>
                        <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                            <span class="text-4xl font-black text-slate-900 leading-none">{{ $totalBalita }}</span>
                            <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest mt-1">Total</span>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 gap-2 pt-6 border-t border-slate-50">
                        @php 
                            $ndLabels = $nutritionStatusDistribution['labels'];
                            $ndData = $nutritionStatusDistribution['data'];
                            $ndColors = ['#059669', '#f59e0b', '#f97316', '#ef4444', '#94a3b8']; 
                        @endphp
                        @foreach($ndLabels as $index => $label)
                        <div class="flex justify-between items-center p-3 rounded-2xl hover:bg-slate-50 transition-all group/item">
                            <div class="flex items-center gap-3">
                                <span class="w-2.5 h-2.5 rounded-full shadow-sm" style="background-color: {{ $ndColors[$index] ?? '#94a3b8' }}"></span>
                                <span class="text-[10px] font-black text-slate-500 group-hover/item:text-slate-900 transition-colors uppercase tracking-tighter">{{ $label }}</span>
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
    <section class="bg-white rounded-[3rem] p-10 md:p-14 border border-slate-100 premium-shadow overflow-hidden group">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-8 mb-12">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-emerald-50 text-emerald-600 text-[9px] font-black uppercase tracking-widest mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Analitik Tahunan
                </div>
                <h3 class="text-3xl font-black text-slate-900 tracking-tight">Tren Aktivitas Pemeriksaan</h3>
                <p class="text-sm text-slate-400 font-medium mt-1">Frekuensi penimbangan kumulatif selama 12 bulan terakhir</p>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Rata-rata Bulanan</p>
                    <p class="text-xl font-black text-slate-900">{{ count($monthlyWeighingData['data']) > 0 ? round(array_sum($monthlyWeighingData['data']) / count($monthlyWeighingData['data'])) : 0 }} Sesi</p>
                </div>
                <div class="w-12 h-12 rounded-2xl bg-slate-900 text-white flex items-center justify-center shadow-xl">
                    <span class="material-symbols-outlined text-[24px]">trending_up</span>
                </div>
            </div>
        </div>
        <div class="h-[400px] -mx-4">
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
