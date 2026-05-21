@extends('layouts.public-layout')

@section('title', 'Tentang Kami - Posyandu ILP Kenanga RW 011')

@section('content')
<div class="bg-slate-50 dark:bg-gray-950 min-h-screen">
    
    {{-- ── 1. HERO SECTION ── --}}
    <section class="relative overflow-hidden pt-12 pb-20 lg:pt-20 lg:pb-28">
        {{-- Decorative Background Blobs --}}
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/10 rounded-full blur-3xl -translate-y-1/2 opacity-70"></div>
        <div class="absolute bottom-0 right-10 w-[500px] h-[500px] bg-teal-500/5 rounded-full blur-3xl opacity-60"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 lg:gap-8 items-center">
                
                {{-- Hero Left: Content --}}
                <div class="lg:col-span-7 space-y-6 text-left">
                    <div class="flex flex-col gap-3">
                        <div>
                            <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 rounded-full border border-primary/20 text-[10px] font-black text-primary uppercase tracking-[0.2em]">
                                <span class="material-symbols-outlined text-[14px]">info</span>
                                Profil & Editorial
                            </span>
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 font-bold italic">
                            Slogan: "Posyandu ILP Kenanga RW 011, Mitra Masyarakat Menuju Hidup Sehat"
                        </p>
                    </div>
                    
                    <h1 class="text-4xl md:text-6xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta leading-tight">
                        Mitra Masyarakat<br/>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-500 italic">Menuju Hidup Sehat.</span>
                    </h1>
                    
                    <p class="text-base md:text-lg text-slate-600 dark:text-slate-400 font-medium leading-relaxed max-w-2xl">
                        Posyandu ILP Kenanga RW 011 Kelurahan Aren Jaya hadir sebagai wujud komitmen nyata warga dalam membangun kualitas kesehatan keluarga yang unggul melalui pendekatan pelayanan kesehatan primer terintegrasi.
                    </p>
                    
                    <div class="flex flex-wrap gap-4 pt-4">
                        <a href="{{ route('public.home') }}#jadwal" class="px-6 py-3.5 bg-primary text-white text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary-dark transition-all duration-300 hover:shadow-lg hover:shadow-primary/20 transform hover:-translate-y-0.5">
                            Jadwal Kegiatan
                        </a>
                        <a href="{{ route('public.contact') }}" class="px-6 py-3.5 bg-white dark:bg-slate-900 text-slate-800 dark:text-white text-xs font-black uppercase tracking-widest rounded-xl border border-slate-200 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800 transition-all duration-300 transform hover:-translate-y-0.5">
                            Hubungi Kami
                        </a>
                    </div>
                </div>

                {{-- Hero Right: Image & Floating Cards --}}
                <div class="lg:col-span-5 relative">
                    <div class="relative mx-auto max-w-[450px] lg:max-w-none">
                        {{-- Background Accent Card --}}
                        <div class="absolute -inset-4 bg-gradient-to-tr from-primary/10 to-teal-500/10 rounded-[2.5rem] blur-xl opacity-80 -z-10"></div>
                        
                        {{-- Main Image Card --}}
                        <div class="relative overflow-hidden rounded-[2rem] border border-white dark:border-slate-800 shadow-2xl bg-white dark:bg-slate-900 p-4">
                            <img src="{{ asset('assets/img/about_hero_illustration.png') }}" alt="Posyandu Kenanga Illustration" class="w-full h-auto object-cover rounded-2xl transform hover:scale-[1.02] transition-transform duration-500">
                        </div>

                        {{-- Floating Highlight Card --}}
                        <div class="absolute -bottom-6 -left-6 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md border border-slate-100 dark:border-slate-800 p-4 rounded-2xl shadow-xl flex items-center gap-3 animate-bounce-slow max-w-[240px]">
                            <div class="w-10 h-10 bg-teal-500 rounded-xl flex items-center justify-center text-white shrink-0 shadow-lg shadow-teal-500/20">
                                <span class="material-symbols-outlined text-[20px]">verified</span>
                            </div>
                            <div>
                                <h4 class="text-xs font-black text-slate-950 dark:text-white font-jakarta">Integrasi ILP</h4>
                                <p class="text-[10px] text-slate-500 dark:text-slate-400 font-medium">Layanan Siklus Hidup Terpadu</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── 2. STATS SECTION (METRIC COUNTERS) ── --}}
    <section class="py-12 bg-white dark:bg-slate-900 border-y border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8 lg:gap-4 divide-y lg:divide-y-0 lg:divide-x divide-slate-100 dark:divide-slate-800">
                
                {{-- Stat 1 --}}
                <div class="text-center pt-6 lg:pt-0">
                    <span class="block text-4xl lg:text-5xl font-black text-primary font-jakarta">12+</span>
                    <span class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest mt-2">Kader Aktif</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-medium">Terlatih &amp; Berdedikasi</span>
                </div>

                {{-- Stat 2 --}}
                <div class="text-center pt-6 lg:pt-0">
                    <span class="block text-4xl lg:text-5xl font-black text-teal-500 font-jakarta">500+</span>
                    <span class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest mt-2">Sasaran Layanan</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-medium">Balita, Ibu Hamil &amp; Lansia</span>
                </div>

                {{-- Stat 3 --}}
                <div class="text-center pt-6 lg:pt-0">
                    <span class="block text-4xl lg:text-5xl font-black text-amber-500 font-jakarta">6</span>
                    <span class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest mt-2">Wilayah RT</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-medium">Cakupan RW 011 Aren Jaya</span>
                </div>

                {{-- Stat 4 --}}
                <div class="text-center pt-6 lg:pt-0">
                    <span class="block text-4xl lg:text-5xl font-black text-emerald-500 font-jakarta">100%</span>
                    <span class="block text-xs font-black text-slate-800 dark:text-slate-200 uppercase tracking-widest mt-2">Integrasi ILP</span>
                    <span class="block text-[10px] text-slate-400 dark:text-slate-500 mt-1 font-medium">Fokus Kesehatan Primer</span>
                </div>

            </div>
        </div>
    </section>

    {{-- ── 3. SAMBUTAN / WELCOME CARD ── --}}
    <section class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 shadow-xl rounded-[2.5rem] overflow-hidden grid grid-cols-1 lg:grid-cols-12">
                
                {{-- Welcome Intro (Accent BG) --}}
                <div class="lg:col-span-4 bg-gradient-to-br from-primary to-primary-dark p-8 md:p-12 text-white flex flex-col justify-between relative overflow-hidden">
                    <div class="absolute -right-10 -bottom-10 w-44 h-44 bg-white/5 rounded-full blur-2xl"></div>
                    
                    <div class="space-y-4">
                        <span class="material-symbols-outlined text-[48px] text-white/90">handshake</span>
                        <h3 class="text-2xl md:text-3xl font-black font-jakarta tracking-tight leading-tight">
                            Selamat Datang di
                        </h3>
                        <p class="text-xs text-white/80 leading-relaxed font-medium">
                            Posyandu ILP Kenanga RW 011 Kelurahan Aren Jaya.
                        </p>
                    </div>

                    <div class="pt-8 border-t border-white/10 text-[10px] font-black uppercase tracking-widest text-white/70">
                        Kelurahan Aren Jaya &bull; RW 011
                    </div>
                </div>

                {{-- Welcome Text Body --}}
                <div class="lg:col-span-8 p-8 md:p-12 lg:p-16 flex flex-col justify-between">
                    {{-- Intro Callout --}}
                    <div class="border-l-4 border-primary pl-4 py-1 mb-8">
                        <p class="text-slate-900 dark:text-white font-bold text-lg md:text-xl leading-snug">
                            Posyandu ILP Kenanga RW 011 hadir sebagai wujud komitmen masyarakat dalam meningkatkan kualitas kesehatan warga melalui pelayanan kesehatan primer yang terpadu, mudah diakses, dan berkelanjutan.
                        </p>
                    </div>

                    {{-- Pillars Grid --}}
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-2">
                        
                        {{-- Pilar 1: Sasaran & Lokasi --}}
                        <div class="relative overflow-hidden bg-slate-50/50 dark:bg-slate-950/50 border border-slate-100/80 dark:border-slate-800/50 p-5 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex flex-col justify-between group">
                            <div class="absolute right-4 top-2 text-6xl font-black text-slate-200/20 dark:text-slate-800/10 select-none">01</div>
                            <div>
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-9 h-9 bg-primary/10 rounded-xl flex items-center justify-center text-primary border border-primary/20 shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">location_on</span>
                                    </div>
                                    <h4 class="text-xs font-black font-jakarta text-slate-900 dark:text-white uppercase tracking-wider">Wilayah &amp; Sasaran</h4>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium mb-4">
                                    Berlokasi di Kelurahan Aren Jaya, kami siap melayani seluruh lapisan masyarakat mulai dari ibu hamil, bayi, balita, remaja, dewasa, hingga lansia.
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-1 mt-auto">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-primary/10 text-primary border border-primary/10">🤰 Ibu Hamil</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-primary/10 text-primary border border-primary/10">👶 Bayi &amp; Balita</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-primary/10 text-primary border border-primary/10">🧒 Remaja</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-primary/10 text-primary border border-primary/10">🧑 Dewasa</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-primary/10 text-primary border border-primary/10">🧓 Lansia</span>
                            </div>
                        </div>

                        {{-- Pilar 2: Layanan ILP & Kader --}}
                        <div class="relative overflow-hidden bg-slate-50/50 dark:bg-slate-950/50 border border-slate-100/80 dark:border-slate-800/50 p-5 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex flex-col justify-between group">
                            <div class="absolute right-4 top-2 text-6xl font-black text-slate-200/20 dark:text-slate-800/10 select-none">02</div>
                            <div>
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-9 h-9 bg-teal-500/10 rounded-xl flex items-center justify-center text-teal-600 dark:text-teal-400 border border-teal-500/20 shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">volunteer_activism</span>
                                    </div>
                                    <h4 class="text-xs font-black font-jakarta text-slate-900 dark:text-white uppercase tracking-wider">Integrasi Layanan</h4>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium">
                                    Sebagai Posyandu dengan pendekatan Integrasi Layanan Primer (ILP), kami mengedepankan pelayanan kesehatan yang menyeluruh dengan fokus pada <strong>pencegahan penyakit</strong>, <strong>promosi kesehatan</strong>, pemantauan tumbuh kembang, deteksi dini risiko kesehatan, serta didukung oleh kader-kader yang aktif, peduli, dan berdedikasi.
                                </p>
                            </div>
                        </div>

                        {{-- Pilar 3: Portal Digital --}}
                        <div class="relative overflow-hidden bg-slate-50/50 dark:bg-slate-950/50 border border-slate-100/80 dark:border-slate-800/50 p-5 rounded-2xl transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex flex-col justify-between group">
                            <div class="absolute right-4 top-2 text-6xl font-black text-slate-200/20 dark:text-slate-800/10 select-none">03</div>
                            <div>
                                <div class="flex items-center gap-2.5 mb-3">
                                    <div class="w-9 h-9 bg-amber-500/10 rounded-xl flex items-center justify-center text-amber-600 dark:text-amber-400 border border-amber-500/20 shrink-0">
                                        <span class="material-symbols-outlined text-[18px]">campaign</span>
                                    </div>
                                    <h4 class="text-xs font-black font-jakarta text-slate-900 dark:text-white uppercase tracking-wider">Portal Digital</h4>
                                </div>
                                <p class="text-[11px] text-slate-600 dark:text-slate-400 leading-relaxed font-medium mb-3">
                                    Website ini kami hadirkan sebagai media informasi dan komunikasi bagi warga RW 011 Aren Jaya untuk memantau:
                                </p>
                            </div>
                            <div class="flex flex-wrap gap-1 mt-auto">
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/10">📅 Jadwal</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/10">📋 Program</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/10">📖 Edukasi</span>
                                <span class="inline-flex items-center px-1.5 py-0.5 rounded-md text-[9px] font-bold bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-500/10">📸 Dokumentasi</span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- ── 4. VISI & MISI SECTION ── --}}
    <section class="py-20 lg:py-28 bg-slate-50 dark:bg-slate-950 border-y border-slate-100 dark:border-slate-800/80 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-primary/5 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/4 opacity-40"></div>
        <div class="absolute bottom-0 left-0 w-[400px] h-[400px] bg-teal-500/5 rounded-full blur-[100px] translate-y-1/3 -translate-x-1/4 opacity-30"></div>
        
        <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
            
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 rounded-full border border-primary/20 text-[9px] font-black text-primary uppercase tracking-[0.2em]">
                    <span class="material-symbols-outlined text-[12px]">explore</span>
                    Arah &amp; Komitmen
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta mt-4">
                    Visi &amp; <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-500 italic">Misi Posyandu</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch mb-12">
                
                {{-- Visi Left Card --}}
                <div class="lg:col-span-5 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/85 rounded-[2rem] p-8 md:p-12 flex flex-col justify-between relative overflow-hidden shadow-xs">
                    <div class="space-y-6">
                        <div class="flex items-center justify-between">
                            <div class="w-12 h-12 bg-primary/10 rounded-2xl flex items-center justify-center text-primary border border-primary/20">
                                <span class="material-symbols-outlined text-[24px]">campaign</span>
                            </div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-primary/10 text-primary text-[9px] font-black uppercase tracking-widest rounded-lg border border-primary/20">
                                <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                Visi Utama
                            </span>
                        </div>
                        <h3 class="text-xl md:text-2xl font-extrabold italic leading-relaxed text-slate-900 dark:text-white font-jakarta">
                            "Menjadi Posyandu ILP Kenanga 1 yang aktif, profesional, inovatif, dan terpercaya dalam memberikan pelayanan kesehatan primer terintegrasi guna mewujudkan masyarakat yang sehat, mandiri, dan sejahtera."
                        </h3>
                    </div>
                </div>

                {{-- Misi Right List --}}
                <div class="lg:col-span-7 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/85 rounded-[2rem] p-8 md:p-12 shadow-xs space-y-6">
                    <div class="flex items-center justify-between">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-teal-500/10 text-teal-600 dark:text-teal-400 text-[9px] font-black uppercase tracking-widest rounded-lg border border-teal-500/20">
                            <span class="w-1.5 h-1.5 bg-teal-500 rounded-full animate-pulse"></span>
                            Misi Pelaksanaan
                        </span>
                        <span class="text-[9px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest">4 Pilar Utama</span>
                    </div>
                    
                    @php
                    $misis = [
                        ['icon' => 'medical_services', 'title' => 'Layanan Kesehatan Dasar', 'desc' => 'Menyelenggarakan pelayanan kesehatan dasar yang mudah diakses, ramah, dan berkualitas bagi seluruh siklus kehidupan masyarakat.'],
                        ['icon' => 'family_history', 'title' => 'Pemantauan Terpadu', 'desc' => 'Meningkatkan pemantauan kesehatan ibu hamil, bayi, balita, remaja, dewasa, dan lansia secara terpadu.'],
                        ['icon' => 'nutrition', 'title' => 'Pencegahan Risiko stunting', 'desc' => 'Mendukung upaya pencegahan stunting, gizi buruk, serta penyakit menular dan tidak menular melalui edukasi dan deteksi dini.'],
                        ['icon' => 'groups', 'title' => 'Pemberdayaan & Kolaborasi', 'desc' => 'Meningkatkan kapasitas kader Posyandu agar kompeten dan sigap, serta menjalin kerja sama erat dengan Puskesmas Bekasi Timur.'],
                    ];
                    @endphp

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        @foreach($misis as $m)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 bg-primary/10 rounded-xl border border-primary/20 flex items-center justify-center text-primary shrink-0">
                                <span class="material-symbols-outlined text-[20px]">{{ $m['icon'] }}</span>
                            </div>
                            <div>
                                <h4 class="text-sm font-black font-jakarta text-slate-900 dark:text-white">{{ $m['title'] }}</h4>
                                <p class="text-[11px] text-slate-500 dark:text-slate-400 leading-relaxed font-medium mt-1">{{ $m['desc'] }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </section>

    {{-- ── 5. TUJUAN STRATEGIS ── --}}
    <section class="py-20 lg:py-28">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 rounded-full border border-primary/20 text-[9px] font-black text-primary uppercase tracking-[0.2em]">
                    <span class="material-symbols-outlined text-[12px]">ads_click</span>
                    Sasaran &amp; Capaian
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta mt-4">
                    Tujuan <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-500 italic">Strategis Kami</span>
                </h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $tujuans = [
                    ['icon' => 'volunteer_activism', 'title' => 'Derajat Kesehatan Utama', 'desc' => 'Meningkatkan derajat kesehatan masyarakat di lingkungan Posyandu ILP Kenanga 1.'],
                    ['icon' => 'child_care', 'title' => 'Pemberantasan Stunting', 'desc' => 'Menurunkan angka stunting, gizi kurang, dan risiko kesehatan ibu serta anak.'],
                    ['icon' => 'vaccines', 'title' => 'Cakupan Imunisasi Lengkap', 'desc' => 'Meningkatkan cakupan imunisasi, pemantauan tumbuh kembang, dan pemeriksaan kesehatan rutin.'],
                    ['icon' => 'health_and_safety', 'title' => 'Kesadaran Hidup Bersih (PHBS)', 'desc' => 'Meningkatkan kesadaran masyarakat terhadap pentingnya pola hidup sehat and pencegahan penyakit.'],
                    ['icon' => 'sync_alt', 'title' => 'Layanan Berkelanjutan', 'desc' => 'Mewujudkan pelayanan Posyandu yang terintegrasi, berkelanjutan, dan bermanfaat bagi seluruh warga.'],
                    ['icon' => 'workspace_premium', 'title' => 'Portal Layanan Terpercaya', 'desc' => 'Menjadikan Posyandu ILP Kenanga 1 sebagai pusat layanan kesehatan masyarakat yang nyaman dan terpercaya.']
                ];
                @endphp

                @foreach($tujuans as $t)
                <div class="bg-white dark:bg-slate-900 p-8 rounded-2xl border border-slate-100 dark:border-slate-800/80 shadow-xs hover:shadow-lg transition-all duration-300 group">
                    <div class="w-12 h-12 bg-primary/10 rounded-xl flex items-center justify-center text-primary border border-primary/20 mb-6 group-hover:bg-primary group-hover:text-white transition-colors duration-300">
                        <span class="material-symbols-outlined text-[24px]">{{ $t['icon'] }}</span>
                    </div>
                    <h3 class="text-lg font-black text-slate-900 dark:text-white font-jakarta mb-2">{{ $t['title'] }}</h3>
                    <p class="text-xs text-slate-500 dark:text-slate-400 leading-relaxed font-medium">{{ $t['desc'] }}</p>
                </div>
                @endforeach
            </div>

        </div>
    </section>

    {{-- ── 6. BIODATA KADER POSYANDU ── --}}
    <section class="py-20 lg:py-28 bg-white dark:bg-slate-900 border-t border-slate-100 dark:border-slate-800">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            
            <div class="text-center mb-16">
                <span class="inline-flex items-center gap-2 px-3 py-1 bg-primary/10 rounded-full border border-primary/20 text-[9px] font-black text-primary uppercase tracking-[0.2em]">
                    <span class="material-symbols-outlined text-[12px]">groups</span>
                    Tim Pelaksana
                </span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta mt-4">
                    Biodata <span class="text-transparent bg-clip-text bg-gradient-to-r from-primary to-teal-500 italic">Kader Posyandu</span>
                </h2>
                <p class="text-xs md:text-sm text-slate-500 dark:text-slate-400 font-medium max-w-xl mx-auto mt-4 leading-relaxed">
                    Mengenal lebih dekat para kader pelaksana Posyandu ILP Kenanga 1 RW 011 Kelurahan Aren Jaya yang siap melayani kebutuhan kesehatan warga.
                </p>
            </div>

            @php
            $kaders = [
                [
                    'name' => 'Sri Hartati',
                    'role' => 'Ketua Kader',
                    'ttl' => 'Lampung, 12 April 1962',
                    'nik' => '3275015204620012',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Sumba 8 No. 232 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081380473365',
                    'email' => 'denyyoga2212@gmail.com',
                    'image' => asset('assets/img/kaders/sri_hartati.png'),
                ],
                [
                    'name' => 'Widayanti Christiani',
                    'role' => 'Sekretaris',
                    'ttl' => 'Jakarta, 05 April 1982',
                    'nik' => '3275014504820054',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Bali 1 No. 330 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '08139914428',
                    'email' => 'widayantichristiani@yahoo.co.id',
                    'image' => asset('assets/img/kaders/widayanti.jpg'),
                ],
                [
                    'name' => 'Parniyati',
                    'role' => 'Bendahara',
                    'ttl' => 'Karanganyar, 15 Juli 1971',
                    'nik' => '3275015507710014',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Madura 3 No 37 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '085770153741',
                    'email' => 'parniyati15.71@gmail.com',
                    'image' => asset('assets/img/kaders/parniyati.png'),
                ],
                [
                    'name' => 'Arimbi Kurniasari',
                    'role' => 'Anggota',
                    'ttl' => 'Klaten, 28 November 1976',
                    'nik' => '3275016811760020',
                    'pendidikan' => 'Magister',
                    'alamat' => 'Jl P. Madura 4 No. 15 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081297963177',
                    'email' => 'arimbi28sari@ggmail.com',
                    'image' => asset('assets/img/kaders/arimbi.png'),
                ],
                [
                    'name' => 'Dewi Pastrinah',
                    'role' => 'Anggota',
                    'ttl' => 'Jakarta, 29 Desember 1981',
                    'nik' => '3275016912810022',
                    'pendidikan' => 'SMK',
                    'alamat' => 'Jl P. Madura 4 No. 22 RT 003 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081517001791',
                    'email' => 'dewigedhe81@gmail.com',
                    'image' => asset('assets/img/kaders/dewi_pastrinah.png'),
                ],
                [
                    'name' => 'Tionar Maulina Purba',
                    'role' => 'Anggota',
                    'ttl' => 'Dolok Sanggul, 25 Januari 1959',
                    'nik' => '3275016501590013',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Madura 3 No 38 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081218385669',
                    'email' => 'tionar.mp@gmail.com',
                    'image' => asset('assets/img/kaders/tionar.png'),
                ],
                [
                    'name' => 'Maita Indriati',
                    'role' => 'Anggota',
                    'ttl' => 'Jakarta, 19 Mei 1963',
                    'nik' => '3275015905630012',
                    'pendidikan' => 'Sarjana',
                    'alamat' => 'Jl Sumba Raya No 03 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081295743714',
                    'email' => 'Maitaindriati1905@gmail.com',
                    'image' => asset('assets/img/kaders/maita.png'),
                ],
                [
                    'name' => 'Arfah',
                    'role' => 'Anggota',
                    'ttl' => 'Jakarta, 15 Mei 1967',
                    'nik' => '3275015505670018',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl Sumba Raya No 27 RT 002 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '087856068033',
                    'email' => 'arfah.6715@gmail.com',
                    'image' => asset('assets/img/kaders/arfah.png'),
                ],
                [
                    'name' => 'Mustikasari',
                    'role' => 'Anggota',
                    'ttl' => 'Subang, 09 September 1956',
                    'nik' => '3275014909560018',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Sumba 7 No. 254 RT 001 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081293290635',
                    'email' => 'Mustikasari@gmail.com',
                    'image' => asset('assets/img/kaders/mustikasari.png'),
                ],
                [
                    'name' => 'Ika Rakhmawati',
                    'role' => 'Anggota',
                    'ttl' => 'Jakarta, 15 Agustus 1978',
                    'nik' => '3275015508780053',
                    'pendidikan' => 'SLTA',
                    'alamat' => 'Jl P. Madura 2 No. 58 RT 004 RW 011 Kel. Aren Jaya, Kec. Bekasi Timur',
                    'hp' => '081315662377',
                    'email' => '-',
                    'image' => asset('assets/img/kaders/ika.jpeg'),
                ],
            ];
            @endphp

            {{-- ── A. PENGURUS INTI (Row 1) ── --}}
            <div class="mb-20">
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-slate-800 dark:text-slate-200 font-jakarta flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-primary rounded-full"></span>
                        Pengurus Harian
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">3 Anggota</span>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    @foreach(array_slice($kaders, 0, 3) as $k)
                    <div class="group relative bg-slate-50 dark:bg-slate-950 border border-slate-100 dark:border-slate-850 rounded-3xl p-6 transition-all duration-500 hover:shadow-xl hover:shadow-primary/5 hover:-translate-y-2 flex flex-col justify-between overflow-hidden">
                        
                        {{-- Background decorative shape --}}
                        <div class="absolute -right-16 -top-16 w-36 h-36 bg-primary/5 rounded-full blur-2xl group-hover:bg-primary/10 transition-colors duration-500"></div>

                        <div class="space-y-6 relative z-10">
                            {{-- Image frame --}}
                            <div class="relative aspect-[4/5] w-full rounded-2xl overflow-hidden bg-slate-200 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800/80 shadow-xs">
                                <img src="{{ $k['image'] }}" alt="{{ $k['name'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-transparent to-transparent opacity-40 group-hover:opacity-60 transition-opacity duration-300"></div>
                                
                                {{-- Floated Badge inside image frame --}}
                                <span class="absolute bottom-4 left-4 inline-flex items-center gap-1.5 px-3 py-1 bg-white/90 dark:bg-slate-900/90 text-slate-900 dark:text-white text-[10px] font-bold rounded-lg shadow-sm backdrop-blur-xs">
                                    <span class="w-1.5 h-1.5 bg-primary rounded-full animate-pulse"></span>
                                    {{ $k['role'] }}
                                </span>
                            </div>

                            {{-- Name & Status --}}
                            <div class="space-y-1">
                                <h4 class="text-lg font-black text-slate-900 dark:text-white font-jakarta tracking-tight group-hover:text-primary transition-colors duration-300">
                                    {{ $k['name'] }}
                                </h4>
                                <p class="text-[11px] font-semibold text-slate-400 dark:text-slate-500 flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[12px]">location_on</span>
                                    Kader Kenanga 1 • Aren Jaya
                                </p>
                            </div>
                        </div>

                        <div class="pt-6 mt-6 border-t border-slate-200/60 dark:border-slate-800/80 relative z-10">
                            <button onclick="openKaderModal('{{ addslashes($k['name']) }}', '{{ addslashes($k['role']) }}', '{{ addslashes($k['pendidikan']) }}', '{{ addslashes($k['alamat']) }}', '{{ addslashes($k['hp']) }}', '{{ addslashes($k['email']) }}', '{{ addslashes($k['image']) }}')" class="w-full py-3.5 bg-white dark:bg-slate-900 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-900 border border-slate-200 dark:border-slate-800 text-slate-800 dark:text-slate-200 text-[10px] font-black uppercase tracking-widest rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-md">
                                <span>Detail Biodata</span>
                                <span class="material-symbols-outlined text-[14px]">arrow_forward</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- ── B. ANGGOTA KADER (Row 2) ── --}}
            <div>
                <div class="flex items-center justify-between mb-8 pb-4 border-b border-slate-100 dark:border-slate-800">
                    <h3 class="text-sm font-bold uppercase tracking-widest text-slate-800 dark:text-slate-200 font-jakarta flex items-center gap-2">
                        <span class="w-1.5 h-6 bg-teal-500 rounded-full"></span>
                        Anggota Kader
                    </h3>
                    <span class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">7 Anggota</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach(array_slice($kaders, 3) as $k)
                    <div class="group relative bg-slate-50/50 dark:bg-slate-950/50 border border-slate-100/80 dark:border-slate-800/50 rounded-2xl p-5 transition-all duration-300 hover:shadow-lg hover:-translate-y-1 flex flex-col justify-between overflow-hidden">
                        
                        <div class="space-y-4">
                            {{-- Image frame --}}
                            <div class="relative aspect-square w-full rounded-xl overflow-hidden bg-slate-200 dark:bg-slate-900 border border-slate-200/50 dark:border-slate-800 shadow-xs">
                                <img src="{{ $k['image'] }}" alt="{{ $k['name'] }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/60 via-transparent to-transparent opacity-30 group-hover:opacity-50 transition-opacity duration-300"></div>
                            </div>

                            {{-- Name & Role --}}
                            <div class="space-y-0.5">
                                <h4 class="text-sm font-black text-slate-900 dark:text-white font-jakarta tracking-tight group-hover:text-primary transition-colors duration-300 line-clamp-1">
                                    {{ $k['name'] }}
                                </h4>
                                <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest block">Anggota</span>
                            </div>
                        </div>

                        <div class="pt-4 mt-4 border-t border-slate-100 dark:border-slate-800">
                            <button onclick="openKaderModal('{{ addslashes($k['name']) }}', '{{ addslashes($k['role']) }}', '{{ addslashes($k['pendidikan']) }}', '{{ addslashes($k['alamat']) }}', '{{ addslashes($k['hp']) }}', '{{ addslashes($k['email']) }}', '{{ addslashes($k['image']) }}')" class="w-full py-2.5 bg-white dark:bg-slate-900 hover:bg-slate-900 dark:hover:bg-white hover:text-white dark:hover:text-slate-900 border border-slate-100 dark:border-slate-800 text-slate-700 dark:text-slate-300 text-[9px] font-black uppercase tracking-widest rounded-lg transition-all duration-300 flex items-center justify-center gap-1.5">
                                <span>Lihat Profil</span>
                                <span class="material-symbols-outlined text-[12px]">visibility</span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>
    </section>

    {{-- ── C. DETAIL MODAL DIALOG ── --}}
    <div id="kaderModal" class="fixed inset-0 z-50 hidden bg-slate-950/70 backdrop-blur-md flex items-center justify-center p-4 transition-opacity duration-300">
        <div class="bg-white/95 dark:bg-slate-900/95 border border-white/20 dark:border-slate-800 rounded-[2.5rem] max-w-2xl w-full overflow-hidden shadow-2xl relative transform scale-95 opacity-0 transition-all duration-300 flex flex-col md:flex-row" id="kaderModalContent">
            
            {{-- Close button --}}
            <button onclick="closeKaderModal()" class="absolute top-6 right-6 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 z-10 w-10 h-10 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center transition-transform hover:rotate-90 duration-300 shadow-sm">
                <span class="material-symbols-outlined text-[20px]">close</span>
            </button>

            {{-- Left side image --}}
            <div class="md:w-5/12 bg-slate-50/50 dark:bg-slate-950/50 p-8 flex flex-col items-center justify-center border-b md:border-b-0 md:border-r border-slate-100 dark:border-slate-800">
                <div class="w-40 h-40 rounded-2xl overflow-hidden border-4 border-white dark:border-slate-900 shadow-xl mb-6 bg-slate-200 dark:bg-slate-800">
                    <img id="modalImage" src="" alt="Kader Photo" class="w-full h-full object-cover">
                </div>
                <h3 id="modalName" class="text-xl font-black text-slate-900 dark:text-white font-jakarta text-center leading-tight"></h3>
                <span id="modalRole" class="inline-block mt-3 px-3 py-1 bg-primary/10 text-primary text-[10px] font-black uppercase tracking-wider rounded-full border border-primary/20"></span>
            </div>

            {{-- Right side info fields --}}
            <div class="md:w-7/12 p-8 md:p-10 flex flex-col justify-between">
                <div>
                    <h4 class="text-[10px] font-black uppercase tracking-widest text-primary/80 dark:text-primary font-jakarta mb-6 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">badge</span>
                        Biodata Lengkap Kader
                    </h4>
                    
                    <div class="grid grid-cols-1 gap-4 text-xs md:text-sm">
                        {{-- Pendidikan --}}
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 mt-0.5 text-[18px]">school</span>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Pendidikan</span>
                                <span id="modalPendidikan" class="font-semibold text-slate-700 dark:text-slate-300"></span>
                            </div>
                        </div>
                        {{-- Alamat --}}
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 mt-0.5 text-[18px]">home_pin</span>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Alamat Lengkap</span>
                                <span id="modalAlamat" class="font-medium text-slate-600 dark:text-slate-400 leading-relaxed"></span>
                            </div>
                        </div>
                        {{-- Email --}}
                        <div class="flex items-start gap-3">
                            <span class="material-symbols-outlined text-slate-400 dark:text-slate-500 mt-0.5 text-[18px]">alternate_email</span>
                            <div class="flex flex-col gap-0.5">
                                <span class="text-[9px] text-slate-400 dark:text-slate-500 font-bold uppercase tracking-wider">Email</span>
                                <span id="modalEmail" class="font-semibold text-slate-700 dark:text-slate-300 break-all"></span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Action / WA Chat Button --}}
                <div class="pt-6 mt-6 border-t border-slate-100 dark:border-slate-800">
                    <a id="modalWaBtn" href="" target="_blank" class="w-full py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-black text-center text-[10px] uppercase tracking-widest rounded-xl transition-all duration-300 flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-emerald-500/20">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.739-1.456L0 24zm6.59-4.846c1.6.95 3.188 1.449 4.825 1.451 5.436 0 9.86-4.37 9.864-9.799.002-2.63-1.023-5.101-2.885-6.965C16.59 1.977 14.113.953 11.5.953c-5.437 0-9.862 4.371-9.866 9.802-.001 1.762.48 3.483 1.396 4.98L2.025 21.97l6.622-1.737zM18.621 15.1c-.328-.164-1.94-.957-2.24-1.066-.3-.11-.519-.164-.737.164-.219.328-.847 1.066-1.038 1.284-.19.219-.382.246-.71.082-1.128-.567-2.23-1.002-3.187-1.828-.79-.68-1.697-1.488-2.036-2.062-.328-.574-.035-.885.246-1.164.254-.252.574-.683.847-1.012.274-.328.365-.547.547-.902.18-.356.09-.657-.046-.93-.137-.273-1.038-2.513-1.42-3.442-.375-.901-.756-.777-1.038-.791-.219-.011-.47-.015-.71-.015-.24 0-.628.09-.957.447-.328.356-1.256 1.23-1.256 2.99s1.284 3.442 1.464 3.688c.18.246 2.528 3.861 6.126 5.409 3.598 1.548 3.598 1.032 4.255 1.032.656 0 2.11-.862 2.4-1.652.29-.791.29-1.466.205-1.602-.085-.137-.273-.219-.601-.383z"/>
                        </svg>
                        <span>Hubungi Kader (WhatsApp)</span>
                    </a>
                </div>
            </div>

        </div>
    </div>

    <script>
    function openKaderModal(name, role, pendidikan, alamat, hp, email, image) {
        document.getElementById('modalImage').src = image;
        document.getElementById('modalName').innerText = name;
        document.getElementById('modalRole').innerText = role;
        document.getElementById('modalPendidikan').innerText = pendidikan;
        document.getElementById('modalAlamat').innerText = alamat;
        document.getElementById('modalEmail').innerText = email && email !== '-' ? email : '-';

        // Format WA URL
        const cleanHp = hp.replace(/[^0-9]/g, '');
        const waNumber = cleanHp.startsWith('0') ? '62' + cleanHp.slice(1) : cleanHp;
        
        const waBtn = document.getElementById('modalWaBtn');
        waBtn.href = 'https://wa.me/' + waNumber + '?text=' + encodeURIComponent('Halo Ibu ' + name + ', saya ingin bertanya mengenai layanan Posyandu.');

        const modal = document.getElementById('kaderModal');
        const content = document.getElementById('kaderModalContent');
        
        modal.classList.remove('hidden');
        setTimeout(() => {
            content.classList.remove('scale-95', 'opacity-0');
            content.classList.add('scale-100', 'opacity-100');
        }, 10);
    }

    function closeKaderModal() {
        const modal = document.getElementById('kaderModal');
        const content = document.getElementById('kaderModalContent');
        
        content.classList.remove('scale-100', 'opacity-100');
        content.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }

    document.getElementById('kaderModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeKaderModal();
        }
    });
    </script>

        </div>
    </section>

    {{-- ── 7. CALL TO ACTION SECTION ── --}}
    <section class="pb-24">
        <div class="max-w-7xl mx-auto px-6 md:px-12">
            <div class="bg-gradient-to-tr from-slate-900 to-slate-950 text-white rounded-[2.5rem] p-8 md:p-16 text-center relative overflow-hidden shadow-2xl border border-slate-800">
                <div class="absolute -left-10 -bottom-10 w-48 h-48 bg-primary/10 rounded-full blur-2xl"></div>
                <div class="absolute -right-10 -top-10 w-48 h-48 bg-teal-500/10 rounded-full blur-2xl"></div>
                
                <div class="relative z-10 max-w-2xl mx-auto space-y-6">
                    <span class="material-symbols-outlined text-[48px] text-primary">clinical_notes</span>
                    <h2 class="text-2xl md:text-4xl font-black font-jakarta tracking-tight">Ingin berkonsultasi?</h2>
                    <p class="text-xs md:text-sm text-slate-400 leading-relaxed font-medium">
                        Kami mengadakan pelayanan pemeriksaan fisik rutin, penimbangan berat badan, imunisasi, serta konseling gizi terpadu setiap bulannya. Kunjungi kontak atau datang ke jadwal posyandu terdekat.
                    </p>
                    <div class="pt-4">
                        <a href="{{ route('public.contact') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-white text-slate-900 text-xs font-black uppercase tracking-widest rounded-xl hover:bg-primary hover:text-white hover:shadow-lg hover:shadow-primary/20 transition-all duration-300 transform hover:-translate-y-0.5">
                            Hubungi Kontak Kami
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection
