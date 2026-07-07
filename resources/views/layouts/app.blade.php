<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Posyandu') }} - @yield('title', 'Dashboard')</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
    <link rel="apple-touch-icon" href="{{ asset('apple-touch-icon.png') }}">

    <!-- Fonts: preconnect first for minimal DNS latency -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <!-- Core fonts (render-blocking intentionally: prevents FOUT on LCP text) -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet">

    <!-- Font Awesome: deferred to unblock main thread (icons are non-LCP) -->
    <link rel="preload" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" as="style" onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"></noscript>

    <!-- Scripts & Styles (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/admin.js'])

    <!-- WAJIB: Livewire Styles -->
    @livewireStyles
    
    <style>
        :root { --sidebar-width: 260px; }

        #mainContent {
            width: 100%;
            transition: all 300ms ease-out;
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
                transition: width 300ms ease-out;
            }
            #mainContent {
                width: 100%;
                min-width: 0;
                display: flex;
                flex-direction: column;
            }
        }

        /* Prevent table overflow in cards */
        .section-card, .premium-card, .bento-card, .widget-card {
            max-width: 100%;
        }

        .overflow-x-auto {
            -webkit-overflow-scrolling: touch;
        }

        [x-cloak] {
            display: none !important;
        }

        @media print {
            /* Sembunyikan elemen navigasi dan filter saat cetak (DASH-36) */
            header, nav, #sidebar, .sidebar, .navbar, .no-print, footer, #toast-container {
                display: none !important;
            }
            body, main {
                background: white !important;
                color: black !important;
            }
            #mainContent {
                margin: 0 !important;
                padding: 0 !important;
            }
            .app-grid {
                display: block !important;
            }
            /* Hilangkan bayangan & pastikan ukuran penuh */
            .widget-card, .kpi-card, .premium-card {
                box-shadow: none !important;
                border: 1px solid #e2e8f0 !important;
                page-break-inside: avoid;
            }
            .hero-gradient, .hero-orb-1, .hero-orb-2 {
                background: none !important;
                display: none !important;
            }
        }
    </style>

    @stack('styles')
</head>
<body class="font-sans antialiased bg-slate-50 text-slate-900">

    <div class="min-h-screen app-grid bg-dashboard">
        <!-- Sidebar -->
        @include('components.layouts.app.sidebar')
        
        <!-- Main Content Wrapper -->
        <div id="mainContent" class="flex-1 shrink-0 flex flex-col min-h-screen transition-all duration-300 ease-in-out relative">
            
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
            @php session()->forget('success'); @endphp
        @endif

        @if (session('error'))
            <x-ui.notification type="error" :message="session('error')" />
            @php session()->forget('error'); @endphp
        @endif

        {{-- Dynamic Notifications from Livewire --}}
        <template x-for="n in notifications" :key="n.id">
            <x-ui.notification x-bind:type="n.type" x-bind:message="n.message" />
        </template>
    </div>
    
    @stack('scripts')
    
</body>
</html>