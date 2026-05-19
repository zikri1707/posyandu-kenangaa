<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Posyandu') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts (Outfit) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- WAJIB: Livewire Styles -->
    @livewireStyles
    
    <style>
        :root { --sidebar-width: 260px; }
        
        #mainContent {
            width: 100% !important;
            transition: all 0.3s ease-in-out;
        }

        @media (min-width: 1024px) {
            .app-grid {
                display: grid;
                grid-template-columns: var(--sidebar-width, 260px) 1fr;
                min-height: 100vh;
            }
            #sidebar { 
                position: sticky !important;
                top: 0;
                height: 100vh;
                width: var(--sidebar-width) !important; 
            }
            #mainContent {
                width: 100% !important;
                min-width: 0;
                display: flex;
                flex-direction: column;
            }
        }

        /* Prevent table overlap in cards */
        .premium-card, .bento-card {
            max-width: 100%;
        }
        
        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }
    </style>

    @stack('styles')
</head>
<body class="font-outfit antialiased bg-slate-50 text-slate-900">

    <div class="min-h-screen app-grid bg-dashboard">
        <!-- Sidebar -->
        @include('components.layouts.app.sidebar')
        
        <!-- Main Content Wrapper -->
        <div id="mainContent" class="flex-1 flex-shrink-0 flex flex-col min-h-screen transition-all duration-300 ease-in-out relative overflow-y-auto">
            
            <!-- Navbar (Now part of the right-side flow) -->
            <x-layouts.app.navbar />
            
            <!-- Main Content Area -->
            <main class="flex-1 w-full p-4 md:px-8 md:pt-1 md:pb-8">
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('components.layouts.ui.footer')
        </div>
    </div>

    <!-- WAJIB: Livewire Scripts -->
    @livewireScripts
    
    {{-- Session & Dynamic Notifications --}}
    <div id="toast-container" x-data="{ 
        notifications: [],
        add(type, message) {
            const id = Date.now();
            this.notifications.push({ id, type, message });
            setTimeout(() => this.remove(id), 5000);
        },
        remove(id) {
            this.notifications = this.notifications.filter(n => n.id !== id);
        }
    }" @notify.window="add($event.detail.type, $event.detail.message)" class="fixed bottom-4 right-4 z-50 flex flex-col gap-3 w-full max-w-sm">
        
        {{-- Existing Session Notifications (Initial Load) --}}
        @if (session('success'))
            <x-ui.notification type="success" :message="session('success')" />
        @endif

        @if (session('error'))
            <x-ui.notification type="error" :message="session('error')" />
        @endif

        {{-- Dynamic Notifications from Livewire --}}
        <template x-for="n in notifications" :key="n.id">
            <x-ui.notification x-bind:type="n.type" x-bind:message="n.message" />
        </template>
    </div>
    
    @stack('scripts')
    
</body>
</html>