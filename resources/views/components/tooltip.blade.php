@props([
    'text',
    'position' => 'top', // top, right, bottom, left
    'trigger' => 'hover', // hover, click
])

@php
    $positions = [
        'top' => 'bottom-full left-1/2 transform -translate-x-1/2 mb-2',
        'right' => 'left-full top-1/2 transform -translate-y-1/2 ml-2',
        'bottom' => 'top-full left-1/2 transform -translate-x-1/2 mt-2',
        'left' => 'right-full top-1/2 transform -translate-y-1/2 mr-2',
    ];
    
    $triggerEvent = $trigger === 'click' ? '@click' : '@mouseenter @mouseleave';
@endphp

<div x-data="{ show: false }" class="relative inline-block">
    <span {{ $triggerEvent }}="show = !show" class="cursor-pointer">
        {{ $slot }}
    </span>
    
    <div x-show="show"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="absolute z-10 w-max max-w-xs px-3 py-2 text-sm font-medium text-white bg-gray-900 rounded-lg shadow-sm {{ $positions[$position] }}"
         role="tooltip">
        {{ $text }}
        <div class="absolute w-2 h-2 bg-gray-900 transform rotate-45 -translate-x-1/2" 
             :class="{
                 'bottom-0 left-1/2 -mb-1': position === 'top',
                 'left-0 top-1/2 -ml-1': position === 'right',
                 'top-0 left-1/2 -mt-1': position === 'bottom',
                 'right-0 top-1/2 -mr-1': position === 'left',
             }"></div>
    </div>
</div>