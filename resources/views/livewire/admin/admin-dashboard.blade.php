<div class="space-y-6">
    {{-- Dashboard-specific styles --}}
    @push('styles')
        <style>
            .hero-gradient {
                background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #134e4a 100%);
            }

            .hero-orb-1 {
                background: radial-gradient(circle, rgba(20, 184, 166, 0.25) 0%, transparent 70%);
            }

            .hero-orb-2 {
                background: radial-gradient(circle, rgba(99, 102, 241, 0.2) 0%, transparent 70%);
            }

            .kpi-card {
                background: white;
                border: 1px solid rgba(0, 0, 0, 0.06);
                border-radius: 16px;
                padding: 1.25rem;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                transition: all 200ms ease-out;
                cursor: default;
            }

            .kpi-card:hover {
                transform: translateY(-3px);
                box-shadow: 0 8px 24px -4px rgba(0, 104, 73, 0.12);
                border-color: rgba(0, 104, 73, 0.15);
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
                border: 1px solid rgba(0, 0, 0, 0.06);
                border-radius: 20px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
                overflow: hidden;
            }

            .widget-header {
                padding: 1.25rem 1.5rem;
                border-bottom: 1px solid rgba(0, 0, 0, 0.05);
                display: flex;
                align-items: center;
                justify-content: space-between;
            }

            .table-row-hover:hover {
                background: #f8fafb;
            }

            @keyframes ping-slow {

                0%,
                100% {
                    transform: scale(1);
                    opacity: 0.8;
                }

                50% {
                    transform: scale(1.8);
                    opacity: 0;
                }
            }

            .animate-ping-slow {
                animation: ping-slow 2s ease-in-out infinite;
            }
        </style>
    @endpush

    @php
        $hour = now()->hour;
        $sapa =
            $hour < 11
                ? 'Selamat Pagi'
                : ($hour < 15
                    ? 'Selamat Siang'
                    : ($hour < 18
                        ? 'Selamat Sore'
                        : 'Selamat Malam'));
        $user = Auth::user();
        $posyanduName = $user->posyandu?->name ?? 'Posyandu';
    @endphp

    {{-- ── Hero Section ── --}}
    <section class="relative rounded-2xl overflow-hidden" style="background:#0f172a;">
        {{-- Background layers --}}
        <div class="absolute inset-0 hero-gradient"></div>
        <div class="absolute top-0 left-1/4 w-72 h-72 hero-orb-1 rounded-full filter blur-[60px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-72 h-72 hero-orb-2 rounded-full filter blur-[60px]"
            style="animation:pulse 4s ease-in-out 1.5s infinite;"></div>
        {{-- Grid overlay --}}
        <div class="absolute inset-0 opacity-[0.04]"
            style="background-image:url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSIjZmZmIiBzdHJva2Utd2lkdGg9IjAuNSIgZmlsbD0ibm9uZSI+PHBhdGggZD0iTTQwIDBMMCA0ME0wIDBsNDAgNDAiLz48L2c+PC9zdmc+');">
        </div>

        <div class="relative z-10 px-8 py-10 md:px-12 md:py-12 max-w-3xl">
            {{-- Live badge --}}
            <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full mb-6"
                style="background:rgba(255,255,255,0.08); border:1px solid rgba(255,255,255,0.1);">
                <span class="relative flex h-2 w-2">
                    <span
                        class="animate-ping-slow absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
                    <span class="relative inline-flex h-2 w-2 rounded-full bg-teal-400"></span>
                </span>
                <span class="text-xs font-semibold text-teal-100 tracking-wide">Sistem Informasi Posyandu Aktif</span>
            </div>

            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4 leading-tight"
                style="letter-spacing:-0.02em;">
                {{ $sapa }},
                <span class="text-transparent bg-clip-text"
                    style="background-image:linear-gradient(135deg,#5eead4,#a7f3d0);">
                    {{ explode(' ', $user->name)[0] }}!
                </span>
            </h1>

            <p class="text-slate-300 text-base max-w-xl leading-relaxed mb-8 font-normal" style="opacity:0.85;">
                Pantau indikator kesehatan masyarakat secara real-time dan tingkatkan kualitas pelayanan Posyandu di
                wilayah Anda.
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

                <button onclick="window.print()"
                    class="inline-flex items-center gap-2 h-11 px-6 rounded-xl font-semibold text-sm text-white transition-all hover:-translate-y-0.5 no-print"
                    style="background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); backdrop-filter:blur(8px);">
                    <span class="material-symbols-outlined text-[20px]">print</span>
                    Cetak Laporan
                </button>
            </div>
        </div>
    </section>

    {{-- ── Global Filter Bar ── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5 relative z-20">
        <div class="flex flex-col md:flex-row gap-4 items-end">
            <!-- Filter Periode -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Periode Pencarian</label>
                <select wire:model.live="filterPeriode"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">
                    <option value="semua">Sepanjang Waktu (Semua)</option>
                    <option value="bulan_ini">Bulan Ini</option>
                    <option value="bulan_lalu">Bulan Lalu</option>
                    <option value="tahun_ini">Tahun Ini</option>
                    <option value="tahun_lalu">Tahun Lalu</option>
                    <option value="custom">Rentang Kustom...</option>
                </select>
            </div>

            <!-- Custom Date Range -->
            @if ($filterPeriode === 'custom')
                <div class="w-full md:w-1/4 flex gap-2">
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Dari Tanggal</label>
                        <input type="date" wire:model.live="filterCustomStartDate"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-teal-500 bg-slate-50/50">
                    </div>
                    <div class="w-1/2">
                        <label class="block text-xs font-semibold text-slate-500 mb-1.5">Sampai</label>
                        <input type="date" wire:model.live="filterCustomEndDate"
                            class="w-full rounded-xl border-slate-200 text-sm focus:ring-teal-500 bg-slate-50/50">
                    </div>
                </div>
            @endif

            <!-- Filter Posyandu -->
            @if (Auth::user()->isSuperAdmin())
                <div class="w-full md:w-1/4">
                    <label class="block text-xs font-semibold text-slate-500 mb-1.5">Wilayah / Dusun (Posyandu)</label>
                    <select wire:model.live="filterPosyandu"
                        class="w-full rounded-xl border-slate-200 text-sm focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">
                        <option value="semua">Semua Wilayah</option>
                        @foreach ($availablePosyandus as $pos)
                            <option value="{{ $pos->id }}">{{ $pos->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <!-- Filter Risiko -->
            <div class="w-full md:w-1/4">
                <label class="block text-xs font-semibold text-slate-500 mb-1.5">Kategori Risiko</label>
                <select wire:model.live="filterRisiko"
                    class="w-full rounded-xl border-slate-200 text-sm focus:ring-teal-500 focus:border-teal-500 bg-slate-50/50">
                    <option value="semua">Semua Warga</option>
                    <option value="risiko_tinggi">Hanya Risiko Tinggi</option>
                </select>
            </div>

            <!-- Reset -->
            <div class="w-full md:w-auto">
                <button wire:click="resetFilters"
                    class="w-full md:w-auto h-10.5 px-5 rounded-xl font-medium text-sm text-slate-600 bg-slate-100 hover:bg-slate-200 transition flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    Reset
                </button>
            </div>

            <div wire:loading
                wire:target="filterPeriode, filterCustomStartDate, filterCustomEndDate, filterPosyandu, filterRisiko, resetFilters"
                class="ml-auto flex items-center text-teal-600 text-sm font-medium">
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-teal-600" xmlns="http://www.w3.org/2000/svg"
                    fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                    </path>
                </svg>
                Memuat Data...
            </div>
        </div>
    </div>

    {{-- ── Critical Alerts (DASH-23) ── --}}
    @php
        $giziBurukCount = $balitaStunting
            ->filter(function ($b) {
                $mr = $b->medicalRecords->first();
                return $mr &&
                    ($mr->nutrition_status === 'Gizi Buruk' ||
                        $mr->wasting_status === 'Gizi Buruk' ||
                        $mr->stunting_status === 'Sangat Pendek');
            })
            ->count();
    @endphp

    @if ($giziBurukCount > 0)
        <div
            class="bg-red-500 text-white p-5 rounded-2xl mb-6 shadow-md flex flex-col md:flex-row items-center justify-between gap-4 mt-6">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center shrink-0">
                    <span class="material-symbols-outlined text-[28px] text-white">warning</span>
                </div>
                <div>
                    <h4 class="font-bold text-lg leading-tight text-white mb-1">Perhatian: Kasus Gizi Buruk Terdeteksi!
                    </h4>
                    <p class="text-red-50 text-sm">Terdapat {{ $giziBurukCount }} anak balita dengan status Gizi Buruk
                        atau Sangat Pendek yang memerlukan penanganan segera.</p>
                </div>
            </div>
            <a href="{{ route('admin.patients.index') }}"
                class="shrink-0 bg-white text-red-600 px-5 py-2.5 rounded-xl font-bold text-sm hover:bg-red-50 transition-colors shadow-sm">
                Lihat Data Pasien
            </a>
        </div>
    @endif

    {{-- ── KPI Cards ── --}}
    @php
        $stats = [
            [
                'label' => 'Total Anak',
                'value' => $totalBalita,
                'icon' => 'child_care',
                'bg' => '#eff6ff',
                'fg' => '#2563eb',
                'unit' => 'anak',
                'link' => route('admin.patients.index', ['category' => 'balita']),
            ],
            [
                'label' => 'Total Pemeriksaan (YTD)',
                'value' => $totalPemeriksaan,
                'icon' => 'medical_services',
                'bg' => '#ecfdf5',
                'fg' => '#059669',
                'unit' => 'kali',
                'link' => route('admin.medical-records.index'),
            ],
            [
                'label' => 'Total Imunisasi (YTD)',
                'value' => $totalImunisasi,
                'icon' => 'vaccines',
                'bg' => '#f0fdfa',
                'fg' => '#0f766e',
                'unit' => 'dosis',
                'link' => null,
            ],
        ];
    @endphp

    <section class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @foreach ($stats as $s)
            @if (isset($s['link']) && $s['link'])
                <a href="{{ $s['link'] }}" class="kpi-card block cursor-pointer">
                @else
                    <div class="kpi-card">
            @endif
            <div class="kpi-icon" style="background:{{ $s['bg'] }}; color:{{ $s['fg'] }};">
                <span class="material-symbols-outlined text-[22px]">{{ $s['icon'] }}</span>
            </div>
            <p class="text-xs font-semibold text-slate-500 mb-1">{{ $s['label'] }}</p>
            <div class="flex items-baseline gap-1">
                <span class="text-2xl font-bold text-slate-900"
                    style="letter-spacing:-0.03em; font-variant-numeric:tabular-nums;">{{ number_format($s['value'] ?? 0) }}</span>
                <span class="text-xs font-medium text-slate-400">{{ $s['unit'] }}</span>
            </div>
            @if ($s['label'] === 'Total Anak' && isset($kelahiranBulanIni) && $kelahiranBulanIni > 0)
                <p
                    class="text-[10px] font-semibold text-emerald-600 mt-2 bg-emerald-50 inline-block px-2 py-0.5 rounded-full">
                    +{{ $kelahiranBulanIni }} kelahiran bulan ini
                </p>
            @endif
            @if (isset($s['link']) && $s['link'])
                </a>
            @else
</div>
@endif
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
                    <div class="w-9 h-9 rounded-xl bg-red-50 text-red-500 flex items-center justify-center shrink-0">
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
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Balita</th>
                            <th
                                class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                Usia</th>
                            <th
                                class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                Status</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($balitaStunting as $balita)
                            <tr class="table-row-hover transition-colors"
                                style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($balita->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block text-sm font-semibold text-slate-800">{{ $balita->full_name }}</span>
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
                                                $latestRecord->wasting_status,
                                            ];
                                            foreach ($possibleStatuses as $ps) {
                                                if (
                                                    str_contains($ps, 'Sangat') ||
                                                    str_contains($ps, 'Buruk') ||
                                                    str_contains($ps, 'Pendek')
                                                ) {
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
                                        <div
                                            class="w-14 h-14 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300">
                                            <span class="material-symbols-outlined text-[28px]">verified_user</span>
                                        </div>
                                        <p class="text-sm font-medium text-slate-400">Semua data terpantau normal
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Bumil Risiko Tinggi Alert Table --}}
        <div class="widget-card">
            <div class="widget-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-rose-50 text-rose-500 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">pregnant_woman</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Ibu Hamil Risiko Tinggi</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pemantauan khusus bumil berisiko</p>
                    </div>
                </div>
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-rose-50 border border-rose-100">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500 animate-pulse"></span>
                    <span class="text-xs font-semibold text-rose-600">{{ count($bumilRisikoTinggi) }} Kasus</span>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:1px solid rgba(0,0,0,0.05);">
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">Ibu
                                Hamil</th>
                            <th
                                class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                Usia</th>
                            <th
                                class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-center">
                                Kondisi</th>
                            <th
                                class="px-6 py-3.5 text-right text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($bumilRisikoTinggi as $bumil)
                            <tr class="table-row-hover transition-colors"
                                style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($bumil->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block text-sm font-semibold text-slate-800">{{ $bumil->full_name }}</span>
                                            <span class="text-xs text-slate-400">ID: {{ $bumil->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600 text-center">{{ $bumil->age }}</td>
                                <td class="px-6 py-4 text-center">
                                    <span class="badge badge-red">Risiko Tinggi</span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <a href="{{ route('admin.patients.show', $bumil->id) }}"
                                        class="inline-flex w-8 h-8 items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-rose-500 hover:text-white transition-all">
                                        <span class="material-symbols-outlined text-[18px]">arrow_forward</span>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    Tidak ada ibu hamil dengan risiko tinggi.
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
                    <div class="w-9 h-9 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">history</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Pemeriksaan Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Kunjungan pemeriksaan terbaru</p>
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
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Pasien</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Waktu Visit</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Unit Posyandu</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($recentActivities as $activity)
                            <tr class="table-row-hover transition-colors"
                                style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($activity->patient->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span class="block text-sm font-semibold text-slate-800">
                                                <a href="{{ route('admin.patients.show', $activity->patient->id) }}"
                                                    class="hover:text-teal-600 transition-colors">
                                                    {{ $activity->patient->full_name }}
                                                </a>
                                            </span>
                                            <span
                                                class="text-xs text-slate-400">{{ $activity->patient->category }}</span>
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

        {{-- Recent Immunizations --}}
        <div class="widget-card">
            <div class="widget-header">
                <div class="flex items-center gap-3">
                    <div
                        class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                        <span class="material-symbols-outlined text-[20px]">vaccines</span>
                    </div>
                    <div>
                        <h3 class="font-bold text-slate-900 text-sm">Imunisasi Terbaru</h3>
                        <p class="text-xs text-slate-500 mt-0.5">Pemberian imunisasi terbaru</p>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left">
                    <thead>
                        <tr style="background:#f9fafb; border-bottom:1px solid rgba(0,0,0,0.05);">
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Balita</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Tanggal Imunisasi</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Jenis Imunisasi</th>
                            <th class="px-6 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider">
                                Petugas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($recentImmunizations as $vaxRecord)
                            <tr class="table-row-hover transition-colors"
                                style="border-bottom:1px solid rgba(0,0,0,0.04);">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-9 h-9 rounded-xl bg-indigo-50 text-indigo-700 flex items-center justify-center font-bold text-xs shrink-0">
                                            {{ strtoupper(substr($vaxRecord->patient->full_name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <span
                                                class="block text-sm font-semibold text-slate-800">{{ $vaxRecord->patient->full_name }}</span>
                                            <span class="text-xs text-slate-400">ID:
                                                {{ $vaxRecord->patient->id }}</span>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $vaxRecord->visit_date->translatedFormat('d M Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span
                                        class="badge badge-teal">{{ $vaxRecord->vaccine_name && $vaxRecord->vaccine_name !== 'Tidak ada' ? $vaxRecord->vaccine_name : $vaxRecord->immunization }}</span>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    {{ $vaxRecord->user->name ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center text-slate-400 text-sm">
                                    Belum ada data pemberian imunisasi terbaru.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Right Side: 4 Columns --}}
    <div class="lg:col-span-4 flex flex-col gap-5">

        {{-- Upcoming Schedule Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-teal-50 rounded-full blur-3xl pointer-events-none">
            </div>
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

                @if ($upcomingSchedule)
                    <div class="space-y-4">
                        <div class="flex items-center gap-4">
                            <div
                                class="w-14 h-14 rounded-2xl bg-slate-900 flex flex-col items-center justify-center text-white shadow-lg shrink-0">
                                <span
                                    class="text-[9px] font-bold uppercase opacity-50">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->translatedFormat('M') }}</span>
                                <span
                                    class="text-xl font-bold leading-none">{{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('d') }}</span>
                            </div>
                            <div class="min-w-0">
                                <h4 class="font-bold text-slate-900 text-sm leading-tight truncate">
                                    {{ $upcomingSchedule->title }}</h4>
                                <p class="text-xs text-slate-500 flex items-center gap-1 mt-1">
                                    <span class="material-symbols-outlined text-[14px] text-teal-500">schedule</span>
                                    {{ \Carbon\Carbon::parse($upcomingSchedule->start_time)->format('H:i') }} WIB
                                </p>
                            </div>
                        </div>

                        <div class="flex items-center gap-3 p-3 rounded-xl bg-slate-50 border border-slate-100">
                            <span
                                class="material-symbols-outlined text-[18px] text-teal-500 shrink-0">location_on</span>
                            <span
                                class="text-sm text-slate-700 font-medium truncate">{{ $upcomingSchedule->location ?: 'Pusat Posyandu' }}</span>
                        </div>

                        @if (\Carbon\Carbon::parse($upcomingSchedule->start_time)->isToday())
                            <div class="pt-3 border-t border-slate-100 mt-3">
                                <p class="text-[11px] font-bold text-teal-600 mb-2.5 uppercase tracking-wider">Target
                                    Imunisasi Hari Ini</p>
                                <div class="space-y-2">
                                    @forelse($missingImmunizations->take(3) as $item)
                                        <div
                                            class="flex items-center justify-between bg-white border border-slate-100 p-2 rounded-lg shadow-sm">
                                            <div class="flex items-center gap-2 overflow-hidden">
                                                <div
                                                    class="w-6 h-6 rounded bg-orange-50 text-orange-600 flex shrink-0 items-center justify-center font-bold text-[10px]">
                                                    {{ strtoupper(substr($item['patient']->full_name, 0, 2)) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="text-xs font-semibold text-slate-800 truncate">
                                                        {{ $item['patient']->full_name }}</p>
                                                    <p class="text-[10px] text-slate-500 truncate">
                                                        {{ $item['next_vaccine'] }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">Tidak ada target khusus.</p>
                                    @endforelse
                                    @if (count($missingImmunizations) > 3)
                                        <p class="text-[10px] text-center text-teal-600 font-medium mt-1">
                                            + {{ count($missingImmunizations) - 3 }} warga lainnya
                                        </p>
                                    @endif
                                </div>
                            </div>
                        @endif

                        <a href="{{ route('admin.schedules.index') }}"
                            class="w-full h-11 bg-teal-600 text-white rounded-xl font-semibold text-sm flex items-center justify-center hover:bg-teal-700 transition-colors gap-2">
                            <span class="material-symbols-outlined text-[18px]">calendar_month</span>
                            Buka Kalender
                        </a>
                    </div>
                @else
                    <div class="flex flex-col items-center py-6 text-center">
                        <div
                            class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                            <span class="material-symbols-outlined text-[24px]">event_busy</span>
                        </div>
                        <p class="text-sm font-medium text-slate-400">Tidak ada jadwal terdekat</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Missing Immunizations Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-red-50 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-red-500 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">vaccines</span>
                        </div>
                        <span class="font-bold text-slate-900 text-sm">Atensi Imunisasi</span>
                    </div>
                    @if (count($missingImmunizations) > 0)
                        <span class="badge badge-red">{{ count($missingImmunizations) }} Anak</span>
                    @endif
                </div>

                <div class="space-y-2">
                    @forelse($missingImmunizations as $item)
                        <div
                            class="flex items-center justify-between p-3 rounded-xl bg-red-100 border border-red-400 hover:bg-red-200 hover:shadow-sm transition-all">
                            <div class="flex items-center gap-2.5 overflow-hidden">
                                <div
                                    class="w-8 h-8 rounded-lg bg-red-500 text-white shrink-0 flex items-center justify-center font-bold text-[11px]">
                                    {{ strtoupper(substr($item['patient']->full_name, 0, 2)) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-semibold text-red-900 truncate">
                                        {{ $item['patient']->full_name }}</p>
                                    <p class="text-xs text-red-500 truncate">Target: {{ $item['next_vaccine'] }}
                                    </p>
                                </div>
                            </div>
                            <a href="{{ route('admin.patients.show', $item['patient']->id) }}"
                                class="w-7 h-7 shrink-0 flex items-center justify-center rounded-lg bg-red-500 border border-red-500 text-white hover:bg-red-700 hover:border-red-700 transition-all">
                                <span class="material-symbols-outlined text-[15px]">arrow_forward</span>
                            </a>
                        </div>
                    @empty
                        <div class="flex flex-col items-center py-6 text-center">
                            <div
                                class="w-12 h-12 rounded-2xl bg-slate-50 flex items-center justify-center text-slate-300 mb-3">
                                <span class="material-symbols-outlined text-[24px]">verified</span>
                            </div>
                            <p class="text-sm font-medium text-slate-400">Semua Imunisasi Terpenuhi</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Kehadiran Balita Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-emerald-50 rounded-full blur-3xl pointer-events-none">
            </div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-5">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-emerald-500 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">how_to_reg</span>
                        </div>
                        <span class="font-bold text-slate-900 text-sm">Partisipasi (D/S)</span>
                    </div>
                    <span class="badge badge-emerald">{{ $kehadiranBalita['persentase'] }}%</span>
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div
                        class="p-3 rounded-xl bg-emerald-50 border border-emerald-100 flex flex-col justify-center items-center text-center">
                        <span class="text-xl font-bold text-emerald-700">{{ $kehadiranBalita['hadir'] }}</span>
                        <span class="text-[10px] font-semibold text-emerald-600 uppercase mt-1">Hadir</span>
                    </div>
                    <div
                        class="p-3 rounded-xl bg-rose-50 border border-rose-100 flex flex-col justify-center items-center text-center">
                        <span class="text-xl font-bold text-rose-700">{{ $kehadiranBalita['tidak_hadir'] }}</span>
                        <span class="text-[10px] font-semibold text-rose-600 uppercase mt-1">Tidak Hadir</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Ibu Hamil Trimester Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-pink-50 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-pink-500 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">pregnant_woman</span>
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">Bumil per Trimester</span>
                            <span class="text-[10px] text-slate-400">Ibu hamil terdaftar aktif</span>
                        </div>
                    </div>
                    @php $totalBumil = array_sum($bumilTrimester); @endphp
                    <div class="text-right">
                        <span class="text-xl font-bold text-pink-600">{{ $totalBumil }}</span>
                        <span class="text-[10px] text-slate-400 block">Total Bumil</span>
                    </div>
                </div>

                {{-- Ringkasan Risiko --}}
                @php $risikoCount = count($bumilRisikoTinggi ?? []); @endphp
                @if($risikoCount > 0)
                <div class="flex items-center gap-2 mb-4 p-2.5 rounded-xl bg-red-50 border border-red-200">
                    <span class="material-symbols-outlined text-red-500 text-[16px]">warning</span>
                    <span class="text-xs font-semibold text-red-700">{{ $risikoCount }} bumil terdeteksi risiko tinggi</span>
                </div>
                @else
                <div class="flex items-center gap-2 mb-4 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="material-symbols-outlined text-emerald-500 text-[16px]">check_circle</span>
                    <span class="text-xs font-semibold text-emerald-700">Tidak ada bumil risiko tinggi</span>
                </div>
                @endif

                <div class="space-y-3">
                    @foreach ([
                        'T1' => ['label' => 'Trimester 1', 'sub' => '0–13 minggu', 'color' => 'bg-pink-300'],
                        'T2' => ['label' => 'Trimester 2', 'sub' => '14–27 minggu', 'color' => 'bg-pink-500'],
                        'T3' => ['label' => 'Trimester 3', 'sub' => '28+ minggu', 'color' => 'bg-pink-700'],
                    ] as $key => $info)
                        @php
                            $count = $bumilTrimester[$key];
                            $percent = $totalBumil > 0 ? round(($count / $totalBumil) * 100, 1) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-xs mb-1">
                                <div>
                                    <span class="font-semibold text-slate-700">{{ $info['label'] }}</span>
                                    <span class="text-slate-400 ml-1">{{ $info['sub'] }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="text-slate-500">{{ $count }} orang</span>
                                    <span class="font-bold text-pink-600 w-10 text-right">{{ $percent }}%</span>
                                </div>
                            </div>
                            <div class="w-full bg-slate-100 rounded-full h-2">
                                <div class="{{ $info['color'] }} h-2 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                            </div>
                            @if(count($bumilTrimesterNames[$key]) > 0)
                            <div class="mt-2 flex flex-wrap gap-1">
                                @foreach($bumilTrimesterNames[$key] as $warga)
                                    <a href="{{ route('admin.patients.show', $warga['id']) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-pink-50 hover:bg-pink-100 text-pink-700 hover:text-pink-800 text-[10px] transition-colors border border-pink-100/50">
                                        <span class="w-1.5 h-1.5 rounded-full bg-pink-400"></span>
                                        <span class="font-medium truncate max-w-[120px]">{{ $warga['name'] }}</span>
                                        <span class="text-pink-400 text-[9px] font-normal">({{ $warga['gestational_age'] }})</span>
                                    </a>
                                @endforeach
                            </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                @if($totalBumil > 0)
                <div class="mt-4 pt-3 border-t border-slate-100 flex justify-between text-[10px] text-slate-400">
                    <span>T3 perlu perhatian lebih</span>
                    <span class="font-semibold text-pink-500">{{ $bumilTrimester['T3'] }} orang menuju persalinan</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Demografi Lansia Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-orange-50 rounded-full blur-3xl pointer-events-none"></div>
            <div class="relative z-10">
                @php
                    $totalLansia = $lansiaDemografi['60_69'] + $lansiaDemografi['70_plus'];
                    $pct60 = $totalLansia > 0 ? round(($lansiaDemografi['60_69'] / $totalLansia) * 100, 1) : 0;
                    $pct70 = $totalLansia > 0 ? round(($lansiaDemografi['70_plus'] / $totalLansia) * 100, 1) : 0;
                @endphp
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center gap-2.5">
                        <div class="w-9 h-9 rounded-xl bg-orange-500 text-white flex items-center justify-center">
                            <span class="material-symbols-outlined text-[18px]">elderly</span>
                        </div>
                        <div>
                            <span class="font-bold text-slate-900 text-sm block">Demografi Lansia</span>
                            <span class="text-[10px] text-slate-400">Usia 60 tahun ke atas</span>
                        </div>
                    </div>
                    <div class="text-right">
                        <span class="text-xl font-bold text-orange-600">{{ $totalLansia }}</span>
                        <span class="text-[10px] text-slate-400 block">Total Lansia</span>
                    </div>
                </div>

                {{-- Kartu dua kelompok --}}
                <div class="grid grid-cols-2 gap-3 mb-4">
                    <div class="p-3 rounded-xl bg-orange-50 border border-orange-200 flex flex-col justify-center items-center text-center">
                        <span class="text-2xl font-bold text-orange-700">{{ $lansiaDemografi['60_69'] }}</span>
                        <span class="text-[10px] font-semibold text-orange-600 uppercase mt-1">60–69 Tahun</span>
                        <span class="text-[10px] text-orange-400 mt-0.5">{{ $pct60 }}% dari total</span>
                    </div>
                    <div class="p-3 rounded-xl bg-red-50 border border-red-200 flex flex-col justify-center items-center text-center">
                        <span class="text-2xl font-bold text-red-700">{{ $lansiaDemografi['70_plus'] }}</span>
                        <span class="text-[10px] font-semibold text-red-600 uppercase mt-1">70+ Tahun</span>
                        <span class="text-[10px] text-red-400 mt-0.5">{{ $pct70 }}% dari total</span>
                    </div>
                </div>

                {{-- Progress proporsi --}}
                <div class="mb-3">
                    <div class="flex justify-between text-[10px] text-slate-500 mb-1">
                        <span>Proporsi kelompok usia</span>
                    </div>
                    <div class="w-full h-2.5 rounded-full overflow-hidden flex">
                        <div class="bg-orange-400 h-full transition-all duration-500" style="width: {{ $pct60 }}%"></div>
                        <div class="bg-red-500 h-full transition-all duration-500" style="width: {{ $pct70 }}%"></div>
                    </div>
                    <div class="flex justify-between text-[10px] mt-1">
                        <span class="text-orange-500 font-semibold">● 60–69 thn</span>
                        <span class="text-red-500 font-semibold">70+ thn ●</span>
                    </div>
                </div>

                {{-- Daftar Lansia --}}
                @if($totalLansia > 0)
                <div class="mt-3 mb-3 pt-3 border-t border-slate-100 space-y-2">
                    <span class="text-[10px] font-bold text-slate-500 uppercase tracking-wider block">Daftar Lansia</span>
                    
                    @if(count($lansiaDemografiNames['60_69']) > 0)
                    <div>
                        <span class="text-[9px] font-semibold text-orange-600 block mb-1">60–69 Tahun:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($lansiaDemografiNames['60_69'] as $warga)
                                <a href="{{ route('admin.patients.show', $warga['id']) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-orange-50 hover:bg-orange-100 text-orange-700 hover:text-orange-800 text-[10px] transition-colors border border-orange-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-orange-400"></span>
                                    <span class="font-medium truncate max-w-[120px]">{{ $warga['name'] }}</span>
                                    <span class="text-orange-400 text-[9px] font-normal">({{ $warga['age'] }} thn)</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if(count($lansiaDemografiNames['70_plus']) > 0)
                    <div>
                        <span class="text-[9px] font-semibold text-red-600 block mb-1">70+ Tahun:</span>
                        <div class="flex flex-wrap gap-1">
                            @foreach($lansiaDemografiNames['70_plus'] as $warga)
                                <a href="{{ route('admin.patients.show', $warga['id']) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-50 hover:bg-red-100 text-red-700 hover:text-red-800 text-[10px] transition-colors border border-red-100/50">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-400"></span>
                                    <span class="font-medium truncate max-w-[120px]">{{ $warga['name'] }}</span>
                                    <span class="text-red-400 text-[9px] font-normal">({{ $warga['age'] }} thn)</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Keterangan risiko --}}
                @if($lansiaDemografi['70_plus'] > 0)
                <div class="flex flex-col gap-2 p-2.5 rounded-xl bg-red-50 border border-red-200">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-red-500 text-[16px]">monitor_heart</span>
                        <span class="text-xs font-semibold text-red-700">{{ $lansiaDemografi['70_plus'] }} lansia (70+ thn) perlu pemantauan intensif:</span>
                    </div>
                    <div class="flex flex-wrap gap-1 pl-6">
                        @foreach($lansiaDemografiNames['70_plus'] as $warga)
                            <a href="{{ route('admin.patients.show', $warga['id']) }}" class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-red-100 hover:bg-red-200 text-red-800 hover:text-red-900 text-[10px] transition-colors border border-red-200/50">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                <span class="font-medium truncate max-w-[120px]">{{ $warga['name'] }}</span>
                                <span class="text-red-500 text-[9px] font-normal">({{ $warga['age'] }} thn)</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="flex items-center gap-2 p-2.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="material-symbols-outlined text-emerald-500 text-[16px]">check_circle</span>
                    <span class="text-xs font-semibold text-emerald-700">Semua lansia di kelompok risiko rendah</span>
                </div>
                @endif
            </div>
        </div>

        {{-- Nutrition Status Widget --}}
        <div class="widget-card p-5 relative overflow-hidden">
            <div class="absolute -right-8 -top-8 w-32 h-32 bg-indigo-50 rounded-full blur-3xl pointer-events-none">
            </div>
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
                    hiddenItems: [],
                    isEmpty: false,
                    init() {
                        if (typeof Chart === 'undefined') { setTimeout(() => this.init(), 100); return; }
                        const nd = $wire.nutritionStatusDistribution;
                        if (!nd || !nd.labels || !nd.data || nd.data.length === 0) {
                            this.isEmpty = true;
                            return;
                        }
                        this.isEmpty = nd.data.every(v => parseInt(v) === 0);
                        if (this.isEmpty) return;
                
                        const colors = nd.labels.map(label => {
                            if (label.includes('Normal') || label.includes('Baik')) return '#059669';
                            if (label.includes('Kurang') && !label.includes('Sangat')) return '#f59e0b';
                            if (label.includes('Risiko') || label.includes('Berisiko')) return '#f59e0b';
                            if (label.includes('Sangat') || label.includes('Buruk') || label.includes('Pendek')) return '#ef4444';
                            if (label.includes('Lebih') || label.includes('Obesitas')) return '#f59e0b'; // Oranye untuk Gizi Lebih
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
                                animation: { duration: 800, easing: 'easeOutQuart' },
                                onClick: (event, activeElements) => {
                                    if (activeElements && activeElements.length > 0) {
                                        const index = activeElements[0].index;
                                        const label = nd.labels[index];
                                        $wire.selectNutritionStatus(label);
                                    }
                                },
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
                    },
                    toggleVisibility(index) {
                        if (!this.chart) return;
                        this.chart.toggleDataVisibility(index);
                        this.chart.update();
                        if (this.hiddenItems.includes(index)) {
                            this.hiddenItems = this.hiddenItems.filter(i => i !== index);
                        } else {
                            this.hiddenItems.push(index);
                        }
                    }
                }" wire:ignore class="relative flex justify-center mb-5">
                    <canvas x-show="!isEmpty" x-ref="canvas" width="180" height="180"
                        style="max-width:180px;max-height:180px;"></canvas>
                    <div x-show="!isEmpty"
                        class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                        <span class="text-2xl font-bold text-slate-900 leading-none"
                            style="font-variant-numeric:tabular-nums;">{{ $chartTotal }}</span>
                        <span class="text-xs font-medium text-slate-400 mt-1">Total</span>
                    </div>
                    <template x-if="isEmpty">
                        <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                            <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">pie_chart</span>
                            <span class="text-xs font-medium">Belum ada data</span>
                        </div>
                    </template>
                </div>

                {{-- Legend --}}
                <div class="space-y-2.5 pt-4 border-t border-slate-100">
                    @foreach ($ndLabels as $index => $label)
                        @php
                            $count = $ndData[$index] ?? 0;
                            $percentage = $chartTotal > 0 ? round(($count / $chartTotal) * 100, 1) : 0;
                            $color = match (true) {
                                str_contains($label, 'Normal') || str_contains($label, 'Baik') => '#059669',
                                str_contains($label, 'Kurang') && !str_contains($label, 'Sangat') => '#f59e0b',
                                str_contains($label, 'Risiko') || str_contains($label, 'Berisiko') => '#f59e0b',
                                str_contains($label, 'Sangat') ||
                                    str_contains($label, 'Buruk') ||
                                    str_contains($label, 'Pendek')
                                    => '#ef4444',
                                str_contains($label, 'Lebih') || str_contains($label, 'Obesitas') => '#f59e0b',
                                default => '#94a3b8',
                            };
                        @endphp
                        <div class="flex items-center gap-2 transition-opacity duration-200 hover:opacity-80"
                             :class="hiddenItems.includes({{ $index }}) ? 'opacity-40 grayscale' : ''">
                            <span class="w-2 h-2 rounded-full shrink-0 cursor-pointer"
                                style="background:{{ $color }}" @click="toggleVisibility({{ $index }})"></span>
                            <span
                                class="text-xs text-slate-600 flex-1 truncate select-none cursor-pointer hover:text-indigo-600 hover:underline"
                                wire:click="selectNutritionStatus('{{ $label }}')">{{ $label }}</span>
                            <div class="flex items-center gap-2 shrink-0">
                                <div class="w-12 h-1.5 bg-slate-100 rounded-full overflow-hidden cursor-pointer" @click="toggleVisibility({{ $index }})">
                                    <div class="h-full rounded-full transition-all duration-500"
                                        style="background:{{ $color }}; width:{{ $percentage }}%;">
                                    </div>
                                </div>
                                <span class="text-xs font-semibold text-slate-700 w-8 text-right select-none cursor-pointer"
                                    style="font-variant-numeric:tabular-nums;" @click="toggleVisibility({{ $index }})">{{ $percentage }}%</span>
                                <button wire:click="selectNutritionStatus('{{ $label }}')" class="w-6 h-6 flex items-center justify-center rounded bg-slate-50 hover:bg-indigo-50 text-slate-400 hover:text-indigo-600 transition-colors" title="Lihat daftar balita">
                                    <span class="material-symbols-outlined text-[14px]">visibility</span>
                                </button>
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
            <div
                class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-50 border border-emerald-100 mb-3">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                <span class="text-xs font-semibold text-emerald-700">Analitik Tahunan</span>
            </div>
            <h3 class="font-bold text-slate-900 text-lg" style="letter-spacing:-0.02em;">Tren Aktivitas
                Pemeriksaan</h3>
            <p class="text-sm text-slate-500 mt-1">Frekuensi penimbangan kumulatif selama 12 bulan terakhir</p>
        </div>
        <div class="flex items-center gap-3 sm:shrink-0">
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
            if (typeof Chart === 'undefined') { setTimeout(() => this.init(), 100); return; }
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
                    animation: { duration: 1000, easing: 'easeOutQuart' },
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
                            bodyFont: { family: '\'Public Sans\', sans-serif', size: 13, weight: 'bold' },
                            callbacks: {
                                label: function(context) {
                                    let label = context.dataset.label || '';
                                    if (label) label += ': ';
                                    if (context.parsed.y !== null) label += context.parsed.y + ' Kunjungan';
                                    return label;
                                },
                                afterLabel: function(context) {
                                    const dataIndex = context.dataIndex;
                                    if (dataIndex > 0) {
                                        const current = context.parsed.y;
                                        const previous = context.dataset.data[dataIndex - 1];
                                        if (previous > 0) {
                                            const diff = current - previous;
                                            const percent = ((diff / previous) * 100).toFixed(1);
                                            if (diff > 0) return `▲ Naik ${percent}% dari bulan lalu`;
                                            if (diff < 0) return `▼ Turun ${Math.abs(percent)}% dari bulan lalu`;
                                            return `▶ Stabil (0%)`;
                                        } else if (current > 0) {
                                            return `▲ Naik 100% dari bulan lalu`;
                                        }
                                    }
                                    return '';
                                }
                            }
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

    {{-- ── Daftar Balita berdasarkan Status Gizi Modal ── --}}
    <div x-data="{ open: @entangle('showNutritionModal') }">
        <x-modals.info-modal title="Daftar Balita - Status Gizi: {{ $selectedNutritionStatus }}" size="lg">
            @if(empty($balitasForSelectedStatus))
                <div class="text-center py-6 text-slate-400">
                    <span class="material-symbols-outlined text-[32px] mb-2 opacity-50">child_care</span>
                    <p class="text-xs font-medium">Tidak ada balita dengan status gizi ini.</p>
                </div>
            @else
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-100 text-slate-400 text-[10px] font-bold uppercase tracking-wider">
                                <th class="py-2.5">Nama</th>
                                <th class="py-2.5">Usia</th>
                                <th class="py-2.5">Gender</th>
                                <th class="py-2.5 text-right">BB / TB</th>
                                <th class="py-2.5 pl-4">Posyandu</th>
                                <th class="py-2.5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-xs text-slate-600">
                            @foreach($balitasForSelectedStatus as $balita)
                                <tr>
                                    <td class="py-3 font-semibold text-slate-800">{{ $balita['name'] }}</td>
                                    <td class="py-3">{{ $balita['age'] }}</td>
                                    <td class="py-3">{{ $balita['gender'] }}</td>
                                    <td class="py-3 text-right font-medium text-slate-800">
                                        {{ $balita['weight'] }} kg / {{ $balita['height'] }} cm
                                    </td>
                                    <td class="py-3 pl-4">{{ $balita['posyandu_name'] }}</td>
                                    <td class="py-3 text-right">
                                        <a href="{{ route('admin.patients.show', $balita['id']) }}" 
                                           class="inline-flex w-7 h-7 items-center justify-center rounded-lg bg-slate-50 text-slate-400 hover:bg-indigo-500 hover:text-white transition-all">
                                            <span class="material-symbols-outlined text-[16px]">arrow_forward</span>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </x-modals.info-modal>
    </div>

</div>
