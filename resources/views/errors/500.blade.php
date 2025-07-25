<!-- resources/views/errors/500.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-teal-500">500</h1>
            <p class="text-xl text-gray-600 mt-4">Something went wrong on our end. Please try again later.</p>
            <a href="{{ route('admin.dashboard') }}" class="text-teal-500 mt-4 inline-block">Go back to Dashboard</a>
        </div>
    </div>
@endsection
