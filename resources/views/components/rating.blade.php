@props([
    'value' => 0,
    'max' => 5,
    'size' => 'md', // sm, md, lg
    'color' => 'yellow', // yellow, blue, green, red, purple
    'editable' => false,
    'name' => 'rating',
])

@php
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-6 h-6',
        'lg' => 'w-8 h-8',
    ];
    
    $colors = [
        'yellow' => 'text-yellow-400',
        'blue' => 'text-blue-400',
        'green' => 'text-green-400',
        'red' => 'text-red-400',
        'purple' => 'text-purple-400',
    ];
@endphp

<div class="flex items-center">
    @for($i = 1; $i <= $max; $i++)
        @if($editable)
            <button type="button" wire:click="$set('{{ $name }}', {{ $i }})" class="focus:outline-none">
                <x-icon name="star" class="{{ $sizes[$size] }} {{ $colors[$color] }} {{ $i <= $value ? 'fill-current' : 'text-gray-300' }}" />
            </button>
        @else
            <x-icon name="star" class="{{ $sizes[$size] }} {{ $colors[$color] }} {{ $i <= $value ? 'fill-current' : 'text-gray-300' }}" />
        @endif
    @endfor
    
    @if($editable)
        <input type="hidden" name="{{ $name }}" value="{{ $value }}" />
    @endif
</div>