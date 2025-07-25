<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Posyandu') }} | @yield('title')</title>
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('storage/icons/favicon.ico') }}">
    
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @livewireStyles
    @stack('styles')
</head>
<body class="font-sans antialiased bg-gray-50">
    <div class="min-h-screen flex flex-col">
        @include('components.navbar')
        
        <div class="flex flex-1">
            @include('components.sidebar')
            
            <!-- Main Content -->
            <main class="flex-1 p-6 overflow-y-auto">
                @include('components.breadcrumbs')
                
                @if (session('success'))
                    @include('components.alert', ['type' => 'success', 'message' => session('success')])
                @endif
                
                @if (session('error'))
                    @include('components.alert', ['type' => 'error', 'message' => session('error')])
                @endif
                
                @yield('content')
            </main>
        </div>
        
        @include('components.footer')
    </div>
    
    @livewireScripts
    @stack('scripts')
</body>
</html>