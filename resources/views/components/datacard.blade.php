@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null, // 'up' or 'down'
    'change' => null,
    'period' => 'bulan lalu',
])

@php
    $colors = [
        'blue' => 'bg-blue-100 text-blue-600',
        'green' => 'bg-green-100 text-green-600',
        'red' => 'bg-red-100 text-red-600',
        'yellow' => 'bg-yellow-100 text-yellow-600',
        'indigo' => 'bg-indigo-100 text-indigo-600',
        'purple' => 'bg-purple-100 text-purple-600',
        'pink' => 'bg-pink-100 text-pink-600',
    ];

    $iconClasses = 'w-8 h-8 p-2 rounded-full ' . ($colors[$color] ?? $colors['blue']);
@endphp

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-gray-500 truncate">{{ $title }}</p>
            <p class="mt-1 text-3xl font-semibold text-gray-900">{{ $value }}</p>
        </div>
        <div class="{{ $iconClasses }}">
            <x-icon :name="$icon" class="w-4 h-4" />
        </div>
    </div>

    @if ($trend && $change)
        <div class="mt-4 flex items-center">
            @if ($trend === 'up')
                <x-icon name="arrow-trending-up" class="w-5 h-5 text-green-500" />
                <span class="ml-2 text-sm font-medium text-green-600">
                    +{{ $change }}%
                </span>
            @else
                <x-icon name="arrow-trending-down" class="w-5 h-5 text-red-500" />
                <span class="ml-2 text-sm font-medium text-red-600">
                    -{{ $change }}%
                </span>
            @endif
            <span class="ml-1 text-sm text-gray-500">dari {{ $period }}</span>
        </div>
    @endif
</div>
