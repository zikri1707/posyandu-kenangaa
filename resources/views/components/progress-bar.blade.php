@props([
    'value' => 0,
    'max' => 100,
    'color' => 'blue', // blue, green, red, yellow, indigo, purple, pink
    'showLabel' => true,
    'labelPosition' => 'inside', // inside, outside
    'height' => 'h-2.5', // h-1, h-2, h-2.5, h-3, h-4
    'rounded' => 'rounded-full'
])

@php
    $percentage = min(100, max(0, ($value / $max) * 100));
    
    $colors = [
        'blue' => 'bg-blue-600',
        'green' => 'bg-green-600',
        'red' => 'bg-red-600',
        'yellow' => 'bg-yellow-500',
        'indigo' => 'bg-indigo-600',
        'purple' => 'bg-purple-600',
        'pink' => 'bg-pink-600',
    ];
    
    $labelClasses = $showLabel && $labelPosition === 'inside' ? 'text-white text-xs font-medium text-center' : '';
@endphp

<div class="w-full">
    @if($showLabel && $labelPosition === 'outside')
        <div class="flex justify-between mb-1">
            <span class="text-sm font-medium text-gray-700">{{ $value }}/{{ $max }}</span>
            <span class="text-sm font-medium text-gray-700">{{ round($percentage) }}%</span>
        </div>
    @endif
    
    <div class="w-full bg-gray-200 {{ $rounded }} {{ $height }}">
        <div 
            class="{{ $colors[$color] }} {{ $rounded }} {{ $height }} transition-all duration-500 ease-out" 
            style="width: {{ $percentage }}%"
            role="progressbar" 
            aria-valuenow="{{ $value }}" 
            aria-valuemin="0" 
            aria-valuemax="{{ $max }}">
            @if($showLabel && $labelPosition === 'inside' && $percentage >= 25)
                <span class="{{ $labelClasses }}">{{ round($percentage) }}%</span>
            @endif
        </div>
    </div>
</div>