@props([
    'type' => 'info', // info, success, warning, error
    'message',
    'closable' => true
])

@php
    $colors = [
        'info' => 'bg-blue-50 border-blue-400 text-blue-700',
        'success' => 'bg-green-50 border-green-400 text-green-700',
        'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-700',
        'error' => 'bg-red-50 border-red-400 text-red-700',
    ];
    
    $icons = [
        'info' => 'information-circle',
        'success' => 'check-circle',
        'warning' => 'exclamation-triangle',
        'error' => 'x-circle',
    ];
@endphp

<div x-data="{ show: true }" 
     x-show="show"
     x-transition:leave="transition ease-in duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="mb-4 border-l-4 p-4 {{ $colors[$type] }} rounded">
    <div class="flex items-center">
        <div class="flex-shrink-0">
            <x-icon :name="$icons[$type]" class="w-5 h-5" />
        </div>
        <div class="ml-3">
            <p class="text-sm">{{ $message }}</p>
        </div>
        @if($closable)
            <div class="ml-auto pl-3">
                <div class="-mx-1.5 -my-1.5">
                    <button @click="show = false" type="button" class="inline-flex rounded-md p-1.5 focus:outline-none focus:ring-2 focus:ring-offset-2">
                        <span class="sr-only">Dismiss</span>
                        <x-icon name="x-mark" class="w-5 h-5" />
                    </button>
                </div>
            </div>
        @endif
    </div>
</div>