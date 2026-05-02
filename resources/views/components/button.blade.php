@props(['variant' => 'primary', 'size' => 'md', 'type' => 'button', 'icon' => null])

@php
    $baseClasses = 'inline-flex items-center justify-center font-bold transition-all active:scale-95 disabled:opacity-50 disabled:pointer-events-none min-h-[44px] min-w-[44px]';
    
    $variants = [
        'primary' => 'bg-premium-gradient btn-premium text-white shadow-lg',
        'secondary' => 'bg-slate-800 text-white hover:bg-slate-900 shadow-sm',
        'outline' => 'bg-transparent border border-slate-300 text-slate-700 hover:bg-slate-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 shadow-sm',
        'ghost' => 'bg-transparent text-slate-600 hover:bg-slate-100',
    ];
    
    $sizes = [
        'xs' => 'px-3 py-2 text-sm rounded-lg',
        'sm' => 'px-4 py-2.5 text-sm rounded-lg',
        'md' => 'px-6 py-3 text-base rounded-xl',
        'lg' => 'px-8 py-4 text-lg rounded-2xl',
    ];
    
    $classes = "{$baseClasses} {$variants[$variant]} {$sizes[$size]}";
@endphp

@if($attributes->has('href'))
    <a {{ $attributes->merge(['class' => $classes]) }} role="button" aria-label="{{ $slot }}">
        @if($icon)
            <span class="material-symbols-outlined mr-2" style="font-size: 1.25em;">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <span class="material-symbols-outlined mr-2" style="font-size: 1.25em;">{{ $icon }}</span>
        @endif
        {{ $slot }}
    </button>
@endif
