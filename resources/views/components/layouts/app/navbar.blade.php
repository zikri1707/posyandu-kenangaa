{{-- ── Navbar Shell ── --}}
<header id="topNavbar"
    class="glass-header sticky top-0 z-30 flex items-center justify-between h-16 px-4 md:px-6 transition-transform duration-300"
    style="font-family:'Public Sans','Inter',sans-serif;">

    {{-- ── LEFT: Mobile toggle + Search ── --}}
    <div class="flex items-center gap-3 flex-1 min-w-0">

        {{-- Mobile hamburger --}}
        <button id="mobileSidebarToggle"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800/60 hover:border-slate-300 dark:hover:border-slate-700
                   active:scale-95 transition-all duration-150 shrink-0">
            <i class="fas fa-bars" style="font-size:14px;"></i>
        </button>

        {{-- Global Search --}}
        <div class="hidden lg:block w-full max-w-xl">
            @livewire('global-search')
        </div>
    </div>

    {{-- ── RIGHT: Notif · Profile ── --}}
    <div class="flex items-center gap-2 shrink-0"
         x-data="{ profileOpen: false }"
         @keydown.escape.window="profileOpen = false; $dispatch('close-dropdowns')"
         @close-dropdowns.window="if ($event.detail !== 'profile') profileOpen = false"
         wire:ignore.self>

        {{-- Mobile search toggle --}}
        <button id="mobileSearchBtn"
            class="lg:hidden w-9 h-9 flex items-center justify-center rounded-xl
                   text-slate-500 dark:text-slate-400 border border-slate-200 dark:border-slate-800/80 hover:bg-slate-50 dark:hover:bg-slate-800/60
                   active:scale-95 transition-all duration-150">
            <i class="fas fa-search" style="font-size:13px;"></i>
        </button>

        {{-- Notification bell (Admin RW Only) --}}
        @if(auth()->user()->isSuperAdmin())
            @livewire('shared.notification-bell')
        @endif

        {{-- Divider --}}
        <div class="hidden sm:block w-px h-5 bg-slate-200 dark:bg-slate-800/80 mx-1"></div>

        {{-- ── Profile dropdown ── --}}
        <div class="relative">
            <button @click="profileOpen = !profileOpen; if(profileOpen) $dispatch('close-dropdowns', 'profile')"
                class="flex items-center gap-2.5 pl-1.5 pr-3 py-1.5 rounded-xl
                       hover:bg-slate-50 dark:hover:bg-slate-800/60 active:scale-95 transition-all duration-200 group
                       border border-transparent hover:border-slate-200 dark:hover:border-slate-800">

                {{-- Avatar --}}
                <x-avatar :name="$name" size="medium" status="online" />

                {{-- Name + role --}}
                <div class="hidden md:flex flex-col items-start leading-tight">
                    <div class="flex items-center gap-1.5">
                        <span class="text-slate-800 dark:text-slate-200 font-bold text-sm">
                            {{ explode(' ', $name)[0] }}
                        </span>
                        <span class="px-1.5 py-0.5 rounded-md {{ $badgeClass }} text-[10px] font-bold tracking-wide">
                            {{ $role }}
                        </span>
                    </div>
                    <span class="text-slate-400 dark:text-slate-500 text-xs font-medium">{{ $user?->email }}</span>
                </div>

                <i class="fas fa-chevron-down text-slate-400 dark:text-slate-500 group-hover:text-slate-600 dark:group-hover:text-slate-300 transition-all duration-200 hidden md:block"
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
                class="absolute right-0 mt-2 w-64 bg-white dark:bg-slate-900 rounded-2xl z-50 p-1.5 border border-slate-100 dark:border-slate-800/80 shadow-lg shadow-slate-200/50 dark:shadow-none"
                x-cloak>

                {{-- User card --}}
                <div class="px-3 py-3 rounded-xl mb-1 bg-slate-50 dark:bg-slate-950/40 border border-slate-100 dark:border-slate-800/60">
                    <div class="flex items-center gap-3">
                        <x-avatar :name="$name" size="medium" />
                        <div class="min-w-0">
                            <p class="text-slate-900 dark:text-slate-100 font-bold text-sm truncate leading-tight">{{ $name }}</p>
                            <p class="text-slate-500 dark:text-slate-400 text-xs font-medium truncate mt-0.5">{{ $user?->email }}</p>
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
                       class="flex items-center gap-3 px-3 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800/40 hover:text-slate-900 dark:hover:text-slate-100 rounded-xl transition-all group">
                        <div @class([
                            'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 transition-all',
                            'bg-emerald-50 text-emerald-600 group-hover:bg-emerald-100 dark:bg-emerald-950/30 dark:text-emerald-400 dark:group-hover:bg-emerald-900/30' => $item['color'] === 'emerald',
                            'bg-blue-50 text-blue-600 group-hover:bg-blue-100 dark:bg-blue-950/30 dark:text-blue-400 dark:group-hover:bg-blue-900/30' => $item['color'] === 'blue',
                            'bg-amber-50 text-amber-600 group-hover:bg-amber-100 dark:bg-amber-950/30 dark:text-amber-400 dark:group-hover:bg-amber-900/30' => $item['color'] === 'amber',
                            'bg-violet-50 text-violet-600 group-hover:bg-violet-100 dark:bg-violet-950/30 dark:text-violet-400 dark:group-hover:bg-violet-900/30' => $item['color'] === 'violet',
                            'bg-indigo-50 text-indigo-600 group-hover:bg-indigo-100 dark:bg-indigo-950/30 dark:text-indigo-400 dark:group-hover:bg-indigo-900/30' => $item['color'] === 'indigo',
                        ])>
                            <i class="fas {{ $item['icon'] }} text-[12px]"></i>
                        </div>
                        <span class="text-sm font-medium">{{ $item['label'] }}</span>
                    </a>
                    @endforeach
                </div>

                {{-- Logout --}}
                <div class="pt-1.5 border-t border-slate-100 dark:border-slate-800/80 mt-1.5">
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button type="submit" 
                                class="flex items-center w-full gap-3 px-3 py-2.5 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-xl transition-all group cursor-pointer border-0">
                            <div class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0 bg-red-50 dark:bg-red-950/30 text-red-600 dark:text-red-400 group-hover:bg-red-100 dark:group-hover:bg-red-900/30 transition-all">
                                <i class="fas fa-right-from-bracket text-[12px]"></i>
                            </div>
                            <span class="text-sm font-semibold">Keluar</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</header>

{{-- ── Mobile Search Bar (slide-down) ── --}}
<div id="mobileSearchBar"
    class="hidden lg:hidden px-4 py-2.5 bg-white dark:bg-slate-900 border-b border-slate-100 dark:border-slate-800/80 shadow-sm">
    <div class="w-full">
        <livewire:global-search />
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