{{-- Sidebar Component --}}
<aside id="sidebar"
    class="flex-shrink-0 flex flex-col h-screen fixed lg:sticky top-0 left-0 z-30 overflow-hidden transition-all duration-300 ease-in-out font-outfit"
    style="width:260px; background:#FFFFFF; border-right:1px solid #E2E8F0;">

    {{-- ── Logo & Toggle ── --}}
    <div class="h-16 flex items-center justify-between px-5 flex-shrink-0" style="border-bottom:1px solid #F1F5F9;">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group sidebar-logo min-w-0">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0 shadow-md shadow-emerald-500/10"
                 style="background:linear-gradient(135deg,#006c49 0%,#004f36 100%);">
                <i class="fas fa-heartbeat text-white text-[15px]"></i>
            </div>
            <div class="sidebar-text overflow-hidden">
                <span class="block font-extrabold text-slate-900 tracking-tight leading-none text-[16px]">Kenanga ILP</span>
                <span class="block text-slate-400 font-black text-[9px] uppercase tracking-widest mt-1">Dashboard Admin</span>
            </div>
        </a>

        <button id="sidebarToggleBtn"
            class="hidden lg:flex w-8 h-8 items-center justify-center rounded-lg border border-slate-100 bg-slate-50 text-slate-400 hover:text-slate-900 transition-all flex-shrink-0">
            <i id="toggleIcon" class="fas fa-chevron-left transition-transform duration-300 text-[10px]"></i>
        </button>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-6 custom-scrollbar px-3 space-y-5">

        {{-- Section: Overview --}}
        <div>
            <div class="sidebar-section-label mb-2 px-3">
                <span class="text-slate-400 font-extrabold text-[9.5px] uppercase tracking-[0.15em]">Ringkasan Data</span>
            </div>

            @php
                $navLink = 'flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all duration-200 group border-l-4';
                $active  = 'bg-emerald-50/80 text-emerald-800 font-extrabold border-emerald-600 shadow-sm shadow-emerald-500/5';
                $idle    = 'text-slate-600 hover:bg-slate-50 hover:text-slate-900 border-transparent';
            @endphp

            <div class="space-y-1">
                <a href="{{ route('dashboard') }}"
                   class="{{ $navLink }} {{ request()->routeIs('dashboard') ? $active : $idle }}">
                    <i class="fas fa-house w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('dashboard') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Halaman Utama</span>
                </a>

                <a href="{{ route('admin.analytics') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.analytics') ? $active : $idle }}">
                    <i class="fas fa-chart-line w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.analytics') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Analitik & Grafik</span>
                </a>
            </div>
        </div>

        {{-- Section: Manajemen --}}
        <div>
            <div class="sidebar-section-label mb-2 px-3">
                <span class="text-slate-400 font-extrabold text-[9.5px] uppercase tracking-[0.15em]">Manajemen Layanan</span>
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
                }
                
                // Bulan Penimbangan - only superadmin and admin
                if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) {
                    $items[] = ['route' => 'admin.medical-records.bulk', 'pattern' => 'admin.medical-records.bulk', 'icon' => 'fa-file-medical', 'label' => 'Bulan Penimbangan'];
                }
            @endphp

            <div class="space-y-1">
                @foreach($items as $item)
                <a href="{{ route($item['route']) }}"
                   class="{{ $navLink }} {{ request()->routeIs($item['pattern']) ? $active : $idle }}">
                    <i class="fas {{ $item['icon'] }} w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs($item['pattern']) ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">{{ $item['label'] }}</span>
                </a>
                @endforeach
            </div>
        </div>

        {{-- Section: Laporan --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div>
            <div class="sidebar-section-label mb-2 px-3">
                <span class="text-slate-400 font-extrabold text-[9.5px] uppercase tracking-[0.15em]">Admin RW & Riwayat</span>
            </div>

            <div class="space-y-1">
                <a href="{{ route('admin.reports.index') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.reports.*') ? $active : $idle }}">
                    <i class="fas fa-chart-bar w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.reports.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Laporan Bulanan</span>
                </a>

                @if(auth()->user()->isSuperAdmin())
                <a href="{{ route('admin.activity-logs.index') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.activity-logs.*') ? $active : $idle }}">
                    <i class="fas fa-clipboard-list w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.activity-logs.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Log Aktivitas</span>
                </a>
                @endif
            </div>
        </div>
        @endif

        {{-- Section: Konten --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div>
            <div class="sidebar-section-label mb-2 px-3">
                <span class="text-slate-400 font-extrabold text-[9.5px] uppercase tracking-[0.15em]">Konten Publik</span>
            </div>

            <div class="space-y-1">
                <a href="{{ route('admin.articles.index') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.articles.*') ? $active : $idle }}">
                    <i class="fas fa-newspaper w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.articles.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Artikel & Berita</span>
                </a>

                <a href="{{ route('admin.gallery.index') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.gallery.*') ? $active : $idle }}">
                    <i class="fas fa-images w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.gallery.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Galeri Foto</span>
                </a>
            </div>
        </div>
        @endif

        {{-- Section: Sistem --}}
        @if(auth()->user()->isSuperAdmin())
        <div>
            <div class="sidebar-section-label mb-2 px-3">
                <span class="text-slate-400 font-extrabold text-[9.5px] uppercase tracking-[0.15em]">Sistem</span>
            </div>

            <div class="space-y-1">
                <a href="{{ route('admin.users.index') }}"
                   class="{{ $navLink }} {{ request()->routeIs('admin.users.*') ? $active : $idle }}">
                    <i class="fas fa-user-shield w-5 text-center flex-shrink-0 text-[15px] {{ request()->routeIs('admin.users.*') ? 'text-emerald-600' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text text-[13.5px] font-semibold whitespace-nowrap">Manajemen User</span>
                </a>
            </div>
        </div>
        @endif

    </nav>

    {{-- ── User Footer ── --}}
    <div class="flex-shrink-0 p-3" style="border-top:1px solid #F1F5F9;">
        <div class="flex items-center gap-3 p-2 rounded-xl transition-all duration-200 cursor-pointer hover:bg-slate-50">
            {{-- Avatar --}}
            <div class="w-9 h-9 rounded-xl flex-shrink-0 flex items-center justify-center text-white font-extrabold text-[12px] shadow-sm"
                 style="background:linear-gradient(135deg,#1e293b 0%,#475569 100%);">
                {{ strtoupper(substr(Auth::user()->name ?? 'A', 0, 2)) }}
            </div>

            {{-- Info --}}
            <div class="sidebar-text flex-1 min-w-0">
                <p class="text-slate-800 font-extrabold truncate leading-tight text-[12px]">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-slate-400 truncate text-[10px] font-semibold mt-0.5">
                    {{ auth()->user()->role_label }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 sidebar-text">
                @csrf
                <button type="submit"
                    class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 hover:bg-red-50 hover:text-red-500 transition-all"
                    title="Keluar">
                    <i class="fas fa-arrow-right-from-bracket text-[11px]"></i>
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