@extends('layouts.app')

@section('content')
<div class="w-full max-w-full px-4 sm:px-6 lg:px-8 py-8">
    <!-- Admin Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8 w-full">
        <div class="flex-1 min-w-0 w-full">
            <h1 class="text-2xl font-bold text-gray-900 truncate">@yield('admin-title')</h1>
        </div>
        
        @hasSection('admin-actions')
            <div class="flex flex-wrap gap-3 items-center justify-start sm:justify-end shrink-0 w-full sm:w-auto">
                @yield('admin-actions')
            </div>
        @endif
    </div>
    
    <!-- Admin Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @if(isset($slot))
            {{ $slot }}
        @else
            @yield('admin-content')
        @endif
    </div>
</div>
@endsection

@push('styles')
<style>
    /* Custom styles that extend Tailwind */
    .admin-table {
        @apply min-w-full divide-y divide-gray-200;
    }
    .admin-table thead {
        @apply bg-gray-50;
    }
    .admin-table th {
        @apply px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider;
    }
    .admin-table tbody {
        @apply bg-white divide-y divide-gray-200;
    }
    .admin-table td {
        @apply px-6 py-4 whitespace-nowrap text-sm text-gray-500;
    }
    
    /* Status badges */
    .status-badge {
        @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
    }
    .status-active {
        @apply bg-green-100 text-green-800;
    }
    .status-inactive {
        @apply bg-red-100 text-red-800;
    }
    .status-pending {
        @apply bg-yellow-100 text-yellow-800;
    }
    
    /* Card variations */
    .card-primary {
        @apply bg-white rounded-lg shadow border border-gray-200;
    }
    .card-secondary {
        @apply bg-gray-50 rounded-lg shadow-inner border border-gray-100;
    }
</style>
@endpush