@extends('layouts.admin-layout')

@section('admin-title')
    Detail Rekam Medis - {{ $medicalRecord->patient->full_name }}
@endsection

@section('admin-content')
<div class="max-w-5xl mx-auto space-y-6">
    {{-- Header --}}
    <div class="flex flex-wrap items-center justify-between gap-4">
        <div>
            <h2 class="text-2xl font-black text-slate-900 tracking-tight">Detail Pemeriksaan</h2>
            <p class="text-sm font-semibold text-slate-500">Informasi lengkap hasil kunjungan bulanan.</p>
        </div>
        <div class="flex items-center gap-2">
            <x-button href="{{ route('admin.medical-records.edit', $medicalRecord->id) }}" variant="secondary" size="md" icon="edit">
                Edit Data
            </x-button>
            <x-button href="{{ route('admin.medical-records.index') }}" variant="outline" size="md" icon="arrow_back">
                Kembali
            </x-button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Left: Patient & Measurement --}}
        <div class="lg:col-span-2 space-y-6">
            {{-- Assessment Card --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-8">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">analytics</span>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Hasil Pengukuran Utama</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="p-6 rounded-3xl bg-slate-50/50 border border-slate-100 text-center">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Berat Badan</div>
                        <div class="text-2xl font-black text-slate-900">{{ $medicalRecord->weight ?? '-' }} <span class="text-sm font-bold text-slate-400">kg</span></div>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50/50 border border-slate-100 text-center">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Tinggi Badan</div>
                        <div class="text-2xl font-black text-slate-900">{{ $medicalRecord->height ?? '-' }} <span class="text-sm font-bold text-slate-400">cm</span></div>
                    </div>
                    <div class="p-6 rounded-3xl bg-slate-50/50 border border-slate-100 text-center">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Lila</div>
                        <div class="text-2xl font-black text-slate-900">{{ $medicalRecord->head_circumference ?? '-' }} <span class="text-sm font-bold text-slate-400">cm</span></div>
                    </div>
                </div>

                <div class="mt-8 space-y-6">
                    <div class="space-y-2">
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Keluhan / Temuan</div>
                        <div class="p-5 bg-slate-50 rounded-2xl text-sm font-semibold text-slate-700 leading-relaxed italic">
                            "{{ $medicalRecord->complaint ?: 'Tidak ada keluhan khusus yang dicatat.' }}"
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Diagnosa / Hasil Akhir</div>
                        <div class="p-6 bg-teal-50/30 border border-teal-100 rounded-2xl text-sm font-bold text-slate-800 leading-relaxed">
                            {{ $medicalRecord->diagnosis }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right: Status & Context --}}
        <div class="space-y-6">
            {{-- Patient Bio --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    <div class="w-20 h-20 rounded-3xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-2xl border-4 border-white shadow-xl mb-4">
                        {{ strtoupper(substr($medicalRecord->patient->full_name, 0, 1)) }}
                    </div>
                    <h4 class="font-black text-slate-900 text-lg leading-tight">{{ $medicalRecord->patient->full_name }}</h4>
                    <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-500 text-[10px] font-black uppercase tracking-widest mt-2 border border-slate-200">
                        {{ $medicalRecord->patient->category }}
                    </span>
                    
                    <div class="w-full grid grid-cols-2 gap-4 mt-8 pt-8 border-t border-slate-100">
                        <div class="text-left">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">NIK</div>
                            <div class="text-xs font-bold text-slate-700">{{ $medicalRecord->patient->id_number }}</div>
                        </div>
                        <div class="text-left">
                            <div class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-0.5">Umur</div>
                            <div class="text-xs font-bold text-slate-700">{{ $medicalRecord->patient->age }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Visit Context --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="space-y-6">
                    <div class="flex items-center justify-between">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Periksa</div>
                        <div class="text-sm font-bold text-slate-900">{{ $medicalRecord->visit_date->format('d M Y') }}</div>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Petugas Pemeriksa</div>
                        <div class="text-sm font-bold text-slate-900">{{ $medicalRecord->user->name ?? '-' }}</div>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Imunisasi</div>
                        <div class="text-sm font-bold {{ $medicalRecord->vaccine_name ? 'text-teal-600' : 'text-slate-300' }}">
                            {{ $medicalRecord->vaccine_name ?: 'Tidak Ada' }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Vitamin A</div>
                        <div @class([
                            'text-sm font-bold',
                            'text-blue-600' => $medicalRecord->vitamin_a_color === 'biru',
                            'text-red-600' => $medicalRecord->vitamin_a_color === 'merah',
                            'text-slate-300' => $medicalRecord->vitamin_a_color === 'none',
                        ])>
                            {{ $medicalRecord->vitamin_a_color === 'none' ? 'Tidak Ada' : 'Kapsul ' . ucfirst($medicalRecord->vitamin_a_color) }}
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-6 border-t border-slate-50">
                        <div class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Obat Cacing</div>
                        <div class="text-sm font-bold {{ $medicalRecord->deworming_medicine ? 'text-orange-600' : 'text-slate-300' }}">
                            {{ $medicalRecord->deworming_medicine ? 'Sudah Diberikan' : 'Tidak' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection