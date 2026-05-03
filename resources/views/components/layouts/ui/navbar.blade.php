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
    <div class="flex items-center gap-2 md:gap-3 flex-shrink-0">

        {{-- ── Search (desktop) ── --}}
        @livewire('global-search')

        {{-- ── Mobile search toggle ── --}}
        <button id="mobileSearchBtn"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 border border-slate-200 hover:bg-slate-50
                   active:scale-95 transition-all duration-150">
            <i class="fas fa-search" style="font-size:13px;"></i>
        </button>

        {{-- ── Notification bell ── --}}
        <div class="relative" id="notifWrapper">
            <button id="notifBtn"
                class="w-9 h-9 flex items-center justify-center rounded-xl
                       text-slate-500 border border-slate-200 hover:bg-slate-50
                       active:scale-95 transition-all duration-150 relative">
                <i class="fas fa-bell" style="font-size:13px;"></i>
                {{-- Unread dot --}}
                <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-red-500 border-2 border-white"></span>
            </button>

            {{-- Notification dropdown --}}
            <div id="notifDropdown"
                class="hidden absolute right-0 mt-2 w-80 bg-white rounded-2xl shadow-xl
                       border border-slate-100 overflow-hidden z-50"
                style="top:100%;">

                {{-- Header --}}
                <div class="flex items-center justify-between px-4 py-3 border-b border-slate-100">
                    <span class="text-slate-900 font-bold text-[13px]">Notifikasi</span>
                    <span class="text-[11px] font-semibold text-white px-2 py-0.5 rounded-full bg-red-500">3 Baru</span>
                </div>

                {{-- Items --}}
                <div class="divide-y divide-slate-50 max-h-72 overflow-y-auto">
                    @php
                        $notifs = [
                            ['icon'=>'fa-calendar-check','color'=>'bg-blue-100 text-blue-600',  'title'=>'Jadwal Posyandu Besok','desc'=>'Posyandu Melati — 08.00 WIB','time'=>'5 menit lalu','unread'=>true],
                            ['icon'=>'fa-user-plus',     'color'=>'bg-emerald-100 text-emerald-600','title'=>'Pasien Baru Terdaftar','desc'=>'Budi Santoso telah didaftarkan','time'=>'1 jam lalu','unread'=>true],
                            ['icon'=>'fa-notes-medical', 'color'=>'bg-violet-100 text-violet-600','title'=>'Rekam Medis Diperbarui','desc'=>'Data Siti Aminah diperbarui','time'=>'3 jam lalu','unread'=>true],
                            ['icon'=>'fa-chart-bar',     'color'=>'bg-amber-100 text-amber-600',  'title'=>'Laporan Bulan Ini Siap','desc'=>'Laporan April 2026 tersedia','time'=>'1 hari lalu','unread'=>false],
                        ];
                    @endphp

                    @foreach($notifs as $n)
                    <div class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition-colors cursor-pointer {{ $n['unread'] ? 'bg-blue-50/40' : '' }}">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center flex-shrink-0 {{ $n['color'] }}">
                            <i class="fas {{ $n['icon'] }}" style="font-size:12px;"></i>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-slate-800 font-semibold text-[12px] leading-tight">{{ $n['title'] }}</p>
                            <p class="text-slate-500 text-[11px] mt-0.5 truncate">{{ $n['desc'] }}</p>
                            <p class="text-slate-400 text-[10px] mt-1">{{ $n['time'] }}</p>
                        </div>
                        @if($n['unread'])
                        <div class="w-2 h-2 rounded-full bg-blue-500 flex-shrink-0 mt-1.5"></div>
                        @endif
                    </div>
                    @endforeach
                </div>

                {{-- Footer --}}
                <div class="px-4 py-2.5 border-t border-slate-100 text-center">
                    <a href="#" class="text-[12px] font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                        Lihat semua notifikasi →
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Divider ── --}}
        <div class="hidden sm:block w-px h-6 bg-slate-200 mx-1"></div>

        {{-- ── Profile dropdown ── --}}
        <div class="relative" id="profileWrapper">
            <button id="profileBtn"
                class="flex items-center gap-2.5 pl-1 pr-2 py-1 rounded-xl
                       hover:bg-slate-50 active:scale-95 transition-all duration-150 group">

                {{-- Avatar --}}
                <div class="w-8 h-8 rounded-xl flex items-center justify-center text-white font-bold flex-shrink-0 shadow-sm"
                     style="background:{{ $avatarGrad }}; font-size:11px; letter-spacing:.03em;">
                    {{ $initials }}
                </div>

                {{-- Name + role (hidden on small screens) --}}
                <div class="hidden md:flex flex-col items-start leading-none gap-0.5">
                    <span class="text-slate-900 font-black text-[13px] tracking-tight">{{ Str::limit($name, 18) }}</span>
                    <span class="text-[10px] font-black uppercase tracking-widest px-2 py-0.5 rounded-md {{ $badgeClass }}">{{ $role }}</span>
                </div>

                <i class="fas fa-chevron-down text-slate-400 group-hover:text-slate-600 transition-all duration-200 hidden md:block"
                   style="font-size:9px;" id="profileChevron"></i>
            </button>

            {{-- Profile dropdown panel --}}
            <div id="profileDropdown"
                class="hidden absolute right-0 mt-2 w-64 bg-white rounded-2xl shadow-xl
                       border border-slate-100 overflow-hidden z-50"
                style="top:100%;">

                {{-- User card --}}
                <div class="px-4 py-4 border-b border-slate-100"
                     style="background:linear-gradient(135deg,#f8fafc 0%,#f1f5f9 100%);">
                    <div class="flex items-center gap-3">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center text-white font-bold shadow-sm"
                             style="background:{{ $avatarGrad }}; font-size:13px;">
                            {{ $initials }}
                        </div>
                        <div class="min-w-0">
                            <p class="text-slate-900 font-bold text-[13px] truncate">{{ $name }}</p>
                            <p class="text-slate-500 text-[11px] truncate">{{ $user?->email }}</p>
                            <span class="inline-block text-[10px] font-semibold px-2 py-0.5 rounded-full mt-1 {{ $badgeClass }}">
                                {{ $role }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Menu items --}}
                <div class="py-1.5">
                    @php
                        $menuItems = [
                            ['href' => route('dashboard'), 'icon' => 'fa-grid-2', 'label' => 'Dashboard'],
                            ['href' => route('admin.schedules.index'), 'icon' => 'fa-calendar-days', 'label' => 'Jadwal Kegiatan'],
                        ];
                        if ($user?->isSuperAdmin() || $user?->isAdmin()) {
                            $menuItems[] = ['href' => route('admin.reports.index'), 'icon' => 'fa-chart-bar', 'label' => 'Laporan Bulanan'];
                        }
                        if ($user?->isSuperAdmin()) {
                            $menuItems[] = ['href' => route('admin.users.index'), 'icon' => 'fa-user-shield', 'label' => 'Manajemen User'];
                        }
                    @endphp

                    @foreach($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 px-4 py-2.5 text-slate-600 hover:bg-slate-50
                              hover:text-slate-900 transition-colors group">
                        <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-slate-100
                                    group-hover:bg-slate-200 transition-colors flex-shrink-0">
                            <i class="fas {{ $item['icon'] }} text-slate-500 group-hover:text-slate-700"
                               style="font-size:11px;"></i>
                        </div>
                        <span class="text-[13px] font-medium">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Logout --}}
                <div class="border-t border-slate-100 py-1.5">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2.5 text-red-500
                                   hover:bg-red-50 transition-colors group">
                            <div class="w-7 h-7 rounded-lg flex items-center justify-center bg-red-50
                                        group-hover:bg-red-100 transition-colors flex-shrink-0">
                                <i class="fas fa-arrow-right-from-bracket text-red-400 group-hover:text-red-600"
                                   style="font-size:11px;"></i>
                            </div>
                            <span class="text-[13px] font-semibold">Keluar</span>
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
    // Generic dropdown toggle
    function setupDropdown(btnId, dropId, chevronId) {
        const btn  = document.getElementById(btnId);
        const drop = document.getElementById(dropId);
        const chev = chevronId ? document.getElementById(chevronId) : null;
        if (!btn || !drop) return;

        btn.addEventListener('click', function (e) {
            e.stopPropagation();
            const open = !drop.classList.contains('hidden');
            // Close all dropdowns first
            document.querySelectorAll('[data-navbar-drop]').forEach(d => {
                d.classList.add('hidden');
            });
            document.querySelectorAll('[data-navbar-chev]').forEach(c => {
                c.style.transform = 'rotate(0deg)';
            });
            if (!open) {
                drop.classList.remove('hidden');
                if (chev) chev.style.transform = 'rotate(180deg)';
            }
        });

        drop.setAttribute('data-navbar-drop', '1');
        if (chev) chev.setAttribute('data-navbar-chev', '1');
    }

    setupDropdown('notifBtn',   'notifDropdown');
    setupDropdown('profileBtn', 'profileDropdown', 'profileChevron');

    // Close on outside click
    document.addEventListener('click', function () {
        document.querySelectorAll('[data-navbar-drop]').forEach(d => d.classList.add('hidden'));
        document.querySelectorAll('[data-navbar-chev]').forEach(c => c.style.transform = 'rotate(0deg)');
    });

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

    // Global Search Redirect
    const globalSearch = document.getElementById('globalSearch');
    if (globalSearch) {
        globalSearch.addEventListener('keypress', function (e) {
            if (e.key === 'Enter') {
                const query = this.value.trim();
                if (query) {
                    window.location.href = `{{ route('admin.patients.index') }}?search=${encodeURIComponent(query)}`;
                }
            }
        });
    }
})();
</script>
