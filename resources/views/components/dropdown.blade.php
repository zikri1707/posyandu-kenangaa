@props([
    'align' => 'right', // right, left
    'width' => '48', // 48, 64
    'trigger' => null,
    'contentClasses' => 'py-1 bg-white',
])

@php
    $alignmentClasses = [
        'right' => 'origin-top-right right-0',
        'left' => 'origin-top-left left-0',
    ];
    
    $widthClasses = [
        '48' => 'w-48',
        '64' => 'w-64',
    ];
@endphp

<div x-data="{ open: false }" @click.away="open = false" class="relative">
    <!-- Trigger -->
    <div @click="open = !open">
        {{ $trigger }}
    </div>
    
    <!-- Dropdown -->
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute z-50 mt-2 {{ $alignmentClasses[$align] }} rounded-md shadow-lg {{ $widthClasses[$width] }}"
         style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
            {{ $slot }}
        </div>
    </div>
</div>

<!-- Dropdown Link Component -->
@once
    @push('components')
        @verbatim
        <x-dropdown.link :href="href" {{ $attributes->merge(['class' => 'block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100']) }}>
            {{ $slot }}
        </x-dropdown.link>
        @endverbatim
    @endpush
@endonce