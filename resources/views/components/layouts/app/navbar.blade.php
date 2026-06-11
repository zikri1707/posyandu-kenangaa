{{-- ── Navbar Shell ── --}}
<header id="topNavbar"
    class="glass-header sticky top-0 z-30 flex items-center justify-between h-16 px-4 md:px-6 transition-transform duration-300"
    style="font-family:'Public Sans','Inter',sans-serif;">

    {{-- ── LEFT: Mobile toggle + Search ── --}}
    <div class="flex items-center gap-3 flex-1 min-w-0">

        {{-- Mobile hamburger --}}
        <button id="mobileSidebarToggle"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 border border-slate-200 hover:bg-slate-50 hover:border-slate-300
                   active:scale-95 transition-all duration-150 flex-shrink-0">
            <i class="fas fa-bars" style="font-size:14px;"></i>
        </button>

        {{-- Global Search --}}
        <div class="hidden lg:block w-full max-w-xl">
            @livewire('global-search')
        </div>
    </div>

    {{-- ── RIGHT: Notif · Profile ── --}}
    <div class="flex items-center gap-2 flex-shrink-0"
         x-data="{ notifOpen: false, profileOpen: false }"
         @keydown.escape.window="profileOpen = false; notifOpen = false"
         wire:ignore.self>

        {{-- Mobile search toggle --}}
        <button id="mobileSearchBtn"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 border border-slate-200 hover:bg-slate-50
                   active:scale-95 transition-all duration-150">
            <i class="fas fa-search" style="font-size:13px;"></i>
        </button>

        {{-- Notification bell (Admin RW Only) --}}
        @if(auth()->user()->isSuperAdmin())
            @livewire('shared.notification-bell')
        @endif

        {{-- Divider --}}
        <div class="hidden sm:block w-px h-5 bg-slate-200 mx-1"></div>

        {{-- ── Profile dropdown ── --}}
        <div class="relative">
            <button @click="profileOpen = !profileOpen; notifOpen = false"
                class="flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-xl
                       hover:bg-slate-50 active:scale-95 transition-all duration-200 group
                       border border-transparent hover:border-slate-200">

                {{-- Avatar --}}
                <x-avatar :name="$name" size="medium" status="online" />

                {{-- Name + role --}}
                <div class="hidden md:flex flex-col items-start leading-tight">
                    <div class="flex items-center gap-1.5">
                        <span class="text-slate-800 font-bold text-sm">
                            {{ explode(' ', $name)[0] }}
                        </span>
                        <span class="px-1.5 py-0.5 rounded-md {{ $badgeClass }} text-[10px] font-bold tracking-wide">
                            {{ $role }}
                        </span>
                    </div>
                    <span class="text-slate-400 text-xs font-medium">{{ $user?->email }}</span>
                </div>

                <i class="fas fa-chevron-down text-slate-400 group-hover:text-slate-600 transition-all duration-200 hidden md:block"
                   :class="profileOpen ? 'rotate-180' : ''"
                   style="font-size:10px;"></i>
            </button>

            {{-- Profile dropdown panel --}}
            <div x-show="profileOpen"
                @click.away="profileOpen = false"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="opacity-0 translate-y-1 scale-[0.97]"
                x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                x-transition:leave="transition ease-in duration-150"
                x-transition:leave-start="opacity-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="absolute right-0 mt-2 w-64 bg-white rounded-2xl z-50 p-1.5"
                style="box-shadow:0 12px 32px -4px rgba(0,0,0,0.12), 0 0 0 1px rgba(0,0,0,0.06);"
                x-cloak>

                {{-- User card --}}
                <div class="px-3 py-3 rounded-xl mb-1 bg-slate-50 border border-slate-100">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$name" size="medium" />
                        <div class="min-w-0">
                            <p class="text-slate-900 font-bold text-sm truncate leading-tight">{{ $name }}</p>
                            <p class="text-slate-500 text-xs font-medium truncate mt-0.5">{{ $user?->email }}</p>
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold {{ $badgeClass }} mt-1">
                                {{ $role }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Menu items --}}
                <div class="py-1">
                    @foreach($menuItems as $item)
                    <a href="{{ $item['href'] }}"
                       class="flex items-center gap-3 px-3 py-2.5 text-slate-600 hover:bg-slate-50
                              hover:text-slate-900 rounded-xl transition-all group">
                        <div @class([
                            'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all',
                            'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100' => $item['color'] === 'emerald',
                            'bg-blue-50 text-blue-600 group-hover:bg-blue-100' => $item['color'] === 'blue',
                            'bg-amber-50 text-amber-600 group-hover:bg-amber-100' => $item['color'] === 'amber',
                            'bg-violet-50 text-violet-600 group-hover:bg-violet-100' => $item['color'] === 'violet',
                            'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100' => $item['color'] === 'indigo',
                        ])>
                            <i class="fas {{ $item['icon'] }} text-[12px]"></i>
                        </div>
                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Logout --}}
                <div class="pt-2 border-t border-slate-100 mt-2 flex justify-center">
                    <form method="POST" action="{{ route('logout') }}">
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
            class="w-full h-10 pl-9 pr-4 rounded-xl border border-slate-200 bg-slate-50
                   text-slate-700 placeholder-slate-400 text-sm font-medium
                   focus:outline-none focus:ring-2 focus:ring-primary/10 focus:border-primary/30
                   focus:bg-white transition-all">
    </div>
</div>

{{-- ── Navbar JS ── --}}
<script>
(function () {
    const mobileSearchBtn = document.getElementById('mobileSearchBtn');
    const mobileSearchBar = document.getElementById('mobileSearchBar');
    if (mobileSearchBtn && mobileSearchBar) {
        mobileSearchBtn.addEventListener('click', function () {
            const isHidden = mobileSearchBar.classList.contains('hidden');
            mobileSearchBar.classList.toggle('hidden', !isHidden);
            if (isHidden) mobileSearchBar.querySelector('input')?.focus();
        });
    }

    // Smart sticky navbar - bind to window since layout overflow has been fixed
    const navbar = document.getElementById('topNavbar');
    if (navbar) {
        let lastScroll = window.scrollY || document.documentElement.scrollTop;
        window.addEventListener('scroll', () => {
            const currentScroll = window.scrollY || document.documentElement.scrollTop;
            if (currentScroll > lastScroll && currentScroll > 64) {
                // Scrolling down -> hide navbar
                navbar.classList.add('-translate-y-full');
            } else {
                // Scrolling up -> show navbar
                navbar.classList.remove('-translate-y-full');
            }
            lastScroll = currentScroll <= 0 ? 0 : currentScroll;
        }, { passive: true });
    }
})();
</script>