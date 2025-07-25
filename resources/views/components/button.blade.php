@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline, danger, disabled
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'fullWidth' => false,
])

@php
    $baseClasses = 'inline-flex items-center justify-center rounded-md font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors duration-200';
    
    // Variant classes
    $variantClasses = [
        'primary' => 'bg-blue-600 text-white hover:bg-blue-700',
        'secondary' => 'bg-gray-600 text-white hover:bg-gray-700',
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
        'danger' => 'bg-red-600 text-white hover:bg-red-700',
        'disabled' => 'bg-gray-300 text-gray-500 cursor-not-allowed',
    ];
    
    // Size classes
    $sizeClasses = [
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg',
    ];
    
    // Icon size classes
    $iconSizeClasses = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    
    $classes = $baseClasses . ' ' . $variantClasses[$variant] . ' ' . $sizeClasses[$size];
    
    if ($fullWidth) {
        $classes .= ' w-full';
    }
    
    $iconClasses = $iconSizeClasses[$size] . ' ' . ($iconPosition === 'left' ? 'mr-2' : 'ml-2');
@endphp

<button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }} {{ $variant === 'disabled' ? 'disabled' : '' }}>
    @if($icon && $iconPosition === 'left')
        <x-icon :name="$icon" :class="$iconClasses" />
    @endif
    
    {{ $slot }}
    
    @if($icon && $iconPosition === 'right')
        <x-icon :name="$icon" :class="$iconClasses" />
    @endif
</button>