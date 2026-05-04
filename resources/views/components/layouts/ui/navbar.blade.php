{{-- ═══════════════════════════════════════════════════════════
    Posyandu — Top Navigation Bar
    Sticky header with search, notifications & profile dropdown
═══════════════════════════════════════════════════════════ --}}
@php
    $user = Auth::user();
    $name = $user->name ?? 'Admin';
    $initials = strtoupper(substr($name, 0, 1)) . (str_contains($name, ' ') ? strtoupper(substr(strstr($name, ' '), 1, 1)) : '');

    $role = 'Admin';
    if ($user) {
        $role = $user->display_role_name;
        // Format for display: admin1 -> Admin 1, superadmin -> Super Admin
        if ($role === 'superadmin') {
            $role = 'Super Admin';
        } else {
            $role = ucfirst(substr($role, 0, -1)) . ' ' . substr($role, -1);
        }
    }

    // Role badge colour
    $badgeClass = match(true) {
        $user?->isSuperAdmin()          => 'bg-violet-100 text-violet-700',
        $user?->isAdmin()               => 'bg-blue-100 text-blue-700',
        $user?->isKader()               => 'bg-emerald-100 text-emerald-700',
        default                         => 'bg-slate-100 text-slate-600',
    };

    // Avatar gradient
    $avatarGrad = match(true) {
        $user?->isSuperAdmin()          => 'linear-gradient(135deg,#7c3aed 0%,#a78bfa 100%)',
        $user?->isAdmin()               => 'linear-gradient(135deg,#1e40af 0%,#3b82f6 100%)',
        $user?->isKader()               => 'linear-gradient(135deg,#065f46 0%,#10b981 100%)',
        default                         => 'linear-gradient(135deg,#1e293b 0%,#475569 100%)',
    };
@endphp

{{-- ── Navbar Shell ── --}}
<header id="topNavbar"
    class="sticky top-0 z-40 flex items-center justify-between h-16 px-4 md:px-6
           bg-white/95 backdrop-blur-md border-b border-slate-100 shadow-[0_1px_3px_0_rgb(0,0,0,.04)]
           transition-shadow duration-200"
    style="font-family:'Public Sans','Inter',sans-serif;">

    {{-- ── LEFT: Mobile toggle + Page title ── --}}
    <div class="flex items-center gap-3 min-w-0">

        {{-- Mobile hamburger --}}
        <button id="mobileSidebarToggle"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 border border-slate-200 hover:bg-slate-50
                   active:scale-95 transition-all duration-150 flex-shrink-0">
            <i class="fas fa-bars" style="font-size:14px;"></i>
        </button>

        {{-- Page title / breadcrumb area --}}
        <div class="hidden sm:flex flex-col justify-center min-w-0">
            <h1 class="text-slate-900 font-extrabold leading-tight truncate" style="font-size:18px; letter-spacing:-0.02em;">
                {{ $pageTitle ?? 'Dashboard' }}
            </h1>
            <p class="text-slate-500 font-bold leading-none mt-1 truncate" style="font-size:12.5px;">
                {{ $pageSubtitle ?? 'Sistem Informasi Posyandu' }}
            </p>
        </div>
    </div>

    {{-- ── RIGHT: Search · Notif · Profile ── --}}
    <div class="flex items-center gap-2 md:gap-3 flex-shrink-0" x-data="{ notifOpen: false, profileOpen: false }">

        {{-- ── Search (desktop) ── --}}
        @livewire('global-search')

        {{-- ── Mobile search toggle ── --}}
        <button id="mobileSearchBtn"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 border border-slate-200 hover:bg-slate-50
                   active:scale-95 transition-all duration-150">
            <i class="fas fa-search" style="font-size:13px;"></i>
        </button>

        {{-- ── Notification bell (Super Admin Only) ── --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="relative">
            <button @click="notifOpen = !notifOpen; profileOpen = false" 
                class="w-10 h-10 flex items-center justify-center rounded-xl
                       text-slate-500 border border-slate-200 hover:bg-slate-100 hover:text-teal-600
                       active:scale-95 transition-all duration-150 relative shadow-sm bg-white">
                <i class="fas fa-bell" style="font-size:14px;"></i>
                {{-- Unread count calculation --}}
                @php
                    $unreadCount = \App\Models\ActivityLog::where('created_at', '>', now()->subHours(12))->count();
                @endphp
                @if($unreadCount > 0)
                <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white"></span>
                @endif
            </button>

            {{-- Notification dropdown --}}
            <div x-show="notifOpen" 
                @click.away="notifOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1"
                x-transition:enter-end="opacity-100 translate-y-0"
                class="absolute right-0 mt-3 w-80 md:w-96 bg-white rounded-3xl shadow-2xl
                       border border-slate-100 overflow-hidden z-50 py-2">

                {{-- Header --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                    <span class="text-slate-900 font-black text-sm">Notifikasi Sistem</span>
                    @if($unreadCount > 0)
                    <span class="text-[10px] font-black text-white px-2.5 py-1 rounded-full bg-red-500 uppercase tracking-widest">{{ $unreadCount }} Baru</span>
                    @endif
                </div>

                {{-- Items --}}
                <div class="divide-y divide-slate-50 max-h-96 overflow-y-auto custom-scrollbar">
                    @php
                        $recentLogs = \App\Models\ActivityLog::latest()->take(10)->get();
                        
                        $notifs = $recentLogs->map(function($log) {
                            $icon = match($log->action_type) {
                                'create' => 'fa-circle-plus',
                                'update' => 'fa-pen-to-square',
                                'delete' => 'fa-trash-can',
                                default => 'fa-bell',
                            };
                            $color = match($log->action_type) {
                                'create' => 'bg-emerald-50 text-emerald-600',
                                'update' => 'bg-blue-50 text-blue-600',
                                'delete' => 'bg-red-50 text-red-600',
                                default => 'bg-slate-50 text-slate-600',
                            };
                            
                            $title = match($log->action_type) {
                                'create' => 'Data Baru',
                                'update' => 'Pembaruan Data',
                                'delete' => 'Penghapusan',
                                default => 'Aktivitas',
                            };

                            // Link ke detail jika memungkinkan
                            $targetUrl = match(true) {
                                str_contains($log->entity_type, 'Patient') && $log->action_type !== 'delete' 
                                    => route('admin.patients.show', $log->entity_id),
                                str_contains($log->entity_type, 'MedicalRecord') && $log->action_type !== 'delete' 
                                    => route('admin.medical-records.show', $log->entity_id),
                                str_contains($log->entity_type, 'Schedule') => route('admin.schedules.index'),
                                str_contains($log->entity_type, 'Article') => route('admin.articles.index'),
                                default => route('admin.activity-logs.index'),
                            };

                            return [
                                'icon' => $icon,
                                'color' => $color,
                                'title' => $title,
                                'desc' => $log->description,
                                'time' => $log->created_at->diffForHumans(),
                                'unread' => $log->created_at->gt(now()->subHours(12)),
                                'url' => $targetUrl
                            ];
                        });
                    @endphp

                    @forelse($notifs as $n)
                    <a href="{{ $n['url'] }}" class="flex items-start gap-4 px-5 py-4 hover:bg-slate-50 transition-colors cursor-pointer {{ $n['unread'] ? 'bg-blue-50/10' : '' }}">
                        <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $n['color'] }} shadow-sm border border-current/10">
                            <i class="fas {{ $n['icon'] }}" style="font-size:14px;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-900 font-black text-[13px] leading-tight">{{ $n['title'] }}</p>
                            <p class="text-slate-500 text-[12px] mt-1 line-clamp-2 font-medium leading-relaxed">{{ $n['desc'] }}</p>
                            <p class="text-slate-400 text-[10px] mt-2 font-bold uppercase tracking-wider flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[12px]">schedule</span>
                                {{ $n['time'] }}
                            </p>
                        </div>
                        @if($n['unread'])
                        <div class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0 mt-2 shadow-sm shadow-blue-500/30"></div>
                        @endif
                    </a>
                    @empty
                    <div class="px-5 py-12 text-center">
                        <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-300">
                            <i class="fas fa-bell-slash text-2xl"></i>
                        </div>
                        <p class="text-slate-400 font-bold text-sm">Belum ada notifikasi baru</p>
                    </div>
                    @endforelse
                </div>

                {{-- Footer --}}
                <div class="px-5 py-4 border-t border-slate-50 text-center">
                    <a href="{{ route('admin.activity-logs.index') }}" class="text-[12px] font-black text-teal-600 hover:text-teal-800 transition-colors uppercase tracking-widest">
                        Lihat Semua Log Aktivitas
                    </a>
                </div>
            </div>
        </div>
        @endif

        {{-- ── Divider ── --}}
        <div class="hidden sm:block w-px h-6 bg-slate-100 mx-1"></div>

        {{-- ── Profile dropdown ── --}}
        <div class="relative">
            <button @click="profileOpen = !profileOpen; notifOpen = false"
                class="flex items-center gap-3 pl-1.5 pr-4 py-1.5 rounded-[1.25rem]
                       hover:bg-slate-50 active:scale-95 transition-all duration-300 group bg-white border border-slate-100 shadow-sm hover:shadow-md hover:border-teal-100">

                {{-- Avatar with Ring --}}
                <div class="w-10 h-10 rounded-xl flex items-center justify-center text-white font-black flex-shrink-0 shadow-lg relative group-hover:rotate-3 transition-transform"
                     style="background:{{ $avatarGrad }}; font-size:13px; letter-spacing:.05em;">
                    {{ $initials }}
                    <div class="absolute inset-0 rounded-xl border-2 border-white/20"></div>
                </div>

                {{-- Name + role --}}
                <div class="hidden md:flex flex-col items-start leading-tight">
                    <div class="flex items-center gap-2 mb-0.5">
                        <span class="text-slate-900 font-black text-[14px] tracking-tight">
                            {{ explode(' ', $name)[0] }}
                        </span>
                        <span class="px-2 py-0.5 rounded-lg {{ $badgeClass }} text-[9px] uppercase font-black tracking-widest shadow-sm">
                            {{ $role }}
                        </span>
                    </div>
                    <span class="text-slate-400 font-bold text-[10px] tracking-tight">{{ $user?->email }}</span>
                </div>

                <i class="fas fa-chevron-down text-slate-300 group-hover:text-teal-600 transition-all duration-300 hidden md:block"
                   :class="profileOpen ? 'rotate-180' : ''"
                   style="font-size:11px;"></i>
            </button>

            {{-- Profile dropdown panel (Compact & More Options) --}}
            <div x-show="profileOpen"
                @click.away="profileOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                class="absolute right-0 mt-2.5 w-72 bg-white rounded-[2rem] shadow-[0_15px_40px_rgba(0,0,0,0.12)]
                       border border-slate-100 overflow-hidden z-50 p-2">

                {{-- User Card Section (More Compact) --}}
                <div class="px-4 py-4 rounded-3xl mb-2 relative overflow-hidden group/card bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3.5 relative z-10">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-black shadow-lg"
                             style="background:{{ $avatarGrad }}; font-size:13px;">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-slate-900 font-black text-[14px] truncate leading-tight mb-0.5">{{ $name }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-lg text-[8.5px] font-black uppercase tracking-wider {{ $badgeClass }} shadow-sm">
                                {{ $role }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Menu items (Compact) --}}
                <div class="space-y-0.5">
                    @php
                        $menuItems = [
                            ['href' => route('dashboard'), 'icon' => 'fa-house', 'label' => 'Dashboard', 'color' => 'emerald'],
                            ['href' => route('admin.patients.index'), 'icon' => 'fa-users', 'label' => 'Data Warga', 'color' => 'blue'],
                            ['href' => route('admin.schedules.index'), 'icon' => 'fa-calendar-days', 'label' => 'Jadwal', 'color' => 'amber'],
                        ];
                        
                        if ($user?->isSuperAdmin() || $user?->isAdmin()) {
                            $menuItems[] = ['href' => route('admin.reports.index'), 'icon' => 'fa-chart-bar', 'label' => 'Laporan Bulanan', 'color' => 'violet'];
                        }
                        
                        if ($user?->isSuperAdmin()) {
                            $menuItems[] = ['href' => route('admin.activity-logs.index'), 'icon' => 'fa-clipboard-list', 'label' => 'Log Aktivitas', 'color' => 'indigo'];
                        }
                    @endphp

                    @foreach($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3.5 px-3 py-2.5 text-slate-600 hover:bg-slate-50
                              hover:text-slate-900 rounded-xl transition-all group font-bold relative">
                        <div @class([
                            'w-8 h-8 rounded-lg flex items-center justify-center transition-all flex-shrink-0 shadow-sm',
                            'bg-emerald-50 text-emerald-600' => $item['color'] === 'emerald',
                            'bg-blue-50 text-blue-600' => $item['color'] === 'blue',
                            'bg-amber-50 text-amber-600' => $item['color'] === 'amber',
                            'bg-violet-50 text-violet-600' => $item['color'] === 'violet',
                            'bg-indigo-50 text-indigo-600' => $item['color'] === 'indigo',
                        ])>
                            <i class="fas {{ $item['icon'] }} text-[12px] group-hover:scale-110 transition-transform"></i>
                        </div>
                        <span class="text-[13px] tracking-tight">{{ $item['label'] }}</span>
                        <i class="fas fa-chevron-right absolute right-3 opacity-0 group-hover:opacity-40 transition-opacity text-[9px]"></i>
                    </a>
                    @endforeach
                </div>

                {{-- Logout Button (Smaller) --}}
                <div class="mt-2 pt-2 border-t border-slate-50">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full h-11 flex items-center gap-3.5 px-3 bg-red-50 text-red-600
                                   hover:bg-red-600 hover:text-white rounded-xl transition-all duration-300 group font-black shadow-sm">
                            <div class="w-8 h-8 rounded-lg bg-white flex items-center justify-center shadow-sm group-hover:bg-red-500 group-hover:text-white transition-all flex-shrink-0">
                                <i class="fas fa-power-off text-[12px]"></i>
                            </div>
                            <span class="text-[13px] uppercase tracking-widest">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

{{-- ── Mobile Search Bar (slide-down) ── --}}
<div id="mobileSearchBar"
    class="hidden lg:hidden px-4 py-2.5 bg-white border-b border-slate-100 shadow-sm">
    <div class="relative">
        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"
           style="font-size:12px;"></i>
        <input type="text"
            placeholder="Cari pasien, jadwal, artikel…"
            class="w-full h-9 pl-9 pr-4 rounded-xl border border-slate-200 bg-slate-50
                   text-slate-700 placeholder-slate-400 text-[13px] font-medium
                   focus:outline-none focus:ring-2 focus:ring-slate-900/10 focus:border-slate-300
                   focus:bg-white transition-all">
    </div>
</div>

{{-- ── Navbar JS ── --}}
<script>
(function () {
    // Mobile search toggle
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    if (mobileSearchBtn && mobileSearchBar) {
        mobileSearchBtn.addEventListener('click', function () {
            mobileSearchBar.classList.toggle('hidden');
            if (!mobileSearchBar.classList.contains('hidden')) {
                mobileSearchBar.querySelector('input')?.focus();
            }
        });
    }
})();
</script>
