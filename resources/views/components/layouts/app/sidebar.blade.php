{{-- Sidebar Component --}}
<aside id="sidebar"
    class="flex-shrink-0 flex flex-col h-screen fixed lg:sticky top-0 left-0 z-50 overflow-hidden transition-all duration-300 ease-in-out"
    style="width:260px; background:#ffffff; border-right:1px solid rgba(0,0,0,0.07); box-shadow:1px 0 12px rgba(0,0,0,0.04);">

    {{-- ── Logo & Toggle ── --}}
    <div class="h-16 flex items-center justify-between px-4 flex-shrink-0"
         style="border-bottom:1px solid rgba(0,0,0,0.06);">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group sidebar-logo min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center flex-shrink-0 shadow-sm"
                 style="background:linear-gradient(135deg,#006c49 0%,#0d9488 100%);">
                <i class="fas fa-heartbeat text-white" style="font-size:15px;"></i>
            </div>
            <div class="sidebar-text overflow-hidden transition-all duration-300">
                <span class="block font-extrabold text-slate-900 leading-none" style="font-size:15px; letter-spacing:-0.02em;">Posyandu</span>
                <span class="block text-slate-400 font-semibold" style="font-size:10px; letter-spacing:.08em; margin-top:2px; text-transform:uppercase;">Admin Dashboard</span>
            </div>
        </a>

        <button id="sidebarToggleBtn"
            class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-100 transition-all flex-shrink-0"
            title="Toggle sidebar">
            <i id="toggleIcon" class="fas fa-chevron-left transition-transform duration-300" style="font-size:9px;"></i>
        </button>
    </div>

    {{-- ── Navigation ── --}}
    <nav class="flex-1 overflow-y-auto overflow-x-hidden py-3 scrollbar-hide" style="padding-left:10px; padding-right:10px;">

        @php
            $navLinkBase = 'nav-link mb-0.5';
            $isActive = fn($pattern) => request()->routeIs($pattern) ? 'active' : '';
        @endphp

        {{-- Section: Overview --}}
        <div class="sidebar-section-label px-2 mb-1.5 mt-2 transition-all duration-300">
            <span class="block font-bold text-slate-400" style="font-size:10px; letter-spacing:.1em; text-transform:uppercase;">Ringkasan</span>
        </div>

        <a href="{{ route('dashboard') }}"
           class="{{ $navLinkBase }} {{ $isActive('dashboard') }}">
            <i class="fas fa-house-chimney w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Halaman Utama</span>
        </a>

        <a href="{{ route('admin.analytics') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.analytics') }}">
            <i class="fas fa-chart-line w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Analitik & Grafik</span>
        </a>

        {{-- Section: Manajemen --}}
        <div class="sidebar-section-label px-2 mb-1.5 mt-5 transition-all duration-300">
            <span class="block font-bold text-slate-400" style="font-size:10px; letter-spacing:.1em; text-transform:uppercase;">Manajemen</span>
        </div>

        @php
            $items = [];
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.patients.index', 'pattern' => 'admin.patients.*', 'icon' => 'fa-users', 'label' => 'Data Warga'];
            }
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) {
                $items[] = ['route' => 'admin.posyandu.index', 'pattern' => 'admin.posyandu.*', 'icon' => 'fa-house-medical', 'label' => 'Data Posyandu'];
            }
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.schedules.index', 'pattern' => 'admin.schedules.*', 'icon' => 'fa-calendar-days', 'label' => 'Jadwal Kegiatan'];
            }
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader()) {
                $items[] = ['route' => 'admin.medical-records.index', 'pattern' => 'admin.medical-records.index', 'icon' => 'fa-notes-medical', 'label' => 'Rekam Medis'];
            }
            if (auth()->user()->isSuperAdmin() || auth()->user()->isAdmin()) {
                $items[] = ['route' => 'admin.medical-records.bulk', 'pattern' => 'admin.medical-records.bulk', 'icon' => 'fa-file-medical', 'label' => 'Bulan Penimbangan'];
            }
        @endphp

        @foreach($items as $item)
        <a href="{{ route($item['route']) }}"
           class="{{ $navLinkBase }} {{ $isActive($item['pattern']) }}">
            <i class="fas {{ $item['icon'] }} w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach

        {{-- Section: Laporan --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label px-2 mb-1.5 mt-5 transition-all duration-300">
            <span class="block font-bold text-slate-400" style="font-size:10px; letter-spacing:.1em; text-transform:uppercase;">Laporan & Riwayat</span>
        </div>

        <a href="{{ route('admin.reports.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.reports.*') }}">
            <i class="fas fa-chart-bar w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Laporan Bulanan</span>
        </a>

        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.activity-logs.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.activity-logs.*') }}">
            <i class="fas fa-clipboard-list w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Log Aktivitas</span>
        </a>
        @endif
        @endif

        {{-- Section: Konten --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label px-2 mb-1.5 mt-5 transition-all duration-300">
            <span class="block font-bold text-slate-400" style="font-size:10px; letter-spacing:.1em; text-transform:uppercase;">Konten</span>
        </div>

        <a href="{{ route('admin.articles.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.articles.*') }}">
            <i class="fas fa-newspaper w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Artikel & Berita</span>
        </a>

        <a href="{{ route('admin.gallery.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.gallery.*') }}">
            <i class="fas fa-images w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Galeri</span>
        </a>
        @endif

        {{-- Section: Sistem --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="sidebar-section-label px-2 mb-1.5 mt-5 transition-all duration-300">
            <span class="block font-bold text-slate-400" style="font-size:10px; letter-spacing:.1em; text-transform:uppercase;">Sistem</span>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.users.*') }}">
            <i class="fas fa-user-shield w-4 text-center flex-shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Manajemen User</span>
        </a>
        @endif

    </nav>

    {{-- ── User Footer ── --}}
    <div class="flex-shrink-0 p-3" style="border-top:1px solid rgba(0,0,0,0.06);">
        <div class="flex items-center gap-3 p-2.5 rounded-xl transition-all duration-200 cursor-pointer hover:bg-slate-50 group">
            {{-- Avatar --}}
            <x-avatar :name="Auth::user()->name" size="small" status="online" />

            {{-- Info --}}
            <div class="sidebar-text flex-1 min-w-0 transition-all duration-300">
                <p class="text-slate-800 font-bold truncate leading-tight text-[13px]">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-slate-400 truncate text-[11px] font-medium mt-0.5">
                    {{ auth()->user()->role_label }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="flex-shrink-0 sidebar-text overflow-hidden transition-all duration-300">
                @csrf
                <button type="submit" class="Btn" title="Keluar">
                    <div class="sign">
                        <svg viewBox="0 0 512 512">
                            <path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"></path>
                        </svg>
                    </div>
                    <div class="text">Keluar</div>
                </button>
            </form>
        </div>
    </div>

</aside>

{{-- Mobile Overlay --}}
<div id="sidebarOverlay" class="fixed inset-0 bg-black/30 z-40 hidden lg:hidden backdrop-blur-[2px]"></div>

<script>
(function () {
    const KEY       = 'sidebar_v2_collapsed';
    const sidebar   = document.getElementById('sidebar');
    const btn       = document.getElementById('sidebarToggleBtn');
    const mobileBtn = document.getElementById('mobileSidebarToggle');
    const icon      = document.getElementById('toggleIcon');
    const main      = document.getElementById('mainContent');
    const overlay   = document.getElementById('sidebarOverlay');
    const EXP = '260px', COL = '64px';

    let collapsed = localStorage.getItem(KEY) === 'true';

    function apply(animate) {
        const isDesktop = window.innerWidth >= 1024;
        const width = isDesktop ? (collapsed ? COL : EXP) : (collapsed ? '0px' : EXP);

        document.documentElement.style.setProperty('--sidebar-width', width);
        sidebar.style.width = width;

        if (!animate) {
            sidebar.style.transition = 'none';
            if (main) main.style.transition = 'none';
        }

        if (icon) icon.style.transform = collapsed ? 'rotate(180deg)' : 'rotate(0deg)';

        const texts = document.querySelectorAll('.sidebar-text, .sidebar-section-label');
        texts.forEach(el => {
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

    document.addEventListener('click', (e) => {
        if (e.target.closest('#sidebarToggleBtn') || e.target.closest('#mobileSidebarToggle') || e.target.closest('#sidebarOverlay')) {
            toggle();
        }
    });

    window.addEventListener('resize', () => apply(false));
})();
</script>