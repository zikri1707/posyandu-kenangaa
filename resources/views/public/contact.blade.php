@extends('layouts.public-layout')

@section('title', 'Hubungi Kami - Posyandu Kenanga')

@push('head')
<meta name="description" content="Hubungi Posyandu Kenanga Bekasi Timur melalui email. Kami siap memberikan informasi layanan kesehatan, jadwal, dan bantuan untuk keluarga Anda.">
<style>
    /* â”€â”€ Page Background â”€â”€ */
    #contact-page-bg {
        background: #f0f7f4;
        background-image:
            radial-gradient(circle at 15% 20%, rgba(0,108,73,.07) 0%, transparent 45%),
            radial-gradient(circle at 85% 70%, rgba(0,108,73,.05) 0%, transparent 40%);
    }

    /* â”€â”€ Hero â”€â”€ */
    .contact-hero {
        display: grid;
        grid-template-columns: 1fr 1fr;
        min-height: 540px;
        background: #fff;
        border-radius: 2.5rem;
        overflow: hidden;
        border: 1px solid rgba(0,108,73,.1);
        box-shadow: 0 24px 80px -12px rgba(13,59,41,.12), 0 0 0 1px rgba(0,108,73,.05);
        position: relative;
    }
    .contact-hero::before {
        content: '';
        position: absolute;
        inset: 0;
        background-image: radial-gradient(#d1fae5 1px, transparent 1px);
        background-size: 24px 24px;
        opacity: 0.35;
        pointer-events: none;
        z-index: 0;
    }
    @media (max-width: 768px) {
        .contact-hero { grid-template-columns: 1fr; min-height: auto; }
        .contact-hero-img { display: none; }
    }

    /* ── Email Feature Card ── */
    .email-feature {
        display: grid;
        grid-template-columns: 1fr 1.1fr;
        border-radius: 2.5rem;
        overflow: hidden;
        border: 1px solid rgba(0,108,73,.1);
        box-shadow: 0 20px 60px -12px rgba(13,59,41,.14);
        min-height: 340px;
    }
    @media (max-width: 900px) { .email-feature { grid-template-columns: 1fr; } }

    .email-dark-panel {
        background: linear-gradient(155deg, #0d3b29 0%, #0a4f38 50%, #062e1f 100%);
        padding: 3rem 3rem 3rem 3.5rem;
        color: #fff;
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
        background: radial-gradient(circle, rgba(52,211,153,.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .email-dark-panel::after {
        content: '';
        position: absolute;
        bottom: -50px; left: -50px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,168,107,.18) 0%, transparent 70%);
        pointer-events: none;
    }

    .email-address-link {
        display: inline-flex;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.5rem;
        border-radius: 1.25rem;
        background: rgba(255,255,255,.08);
        border: 1px solid rgba(255,255,255,.12);
        color: #fff;
        text-decoration: none;
        font-weight: 700;
        font-size: 0.875rem;
        transition: background 220ms ease, transform 220ms cubic-bezier(.34,1.56,.64,1), border-color 220ms ease;
        word-break: break-all;
        backdrop-filter: blur(8px);
    }
    .email-address-link:hover {
        background: rgba(255,255,255,.15);
        border-color: rgba(255,255,255,.25);
        transform: translateY(-3px);
    }

    /* ── Contact Split ── */
    .contact-split {
        display: grid;
        grid-template-columns: 1fr 1.25fr;
        border-radius: 2.5rem;
        overflow: hidden;
        box-shadow: 0 24px 80px -12px rgba(13,59,41,.14);
    }
    @media (max-width: 900px) { .contact-split { grid-template-columns: 1fr; } }

    .contact-info-panel {
        background: linear-gradient(160deg, #0d3b29 0%, #0a4f36 55%, #063020 100%);
        padding: 3.5rem;
        color: #fff;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        position: relative;
        overflow: hidden;
    }
    /* Decorative circles on info panel */
    .contact-info-panel::before {
        content: '';
        position: absolute;
        top: -80px; right: -80px;
        width: 300px; height: 300px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(107,216,203,.12) 0%, transparent 70%);
        pointer-events: none;
    }
    .contact-info-panel::after {
        content: '';
        position: absolute;
        bottom: -60px; left: -60px;
        width: 240px; height: 240px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(0,108,73,.25) 0%, transparent 70%);
        pointer-events: none;
    }

    .contact-form-panel {
        background: #fff;
        padding: 3.5rem;
    }

    /* ── Form Styles ── */
    .cf-group { margin-bottom: 1.5rem; }
    .cf-label {
        display: block;
        font-size: 0.68rem;
        font-weight: 800;
        text-transform: uppercase;
        letter-spacing: 0.14em;
        color: #3d4947;
        margin-bottom: 0.55rem;
    }
    .cf-input, .cf-textarea, .cf-select {
        width: 100%;
        padding: 0.9rem 1.1rem;
        border-radius: 0.875rem;
        border: 1.5px solid #dce3e0;
        background: #f7fbf9;
        font-family: inherit;
        font-size: 0.925rem;
        color: #171d1c;
        transition: border-color 200ms ease, box-shadow 200ms ease, background 200ms ease;
        outline: none;
        appearance: none;
    }
    .cf-input:focus, .cf-textarea:focus, .cf-select:focus {
        border-color: #006c49;
        background: #fff;
        box-shadow: 0 0 0 4px rgba(0,108,73,.1);
    }
    .cf-input.error, .cf-textarea.error, .cf-select.error {
        border-color: #ba1a1a;
        background: #fff5f5;
        box-shadow: 0 0 0 3px rgba(186,26,26,.08);
    }
    .cf-error {
        font-size: 0.72rem;
        font-weight: 600;
        color: #ba1a1a;
        margin-top: 0.35rem;
        display: none;
    }
    .cf-error.visible { display: block; }
    .cf-textarea { resize: vertical; min-height: 130px; }

    .cf-submit {
        width: 100%;
        padding: 1.1rem;
        background: linear-gradient(135deg, #006c49 0%, #008358 100%);
        color: #fff;
        font-size: 0.7rem;
        font-weight: 900;
        text-transform: uppercase;
        letter-spacing: 0.16em;
        border-radius: 0.875rem;
        border: none;
        cursor: pointer;
        transition: opacity 200ms ease, transform 220ms ease, box-shadow 220ms ease;
        box-shadow: 0 8px 28px -4px rgba(0,108,73,.4);
        position: relative;
        overflow: hidden;
    }
    .cf-submit::before {
        content: '';
        position: absolute;
        inset: 0;
        background: linear-gradient(135deg, rgba(255,255,255,.1) 0%, transparent 60%);
        opacity: 0;
        transition: opacity 200ms ease;
    }
    .cf-submit:hover { transform: translateY(-2px); box-shadow: 0 14px 36px -4px rgba(0,108,73,.48); }
    .cf-submit:hover::before { opacity: 1; }
    .cf-submit:active { transform: translateY(0) scale(.99); }
    .cf-submit:disabled { background: #9eaaa7; cursor: not-allowed; transform: none; box-shadow: none; }
    .cf-submit:focus-visible { outline: 2px solid #006c49; outline-offset: 3px; }

    .cf-success {
        display: none;
        align-items: center;
        gap: 0.75rem;
        padding: 1rem 1.25rem;
        background: linear-gradient(135deg, #f0fdf4, #dcfce7);
        border: 1px solid #86efac;
        border-radius: 0.875rem;
        color: #15803d;
        font-size: 0.825rem;
        font-weight: 700;
        margin-top: 1rem;
        box-shadow: 0 4px 12px -4px rgba(21,128,61,.15);
    }
    .cf-success.visible { display: flex; }

    /* ── Map Strip ── */
    .map-strip {
        display: grid;
        grid-template-columns: 1fr 1fr;
        border-radius: 2.5rem;
        overflow: hidden;
        border: 1px solid rgba(0,108,73,.1);
        box-shadow: 0 12px 40px -8px rgba(0,0,0,.1);
        min-height: 420px;
    }
    @media (max-width: 768px) {
        .map-strip { grid-template-columns: 1fr; }
        .map-photo { min-height: 280px; order: -1; }
    }

    /* ── Staggered entry ── */
    .reveal { opacity: 0; transform: translateY(32px); transition: opacity 600ms ease, transform 600ms cubic-bezier(.22,1,.36,1); }
    .reveal.visible { opacity: 1; transform: translateY(0); }
    .reveal-d1 { transition-delay: 80ms; }
    .reveal-d2 { transition-delay: 180ms; }
    .reveal-d3 { transition-delay: 280ms; }

    /* ── Info panel contact rows ── */
    .info-row {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
        padding: 1rem 1.1rem;
        border-radius: 1rem;
        background: rgba(255,255,255,.06);
        border: 1px solid rgba(255,255,255,.08);
        backdrop-filter: blur(4px);
        transition: background 200ms ease;
    }
    .info-row:hover { background: rgba(255,255,255,.1); }
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
            <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full mb-8 self-start"
                 style="background:linear-gradient(135deg,#d1fae5,#a7f3d0);border:1px solid rgba(0,108,73,.2)">
                <span class="w-2.5 h-2.5 rounded-full bg-teal-500 animate-pulse"></span>
                <span class="text-xs font-black text-teal-800 uppercase tracking-[0.22em]">Bantuan &amp; Layanan</span>
            </div>

            <h1 class="text-6xl md:text-8xl font-black text-slate-900 leading-[1] tracking-tight font-jakarta mb-8" style="text-wrap:balance">
                Hubungi<br>
                <span style="background:linear-gradient(135deg,#006c49,#00a86b);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Kami.</span>
            </h1>

            <p class="text-slate-500 text-xl font-medium leading-relaxed max-w-lg mb-10" style="text-wrap:pretty">
                Kami siap memberikan informasi dan bantuan untuk kesehatan keluarga Anda di wilayah Bekasi Timur.
            </p>

            {{-- Quick stat chips --}}
            <div class="flex gap-4 flex-wrap">
                <div class="inline-flex items-center gap-3 px-5 py-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <span class="material-symbols-outlined text-teal-600 text-[20px]">schedule</span>
                    <span class="text-sm font-bold text-slate-600">Senin – Jumat</span>
                </div>
                <div class="inline-flex items-center gap-3 px-5 py-3 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <span class="material-symbols-outlined text-teal-600 text-[20px]">bolt</span>
                    <span class="text-sm font-bold text-slate-600">Respons < 1 hari</span>
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
                <div class="inline-flex items-center gap-3 px-4 py-2.5 rounded-2xl shadow-lg"
                     style="background:rgba(13,59,41,.85);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15)">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold text-white">Tim Kader SPS Kenanga</span>
                </div>
            </div>
        </div>
    </section>

    {{-- EMAIL FEATURE CARD --}}
    <section class="email-feature mb-8 reveal reveal-d1" aria-label="Kontak email utama">
        <div class="email-dark-panel">
            <div class="relative z-10">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full mb-6"
                     style="background:rgba(52,211,153,.12);border:1px solid rgba(52,211,153,.2)">
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
                    <div class="flex items-start gap-5">
                        <div class="w-12 h-12 rounded-2xl flex items-center justify-center shrink-0 font-black text-lg text-white"
                             style="background:linear-gradient(135deg,#006c49,#008358);box-shadow:0 6px 16px rgba(0,108,73,.25)">
                            {{ $step['num'] }}
                        </div>
                        <div>
                            <p class="font-black text-slate-900 text-lg mb-1 font-jakarta">{{ $step['title'] }}</p>
                            <p class="text-slate-500 text-sm font-medium leading-relaxed">{{ $step['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
                <a href="mailto:posyanduilp_kenanga1@gmail.com"
                   class="inline-flex items-center gap-3 px-8 py-4.5 rounded-2xl text-[13px] font-black uppercase tracking-widest text-white transition-all active:scale-95"
                   style="background:linear-gradient(135deg,#006c49,#008358);box-shadow:0 8px 28px -4px rgba(0,108,73,.45)">
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
                     style="background:linear-gradient(135deg,#e6f4ea,#d1fae5);border:1px solid rgba(0,108,73,.15)">
                    <span class="material-symbols-outlined text-teal-800 text-[14px]">location_on</span>
                    <p class="text-[9px] font-black uppercase tracking-[0.22em] text-teal-800">Temukan Kami</p>
                </div>

                <h2 class="text-4xl md:text-5xl font-black leading-tight font-jakarta mb-6 tracking-tight"
                    style="color:#0a3024">
                    Kunjungi<br>
                    <span style="background:linear-gradient(135deg,#006c49,#059669);-webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text">Lokasi Kami.</span>
                </h2>

                <div class="mb-8">
                    <div class="flex items-start gap-4 p-4 bg-[#f4fbf7] rounded-2xl border border-teal-50/80 shadow-sm">
                        <div class="w-10 h-10 rounded-xl bg-teal-100 flex items-center justify-center text-teal-700 shrink-0">
                            <span class="material-symbols-outlined text-[20px]">location_on</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black uppercase tracking-wider text-teal-800/60 mb-0.5">Alamat Lengkap</p>
                            <p class="text-slate-700 text-sm font-semibold leading-relaxed">
                                Jl. Pulau Lombok I, RT.005/RW.011, Aren Jaya, Kec. Bekasi Timur, Kota Bekasi, Jawa Barat 17111
                            </p>
                        </div>
                    </div>
                </div>

                <a href="https://www.google.com/maps/place/Jl.+Pulau+Lombok+I,+RT.005%2FRW.011,+Aren+Jaya,+Kec.+Bekasi+Tim.,+Kota+Bks,+Jawa+Barat+17111/@-6.2440988,107.0340301,17z/data=!3m1!4b1!4m6!3m5!1s0x2e698ef3b1f96e2d:0x6b6411d4927c81b2!8m2!3d-6.2440988!4d107.0340301!16s%2Fg%2F11lf4vqv43!18m1!1e1?entry=ttu&g_ep=EgoyMDI2MDYyNC4wIKXMDSoASAFQAw%3D%3D" 
                   target="_blank" rel="noopener noreferrer"
                   class="inline-flex items-center gap-3 px-8 py-4 rounded-2xl self-start text-white text-[11px] font-black uppercase tracking-widest transition-all active:scale-95 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2"
                   style="background:linear-gradient(135deg,#0d3b29,#059669);box-shadow:0 8px 24px -4px rgba(13,59,41,.35)">
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
            <div class="absolute top-8 right-8 px-4 py-3 rounded-2xl shadow-xl"
                 style="background:rgba(255,255,255,.92);backdrop-filter:blur(12px)">
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
                <div class="inline-flex items-center gap-2.5 px-4 py-2.5 rounded-2xl"
                     style="background:rgba(13,59,41,.85);backdrop-filter:blur(12px);border:1px solid rgba(255,255,255,.15)">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-[11px] font-bold text-white">Bekasi Timur, Jawa Barat</span>
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
