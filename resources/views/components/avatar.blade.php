@props([
    'src' => null,
    'alt' => 'Avatar',
    'size' => 'md', // xs, sm, md, lg, xl
    'rounded' => 'full', // full, lg, none
    'border' => false,
    'borderColor' => 'gray-200',
    'placeholder' => null,
    'initials' => null,
])

@php
    $sizes = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8',
        'md' => 'w-10 h-10',
        'lg' => 'w-12 h-12',
        'xl' => 'w-16 h-16',
    ];
    
    $roundedClasses = [
        'full' => 'rounded-full',
        'lg' => 'rounded-lg',
        'none' => 'rounded-none',
    ];
    
    $borderClass = $border ? 'border-2 border-' . $borderColor : '';
@endphp

<div class="relative inline-block">
    @if($src)
        <img 
            src="{{ $src }}" 
            alt="{{ $alt }}" 
            class="{{ $sizes[$size] }} {{ $roundedClasses[$rounded] }} {{ $borderClass }} object-cover" />
    @elseif($initials)
        <div class="{{ $sizes[$size] }} {{ $roundedClasses[$rounded] }} {{ $borderClass }} bg-gray-200 flex items-center justify-center font-medium text-gray-600">
            <span class="text-{{ substr($size, 0, 2) === 'xl' ? 'lg' : 'sm' }}">{{ $initials }}</span>
        </div>
    @else
        <div class="{{ $sizes[$size] }} {{ $roundedClasses[$rounded] }} {{ $borderClass }} bg-gray-200 flex items-center justify-center">
            <x-icon name="user" class="w-1/2 h-1/2 text-gray-400" />
        </div>
    @endif
    
    @if($placeholder)
        <div class="absolute -bottom-1 -right-1 bg-white p-0.5 rounded-full">
            {{ $placeholder }}
        </div>
    @endif
</div>