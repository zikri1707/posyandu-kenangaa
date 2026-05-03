{{--
    Posyandu Pagination Component
    ─────────────────────────────
    Usage:
        <x-layouts.ui.pagination :paginator="$items" />

    Optional props:
        :show-info="true"   — tampilkan "Menampilkan X–Y dari Z data" (default: true)
        :simple="false"     — hanya prev/next tanpa nomor halaman (default: false)

    Juga dipakai sebagai override Laravel default pagination via:
        php artisan vendor:publish --tag=laravel-pagination
    (lihat resources/views/vendor/pagination/tailwind.blade.php)
--}}

@props([
    'paginator',
    'showInfo' => true,
    'simple'   => false,
])

@php
    $hasPages = $paginator->hasPages();
    $total    = $paginator->total();
    $from     = $paginator->firstItem() ?? 0;
    $to       = $paginator->lastItem()  ?? 0;
    $current  = $paginator->currentPage();
    $last     = $paginator->lastPage();

    // Rentang halaman yang ditampilkan (maks 5 di sekitar halaman aktif)
    $window = 2;
    $start  = max(1, $current - $window);
    $end    = min($last, $current + $window);
@endphp

@if($hasPages)
<nav
    role="navigation"
    aria-label="Navigasi halaman"
    class="flex flex-col sm:flex-row items-center justify-between gap-3 px-1 py-1"
    style="font-family:'Public Sans',sans-serif;">

    {{-- ── Info: "Menampilkan X–Y dari Z data" ── --}}
    @if($showInfo && $from)
    <p class="text-[13px] font-medium text-on-surface-variant order-2 sm:order-1 whitespace-nowrap">
        Menampilkan
        <span class="font-bold text-on-surface">{{ number_format($from) }}</span>–<span class="font-bold text-on-surface">{{ number_format($to) }}</span>
        dari
        <span class="font-bold text-on-surface">{{ number_format($total) }}</span>
        data
    </p>
    @endif

    {{-- ── Page Controls ── --}}
    <div class="flex items-center gap-1 order-1 sm:order-2">

        {{-- Prev --}}
        @if($paginator->onFirstPage())
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                         text-on-surface-variant opacity-40 cursor-not-allowed select-none"
                  aria-disabled="true">
                <i class="fas fa-chevron-left text-[11px]"></i>
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}"
               wire:navigate
               rel="prev"
               aria-label="Halaman sebelumnya"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                      bg-surface-container-lowest text-on-surface-variant
                      hover:bg-surface-container hover:text-on-surface
                      active:scale-95 transition-all duration-150">
                <i class="fas fa-chevron-left text-[11px]"></i>
            </a>
        @endif

        @if(!$simple)
            {{-- First page + ellipsis --}}
            @if($start > 1)
                <a href="{{ $paginator->url(1) }}"
                   wire:navigate
                   aria-label="Halaman 1"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                          bg-surface-container-lowest text-on-surface-variant text-[13px] font-semibold
                          hover:bg-surface-container hover:text-on-surface
                          active:scale-95 transition-all duration-150">
                    1
                </a>
                @if($start > 2)
                    <span class="inline-flex items-center justify-center w-9 h-9 text-on-surface-variant text-[13px] select-none">
                        …
                    </span>
                @endif
            @endif

            {{-- Page window --}}
            @foreach(range($start, $end) as $page)
                @if($page === $current)
                    <span aria-current="page"
                          aria-label="Halaman {{ $page }}, halaman aktif"
                          class="inline-flex items-center justify-center w-9 h-9 rounded-xl text-[13px] font-bold
                                 text-on-primary shadow-sm select-none"
                          style="background:var(--color-primary, #00685f);">
                        {{ $page }}
                    </span>
                @else
                    <a href="{{ $paginator->url($page) }}"
                       wire:navigate
                       aria-label="Halaman {{ $page }}"
                       class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                              bg-surface-container-lowest text-on-surface-variant text-[13px] font-semibold
                              hover:bg-surface-container hover:text-on-surface
                              active:scale-95 transition-all duration-150">
                        {{ $page }}
                    </a>
                @endif
            @endforeach

            {{-- Ellipsis + last page --}}
            @if($end < $last)
                @if($end < $last - 1)
                    <span class="inline-flex items-center justify-center w-9 h-9 text-on-surface-variant text-[13px] select-none">
                        …
                    </span>
                @endif
                <a href="{{ $paginator->url($last) }}"
                   wire:navigate
                   aria-label="Halaman {{ $last }}"
                   class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                          bg-surface-container-lowest text-on-surface-variant text-[13px] font-semibold
                          hover:bg-surface-container hover:text-on-surface
                          active:scale-95 transition-all duration-150">
                    {{ $last }}
                </a>
            @endif
        @endif

        {{-- Next --}}
        @if($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}"
               wire:navigate
               rel="next"
               aria-label="Halaman berikutnya"
               class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                      bg-surface-container-lowest text-on-surface-variant
                      hover:bg-surface-container hover:text-on-surface
                      active:scale-95 transition-all duration-150">
                <i class="fas fa-chevron-right text-[11px]"></i>
            </a>
        @else
            <span class="inline-flex items-center justify-center w-9 h-9 rounded-xl border border-outline-variant
                         text-on-surface-variant opacity-40 cursor-not-allowed select-none"
                  aria-disabled="true">
                <i class="fas fa-chevron-right text-[11px]"></i>
            </span>
        @endif

    </div>{{-- end controls --}}

</nav>
@endif
