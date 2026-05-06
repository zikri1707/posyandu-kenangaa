@extends('layouts.guest')

@section('title', 'Masuk - Dashboard Posyandu')

@section('content')
{{-- ── Modern Header Section ── --}}
<div class="mb-12 text-center md:text-left">
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-primary/10 text-primary rounded-xl text-[10px] font-black uppercase tracking-[0.2em] mb-8 border border-primary/20">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-primary/40 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-primary"></span>
        </span>
        Akses Petugas & Kader
    </div>
    
    <h2 class="text-display mb-6">
        Selamat Datang <br> <span class="text-primary italic">Kembali.</span>
    </h2>
    <p class="text-body-lg font-bold text-contrast-safe max-w-sm">
        Gunakan akun resmi Anda untuk mengelola data Posyandu.
    </p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-8">
    @csrf

    {{-- ── Email Input Field ── --}}
    <div class="group">
        <label for="email" class="ml-4 font-black text-high-contrast uppercase tracking-[0.3em] text-[10px] flex items-center gap-2 mb-3 group-focus-within:text-primary transition-colors">
            <span class="material-symbols-outlined text-[18px]">alternate_email</span>
            Alamat Email Resmi
        </label>
        <div class="relative">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="nama@posyandu.com"
                   class="w-full h-20 px-8 rounded-[2rem] bg-surface-container-low border-4 border-transparent text-xl font-black text-on-surface placeholder:text-slate-300 focus:outline-none focus:bg-white focus:border-primary/10 focus:ring-[12px] focus:ring-primary/5 transition-all shadow-inner group-hover:bg-surface-container">
            <div class="absolute inset-y-0 right-8 flex items-center text-slate-300 group-focus-within:text-primary transition-colors">
                <span class="material-symbols-outlined text-[32px]">verified_user</span>
            </div>
        </div>
    </div>

    {{-- ── Password Input Field ── --}}
    <div class="group">
        <div class="flex justify-between items-center px-4 mb-3">
            <label for="password" class="font-black text-high-contrast uppercase tracking-[0.3em] text-[10px] flex items-center gap-2 group-focus-within:text-primary transition-colors">
                <span class="material-symbols-outlined text-[18px]">key</span>
                Kata Sandi Akun
            </label>
        </div>
        <div class="relative">
            <input id="password" type="password" name="password" required
                   placeholder="••••••••"
                   class="w-full h-20 px-8 pr-24 rounded-[2rem] bg-surface-container-low border-4 border-transparent text-2xl font-black text-on-surface placeholder:text-slate-200 focus:outline-none focus:bg-white focus:border-primary/10 focus:ring-[12px] focus:ring-primary/5 transition-all shadow-inner group-hover:bg-surface-container tracking-widest">
            
            <button type="button" onclick="togglePassword()" id="toggleBtn"
                    class="absolute right-6 top-1/2 -translate-y-1/2 w-14 h-14 flex items-center justify-center rounded-2xl text-slate-400 hover:bg-white hover:text-primary hover:shadow-lg transition-all active:scale-95">
                <span class="material-symbols-outlined text-[32px]" id="toggleIcon">visibility</span>
            </button>
        </div>
    </div>

    {{-- ── Advanced Options ── --}}
    <div class="flex items-center justify-between px-4">
        <label class="relative flex items-center gap-3 cursor-pointer group select-none">
            <div class="relative">
                <input id="remember_me" type="checkbox" name="remember" 
                       class="sr-only peer">
                <div class="w-10 h-6 bg-slate-200 rounded-full peer peer-checked:bg-primary transition-all after:content-[''] after:absolute after:top-[4px] after:left-[4px] after:bg-white after:rounded-full after:h-4 after:w-4 after:transition-all peer-checked:after:translate-x-4"></div>
            </div>
            <span class="text-sm font-black text-slate-600 group-hover:text-primary transition-colors">Ingat Perangkat</span>
        </label>
    </div>

    {{-- ── Submission ── --}}
    <div class="pt-4">
        <button type="submit" id="submitBtn"
                class="btn-premium bg-premium-gradient w-full h-24 text-white text-2xl font-black uppercase tracking-[0.2em] rounded-[2.5rem] shadow-[0_20px_50px_rgba(0,104,95,0.3)] hover:shadow-[0_25px_60px_rgba(0,104,95,0.4)] hover:-translate-y-1 active:translate-y-0 active:scale-[0.98] transition-all flex items-center justify-center gap-5 group">
            <span>Masuk</span>
            <span class="material-symbols-outlined text-[36px] group-hover:translate-x-2 transition-transform">arrow_forward</span>
        </button>
    </div>

    {{-- ── High-Trust Support Section ── --}}
    <div class="mt-16 p-10 bg-surface-container-high rounded-[3rem] text-on-surface relative overflow-hidden group shadow-2xl border border-primary/5">
        <div class="absolute -right-10 -top-10 w-40 h-40 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>
        
        <div class="relative z-10 flex flex-col items-center text-center gap-6">
            <div class="w-16 h-16 bg-primary/10 rounded-[1.5rem] flex items-center justify-center backdrop-blur-xl border border-primary/10 text-primary">
                <span class="material-symbols-outlined text-[32px]">support_agent</span>
            </div>
            <div class="space-y-2">
                <p class="text-sm font-black uppercase tracking-[0.3em] text-primary">Pusat Bantuan</p>
                <p class="text-base font-bold text-contrast-safe">Butuh bantuan akses atau reset sandi?</p>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank"
               class="w-full h-16 inline-flex items-center justify-center gap-4 bg-white text-on-surface border-2 border-primary/10 rounded-2xl text-xs font-black uppercase tracking-widest hover:bg-primary hover:text-white transition-all active:scale-95">
                <span class="material-symbols-outlined text-[20px] text-primary group-hover:text-white transition-colors">chat_bubble</span>
                Hubungi Kami
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
            btn.classList.add('bg-white', 'text-primary', 'shadow-lg');
        } else {
            passInput.type = 'password';
            icon.textContent = 'visibility';
            btn.classList.remove('bg-white', 'text-primary', 'shadow-lg');
        }
    }

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <div class="w-10 h-10 border-4 border-white/30 border-t-white rounded-full animate-spin"></div>
            <span class="font-black uppercase tracking-[0.2em]">Memverifikasi...</span>
        `;
    });
</script>
@endsection
