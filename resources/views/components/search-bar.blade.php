@props([
    'placeholder' => 'Cari...',
    'model' => 'search',
    'size' => 'md' // sm, md, lg
])

@php
    $sizes = [
        'sm' => 'py-1 px-3 text-sm',
        'md' => 'py-2 px-4',
        'lg' => 'py-3 px-5 text-lg',
    ];
@endphp

<div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
        <x-icon name="magnifying-glass" class="w-5 h-5 text-gray-400" />
    </div>
    <input 
        wire:model.debounce.300ms="{{ $model }}"
        type="text" 
        class="block w-full pl-10 pr-3 border border-gray-300 rounded-lg {{ $sizes[$size] }} leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
        placeholder="{{ $placeholder }}" />
</div>