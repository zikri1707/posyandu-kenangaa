@extends('layouts.admin-layout')

@section('admin-title') @endsection

@section('admin-content')
<div class="w-full space-y-10 pb-24">
    <div class="flex items-center justify-between px-4">
        <div class="bg-white/80 backdrop-blur-md px-8 py-4 rounded-[2rem] border border-white shadow-sm flex items-center gap-4">
            <div class="w-2 h-2 bg-primary rounded-full animate-pulse"></div>
            <h2 class="text-xl font-black text-slate-800 tracking-tight">Pemeriksaan Baru</h2>
        </div>
        <x-button href="{{ route('admin.medical-records.index') }}" variant="ghost" class="!bg-white border border-slate-200 !rounded-2xl !px-6 h-14 font-black">
            <span class="material-symbols-outlined mr-2 text-[24px]">arrow_back</span> Kembali
        </x-button>
    </div>

    <div class="flex flex-col items-center justify-center py-12 px-4 bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mx-4">
        <div class="text-center mb-12">
            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em] mb-3">Pilih Kategori Pemeriksaan</h3>
            <p class="text-xs font-bold text-slate-400">Silakan pilih salah satu kategori di bawah ini untuk melanjutkan pengisian data rekam medis.</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 w-full max-w-5xl">
            <!-- Category: Balita -->
            <a href="{{ route('admin.medical-records.create', array_merge(request()->query(), ['category' => 'balita'])) }}" class="group flex flex-col items-center p-8 bg-slate-50/50 border border-slate-200/60 rounded-[2.5rem] hover:border-primary hover:bg-white hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 text-center">
                <div class="w-20 h-20 mb-6 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 shadow-sm border border-teal-100">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1;">child_care</span>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-3 group-hover:text-primary transition-colors">Balita</h3>
                <p class="text-xs font-semibold text-slate-400 mb-8 leading-relaxed max-w-[240px]">Pemeriksaan tumbuh kembang, berat/tinggi badan, imunisasi, vitamin A, dan obat cacing anak.</p>
                <div class="mt-auto px-6 py-2.5 rounded-xl bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors">
                    Pilih Kategori
                </div>
            </a>

            <!-- Category: Ibu Hamil -->
            <a href="{{ route('admin.medical-records.create', array_merge(request()->query(), ['category' => 'ibu_hamil'])) }}" class="group flex flex-col items-center p-8 bg-slate-50/50 border border-slate-200/60 rounded-[2.5rem] hover:border-primary hover:bg-white hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 text-center">
                <div class="w-20 h-20 mb-6 rounded-2xl bg-pink-50 text-pink-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 shadow-sm border border-pink-100">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1;">pregnant_woman</span>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-3 group-hover:text-primary transition-colors">Ibu Hamil</h3>
                <p class="text-xs font-semibold text-slate-400 mb-8 leading-relaxed max-w-[240px]">Pencatatan pemeriksaan kehamilan rutin, keluhan, rujukan, serta pemberian tablet FE.</p>
                <div class="mt-auto px-6 py-2.5 rounded-xl bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors">
                    Pilih Kategori
                </div>
            </a>

            <!-- Category: Lansia -->
            <a href="{{ route('admin.medical-records.create', array_merge(request()->query(), ['category' => 'lansia'])) }}" class="group flex flex-col items-center p-8 bg-slate-50/50 border border-slate-200/60 rounded-[2.5rem] hover:border-primary hover:bg-white hover:shadow-xl hover:-translate-y-1.5 transition-all duration-300 text-center">
                <div class="w-20 h-20 mb-6 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center transition-transform duration-300 group-hover:scale-110 shadow-sm border border-amber-100">
                    <span class="material-symbols-outlined text-[40px]" style="font-variation-settings: 'FILL' 1;">elderly</span>
                </div>
                <h3 class="text-lg font-black text-slate-800 mb-3 group-hover:text-primary transition-colors">Lansia</h3>
                <p class="text-xs font-semibold text-slate-400 mb-8 leading-relaxed max-w-[240px]">Pencatatan hasil Posbindu lansia (tekanan darah, gula darah, kolesterol, asam urat, obat).</p>
                <div class="mt-auto px-6 py-2.5 rounded-xl bg-slate-100 text-[10px] font-black uppercase tracking-wider text-slate-500 group-hover:bg-primary group-hover:text-white transition-colors">
                    Pilih Kategori
                </div>
            </a>
        </div>
    </div>
</div>
@endsection
