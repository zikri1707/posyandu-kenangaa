@php
    $segments = request()->segments();
    $url = '';
@endphp

<nav class="flex mb-4" aria-label="Breadcrumb">
    <ol class="inline-flex items-center space-x-1 md:space-x-3">
        <li class="inline-flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                <x-icon name="home" class="w-4 h-4 mr-2" />
                Dashboard
            </a>
        </li>
        
        @foreach($segments as $segment)
            @php
                $url .= '/' . $segment;
            @endphp
            
            @if(!is_numeric($segment) <!-- Skip numeric segments (IDs) -->
                <li>
                    <div class="flex items-center">
                        <x-icon name="chevron-right" class="w-4 h-4 text-gray-400 mx-1" />
                        @if($loop->last)
                            <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 capitalize">{{ str_replace('-', ' ', $segment) }}</span>
                        @else
                            <a href="{{ $url }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2 capitalize">{{ str_replace('-', ' ', $segment) }}</a>
                        @endif
                    </div>
                </li>
            @endif
        @endforeach
    </ol>
</nav>