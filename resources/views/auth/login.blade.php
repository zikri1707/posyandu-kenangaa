@extends('layouts.guest')

@section('title', 'Masuk - Dashboard Posyandu')

@section('content')
<div class="mb-10 text-center md:text-left">
    <div class="inline-flex items-center gap-2 px-4 py-2 bg-teal-50 text-teal-700 rounded-full text-xs font-black uppercase tracking-widest mb-6">
        <span class="relative flex h-2 w-2">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-teal-400 opacity-75"></span>
            <span class="relative inline-flex rounded-full h-2 w-2 bg-teal-500"></span>
        </span>
        Sistem Informasi Posyandu
    </div>
    <h2 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tighter leading-[0.9] mb-4">
        Selamat Datang <br> <span class="text-teal-600 italic">Kembali.</span>
    </h2>
    <p class="text-lg font-bold text-slate-500 tracking-tight">Silakan masukkan Email dan Kata Sandi Anda untuk masuk ke sistem.</p>
</div>

<form method="POST" action="{{ route('login') }}" class="space-y-6">
    @csrf

    @if($errors->any())
    <div class="p-6 bg-red-50 border-2 border-red-100 rounded-[2rem] animate-in slide-in-from-top-4 duration-300">
        <div class="flex gap-3 text-red-800">
            <span class="material-symbols-outlined text-[24px]">error</span>
            <div class="text-sm font-bold">
                <p class="mb-1">Maaf, ada kesalahan:</p>
                <ul class="list-disc list-inside opacity-80">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Alamat Email -->
    <div class="space-y-3">
        <label for="email" class="ml-2 font-black text-slate-700 uppercase tracking-[0.2em] text-[11px] flex items-center gap-2">
            <span class="material-symbols-outlined text-[18px] text-teal-600">person</span>
            Email Anda
        </label>
        <div class="relative group">
            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                   placeholder="Contoh: arimbi@posyandu.com"
                   class="w-full h-20 px-8 rounded-[2rem] bg-white border-4 border-slate-50 text-xl font-black text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-8 focus:ring-teal-500/5 focus:border-teal-600 transition-all shadow-sm">
        </div>
    </div>

    <!-- Kata Sandi -->
    <div class="space-y-3">
        <div class="flex justify-between items-center px-2">
            <label for="password" class="font-black text-slate-700 uppercase tracking-[0.2em] text-[11px] flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px] text-teal-600">lock</span>
                Kata Sandi
            </label>
        </div>
        <div class="relative group">
            <input id="password" type="password" name="password" required
                   placeholder="Masukkan sandi..."
                   class="w-full h-20 px-8 pr-20 rounded-[2rem] bg-white border-4 border-slate-50 text-xl font-black text-slate-900 placeholder:text-slate-300 focus:outline-none focus:ring-8 focus:ring-teal-50/5 focus:border-teal-600 transition-all shadow-sm">
            
            <button type="button" onclick="togglePassword()" id="toggleBtn"
                    class="absolute right-6 top-1/2 -translate-y-1/2 w-12 h-12 flex items-center justify-center text-slate-400 hover:text-teal-600 transition-colors">
                <span class="material-symbols-outlined text-[24px]" id="toggleIcon">visibility</span>
            </button>
        </div>
    </div>

    <!-- Opsi Tambahan -->
    <div class="flex items-center justify-between px-2 py-2">
        <label class="relative flex items-center gap-3 cursor-pointer group">
            <input id="remember_me" type="checkbox" name="remember" 
                   class="w-7 h-7 text-teal-600 border-4 border-slate-100 rounded-xl focus:ring-teal-500/20 transition-all cursor-pointer">
            <span class="text-sm font-black text-slate-600 group-hover:text-teal-600 transition-colors">Tetap Masuk</span>
        </label>
    </div>

    <!-- Tombol Masuk -->
    <div class="pt-2">
        <button type="submit" id="submitBtn"
                class="w-full h-24 bg-teal-600 text-white text-2xl font-black uppercase tracking-[0.2em] rounded-[2.5rem] shadow-2xl shadow-teal-900/30 hover:bg-teal-700 hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-4 group">
            Login
            <span class="material-symbols-outlined text-[32px] group-hover:translate-x-2 transition-transform">login</span>
        </button>
    </div>

    {{-- Support / Help Section --}}
    <div class="mt-12 p-8 bg-slate-50 rounded-[2.5rem] border-2 border-dashed border-slate-200">
        <div class="flex flex-col items-center text-center gap-4">
            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-sm text-teal-600">
                <span class="material-symbols-outlined text-[28px]">contact_support</span>
            </div>
            <div>
                <p class="text-sm font-black text-slate-800">Kesulitan Masuk?</p>
                <p class="text-xs font-bold text-slate-500 mt-1">Jangan khawatir! Hubungi Ketua Kader untuk reset sandi atau bantuan lainnya.</p>
            </div>
            <a href="https://wa.me/6281234567890" target="_blank"
               class="inline-flex items-center gap-2 px-6 py-3 bg-white border border-slate-200 rounded-2xl text-xs font-black text-slate-700 hover:bg-slate-100 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px] text-green-500">chat</span>
                Chat WhatsApp Admin
            </a>
        </div>
    </div>

</form>

<script>
    function togglePassword() {
        const passInput = document.getElementById('password');
        const icon = document.getElementById('toggleIcon');
        if (passInput.type === 'password') {
            passInput.type = 'text';
            icon.textContent = 'visibility_off';
        } else {
            passInput.type = 'password';
            icon.textContent = 'visibility';
        }
    }

    document.querySelector('form').addEventListener('submit', function() {
        const btn = document.getElementById('submitBtn');
        btn.disabled = true;
        btn.innerHTML = `
            <svg class="animate-spin h-8 w-8 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="font-black uppercase tracking-[0.2em]">Sedang Masuk...</span>
        `;
    });
</script>
@endsection
