@extends('layouts.public-layout')

@section('title', 'Hubungi Kami - Posyandu Kenanga')

@push('head')
<meta name="description" content="Hubungi Posyandu Kenanga Bekasi Timur melalui email. Kami siap memberikan informasi layanan kesehatan, jadwal, dan bantuan untuk keluarga Anda.">
<style>
    /* ── Page Background ── */
    #contact-page-bg {
        background: #f8faf9;
    }

    /* ── Hero Grid ── */
    .contact-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 520px;
        background: #ffffff;
        border-radius: 32px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.04);
        box-shadow: 
            0 24px 80px -12px rgba(15, 23, 42, 0.05),
            0 0 0 1px rgba(15, 23, 42, 0.01);
        position: relative;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(13, 148, 136, 0.06) 1px, transparent 1px);
        background-size: 24px 24px;
        pointer-events: none;
        z-index: 0;
    }
    @media (max-width: 768px) {
        .contact-hero { grid-template-columns: 1fr; min-height: auto; }
        .contact-hero-img { display: none; }
    }

    /* ── Badges ── */
    .badge-status-light {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 6px 16px;
        background: rgba(13, 148, 136, 0.06);
        border: 1px solid rgba(13, 148, 136, 0.15);
        border-radius: 9999px;
        margin-bottom: 24px;
        align-self: flex-start;
    }
    .badge-status-light .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #0d9488;
        animation: pulse-dot 2s infinite;
    }
    .badge-status-light .text {
        font-size: 11px;
        font-weight: 800;
        color: #0f766e;
        text-transform: uppercase;
        letter-spacing: 0.15em;
    }

    @keyframes pulse-dot {
        0%, 100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.85); }
    }

    .badge-glass-dark {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        background: rgba(4, 47, 46, 0.85);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.12);
        border-radius: 16px;
        box-shadow: 0 10px 25px -5px rgba(4, 47, 46, 0.3);
    }
    .badge-glass-dark .dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #10b981;
    }
    .badge-glass-dark .text {
        font-size: 11px;
        font-weight: 700;
        color: #ffffff;
        letter-spacing: 0.02em;
    }

    /* ── Gradient Text ── */
    .gradient-text {
        background: linear-gradient(135deg, #0d9488 0%, #006c49 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    /* ── Stat Chips ── */
    .stat-chip {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 10px 18px;
        background: #ffffff;
        border-radius: 16px;
        border: 1px solid rgba(15, 23, 42, 0.04);
        box-shadow: 0 4px 12px rgba(15, 23, 42, 0.02);
    }
    .stat-chip .material-symbols-outlined {
        font-size: 18px;
        color: #0d9488;
    }
    .stat-chip .text {
        font-size: 13px;
        font-weight: 700;
        color: #475569;
    }

    /* ── Email Feature Card ── */
    .email-feature {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        border-radius: 32px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.04);
        box-shadow: 
            0 24px 80px -12px rgba(15, 23, 42, 0.05),
            0 0 0 1px rgba(15, 23, 42, 0.01);
        min-height: 360px;
    }
    @media (max-width: 900px) { .email-feature { grid-template-columns: 1fr; } }

    .email-dark-panel {
        background: linear-gradient(135deg, #042f2e 0%, #0d9488 100%);
        padding: 3.5rem;
        color: #ffffff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    .email-dark-panel::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 280px; height: 280px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.06) 0%, transparent 70%);
        pointer-events: none;
    }

    .email-address-link {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        padding: 14px 20px;
        border-radius: 16px;
        background: rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.12);
        color: #ffffff;
        text-decoration: none;
        font-weight: 700;
        font-size: 14px;
        transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1);
        word-break: break-all;
        backdrop-filter: blur(8px);
    }
    .email-address-link:hover {
        background: rgba(255, 255, 255, 0.15);
        border-color: rgba(255, 255, 255, 0.25);
        transform: translateY(-3px);
        box-shadow: 0 10px 25px -10px rgba(0, 0, 0, 0.15);
    }

    /* ── Steps ── */
    .step-card {
        display: flex;
        align-items: start;
        gap: 16px;
    }
    .step-number {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 16px;
        color: #ffffff;
        background: linear-gradient(135deg, #0d9488 0%, #006c49 100%);
        box-shadow: 0 4px 12px rgba(13, 148, 136, 0.25);
        flex-shrink: 0;
    }
    .step-title {
        font-weight: 800;
        color: #0f172a;
        font-size: 16px;
        margin-bottom: 4px;
    }
    .step-desc {
        color: #64748b;
        font-size: 13px;
        line-height: 1.6;
    }

    .btn-gradient {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 16px;
        color: #ffffff;
        font-size: 13px;
        font-weight: 750;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        text-decoration: none;
        background: linear-gradient(135deg, #0d9488 0%, #006c49 100%);
        box-shadow: 0 4px 14px rgba(13, 148, 136, 0.25);
        transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1);
        border: 1px solid rgba(13, 148, 136, 0.1);
        position: relative;
        z-index: 10;
        cursor: pointer;
    }
    .btn-gradient:hover {
        background: linear-gradient(135deg, #0f766e 0%, #005a3c 100%);
        box-shadow: 0 8px 24px rgba(13, 148, 136, 0.35);
        transform: translateY(-2px);
    }
    .btn-gradient:hover span.material-symbols-outlined {
        transform: translateX(3px);
    }
    .btn-gradient span.material-symbols-outlined {
        transition: transform 250ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-gradient:active {
        transform: translateY(0) scale(0.98);
    }

    /* ── Map Strip ── */
    .map-strip {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-radius: 32px;
        overflow: hidden;
        border: 1px solid rgba(15, 23, 42, 0.04);
        box-shadow: 
            0 24px 80px -12px rgba(15, 23, 42, 0.05),
            0 0 0 1px rgba(15, 23, 42, 0.01);
        min-height: 420px;
    }
    @media (max-width: 768px) {
        .map-strip { grid-template-columns: 1fr; }
        .map-photo { min-height: 280px; order: -1; }
    }

    .address-card {
        display: flex;
        align-items: start;
        gap: 16px;
        padding: 20px;
        background: #f8faf9;
        border-radius: 20px;
        border: 1px solid rgba(15, 23, 42, 0.03);
    }
    .address-icon {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(13, 148, 136, 0.08);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #0d9488;
        flex-shrink: 0;
    }

    .btn-navigation {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        padding: 14px 28px;
        border-radius: 16px;
        color: #ffffff;
        font-size: 12px;
        font-weight: 750;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        text-decoration: none;
        background: linear-gradient(135deg, #042f2e 0%, #0d9488 100%);
        box-shadow: 0 4px 14px rgba(4, 47, 46, 0.2);
        transition: all 300ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-navigation:hover {
        background: linear-gradient(135deg, #053a39 0%, #0f766e 100%);
        box-shadow: 0 8px 24px rgba(4, 47, 46, 0.3);
        transform: translateY(-2px);
    }
    .btn-navigation:hover span.material-symbols-outlined:first-child {
        transform: translate(2px, -2px);
    }
    .btn-navigation span.material-symbols-outlined {
        transition: transform 250ms cubic-bezier(0.16, 1, 0.3, 1);
    }
    .btn-navigation:active {
        transform: translateY(0) scale(0.98);
    }

    /* ── Staggered entry ── */
    .reveal { opacity: 0; transform: translateY(32px); transition: opacity 600ms ease, transform 600ms cubic-bezier(.16,1,.3,1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-d1 { transition-delay: 80ms; }
    .reveal-d2 { transition-delay: 180ms; }
    .reveal-d3 { transition-delay: 280ms; }
</style>
@endpush

@section('content')

{{-- Skip link --}}
<a href="#main-content" class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-primary focus:text-white focus:rounded-lg focus:text-sm focus:font-bold">
    Lewati ke konten
</a>

<div id="contact-page-bg">
<main id="main-content" class="max-w-7xl mx-auto px-5 md:px-10 pb-32 pt-8">

    {{-- ─── HERO ─── --}}
    <section class="contact-hero mb-16 reveal" aria-label="Judul halaman kontak">

        {{-- Left: Editorial copy --}}
        <div class="flex flex-col justify-center px-10 md:px-16 py-16 relative z-10">
            {{-- Badge --}}
            <div class="badge-status-light">
                <span class="dot"></span>
                <span class="text">Bantuan &amp; Layanan</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-black text-slate-900 leading-[1] tracking-tight font-jakarta mb-8" style="text-wrap:balance">
                Hubungi<br>
                <span class="gradient-text">Kami.</span>
            </h1>

            <p class="text-slate-500 text-xl font-medium leading-relaxed max-w-lg mb-10" style="text-wrap:pretty">
                Kami siap memberikan informasi dan bantuan untuk kesehatan keluarga Anda di wilayah Bekasi Timur.
            </p>

            {{-- Quick stat chips --}}
            <div class="flex gap-4 flex-wrap">
                <div class="stat-chip">
                    <span class="material-symbols-outlined">schedule</span>
                    <span class="text">Senin – Jumat</span>
                </div>
                <div class="stat-chip">
                    <span class="material-symbols-outlined">bolt</span>
                    <span class="text">Respons &lt; 1 hari</span>
                </div>
            </div>
        </div>

        {{-- Right: Real team photo --}}
        <div class="contact-hero-img relative overflow-hidden">
            <img src="{{ asset('assets/img/tim-kenanga.jpg') }}"
                 alt="Tim kader Posyandu Kenanga SPS berfoto bersama di ruang kegiatan"
                 class="absolute inset-0 w-full h-full object-cover object-top" loading="lazy">
            {{-- Gradient overlay on left edge to blend --}}
            <div class="absolute inset-0" style="background:linear-gradient(to right, rgba(255,255,255,0.55) 0%, transparent 35%)"></div>
            {{-- Badge overlay --}}
            <div class="absolute bottom-6 left-6 right-6">
                <div class="badge-glass-dark">
                    <span class="dot animate-pulse"></span>
                    <span class="text font-bold">Tim Kader SPS Kenanga</span>
                </div>
            </div>
        </div>
    </section>

    {{-- EMAIL FEATURE CARD --}}
    <section class="email-feature mb-8 reveal reveal-d1" aria-label="Kontak email utama">
        <div class="email-dark-panel">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3.5 py-1.5 rounded-full mb-6"
                     style="background:rgba(255, 255, 255, 0.08); border:1px solid rgba(255, 255, 255, 0.15)">
                    <span class="w-1.5 h-1.5 rounded-full bg-teal-300"></span>
                    <p class="text-[9px] font-black uppercase tracking-[0.22em] text-teal-300">Kontak Resmi</p>
                </div>
                <h2 class="text-3xl md:text-4xl font-black text-white leading-tight font-jakarta mb-4" style="text-wrap:balance">
                    Punya<br>Pertanyaan?
                </h2>
                <p class="text-sm font-medium leading-relaxed mb-8" style="color:rgba(255,255,255,.7);max-width:320px">
                    Jika ada pertanyaan mengenai jadwal kegiatan, pendaftaran layanan posyandu, kerja sama, atau saran lainnya, silakan hubungi kami. Kami siap membaca dan membalas pesan Anda.
                </p>
                <a href="mailto:posyanduilp_kenanga1@gmail.com"
                   class="email-address-link"
                   aria-label="Kirim email ke posyanduilp_kenanga1@gmail.com">
                    <span class="material-symbols-outlined text-teal-300 text-[20px] shrink-0">mail</span>
                    <span>posyanduilp_kenanga1@gmail.com</span>
                </a>
            </div>
            <div class="relative z-10 flex gap-6 mt-10">
                <div>
                    <p class="text-2xl font-black text-white font-jakarta">08.00</p>
                    <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.4)">Buka</p>
                </div>
                <div class="w-px self-stretch" style="background:rgba(255,255,255,.12)"></div>
                <div>
                    <p class="text-2xl font-black text-white font-jakarta">16.00</p>
                    <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.4)">Tutup</p>
                </div>
                <div class="w-px self-stretch" style="background:rgba(255,255,255,.12)"></div>
                <div>
                    <p class="text-2xl font-black text-white font-jakarta">WIB</p>
                    <p class="text-[9px] font-black uppercase tracking-widest" style="color:rgba(255,255,255,.4)">Zona</p>
                </div>
            </div>
        </div>
        <div class="flex flex-col justify-center p-12 md:p-16 bg-white relative overflow-hidden">
            <div class="absolute inset-0 pointer-events-none"
                 style="background-image:radial-gradient(#bbf7d0 1px,transparent 1px);background-size:22px 22px;opacity:.45"></div>
            <div class="relative z-10">
                <p class="text-xs font-black uppercase tracking-[0.22em] text-teal-600 mb-8">Cara Menghubungi</p>
                <div class="space-y-6 mb-10">
                    @foreach([
                        ['num'=>'1','title'=>'Tulis pesan','desc'=>'Sampaikan pertanyaan atau kebutuhan Anda dengan jelas.'],
                        ['num'=>'2','title'=>'Kirim ke email','desc'=>'Kirim ke posyanduilp_kenanga1@gmail.com kapan saja.'],
                        ['num'=>'3','title'=>'Tunggu balasan','desc'=>'Tim kami merespons dalam 1 hari kerja, Senin-Jumat.'],
                    ] as $step)
                    <div class="step-card">
                        <div class="step-number">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p class="step-title font-jakarta">{{ $step['title'] }}</p>
                            <p class="step-desc">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="mailto:posyanduilp_kenanga1@gmail.com" class="btn-gradient">
                    <span class="material-symbols-outlined text-[18px]">mail</span>
                    Kirim Email Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- ─── MAP STRIP ─── --}}
    <section class="map-strip reveal reveal-d3" aria-label="Lokasi kantor">

        {{-- Address details --}}
        <div class="flex flex-col justify-center p-12 md:p-16 bg-white relative overflow-hidden">
            {{-- Subtle dot grid --}}
            <div class="absolute inset-0 pointer-events-none"
                 style="background-image:radial-gradient(#a7f3d0 1.2px,transparent 1.2px);background-size:24px 24px;opacity:.35"></div>

            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6"
                     style="background:rgba(13, 148, 136, 0.06); border:1px solid rgba(13, 148, 136, 0.15)">
                    <span class="material-symbols-outlined text-teal-800 text-[14px]">location_on</span>
                    <p class="text-[9px] font-black uppercase tracking-[0.22em] text-teal-800">Temukan Kami</p>
                </div>

                <h2 class="text-4xl md:text-5xl font-black leading-tight font-jakarta mb-6 tracking-tight text-slate-900">
                    Kunjungi<br>
                    <span class="gradient-text">Lokasi Kami.</span>
                </h2>

                <div class="mb-8">
                    <div class="address-card">
                        <div class="address-icon">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-wider text-teal-800/60 mb-0.5 font-jakarta">Alamat Lengkap</p>
                            <p class="text-slate-700 text-sm font-semibold leading-relaxed">
                                Jl. Pulau Lombok I, RT.005/RW.011, Aren Jaya, Kec. Bekasi Timur, Kota Bekasi, Jawa Barat 17111
                            </p>
                        </div>
                    </div>
                </div>

                <a href="https://www.google.com/maps/place/Jl.+Pulau+Lombok+I,+RT.005%2FRW.011,+Aren+Jaya,+Kec.+Bekasi+Tim.,+Kota+Bks,+Jawa+Barat+17111/@-6.2440988,107.0340301,17z/data=!3m1!4b1!4m6!3m5!1s0x2e698ef3b1f96e2d:0x6b6411d4927c81b2!8m2!3d-6.2440988!4d107.0340301!16s%2Fg%2F11lf4vqv43!18m1!1e1?entry=ttu&g_ep=EgoyMDI2MDYyNC4wIKXMDSoASAFQAw%3D%3D" 
                   target="_blank" rel="noopener noreferrer"
                   class="btn-navigation focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2">
                    <span class="material-symbols-outlined text-[16px]">navigation</span>
                    Rute Google Maps
                    <span class="material-symbols-outlined text-[14px]" style="color:rgba(255,255,255,.6)">open_in_new</span>
                </a>
            </div>
        </div>

        {{-- Static Map Screenshot with Animated Overlays --}}
        <div class="map-photo relative overflow-hidden" style="background:#0d3b29">
            <img src="{{ asset('assets/img/peta-lokasi.png') }}"
                 alt="Peta wilayah Jl. Pulau Lombok I, Bekasi Timur"
                 class="absolute inset-0 w-full h-full object-cover opacity-70" loading="lazy">

            {{-- Green tint overlay --}}
            <div class="absolute inset-0" style="background:linear-gradient(145deg,rgba(13,59,41,.4) 0%,rgba(0,108,73,.1) 50%,transparent 80%)"></div>

            {{-- Floating Unit Count --}}
            <div class="absolute top-8 right-8 px-4 py-3 rounded-2xl shadow-xl bg-white/95 backdrop-filter backdrop-blur-[12px] border border-white/10">
                <p class="text-[9px] font-black uppercase tracking-widest text-slate-400 mb-0.5">Unit Posyandu</p>
                <p class="text-2xl font-black text-slate-900 font-jakarta">2</p>
            </div>

            {{-- Animated pin --}}
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2">
                <div class="relative">
                    <div class="absolute inset-0 w-16 h-16 rounded-full animate-ping -translate-x-1 -translate-y-1"
                         style="background:rgba(0,168,107,.35)"></div>
                    <div class="w-14 h-14 rounded-full flex items-center justify-center relative"
                         style="background:linear-gradient(135deg,#006c49,#00a86b);box-shadow:0 0 0 5px rgba(255,255,255,.25),0 10px 32px rgba(0,108,73,.6)">
                        <span class="material-symbols-outlined text-white text-[22px]">push_pin</span>
                    </div>
                    {{-- Ripple rings --}}
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 h-24 rounded-full border border-white/20 animate-ping" style="animation-duration:2s"></div>
                    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-36 h-36 rounded-full border border-white/10 animate-ping" style="animation-duration:2.5s"></div>
                </div>
            </div>

            {{-- Bottom label --}}
            <div class="absolute bottom-6 left-6 right-6">
                <div class="badge-glass-dark">
                    <span class="dot animate-pulse"></span>
                    <span class="text">Bekasi Timur, Jawa Barat</span>
                </div>
            </div>
        </div>

    </section>

</main>
</div>

<script>
(function () {
    'use strict';

    // Staggered reveal
    const obs = new IntersectionObserver(entries => {
        entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('visible'); obs.unobserve(e.target); } });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => obs.observe(el));
})();
</script>

@endsection
