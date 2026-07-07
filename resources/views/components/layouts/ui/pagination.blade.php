{{--
    Posyandu Premium Pagination Component
    ─────────────────────────────────────
    Inspired by Next.js Admin Template "Pagination with Icon"
    Usage:
        <x-layouts.ui.pagination :paginator="$items" />
--}}

@props([
    'paginator',
    'showInfo' => true,
    'simple'   => false,
    'label'    => 'warga',
])

@php
    $hasPages = $paginator->hasPages();
    $total    = $paginator->total();
    $from     = $paginator->firstItem() ?? 0;
    $to       = $paginator->lastItem()  ?? 0;
    $current  = $paginator->currentPage();
    $last     = $paginator->lastPage();

    // Range of pages to display around current
    $window = 2;
    $start  = max(1, $current - $window);
    $end    = min($last, $current + $window);
@endphp

@if($hasPages)
<nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col sm:flex-row items-center justify-between gap-4 py-3">
    {{-- Left: Info --}}
    @if($showInfo && $from)
    <div class="text-sm font-medium text-slate-500 dark:text-slate-400 text-center sm:text-left">
        Menampilkan <span class="text-slate-900 dark:text-white font-bold">{{ number_format($from) }}</span>
        -
        <span class="text-slate-900 dark:text-white font-bold">{{ number_format($to) }}</span>
        dari
        <span class="text-slate-900 dark:text-white font-bold">{{ number_format($total) }}</span> {{ $label }}
    </div>
    @endif

    {{-- Right: Pagination Controls --}}
    <div class="flex items-center gap-1.5 sm:gap-3">
        {{-- Previous Page Button --}}
        @if($paginator->onFirstPage())
            <span class="flex items-center justify-center w-9 sm:w-auto h-9 sm:h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 px-0 sm:px-4 text-slate-400 dark:text-slate-600 text-xs sm:text-sm font-semibold cursor-not-allowed select-none transition-all">
                <span class="material-symbols-outlined text-[20px] sm:mr-1.5">chevron_left</span>
                <span class="hidden sm:inline">Previous</span>
            </span>
        @else
            <button wire:click="previousPage" rel="prev" aria-label="Previous Page" class="flex items-center justify-center w-9 sm:w-auto h-9 sm:h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-0 sm:px-4 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/80 active:scale-95 shadow-sm text-xs sm:text-sm font-semibold transition-all cursor-pointer">
                <span class="material-symbols-outlined text-[20px] sm:mr-1.5">chevron_left</span>
                <span class="hidden sm:inline">Previous</span>
            </button>
        @endif

        {{-- Page Numbers --}}
        @if(!$simple)
            <div class="flex items-center gap-1 sm:gap-1.5">
                {{-- First Page --}}
                @if($start > 1)
                    <button wire:click="gotoPage(1)" aria-label="Go to page 1" class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-teal-600 transition-all">
                        1
                    </button>
                    @if($start > 2)
                        <span class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-slate-400 dark:text-slate-600 text-xs sm:text-sm select-none">...</span>
                    @endif
                @endif

                {{-- Page List --}}
                @foreach(range($start, $end) as $page)
                    @if($page === $current)
                        <span aria-current="page" class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl bg-teal-600 text-white text-xs sm:text-sm font-bold shadow-lg shadow-teal-500/20 select-none">
                            {{ $page }}
                        </span>
                    @else
                        <button wire:click="gotoPage({{ $page }})" aria-label="Go to page {{ $page }}" class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-teal-600 transition-all">
                            {{ $page }}
                        </button>
                    @endif
                @endforeach

                {{-- Last Page --}}
                @if($end < $last)
                    @if($end < $last - 1)
                        <span class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 text-slate-400 dark:text-slate-600 text-xs sm:text-sm select-none">...</span>
                    @endif
                    <button wire:click="gotoPage({{ $last }})" aria-label="Go to page {{ $last }}" class="flex items-center justify-center w-9 h-9 sm:w-10 sm:h-10 rounded-xl text-xs sm:text-sm font-semibold text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800/80 hover:text-teal-600 transition-all">
                        {{ $last }}
                    </button>
                @endif
            </div>
        @endif

        {{-- Next Page Button --}}
        @if($paginator->hasMorePages())
            <button wire:click="nextPage" rel="next" aria-label="Next Page" class="flex items-center justify-center w-9 sm:w-auto h-9 sm:h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-white dark:bg-slate-900 px-0 sm:px-4 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800/80 active:scale-95 shadow-sm text-xs sm:text-sm font-semibold transition-all cursor-pointer">
                <span class="hidden sm:inline">Next</span>
                <span class="material-symbols-outlined text-[20px] sm:ml-1.5">chevron_right</span>
            </button>
        @else
            <span class="flex items-center justify-center w-9 sm:w-auto h-9 sm:h-10 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-900/50 px-0 sm:px-4 text-slate-400 dark:text-slate-600 text-xs sm:text-sm font-semibold cursor-not-allowed select-none transition-all">
                <span class="hidden sm:inline">Next</span>
                <span class="material-symbols-outlined text-[20px] sm:ml-1.5">chevron_right</span>
            </span>
        @endif
    </div>
</nav>
@endif
