@extends('layouts.guest')

@section('title', 'Masuk - Dashboard Posyandu')

@section('content')
{{-- ── Header Section ── --}}
<div class="mb-12 text-center md:text-left">
    <div class="inline-flex items-center gap-2.5 px-5 py-2 bg-teal-50/80 text-teal-800 rounded-full text-xs font-bold uppercase tracking-wider mb-6 border border-teal-200/40">
        <span class="relative flex h-2.5 w-2.5">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-teal-600"></span>
        </span>
        Akses Petugas &amp; Kader
    </div>
    
    <h2 class="text-4xl md:text-5xl font-extrabold text-slate-900 leading-tight tracking-tight font-jakarta mb-4">
        Selamat Datang <br> <span class="text-teal-700">Kembali.</span>
    </h2>
    <p class="text-slate-600 text-lg font-medium leading-relaxed max-w-md">
        Gunakan akun resmi Anda untuk mengelola data Posyandu.
    </p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-8">
    @csrf

    {{-- ── Email/Username Input Field ── --}}
    <div class="group">
        <label for="email" class="ml-1 font-bold text-slate-700 text-base flex items-center gap-2 mb-3 group-focus-within:text-teal-700 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-teal-600">alternate_email</span>
            Email / Username
        </label>
        <div class="relative">
            <input id="email" type="text" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="nama@posyandu.com atau admin_kenanga1"
                   class="w-full h-16 px-6 rounded-2xl bg-slate-50 border border-slate-300 text-lg font-medium text-slate-800 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-100 transition-all"
                   style="border: 1px solid #cbd5e1; outline: none;">
            <div class="absolute inset-y-0 right-6 flex items-center text-slate-400 group-focus-within:text-teal-600 transition-colors">
                <span class="material-symbols-outlined text-[26px]">verified_user</span>
            </div>
        </div>
    </div>

    {{-- ── Password Input Field ── --}}
    <div class="group">
        <label for="password" class="ml-1 font-bold text-slate-700 text-base flex items-center gap-2 mb-3 group-focus-within:text-teal-700 transition-colors">
            <span class="material-symbols-outlined text-[20px] text-teal-600">key</span>
            Kata Sandi Akun
        </label>
        <div class="relative">
            <input id="password" type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full h-16 px-6 pr-16 rounded-2xl bg-slate-50 border border-slate-300 text-xl font-medium text-slate-800 placeholder:text-slate-400 focus:outline-none focus:bg-white focus:border-teal-600 focus:ring-4 focus:ring-teal-100 transition-all tracking-widest"
                   style="border: 1px solid #cbd5e1; outline: none;">
            
            <button type="button" onclick="togglePassword()" id="toggleBtn"
                    class="absolute right-4 top-1/2 -translate-y-1/2 w-11 h-11 flex items-center justify-center rounded-xl text-slate-400 hover:bg-slate-100 hover:text-teal-700 transition-all active:scale-95">
                <span class="material-symbols-outlined text-[24px]" id="toggleIcon">visibility</span>
            </button>
        </div>
    </div>

    {{-- ── Advanced Options (Ingat Perangkat) ── --}}
    <div class="flex items-center px-1">
        <label class="inline-flex items-center gap-3 cursor-pointer select-none">
            <input id="remember_me" type="checkbox" name="remember" 
                   class="w-6 h-6 text-teal-600 border-slate-300 rounded focus:ring-teal-500 cursor-pointer"
                   style="accent-color: #0d9488;">
            <span class="text-base font-bold text-slate-700 hover:text-teal-700 transition-colors">Ingat Perangkat</span>
        </label>
    </div>

    {{-- ── Submission ── --}}
    <div class="pt-2">
        <button type="submit" id="submitBtn"
                class="w-full h-16 text-lg font-bold uppercase tracking-wider rounded-2xl shadow-lg transition-all flex items-center justify-center gap-3 group"
                style="background-color: #0d9488 !important; color: #ffffff !important;">
            <span>LOGIN</span>
            <span class="material-symbols-outlined text-[22px]">arrow_forward</span>
        </button>
    </div>

    {{-- ── Support Section ── --}}
    <div class="mt-12 p-8 bg-slate-50 rounded-3xl border border-slate-200 relative overflow-hidden group shadow-sm">
        <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-500/5 rounded-full blur-2xl group-hover:bg-teal-500/10 transition-colors"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center gap-5">
            <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-700 border border-teal-100/60">
                <span class="material-symbols-outlined text-[28px]">support_agent</span>
            </div>
            <div class="space-y-1.5">
                <p class="text-xs font-bold uppercase tracking-widest text-teal-700">Pusat Bantuan</p>
                <p class="text-base font-semibold text-slate-700">Butuh bantuan akses atau reset sandi?</p>
            </div>
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=posyanduilp_kenanga1@gmail.com"
               target="_blank" rel="noopener noreferrer"
               class="w-full h-14 inline-flex items-center justify-center gap-2.5 bg-white text-slate-800 border border-slate-300 rounded-xl text-sm font-bold uppercase tracking-wider hover:bg-teal-50 hover:text-teal-900 hover:border-teal-300 transition-all active:scale-95 shadow-xs"
               style="border: 1px solid #cbd5e1;">
                <span class="material-symbols-outlined text-[20px] text-teal-600">mail</span>
                Hubungi via Gmail
            </a>
        </div>
    </div>
</form>

<script>
    function togglePassword() {
        const passInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        const btn = document.getElementById('toggleBtn');
        
        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.textContent = 'visibility_off';
            btn.classList.add('bg-slate-100', 'text-teal-700');
        } else {
            passInput.type = 'password';
            icon.textContent = 'visibility';
            btn.classList.remove('bg-slate-100', 'text-teal-700');
        }
    }

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            <span class="font-bold uppercase tracking-widest text-base">Memverifikasi...</span>
        `;
    });
</script>

@endsection
