<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Posyandu') }} - @yield('title', 'Dashboard')</title>

    <!-- Fonts (Public Sans) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet"/>
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Material Symbols -->
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet" />

    <!-- Chart.js for Growth Charts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

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
            #mainContent { 
                margin-left: var(--sidebar-width) !important; 
                width: calc(100% - var(--sidebar-width)) !important; 
            }
            #sidebar { 
                width: var(--sidebar-width) !important; 
                background: #FFFFFF !important;
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
<body class="font-sans antialiased bg-slate-50 text-slate-900">

    <div class="min-h-screen flex overflow-hidden bg-dashboard">
        <!-- Sidebar -->
        @include('components.layouts.app.sidebar')
        
        <!-- Main Content Wrapper -->
        <div id="mainContent" class="flex-1 flex flex-col min-h-screen transition-all duration-300 ease-in-out relative overflow-y-auto">
            
            <!-- Navbar (Now part of the right-side flow) -->
            @php
                $routeTitles = [
                    'dashboard'                  => ['Dashboard',        'Ringkasan data posyandu'],
                    'admin.analytics'            => ['Analytics',        'Statistik & grafik data'],
                    'admin.patients.*'           => ['Data Warga',       'Kelola data pasien posyandu'],
                    'admin.posyandu.*'           => ['Data Posyandu',    'Kelola unit posyandu'],
                    'admin.schedules.*'          => ['Jadwal Kegiatan',  'Kelola jadwal posyandu'],
                    'admin.medical-records.*'    => ['Rekam Medis',      'Data pemeriksaan pasien'],
                    'admin.reports.*'            => ['Laporan Bulanan',  'Laporan & ekspor data'],
                    'admin.activity-logs.*'      => ['Log Aktivitas',    'Riwayat aktivitas sistem'],
                    'admin.articles.*'           => ['Artikel & Berita', 'Kelola konten edukasi'],
                    'admin.gallery.*'            => ['Galeri',           'Kelola foto & media'],
                    'admin.pedukuhans.*'         => ['Data Pedukuhan',   'Kelola data wilayah'],
                    'admin.users.*'              => ['Manajemen User',   'Kelola akun pengguna'],
                ];
                $pageTitle    = 'Dashboard';
                $pageSubtitle = 'Sistem Informasi Posyandu';
                foreach ($routeTitles as $pattern => $labels) {
                    if (request()->routeIs($pattern)) {
                        [$pageTitle, $pageSubtitle] = $labels;
                        break;
                    }
                }
            @endphp
            @include('components.layouts.ui.navbar', compact('pageTitle', 'pageSubtitle'))
            
            <!-- Main Content Area -->
            <main class="flex-1 p-4 md:p-8">
                @yield('content')
            </main>
            
            <!-- Footer -->
            @include('components.layouts.ui.footer')
        </div>
    </div>

    <!-- WAJIB: Livewire Scripts -->
    @livewireScripts
    
    <!-- Flash Messages (Success & Error) -->
    @if (session('success'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-green-50 border-l-4 border-green-500 rounded-r-lg shadow-lg p-4 flex items-start gap-3 transition-all duration-300"
            role="alert"
            aria-live="polite"
        >
            <span class="material-symbols-outlined text-green-600 text-xl">check_circle</span>
            <div class="flex-1">
                <p class="text-sm font-bold text-green-800">Berhasil!</p>
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
            <button 
                @click="show = false"
                class="text-green-500 hover:text-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 rounded"
                aria-label="Tutup pesan"
            >
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    @endif

    @if (session('error'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-red-50 border-l-4 border-red-500 rounded-r-lg shadow-lg p-4 flex items-start gap-3 transition-all duration-300"
            role="alert"
            aria-live="assertive"
        >
            <span class="material-symbols-outlined text-red-600 text-xl">error</span>
            <div class="flex-1">
                <p class="text-sm font-bold text-red-800">Kesalahan!</p>
                <p class="text-sm text-red-700">{{ session('error') }}</p>
            </div>
            <button 
                @click="show = false"
                class="text-red-500 hover:text-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 rounded"
                aria-label="Tutup pesan"
            >
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    @endif

    @if (session('warning'))
        <div 
            x-data="{ show: true }" 
            x-show="show" 
            x-init="setTimeout(() => show = false, 5000)"
            class="fixed bottom-4 right-4 z-50 max-w-sm w-full bg-yellow-50 border-l-4 border-yellow-500 rounded-r-lg shadow-lg p-4 flex items-start gap-3 transition-all duration-300"
            role="alert"
            aria-live="polite"
        >
            <span class="material-symbols-outlined text-yellow-600 text-xl">warning</span>
            <div class="flex-1">
                <p class="text-sm font-bold text-yellow-800">Peringatan!</p>
                <p class="text-sm text-yellow-700">{{ session('warning') }}</p>
            </div>
            <button 
                @click="show = false"
                class="text-yellow-500 hover:text-yellow-700 focus:outline-none focus:ring-2 focus:ring-yellow-500 rounded"
                aria-label="Tutup pesan"
            >
                <span class="material-symbols-outlined text-lg">close</span>
            </button>
        </div>
    @endif
    
    @stack('scripts')
    
</body>
</html>