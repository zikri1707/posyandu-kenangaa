{{-- Sidebar Component --}}
<aside id="sidebar"
    class="flex-shrink-0 flex flex-col h-screen fixed top-0 left-0 z-30 overflow-hidden transition-all duration-300 ease-in-out"
    style="width:260px; background:#FFFFFF; border-right:1px solid #F1F5F9;">

    {{-- ── Logo & Toggle ── --}}
    <div class="h-16 flex items-center justify-between px-5 flex-shrink-0" style="border-bottom:1px solid #F1F5F9;">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group sidebar-logo min-w-0">
            <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#0d9488 0%,#0f766e 100%);">
                <i class="fas fa-heartbeat text-white" style="font-size:16px;"></i>
            </div>
            <div class="sidebar-text overflow-hidden">
                <span class="block font-black text-slate-900 tracking-tight leading-none" style="font-size:17px;">Posyandu</span>
                <span class="block text-slate-500 font-bold" style="font-size:11px; letter-spacing:.06em; margin-top:2px;">DASHBOARD ADMIN</span>
            </div>
        </a>

        <button id="sidebarToggleBtn"
            class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg transition-all flex-shrink-0"
            style="color:#94a3b8; hover-bg:#f8fafc;"
            onmouseenter="this.style.background='#f8fafc'; this.style.color='#0f172a';"
            onmouseleave="this.style.background='transparent'; this.style.color='#94a3b8';">
            <i id="toggleIcon" class="fas fa-chevron-left transition-transform duration-300" style="font-size:9px;"></i>
        </button>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-5 custom-scrollbar" style="padding-left:12px; padding-right:12px;">

        {{-- Section: Overview --}}
        <div class="sidebar-section-label mt-2 mb-2 px-3">
            <span class="text-slate-500 font-black" style="font-size:11px; letter-spacing:.12em; text-transform:uppercase;">Ringkasan Data</span>
        </div>

        @php
            $navLink = 'flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group';
            $active  = 'bg-slate-900 text-white shadow-sm';
            $idle    = 'text-slate-500 hover:bg-slate-50 hover:text-slate-800';
        @endphp

        <a href="{{ route('dashboard') }}"
           class="{{ $navLink }} {{ request()->routeIs('dashboard') ? $active : $idle }} mb-1">
            <i class="fas fa-house w-5 text-center flex-shrink-0 text-[15px]"></i>
            <span class="sidebar-text text-[14.5px] font-bold whitespace-nowrap">Halaman Utama</span>
        </a>

        <a href="{{ route('admin.analytics') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.analytics') ? $active : $idle }} mb-1">
            <i class="fas fa-chart-line w-5 text-center flex-shrink-0 text-[15px]"></i>
            <span class="sidebar-text text-[14.5px] font-bold whitespace-nowrap">Analitik & Grafik</span>
        </a>

        {{-- Section: Manajemen --}}
        <div class="sidebar-section-label mt-6 mb-2 px-3">
            <span class="text-slate-500 font-black" style="font-size:11px; letter-spacing:.12em; text-transform:uppercase;">Manajemen Layanan</span>
        </div>

        @php
            $items = [];
            
            // Data Warga - accessible by all authenticated users
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.patients.index', 'pattern' => 'admin.patients.*', 'icon' => 'fa-users', 'label' => 'Data Warga'];
            }
            
            // Data Posyandu - only superadmin and admin
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) {
                $items[] = ['route' => 'admin.posyandu.index', 'pattern' => 'admin.posyandu.*', 'icon' => 'fa-house-medical', 'label' => 'Data Posyandu'];
            }
            
            // Jadwal Kegiatan - accessible by all authenticated users
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.schedules.index', 'pattern' => 'admin.schedules.*', 'icon' => 'fa-calendar-days', 'label' => 'Jadwal Kegiatan'];
            }
            
            // Rekam Medis - accessible by all authenticated users
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.medical-records.index', 'pattern' => 'admin.medical-records.index', 'icon' => 'fa-notes-medical', 'label' => 'Rekam Medis'];
                $items[] = ['route' => 'admin.medical-records.bulk', 'pattern' => 'admin.medical-records.bulk', 'icon' => 'fa-file-medical', 'label' => 'Bulan Penimbangan'];
            }
        @endphp

        @foreach($items as $item)
        <a href="{{ route($item['route']) }}"
           class="{{ $navLink }} {{ request()->routeIs($item['pattern']) ? $active : $idle }} mb-1">
            <i class="fas {{ $item['icon'] }} w-5 text-center flex-shrink-0 text-[15px]"></i>
            <span class="sidebar-text text-[14.5px] font-bold whitespace-nowrap">{{ $item['label'] }}</span>
        </a>
        @endforeach

        {{-- Section: Laporan --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label mt-6 mb-2 px-3">
            <span class="text-slate-500 font-black" style="font-size:11px; letter-spacing:.12em; text-transform:uppercase;">Laporan & Riwayat</span>
        </div>

        <a href="{{ route('admin.reports.index') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.reports.*') ? $active : $idle }} mb-1">
            <i class="fas fa-chart-bar w-5 text-center flex-shrink-0 text-[15px]"></i>
            <span class="sidebar-text text-[14.5px] font-bold whitespace-nowrap">Laporan Bulanan</span>
        </a>

        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.activity-logs.index') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.activity-logs.*') ? $active : $idle }} mb-0.5">
            <i class="fas fa-clipboard-list w-4 text-center flex-shrink-0 text-[13px]"></i>
            <span class="sidebar-text text-[13px] font-semibold whitespace-nowrap">Log Aktivitas</span>
        </a>
        @endif
        @endif

        {{-- Section: Konten --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label mt-5 mb-1 px-2">
            <span class="text-slate-400 font-bold" style="font-size:9.5px; letter-spacing:.12em; text-transform:uppercase;">Konten</span>
        </div>

        <a href="{{ route('admin.articles.index') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.articles.*') ? $active : $idle }} mb-0.5">
            <i class="fas fa-newspaper w-4 text-center flex-shrink-0 text-[13px]"></i>
            <span class="sidebar-text text-[13px] font-semibold whitespace-nowrap">Artikel & Berita</span>
        </a>

        <a href="{{ route('admin.gallery.index') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.gallery.*') ? $active : $idle }} mb-0.5">
            <i class="fas fa-images w-4 text-center flex-shrink-0 text-[13px]"></i>
            <span class="sidebar-text text-[13px] font-semibold whitespace-nowrap">Galeri</span>
        </a>
        @endif

        {{-- Section: Sistem --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="sidebar-section-label mt-5 mb-1 px-2">
            <span class="text-slate-400 font-bold" style="font-size:9.5px; letter-spacing:.12em; text-transform:uppercase;">Sistem</span>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="{{ $navLink }} {{ request()->routeIs('admin.users.*') ? $active : $idle }} mb-0.5">
            <i class="fas fa-user-shield w-4 text-center flex-shrink-0 text-[13px]"></i>
            <span class="sidebar-text text-[13px] font-semibold whitespace-nowrap">Manajemen User</span>
        </a>
        @endif

    </nav>

    {{-- ── User Footer ── --}}
    <div class="flex-shrink-0 p-3" style="border-top:1px solid #F1F5F9;">
        <div class="flex items-center gap-3 p-2 rounded-xl transition-all duration-200 cursor-pointer hover:bg-slate-50">
            {{-- Avatar --}}
            <div class="w-8 h-8 rounded-lg flex-shrink-0 flex items-center justify-center text-white font-bold text-xs shadow-sm"
                 style="background:linear-gradient(135deg,#1e293b 0%,#475569 100%); font-size:11px;">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>

            {{-- Info --}}
            <div class="sidebar-text flex-1 min-w-0">
                <p class="text-slate-800 font-bold truncate leading-tight" style="font-size:11px;">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-slate-400 truncate" style="font-size:10px;">
                    @php
                        $roleName = auth()->user()->display_role_name;
                        if ($roleName === 'superadmin') {
                            $label = 'Super Admin';
                        } else {
                            $label = ucfirst(substr($roleName, 0, -1)) . ' ' . substr($roleName, -1);
                        }
                    @endphp
                    {{ $label }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 sidebar-text">
                @csrf
                <button type="submit"
                    class="w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 transition-all"
                    onmouseenter="this.style.background='#fee2e2'; this.style.color='#ef4444';"
                    onmouseleave="this.style.background='transparent'; this.style.color='#94a3b8';"
                    title="Keluar">
                    <i class="fas fa-arrow-right-from-bracket" style="font-size:11px;"></i>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- Mobile Overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/40 z-20 hidden lg:hidden backdrop-blur-sm"></div>

<script>
(function () {
    const KEY        = 'sidebar_v2_collapsed';
    const sidebar    = document.getElementById('sidebar');
    const btn        = document.getElementById('sidebarToggleBtn');
    const mobileBtn  = document.getElementById('mobileSidebarToggle');
    const icon       = document.getElementById('toggleIcon');
    const main       = document.getElementById('mainContent');
    const overlay    = document.getElementById('sidebarOverlay');
    const EXP = '260px', COL = '68px';

    let collapsed = localStorage.getItem(KEY) === 'true';

    function apply(animate) {
        const isDesktop = window.innerWidth >= 1024;
        const width = isDesktop ? (collapsed ? COL : EXP) : (collapsed ? '0px' : EXP);
        
        // Update CSS Variable
        document.documentElement.style.setProperty('--sidebar-width', width);

        if (!animate) {
            sidebar.style.transition = 'none';
            if (main) main.style.transition = 'none';
        }

        if (icon) icon.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';

        const els = document.querySelectorAll('.sidebar-text, .sidebar-section-label');
        els.forEach(el => {
            el.style.opacity  = collapsed ? '0' : '1';
            el.style.maxWidth = collapsed ? '0' : '200px';
        });

        if (overlay) {
            overlay.classList.toggle('hidden', isDesktop || collapsed);
        }

        if (!animate) {
            requestAnimationFrame(() => {
                sidebar.style.transition = '';
                if (main) main.style.transition = '';
            });
        }
    }

    function toggle() {
        collapsed = !collapsed;
        localStorage.setItem(KEY, collapsed);
        apply(true);
    }

    apply(false);

    btn?.addEventListener('click', toggle);
    mobileBtn?.addEventListener('click', toggle);
    overlay?.addEventListener('click', toggle);
    window.addEventListener('resize', () => apply(false));
})();
</script>