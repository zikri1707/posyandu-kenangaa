@props([
    'tabs' => [],
    'activeTab' => null,
    'variant' => 'underline', // underline, pill
    'fullWidth' => false,
])

@php
    $variantClasses = [
        'underline' => 'border-b border-gray-200',
        'pill' => 'space-x-2',
    ];
    
    $tabClasses = [
        'underline' => [
            'base' => 'inline-flex items-center px-4 py-3 border-b-2 font-medium text-sm',
            'active' => 'border-blue-500 text-blue-600',
            'inactive' => 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300',
        ],
        'pill' => [
            'base' => 'px-4 py-2 rounded-md text-sm font-medium',
            'active' => 'bg-blue-100 text-blue-700',
            'inactive' => 'text-gray-500 hover:text-gray-700 hover:bg-gray-100',
        ],
    ];
@endphp

<div class="border-b border-gray-200">
    <nav class="-mb-px flex {{ $fullWidth ? 'w-full' : '' }} {{ $variantClasses[$variant] }}" aria-label="Tabs">
        @foreach($tabs as $id => $tab)
            <button
                wire:click="setActiveTab('{{ $id }}')"
                type="button"
                class="{{ $tabClasses[$variant]['base'] }} {{ $activeTab === $id ? $tabClasses[$variant]['active'] : $tabClasses[$variant]['inactive'] }} whitespace-nowrap"
                aria-current="{{ $activeTab === $id ? 'page' : 'false' }}">
                @if(isset($tab['icon']))
                    <x-icon :name="$tab['icon']" class="mr-2 w-4 h-4" />
                @endif
                {{ $tab['label'] }}
                @if(isset($tab['badge']))
                    <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ $tab['badge'] }}
                    </span>
                @endif
            </button>
        @endforeach
    </nav>
</div>

<div class="mt-4">
    @foreach($tabs as $id => $tab)
        <div x-show="activeTab === '{{ $id }}'" x-transition>
            {{ ${"tab_{$id}_content"} ?? '' }}
        </div>
    @endforeach
</div>