@props([
    'title' => null,
    'headerAction' => null,
    'footer' => null,
    'padding' => 'p-6',
    'hover' => false,
])

<div class="bg-white rounded-lg shadow {{ $hover ? 'hover:shadow-md transition-shadow duration-200' : '' }}">
    @if($title || $headerAction)
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            @if($title)
                <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
            @endif
            
            @if($headerAction)
                <div class="flex-shrink-0">
                    {{ $headerAction }}
                </div>
            @endif
        </div>
    @endif
    
    <div class="{{ $padding }}">
        {{ $slot }}
    </div>
    
    @if($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            {{ $footer }}
        </div>
    @endif
</div>