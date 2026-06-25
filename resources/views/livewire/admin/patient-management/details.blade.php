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
            'partial' => 'balita',
            'avatar_icon' => 'child_care'
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
            'partial' => 'lansia',
            'avatar_icon' => 'elderly'
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
            'partial' => 'ibu_hamil',
            'avatar_icon' => 'pregnant_woman'
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
            'partial' => 'umum',
            'avatar_icon' => 'account_circle'
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
                <span class="text-transparent bg-clip-text bg-linear-to-r {{ $theme['gradient'] }}">Profil Detail Warga</span>
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


    {{-- ── Section 2: Data Medis & Sosial (Full Width) ── --}}
    <div class="w-full">
        @include('livewire.admin.patient-management.details.' . $theme['partial'])
    </div>

    @if(in_array($patient->category, ['bayi', 'baduta', 'balita']))
        {{-- Growth Chart (Full Width) ── --}}
        <div class="w-full mt-10 pb-16">
            @livewire('admin.patient-management.growth-chart', ['patient' => $patient, 'isEmbedded' => true])
        </div>
    @endif

</div>

@endsection
