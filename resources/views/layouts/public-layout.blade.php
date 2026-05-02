<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Posyandu Digital - Portal Berita')</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Public+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" rel="stylesheet"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body class="antialiased overflow-x-hidden">
    <!-- Header -->
    <header class="fixed top-2 left-1/2 -translate-x-1/2 w-[95%] max-w-7xl z-50 glass-header px-6 md:px-10 py-4 rounded-[2rem] transition-all duration-500" id="mainHeader">
        <div class="flex justify-between items-center">
            <!-- Logo -->
            <a href="{{ route('public.home') }}" class="flex items-center space-x-3 group">
                <div class="w-10 h-10 bg-primary rounded-2xl flex items-center justify-center text-white shadow-lg group-hover:rotate-12 transition-transform duration-500">
                    <span class="material-symbols-outlined text-[24px]">health_and_safety</span>
                </div>
                <div class="flex flex-col">
                    <h1 class="text-lg font-black tracking-tight text-on-surface leading-none font-jakarta">POSYANDU <span class="text-primary">DIGITAL</span></h1>
                    <p class="text-[9px] text-on-surface-variant font-black uppercase tracking-[0.2em] mt-1 opacity-70">Bekasi Timur</p>
                </div>
            </a>

            <!-- Nav -->
            <nav class="hidden lg:flex items-center space-x-2">
                @php $navs = ['Home' => 'public.home', 'Artikel' => 'public.articles.index', 'Tentang' => 'public.about', 'Kontak' => 'public.contact']; @endphp
                @foreach($navs as $label => $route)
                    <a href="{{ route($route) }}" 
                       class="px-5 py-2 text-[12px] font-black uppercase tracking-widest rounded-xl transition-all
                              {{ request()->routeIs($route) ? 'bg-primary text-white shadow-md' : 'text-on-surface-variant hover:bg-surface-container hover:text-primary' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </nav>

            <!-- Login/Auth -->
            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('dashboard') }}" class="btn-premium px-7 py-2.5 bg-on-surface text-white text-[11px] font-black rounded-full shadow-lg hover:shadow-primary/20 hover:scale-105 transition-all uppercase tracking-widest">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="btn-premium px-7 py-2.5 bg-primary text-white text-[11px] font-black rounded-full shadow-lg hover:shadow-primary/20 hover:scale-105 transition-all uppercase tracking-widest">Akses Kader</a>
                @endauth
            </div>
        </div>
        
        <!-- Reading Progress (Dynamic) -->
        <div class="absolute bottom-0 left-6 right-6 h-[2px] bg-outline-variant/10 overflow-hidden rounded-full">
            <div id="readingProgress" class="h-full w-0"></div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-[100px]">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-on-surface text-white py-24 px-6 md:px-12 mt-20 relative overflow-hidden">
        {{-- Decorative background --}}
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/10 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="absolute bottom-0 left-0 w-[300px] h-[300px] bg-white/5 rounded-full blur-[80px] translate-y-1/2 -translate-x-1/2"></div>

        <div class="max-w-7xl mx-auto relative z-10">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-16 mb-20">
                <div class="md:col-span-1">
                    <div class="flex items-center space-x-3 mb-8">
                        <div class="w-10 h-10 bg-primary/20 rounded-xl flex items-center justify-center text-primary backdrop-blur-md">
                            <span class="material-symbols-outlined text-[24px]">favorite</span>
                        </div>
                        <span class="text-xl font-black tracking-tight font-jakarta uppercase">Posyandu <span class="text-primary">Digital</span></span>
                    </div>
                    <p class="text-sm text-white/60 font-medium leading-loose mb-8">
                        Mewujudkan masyarakat cerdas dan sehat melalui transformasi layanan kesehatan digital yang inklusif.
                    </p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary hover:border-primary transition-all"><i class="fab fa-instagram"></i></a>
                        <a href="#" class="w-10 h-10 rounded-full border border-white/10 flex items-center justify-center hover:bg-primary hover:border-primary transition-all"><i class="fab fa-whatsapp"></i></a>
                    </div>
                </div>
                
                <div>
                    <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-8">Perusahaan</h4>
                    <ul class="space-y-4 text-sm font-bold text-white/80">
                        <li><a href="{{ route('public.about') }}" class="hover:text-primary transition-colors">Tentang Kami</a></li>
                        <li><a href="{{ route('public.contact') }}" class="hover:text-primary transition-colors">Hubungi Kami</a></li>
                        <li><a href="{{ route('public.articles.index') }}" class="hover:text-primary transition-colors">Update Berita</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-8">Layanan</h4>
                    <ul class="space-y-4 text-sm font-bold text-white/80">
                        <li><a href="{{ route('public.articles.index') }}" class="hover:text-primary transition-colors">Portal Edukasi</a></li>
                        <li><a href="{{ route('public.home') }}#jadwal" class="hover:text-primary transition-colors">Jadwal Posyandu</a></li>
                        <li><a href="{{ route('login') }}" class="hover:text-primary transition-colors">Area Kader</a></li>
                    </ul>
                </div>

                <div class="flex flex-col items-start md:items-end">
                    <h4 class="text-[10px] font-black text-white/40 uppercase tracking-[0.3em] mb-8">Bantuan</h4>
                    <p class="text-[13px] font-medium text-white/50 mb-6 text-left md:text-right">Butuh bantuan seputar layanan kami? Tim kami siap membantu kapan saja.</p>
                    <a href="{{ route('public.contact') }}" class="px-8 py-3 bg-white text-on-surface text-[10px] font-black uppercase tracking-widest rounded-full hover:bg-primary hover:text-white transition-all">Support Center</a>
                </div>
            </div>
            
            <div class="border-t border-white/10 pt-10 flex flex-col md:flex-row justify-between items-center space-y-6 md:space-y-0 text-[10px] font-black text-white/30 uppercase tracking-[0.2em]">
                <p>&copy; {{ date('Y') }} POSYANDU DIGITAL BEKASI TIMUR. ALL RIGHTS RESERVED.</p>
                <div class="flex gap-8">
                    <a href="#" class="hover:text-white transition-all underline decoration-white/10">Privacy</a>
                    <a href="#" class="hover:text-white transition-all underline decoration-white/10">Terms</a>
                    <a href="#" class="hover:text-white transition-all underline decoration-white/10">Cookies</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Reading Progress Logic
        window.addEventListener('scroll', () => {
            const h = document.documentElement;
            const b = document.body;
            const st = 'scrollTop';
            const sh = 'scrollHeight';
            const progress = (h[st] || b[st]) / ((h[sh] || b[sh]) - h.clientHeight) * 100;
            const progBar = document.getElementById('readingProgress');
            if (progBar) progBar.style.width = progress + '%';

            // Header Effect
            const header = document.getElementById('mainHeader');
            if (window.scrollY > 40) {
                header.classList.add('py-2', 'top-1', 'shadow-2xl');
                header.classList.remove('py-4', 'top-2');
            } else {
                header.classList.remove('py-2', 'top-1', 'shadow-2xl');
                header.classList.add('py-4', 'top-2');
            }
        });
    </script>
</body>
</html>
