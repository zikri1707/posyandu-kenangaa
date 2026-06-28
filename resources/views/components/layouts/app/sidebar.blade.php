{{-- Sidebar Component --}}
<aside id="sidebar"
    class="shrink-0 flex flex-col h-screen fixed lg:sticky top-0 left-0 z-50 overflow-hidden transition-all duration-300 ease-in-out bg-white dark:bg-slate-900 border-r border-slate-100 dark:border-slate-800/80 shadow-md shadow-slate-100/30 dark:shadow-none"
    style="width:260px;">

    {{-- ── Logo & Toggle ── --}}
    <div class="h-16 flex items-center justify-between px-4 shrink-0 border-b border-slate-100 dark:border-slate-800/80">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group sidebar-logo min-w-0">
            <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 shadow-sm bg-gradient-to-br from-emerald-600 to-teal-600">
                <i class="fas fa-heartbeat text-white" style="font-size:15px;"></i>
            </div>
            <div class="sidebar-text overflow-hidden transition-all duration-300">
                <span class="block font-extrabold text-slate-800 dark:text-slate-100 leading-none text-[15px] tracking-tight">Posyandu</span>
                <span class="block text-slate-400 dark:text-slate-500 font-bold text-[9px] tracking-widest mt-1.5 uppercase">ILP Kenanga</span>
            </div>
        </a>

        <button id="sidebarToggleBtn"
            class="hidden lg:flex w-7 h-7 items-center justify-center rounded-lg text-slate-400 dark:text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all shrink-0"
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
        <div class="sidebar-section-label px-3.5 mb-2 mt-4 transition-all duration-300">
            <span class="block text-[10px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Ringkasan</span>
        </div>

        <a href="{{ route('dashboard') }}"
           class="{{ $navLinkBase }} {{ $isActive('dashboard') }}">
            <i class="fas fa-house-chimney w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Halaman Utama</span>
        </a>

        <a href="{{ route('admin.analytics') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.analytics') }}">
            <i class="fas fa-chart-line w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Analitik & Grafik</span>
        </a>

        {{-- Section: Manajemen --}}
        <div class="sidebar-section-label px-3.5 mb-2 mt-5 transition-all duration-300">
            <span class="block text-[10px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Manajemen</span>
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
            <i class="fas {{ $item['icon'] }} w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">{{ $item['label'] }}</span>
        </a>
        @endforeach

        {{-- Section: Laporan --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label px-3.5 mb-2 mt-5 transition-all duration-300">
            <span class="block text-[10px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Laporan & Riwayat</span>
        </div>

        <a href="{{ route('admin.reports.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.reports.*') }}">
            <i class="fas fa-chart-bar w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Laporan Bulanan</span>
        </a>

        @if(auth()->user()->isSuperAdmin())
        <a href="{{ route('admin.activity-logs.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.activity-logs.*') }}">
            <i class="fas fa-clipboard-list w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Log Aktivitas</span>
        </a>
        @endif
        @endif

        {{-- Section: Konten --}}
        @if(auth()->user()->isSuperAdmin() || auth()->user()->isAdmin() || auth()->user()->isKader())
        <div class="sidebar-section-label px-3.5 mb-2 mt-5 transition-all duration-300">
            <span class="block text-[10px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Konten</span>
        </div>

        @php
            $pendingArticlesCount = \App\Models\Article::where('status', 'pending')->count();
        @endphp
        <a href="{{ route('admin.articles.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.articles.*') }} flex items-center justify-between">
            <div class="flex items-center">
                <i class="fas fa-newspaper w-4 text-center shrink-0" style="font-size:14px;"></i>
                <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm ml-1.5">Artikel & Berita</span>
            </div>
            @if($pendingArticlesCount > 0)
                <span class="sidebar-text bg-red-500 text-white text-[10px] font-bold px-1.5 py-0.5 rounded-md min-w-5 text-center shrink-0">
                    {{ $pendingArticlesCount }}
                </span>
            @endif
        </a>

        <a href="{{ route('admin.gallery.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.gallery.*') }}">
            <i class="fas fa-images w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Galeri</span>
        </a>
        @endif

        {{-- Section: Sistem --}}
        @if(auth()->user()->isSuperAdmin())
        <div class="sidebar-section-label px-3.5 mb-2 mt-5 transition-all duration-300">
            <span class="block text-[10px] font-black tracking-widest text-slate-400 dark:text-slate-500 uppercase">Sistem</span>
        </div>

        <a href="{{ route('admin.users.index') }}"
           class="{{ $navLinkBase }} {{ $isActive('admin.users.*') }}">
            <i class="fas fa-user-shield w-4 text-center shrink-0" style="font-size:14px;"></i>
            <span class="sidebar-text whitespace-nowrap transition-all duration-300 text-sm">Manajemen User</span>
        </a>
        @endif

    </nav>

    {{-- ── User Footer ── --}}
    <div class="shrink-0 p-3 border-t border-slate-100 dark:border-slate-800/80">
        <div class="flex items-center gap-3 p-2 rounded-xl transition-all duration-200 hover:bg-slate-50 dark:hover:bg-slate-800/40 group">
            {{-- Avatar --}}
            <x-avatar :name="Auth::user()->name" size="small" status="online" />

            {{-- Info --}}
            <div class="sidebar-text flex-1 min-w-0 transition-all duration-300">
                <p class="text-slate-800 dark:text-slate-200 font-bold truncate leading-tight text-[13px]">{{ Auth::user()->name ?? 'Admin' }}</p>
                <p class="text-slate-400 dark:text-slate-500 truncate text-[11px] font-semibold mt-0.5">
                    {{ auth()->user()->role_label }}
                </p>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}" class="shrink-0 sidebar-text overflow-hidden transition-all duration-300">
                @csrf
                <button type="submit" 
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-400 dark:text-slate-500 hover:text-red-500 dark:hover:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-all cursor-pointer border-0" 
                        title="Keluar">
                    <i class="fas fa-right-from-bracket text-[14px]"></i>
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