@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Admin Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
        <h1 class="text-2xl font-bold text-gray-800">@yield('admin-title')</h1>
        
        @hasSection('admin-actions')
            <div class="flex flex-wrap gap-2">
                @yield('admin-actions')
            </div>
        @endif
    </div>
    
    <!-- Admin Content -->
    <div class="bg-white rounded-lg shadow-md overflow-hidden">
        @yield('admin-content')
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