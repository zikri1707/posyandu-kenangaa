@extends('layouts.app')

@section('content')
<div class="w-full pt-4 pb-8">
    <!-- Admin Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-6 w-full">
        <div class="flex-1 min-w-0 w-full">
            <h1 class="text-lg font-black text-slate-800 truncate">@yield('admin-title')</h1>
        </div>
        
        @hasSection('admin-actions')
            <div class="flex flex-wrap gap-2 items-center justify-start sm:justify-end shrink-0 w-full sm:w-auto">
                @yield('admin-actions')
            </div>
        @endif
    </div>
    
    <!-- Admin Content -->
    <div class="w-full">
        @if(isset($slot) && ! is_array($slot))
            {{ $slot }}
        @else
            @yield('admin-content')
        @endif
    </div>
</div>
@endsection

@push('scripts')
{{-- Admin specific scripts can go here --}}
@endpush