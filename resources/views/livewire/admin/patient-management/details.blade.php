@extends('layouts.admin-layout')

@section('admin-title') @endsection

@section('admin-content')
@php
    $cat = $patient->category;
    $theme = match($cat) {
        'bayi', 'baduta', 'balita' => [
            'name' => 'Balita',
            'gradient' => 'from-teal-600 to-emerald-500',
            'shadow' => 'shadow-teal-500/10',
            'border' => 'border-teal-100',
            'bg-light' => 'bg-teal-50',
            'text' => 'text-teal-600',
            'text-hover' => 'hover:text-teal-600',
            'border-hover' => 'hover:border-teal-200',
            'partial' => 'balita'
        ],
        'lansia' => [
            'name' => 'Lansia',
            'gradient' => 'from-amber-600 to-orange-500',
            'shadow' => 'shadow-amber-500/10',
            'border' => 'border-amber-100',
            'bg-light' => 'bg-amber-50',
            'text' => 'text-amber-600',
            'text-hover' => 'hover:text-amber-600',
            'border-hover' => 'hover:border-amber-200',
            'partial' => 'lansia'
        ],
        'ibu_hamil' => [
            'name' => 'Ibu Hamil',
            'gradient' => 'from-rose-500 to-pink-500',
            'shadow' => 'shadow-rose-500/10',
            'border' => 'border-rose-100',
            'bg-light' => 'bg-rose-50',
            'text' => 'text-rose-600',
            'text-hover' => 'hover:text-rose-600',
            'border-hover' => 'hover:border-rose-200',
            'partial' => 'ibu_hamil'
        ],
        default => [
            'name' => str_replace('_', ' ', ucfirst($cat)),
            'gradient' => 'from-indigo-600 to-slate-500',
            'shadow' => 'shadow-indigo-500/10',
            'border' => 'border-indigo-100',
            'bg-light' => 'bg-indigo-50',
            'text' => 'text-indigo-600',
            'text-hover' => 'hover:text-indigo-600',
            'border-hover' => 'hover:border-indigo-200',
            'partial' => 'umum'
        ]
    };
@endphp
<div class="max-w-5xl mx-auto space-y-8 pb-10">

    {{-- ── Premium Header & Actions ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 mb-10 px-2">
        <div class="space-y-2">
            {{-- Breadcrumbs --}}
            <nav class="flex items-center gap-2 group/nav">
                <div class="flex items-center gap-1.5 px-3 py-1.5 rounded-full bg-white border border-slate-100 shadow-sm transition-all {{ $theme['border-hover'] }}">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-1.5 text-[10px] font-black uppercase tracking-widest text-slate-400 hover:text-teal-600 transition-colors">
                        <span class="material-symbols-outlined text-[14px]">home</span>
                        Beranda
                    </a>
                    <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                    <a href="{{ route('admin.patients.index') }}" class="text-[10px] font-black uppercase tracking-widest text-slate-400 {{ $theme['text-hover'] }} transition-colors">Data Warga</a>
                    <span class="material-symbols-outlined text-[14px] text-slate-300">chevron_right</span>
                    <span class="text-[10px] font-black uppercase tracking-widest {{ $theme['text'] }}">Profil Detail</span>
                </div>
            </nav>
            <h1 class="text-3xl font-black tracking-tight leading-none">
                <span class="text-transparent bg-clip-text bg-linear-to-r {{ $theme['gradient'] }}">{{ $patient->full_name }}</span>
            </h1>
        </div>

        <div class="flex items-center gap-3 flex-wrap">
            <x-button href="{{ route('admin.patients.edit', $patient->id) }}" 
                      variant="secondary" 
                      class="rounded-2xl! h-14 px-8! font-black shadow-xl {{ $theme['shadow'] }} hover:-translate-y-0.5 transition-all">
                <span class="material-symbols-outlined mr-2">edit</span>
                Edit Profil
            </x-button>

            <x-button href="{{ route('admin.reports.individual', $patient->id) }}"
                      variant="primary"
                      class="rounded-2xl! h-14 px-8! font-black shadow-xl bg-teal-600 text-white hover:bg-teal-700 transition-all">
                <span class="material-symbols-outlined mr-2">article</span>
                Lihat Rapor
            </x-button>
            
            <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Hapus data warga ini?')">
                @csrf @method('DELETE')
                <button type="submit" 
                        class="h-14 w-14 flex items-center justify-center rounded-2xl bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all duration-300 shadow-sm">
                    <span class="material-symbols-outlined">delete</span>
                </button>
            </form>
        </div>
    </div>

    {{-- ── Bento Grid Detail ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Card 1: Identitas Visual (Premium) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[3rem] border border-slate-100 p-10 flex flex-col items-center text-center relative overflow-hidden group shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                {{-- Decorative Background --}}
                <div class="absolute -right-10 -top-10 w-32 h-32 bg-teal-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform duration-700"></div>
                
                <div class="relative mb-8">
                    <div class="w-48 h-48 rounded-[3rem] border-4 border-white bg-slate-50 shadow-xl overflow-hidden relative z-10">
                        @if($patient->profile_photo)
                            <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full bg-linear-to-br from-slate-50 to-slate-100 flex items-center justify-center">
                                <span class="material-symbols-outlined text-slate-300 text-[96px]" style="font-variation-settings: 'wght' 100;">account_circle</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 px-5 py-2 bg-slate-900 text-white text-[10px] font-black rounded-2xl uppercase tracking-[0.2em] shadow-xl z-20 whitespace-nowrap">
                        {{ str_replace('_', ' ', $patient->category) }}
                    </div>
                </div>

                <h2 class="text-3xl font-black text-slate-900 leading-tight mb-2 tracking-tight">{{ $patient->full_name }}</h2>
                <div class="flex items-center gap-2 mb-8">
                    <span @class([
                        'text-[11px] font-black px-4 py-1 rounded-full uppercase tracking-widest border',
                        'text-sky-600 bg-sky-50 border-sky-100' => $patient->gender == 'L' || $patient->gender == 'M',
                        'text-pink-600 bg-pink-50 border-pink-100' => $patient->gender == 'F' || $patient->gender == 'P',
                    ])>NIK: {{ $patient->id_number }}</span>
                </div>

                <div class="w-full grid grid-cols-2 gap-4 pt-8 border-t border-slate-50">
                    <div @class([
                        'p-4 rounded-3xl border text-center group transition-all duration-500',
                        'bg-sky-50 border-sky-100 hover:bg-white hover:shadow-sky-100/50 hover:shadow-xl' => $patient->gender == 'L' || $patient->gender == 'M',
                        'bg-pink-50 border-pink-100 hover:bg-white hover:shadow-pink-100/50 hover:shadow-xl' => $patient->gender == 'F' || $patient->gender == 'P',
                    ])>
                        <p @class([
                            'text-[9px] font-black uppercase tracking-widest mb-1',
                            'text-sky-400' => $patient->gender == 'L' || $patient->gender == 'M',
                            'text-pink-400' => $patient->gender == 'F' || $patient->gender == 'P',
                        ])>Gender</p>
                        <p @class([
                            'text-[11px] font-black',
                            'text-sky-700' => $patient->gender == 'L' || $patient->gender == 'M',
                            'text-pink-700' => $patient->gender == 'F' || $patient->gender == 'P',
                        ])>{{ ($patient->gender == 'L' || $patient->gender == 'M') ? 'Laki-laki' : 'Perempuan' }}</p>
                    </div>
                    
                    <div class="p-4 bg-linear-to-br {{ $theme['gradient'] }} rounded-4xl text-center group hover:scale-105 transition-all duration-500 shadow-lg {{ $theme['shadow'] }} relative overflow-hidden">
                        <div class="absolute -right-4 -top-4 w-12 h-12 bg-white/10 rounded-full blur-lg"></div>
                        <p class="text-[9px] font-black text-white/70 uppercase tracking-widest mb-1 relative z-10">Usia</p>
                        <p class="text-[11px] font-black text-white relative z-10">{{ $patient->age }}</p>
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="bg-white rounded-[3rem] border border-slate-100 p-8 space-y-6 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-xl {{ $theme['bg-light'] }} {{ $theme['text'] }} flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">contact_phone</span>
                    </div>
                    <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Kontak & Domisili</h4>
                </div>
                
                <div class="space-y-5">
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">phone_iphone</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Nomor Telepon</p>
                            <p class="text-sm font-black text-slate-700">{{ $patient->phone_number }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">location_on</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Alamat</p>
                            <p class="text-sm font-bold text-slate-600 leading-relaxed">{{ $patient->address }}</p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <div class="w-8 h-8 rounded-lg bg-slate-50 flex items-center justify-center shrink-0 text-slate-400">
                            <span class="material-symbols-outlined text-[18px]">home_work</span>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Lokasi Layanan</p>
                            <p class="text-sm font-black text-slate-700">{{ str_contains($patient->posyandu->name, 'Posyandu') ? $patient->posyandu->name : 'Posyandu ' . $patient->posyandu->name }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Card 2: Data Medis & Sosial --}}
        <div class="lg:col-span-8 space-y-8">
            
            @include('livewire.admin.patient-management.details.' . $theme['partial'])
        </div>
    </div>

    @if(in_array($patient->category, ['bayi', 'baduta', 'balita']))
        {{-- Growth Chart (Full Width) ── --}}
        <div class="w-full mt-10 pb-16">
            @livewire('admin.patient-management.growth-chart', ['patient' => $patient, 'isEmbedded' => true])
        </div>
    @endif
</div>

@endsection
