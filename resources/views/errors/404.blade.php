<!-- resources/views/errors/404.blade.php -->
@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center h-screen bg-gray-100">
        <div class="text-center">
            <h1 class="text-6xl font-bold text-teal-500">404</h1>
            <p class="text-xl text-gray-600 mt-4">Oops! The page you're looking for doesn't exist.</p>
            <a href="{{ route('admin.dashboard') }}" class="text-teal-500 mt-4 inline-block">Go back to Dashboard</a>
        </div>
    </div>
@endsection
