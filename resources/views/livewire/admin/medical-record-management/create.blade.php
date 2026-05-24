@extends('layouts.admin-layout')

@section('admin-title') @endsection

@push('styles')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<style>
    /* TomSelect Premium Customization */
    .ts-control {
        border-radius: 1.25rem !important;
        padding: 0.75rem 1.25rem !important;
        border: 2px solid #e2e8f0 !important;
        background-color: #ffffff !important;
        font-weight: 800 !important;
        font-size: 0.95rem !important;
        color: #0f172a !important; /* slate-900 */
        transition: all 0.3s ease !important;
        min-height: 4rem !important;
        display: flex !important;
        align-items: center !important;
    }
    .ts-wrapper.focus .ts-control {
        border-color: #006c49 !important; /* primary */
        box-shadow: 0 0 0 4px rgba(0, 108, 73, 0.05) !important;
        background-color: #fff !important;
    }
    .ts-dropdown {
        border-radius: 1.25rem !important;
        border: 1px solid #e2e8f0 !important;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04) !important;
        padding: 0.5rem !important;
        margin-top: 0.5rem !important;
    }
    .ts-dropdown .active {
        background-color: #ffffff !important;
        color: #006c49 !important; /* primary text */
        border: 2px solid #006c49 !important;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06) !important;
        border-radius: 0.75rem !important;
    }
    .ts-dropdown .option {
        padding: 1rem 1.25rem !important;
        border-radius: 0.75rem !important;
        margin-bottom: 0.35rem !important;
    }
    .ts-control .item {
        font-weight: 700 !important;
    }
    
    /* Layout Fix for Squashed UI */
    #mainContent, main {
        width: 100% !important;
        max-width: none !important;
        flex: 1 1 0% !important;
    }
</style>
@endpush

@section('admin-content')
<div class="w-full pb-12 px-4" x-data="{ category: '{{ old('category', request('category', $selectedPatient->category ?? 'balita')) }}' }" @category-updated.window="category = $event.detail">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-3xl bg-primary/10 flex items-center justify-center border border-primary/20 shadow-sm">
                <span class="material-symbols-outlined text-primary text-3xl">medical_information</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Formulir Pemeriksaan</h1>
                <p class="text-sm font-medium text-slate-500">
                    <span x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">Pencatatan data kesehatan rutin balita</span>
                    <span x-show="category === 'ibu_hamil'">Pencatatan data kesehatan ibu hamil</span>
                    <span x-show="category === 'lansia'">Pencatatan data kesehatan lansia</span>
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.medical-records.create') }}" class="px-5 py-3 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center gap-2 hover:bg-slate-50 transition-all text-xs font-bold text-slate-600">
                <span class="material-symbols-outlined text-sm">arrow_back</span>
                <span>Pilih Kategori</span>
            </a>
            <div class="px-4 py-3 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">{{ date('d M Y') }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.medical-records.store') }}" method="POST" class="flex flex-col gap-8 min-w-0">
        @csrf

        {{-- Global Validation Errors --}}
        @if($errors->any())
            <div class="bg-rose-50 border-2 border-rose-200 rounded-[2rem] p-6 flex items-center gap-4 animate-bounce">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <h4 class="text-rose-900 font-black uppercase text-xs tracking-widest">Ada Kesalahan Input</h4>
                    <p class="text-rose-700 text-xs font-bold mt-1">Mohon periksa kembali kolom yang bertanda merah di bawah.</p>
                </div>
            </div>
        @endif

        {{-- Main Form Area --}}
        <div class="flex-1 space-y-8">
            
            {{-- 1. Identitas & Kunjungan --}}
            <div class="bg-white/70 backdrop-blur-xl rounded-[3rem] border border-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-10 relative overflow-hidden group transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.1)]">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-primary/5 rounded-full blur-3xl group-hover:bg-primary/10 transition-colors"></div>
                
                <div class="flex items-center gap-4 mb-10 relative">
                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-primary to-primary-container text-white flex items-center justify-center shadow-lg shadow-primary/20">
                        <span class="material-symbols-outlined text-[24px]">person</span>
                    </div>
                    <div>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Identitas & Kunjungan</h3>
                        <p class="text-xs font-bold text-slate-400 mt-0.5">Informasi dasar kedatangan balita</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-8 relative">
                    {{-- Patient Selection --}}
                    <div class="md:col-span-8 space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">
                            Nama 
                            <span x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">Balita</span>
                            <span x-show="category === 'ibu_hamil'">Ibu Hamil</span>
                            <span x-show="category === 'lansia'">Lansia</span>
                            / Sasaran <span class="text-primary">*</span>
                        </label>
                        <div class="relative group/select">
                            <select name="patient_id" id="patient-select" required placeholder="{{ request('category') === 'ibu_hamil' ? 'Cari nama atau NIK ibu hamil...' : (request('category') === 'lansia' ? 'Cari nama atau NIK lansia...' : 'Cari nama atau NIK balita...') }}"
                                    class="w-full h-16 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all appearance-none cursor-pointer bg-slate-50/30">
                                <option value="">{{ request('category') === 'ibu_hamil' ? 'Cari nama atau NIK ibu hamil...' : (request('category') === 'lansia' ? 'Cari nama atau NIK lansia...' : 'Cari nama atau NIK balita...') }}</option>
                                @foreach($patients as $patient)
                                    @php
                                        $lastRec = $patient->medicalRecords->first();
                                        $secondLastRec = $patient->medicalRecords->skip(1)->first();
                                    @endphp
                                    <option value="{{ $patient->id }}" 
                                            data-nik="{{ $patient->id_number }}" 
                                            data-father="{{ $patient->father_name }}"
                                            data-mother="{{ $patient->mother_name }}"
                                            data-weight-birth="{{ $patient->weight_at_birth }}"
                                            data-height-birth="{{ $patient->height_at_birth }}"
                                            data-last-weight="{{ $lastRec->weight ?? 0 }}"
                                            data-last-status="{{ $lastRec->weight_status ?? '' }}"
                                            data-second-last-status="{{ $secondLastRec->weight_status ?? '' }}"
                                            data-category="{{ $patient->category }}"
                                            {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                        {{ $patient->full_name }} — NIK: {{ $patient->id_number }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @error('patient_id') <p class="text-[11px] text-error font-bold ml-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Visit Date --}}
                    <div class="md:col-span-4 space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Periksa <span class="text-primary">*</span></label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                    </div>

                    {{-- Identity & Birth History --}}
                    <div x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)" class="md:col-span-12 grid grid-cols-1 md:grid-cols-4 gap-6 pt-10 mt-2 border-t border-slate-100/60">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ayah</label>
                            <input type="text" name="father_name" value="{{ old('father_name', $selectedPatient->father_name ?? '') }}" placeholder="Nama ayah..." :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                   class="w-full h-14 px-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ibu</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name', $selectedPatient->mother_name ?? '') }}" placeholder="Nama ibu..." :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                   class="w-full h-14 px-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">BB Lahir (kg)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="weight_at_birth" value="{{ old('weight_at_birth', $selectedPatient->weight_at_birth ?? '') }}" placeholder="0.00" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                       class="w-full h-14 pl-5 pr-10 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-4 top-4 text-[10px] font-black text-slate-300">KG</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">PB Lahir (cm)</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="height_at_birth" value="{{ old('height_at_birth', $selectedPatient->height_at_birth ?? '') }}" placeholder="0.0" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                       class="w-full h-14 pl-5 pr-10 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-4 top-4 text-[10px] font-black text-slate-300">CM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Antropometri, Skrining & Nutrisi (MERGED BENTO) --}}
            <div class="space-y-8">
                {{-- A. Antropometri & Gizi --}}
                <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.08)] relative overflow-hidden group">
                    <div class="absolute -top-24 -left-24 w-64 h-64 bg-secondary/5 rounded-full blur-3xl group-hover:bg-secondary/10 transition-colors"></div>
                    
                    <div class="flex items-center gap-4 mb-10 relative">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-secondary to-secondary-container text-white flex items-center justify-center shadow-lg shadow-secondary/20">
                            <span class="material-symbols-outlined text-[24px]">straighten</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Antropometri & Gizi</h3>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">Pengukuran fisik dan status gizi</p>
                        </div>
                    </div>

                    <div :class="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category) ? 'grid-cols-1 md:grid-cols-3' : 'grid-cols-1 md:grid-cols-2'" class="grid gap-8 relative">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Berat Badan <span class="text-primary">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" placeholder="0.00" required
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30 @error('weight') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">KG</span>
                            </div>
                            @error('weight') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi Badan <span class="text-primary">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.1" name="height" value="{{ old('height') }}" placeholder="0.0" required
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30 @error('height') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">CM</span>
                            </div>
                            @error('height') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3" x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)" x-data="{ 
                            status: '',
                            getStatusLabel() {
                                if (this.status === 'N') return '🟢 N (Naik)';
                                if (this.status === 'T') return '🟡 T (Tetap/Turun)';
                                if (this.status === '2T') return '🔴 2T (2x Tidak Naik)';
                                return '-- Menunggu Data --';
                            },
                            getStatusClass() {
                                if (this.status === 'N') return 'bg-emerald-50 text-emerald-700 border-emerald-200 ring-4 ring-emerald-500/10';
                                if (this.status === 'T') return 'bg-amber-50 text-amber-700 border-amber-200 ring-4 ring-amber-500/10';
                                if (this.status === '2T') return 'bg-rose-50 text-rose-700 border-rose-200 ring-4 ring-rose-500/10';
                                return 'bg-slate-50 text-slate-400 border-slate-100 opacity-60';
                            }
                        }" 
                        @weight-status-updated.window="status = $event.detail.status">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status Berat (Otomatis)</label>
                            <input type="hidden" name="weight_status" :value="status" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                            <div :class="getStatusClass()" 
                                 class="w-full h-16 px-6 rounded-[1.25rem] border flex items-center justify-between transition-all duration-500 shadow-sm">
                                <span class="text-sm font-black uppercase tracking-widest" x-text="getStatusLabel()"></span>
                                <span class="material-symbols-outlined text-[20px]" x-text="status ? 'verified' : 'hourglass_empty'"></span>
                            </div>
                        </div>

                        <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 mt-2 border-t border-slate-100/60" x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Cara Ukur <span class="text-primary">*</span></label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="measurement_method" value="recumbent" {{ old('measurement_method') == 'recumbent' ? 'checked' : '' }} class="sr-only peer" :required="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                        <div class="h-16 flex items-center justify-center rounded-[1.25rem] border-2 border-slate-100 bg-slate-50 text-slate-400 transition-all peer-checked:border-secondary peer-checked:bg-secondary peer-checked:text-white font-black text-[11px] uppercase tracking-widest">
                                            Telentang
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="measurement_method" value="standing" {{ old('measurement_method', 'standing') == 'standing' ? 'checked' : '' }} class="sr-only peer" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                        <div class="h-16 flex items-center justify-center rounded-[1.25rem] border-2 border-slate-100 bg-slate-50 text-slate-400 transition-all peer-checked:border-secondary peer-checked:bg-secondary peer-checked:text-white font-black text-[11px] uppercase tracking-widest">
                                            Berdiri
                                        </div>
                                    </label>
                                </div>
                                @error('measurement_method') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lingkar Kepala</label>
                                    <div class="relative">
                                        <input type="number" step="0.1" name="head_circumference" value="{{ old('head_circumference') }}" placeholder="0.0" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                        <span class="absolute right-5 top-5 text-slate-300 material-symbols-outlined">analytics</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">LiLA (cm)</label>
                                    <div class="relative">
                                        <input type="number" step="0.1" name="upper_arm_circumference" value="{{ old('upper_arm_circumference') }}" placeholder="0.0" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                        <span class="absolute right-5 top-5 text-slate-300 material-symbols-outlined">straighten</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Ibu Hamil Checkups Block --}}
                <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(244,63,94,0.08)] relative overflow-hidden group"
                     x-show="category === 'ibu_hamil'"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform translate-y-8"
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-pink-500/5 rounded-full blur-3xl group-hover:bg-pink-500/10 transition-colors"></div>
                    
                    <div class="flex items-center gap-4 mb-10 relative">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-pink-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-pink-200">
                            <span class="material-symbols-outlined text-[24px]">pregnant_woman</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Pemeriksaan Ibu Hamil</h3>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">Pencatatan konsumsi Pil Fe dan tekanan darah</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                        {{-- Pill FE Toggle --}}
                        <div x-data="{ checked: {{ old('pill_fe') ? 'true' : 'false' }} }" class="space-y-3">
                            <label class="relative flex flex-col items-center justify-center p-5 rounded-[2rem] border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-pink-200 transition-all cursor-pointer group/toggle h-full transition-all duration-300"
                                   :class="checked ? 'border-pink-500 bg-pink-50/30' : 'border-slate-100 bg-slate-50/30'">
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">Pil Fe (Tablet Tambah Darah)</span>
                                <div class="relative inline-flex items-center">
                                    <input type="checkbox" name="pill_fe" value="1" x-model="checked" :disabled="category !== 'ibu_hamil'" {{ old('pill_fe') ? 'checked' : '' }} class="sr-only peer">
                                    <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-pink-500 shadow-inner transition-colors"></div>
                                </div>
                                <div class="mt-3 px-3 py-1 rounded-full text-[9px] font-black tracking-tighter transition-all uppercase"
                                     :class="checked ? 'bg-pink-100 text-pink-800' : 'bg-slate-100 text-slate-800'"
                                     x-text="checked ? 'DIKONSUMSI / DIBERIKAN' : 'TIDAK'">
                                    TIDAK
                                </div>
                            </label>
                            @error('pill_fe') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tekanan Darah (Sistolik)</label>
                            <div class="relative">
                                <input type="number" name="systolic_bp" value="{{ old('systolic_bp') }}" placeholder="Contoh: 120" :disabled="category !== 'ibu_hamil'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/5 transition-all bg-slate-50/30 @error('systolic_bp') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mmHg</span>
                            </div>
                            @error('systolic_bp') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tekanan Darah (Diastolik)</label>
                            <div class="relative">
                                <input type="number" name="diastolic_bp" value="{{ old('diastolic_bp') }}" placeholder="Contoh: 80" :disabled="category !== 'ibu_hamil'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-pink-500 focus:ring-4 focus:ring-pink-500/5 transition-all bg-slate-50/30 @error('diastolic_bp') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mmHg</span>
                            </div>
                            @error('diastolic_bp') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- Lansia Checkups Block --}}
                <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(249,115,22,0.08)] relative overflow-hidden group"
                     x-show="category === 'lansia'"
                     x-transition:enter="transition ease-out duration-500"
                     x-transition:enter-start="opacity-0 transform translate-y-8"
                     x-transition:enter-end="opacity-100 transform translate-y-0">
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-orange-500/5 rounded-full blur-3xl group-hover:bg-orange-500/10 transition-colors"></div>
                    
                    <div class="flex items-center gap-4 mb-10 relative">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-orange-500 to-amber-600 text-white flex items-center justify-center shadow-lg shadow-orange-200">
                            <span class="material-symbols-outlined text-[24px]">favorite</span>
                        </div>
                        <div>
                            <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Pemeriksaan Fisik Lansia (Posbindu)</h3>
                            <p class="text-xs font-bold text-slate-400 mt-0.5">Pengukuran tekanan darah, gula darah, kolesterol, dan asam urat</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-8 relative">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tekanan Darah (Sistolik)</label>
                            <div class="relative">
                                <input type="number" name="systolic_bp" value="{{ old('systolic_bp') }}" placeholder="Contoh: 120" :disabled="category !== 'lansia'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 @error('systolic_bp') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mmHg</span>
                            </div>
                            @error('systolic_bp') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tekanan Darah (Diastolik)</label>
                            <div class="relative">
                                <input type="number" name="diastolic_bp" value="{{ old('diastolic_bp') }}" placeholder="Contoh: 80" :disabled="category !== 'lansia'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 @error('diastolic_bp') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mmHg</span>
                            </div>
                            @error('diastolic_bp') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Gula Darah</label>
                            <div class="relative">
                                <input type="number" name="blood_sugar" value="{{ old('blood_sugar') }}" placeholder="Contoh: 120" :disabled="category !== 'lansia'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 @error('blood_sugar') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mg/dL</span>
                            </div>
                            @error('blood_sugar') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Asam Urat</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="uric_acid" value="{{ old('uric_acid') }}" placeholder="Contoh: 5.4" :disabled="category !== 'lansia'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 @error('uric_acid') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mg/dL</span>
                            </div>
                            @error('uric_acid') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Kolesterol</label>
                            <div class="relative">
                                <input type="number" name="cholesterol" value="{{ old('cholesterol') }}" placeholder="Contoh: 180" :disabled="category !== 'lansia'"
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 @error('cholesterol') border-error bg-error/5 @enderror">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">mg/dL</span>
                            </div>
                            @error('cholesterol') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="lg:col-span-5 space-y-3 pt-4">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Obat yang Sedang Diminum</label>
                            <textarea name="current_medication" rows="2" placeholder="Contoh: Amlodipine 5mg 1x1, Metformin 500mg 2x1..." :disabled="category !== 'lansia'"
                                      class="w-full p-6 border border-slate-200 rounded-[2rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-orange-500 focus:ring-4 focus:ring-orange-500/5 transition-all bg-slate-50/30 resize-none @error('current_medication') border-error bg-error/5 @enderror">{{ old('current_medication') }}</textarea>
                            @error('current_medication') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8" x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                    {{-- B. Skrining TBC & Gejala --}}
                    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(244,63,94,0.06)] relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-rose-500/5 rounded-full blur-3xl group-hover:bg-rose-500/10 transition-colors"></div>
                        
                        <div class="flex items-center gap-4 mb-8 relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-500 to-rose-600 text-white flex items-center justify-center shadow-lg shadow-rose-200">
                                <span class="material-symbols-outlined text-[24px]">medical_services</span>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Skrining & Gejala</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">Deteksi dini kesehatan balita</p>
                            </div>
                        </div>

                        <div class="space-y-6 relative">
                            <div class="grid grid-cols-1 gap-3">
                                @foreach([
                                    'tbc_screening_cough' => 'Batuk > 2 Minggu',
                                    'tbc_screening_fever' => 'Demam > 2 Minggu',
                                    'tbc_screening_contact' => 'Kontak Serumah TBC',
                                    'tbc_screening_lethargy' => 'Anak Lesu / Tidak Aktif',
                                    'tbc_screening_lumps' => 'Benjolan di Leher'
                                ] as $name => $label)
                                    <label class="relative flex items-center justify-between p-5 rounded-2xl border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-teal-200 hover:shadow-md transition-all cursor-pointer group/item has-[:checked]:border-teal-600 has-[:checked]:bg-teal-400 shadow-sm">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 group-hover/item:text-teal-600 transition-colors shadow-sm">
                                                <span class="material-symbols-outlined text-[20px] group-has-[:checked]:text-teal-600">check_circle</span>
                                            </div>
                                            <span class="text-sm font-bold text-slate-700 select-none group-has-[:checked]:text-black">{{ $label }}</span>
                                                                            <input type="checkbox" name="{{ $name }}" value="1" {{ old($name) ? 'checked' : '' }} :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                               class="w-6 h-6 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20 transition-all accent-teal-600">
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-3 pt-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Gejala / Temuan Lainnya</label>
                                <textarea name="other_symptoms" rows="10" placeholder="Sebutkan gejala lain jika ada secara detail..." :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                          class="w-full p-6 border border-slate-200 rounded-[2rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-slate-50/30 resize-none">{{ old('other_symptoms') }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- C. Nutrisi, Vitamin & Imunisasi --}}
                    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(20,184,166,0.06)] relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl group-hover:bg-teal-500/10 transition-colors"></div>
                        
                        <div class="flex items-center gap-4 mb-8 relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-500 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-200">
                                <span class="material-symbols-outlined text-[24px]">nutrition</span>
                            </div>
                            <div>
                                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Nutrisi & Vitamin</h3>
                                <p class="text-xs font-bold text-slate-400 mt-0.5">Asupan gizi dan imunisasi</p>
                            </div>
                        </div>

                        <div class="space-y-6 relative">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach([
                                    'is_exclusive_breastfeeding' => 'ASI Eksklusif',
                                    'mp_asi' => 'MP-ASI Sesuai'
                                ] as $name => $label)
                                    <div class="p-5 rounded-[2rem] border border-slate-100 bg-slate-50/30">
                                        <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-4 text-center">{{ $label }}</label>
                                        <div class="flex gap-2">
                                            @foreach(['1' => 'Ya', '0' => 'Tidak'] as $val => $text)
                                                <label class="flex-1 cursor-pointer group">
                                                    <input type="radio" name="{{ $name }}" value="{{ $val }}" {{ old($name) == $val ? 'checked' : '' }} class="sr-only peer" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                                    <div class="h-11 flex items-center justify-center rounded-xl border border-slate-200 bg-white transition-all shadow-sm text-[11px] font-black uppercase
                                                        {{ $val == '1' 
                                                            ? 'peer-checked:border-teal-500 peer-checked:bg-teal-500 peer-checked:text-white text-slate-400' 
                                                            : 'peer-checked:border-red-500 peer-checked:bg-red-500 peer-checked:text-white text-slate-400' }}">
                                                        {{ $text }}
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                             <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach([
                                    'vitamin_a' => ['Vitamin A', 'biotech'],
                                    'deworming_medicine' => ['Obat Cacing', 'pill']
                                ] as $name => $info)
                                    <div x-data="{ checked: {{ old($name) ? 'true' : 'false' }} }" class="space-y-3">
                                        <label class="relative flex flex-col items-center justify-center p-5 rounded-[2rem] border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-teal-200 transition-all cursor-pointer group/toggle has-[:checked]:border-teal-500/50 has-[:checked]:bg-teal-50/30">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $info[0] }}</span>
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" name="{{ $name }}" value="1" x-model="checked" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)" {{ old($name) ? 'checked' : '' }} class="sr-only peer">
                                                <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500 shadow-inner transition-colors"></div>
                                            </div>
                                            <div class="mt-3 px-3 py-1 rounded-full text-[9px] font-black tracking-tighter transition-all bg-slate-100 text-slate-800 peer-checked:bg-teal-100 peer-checked:text-slate-800 uppercase" 
                                                 x-text="checked ? 'DIBERIKAN' : 'TIDAK'">
                                                TIDAK
                                            </div>
                                        </label>

                                        {{-- Conditional Color Selector for Vitamin A --}}
                                        @if($name === 'vitamin_a')
                                            <div x-show="checked" x-transition class="pt-2">
                                                <x-forms.select-input name="vitamin_a_color" placeholder="-- Pilih Warna Kapsul --" :placeholderDisabled="false" value="{{ old('vitamin_a_color') }}" ::disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                                    <option value="biru" {{ old('vitamin_a_color') == 'biru' ? 'selected' : '' }}>🔵 Kapsul Biru (6-11 bln)</option>
                                                    <option value="merah" {{ old('vitamin_a_color') == 'merah' ? 'selected' : '' }}>🔴 Kapsul Merah (1-5 thn)</option>
                                                </x-forms.select-input>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>

                            <div class="grid grid-cols-1 gap-4 pt-2">
                                <div class="space-y-4 pt-2" x-data="{ 
                                    selectedVaccines: [],
                                    init() {
                                        this.selectedVaccines = '{{ old('vaccine_name') }}' ? '{{ old('vaccine_name') }}'.split(', ') : [];
                                    },
                                    toggleVaccine(name) {
                                        if (this.selectedVaccines.includes(name)) {
                                            this.selectedVaccines = this.selectedVaccines.filter(v => v !== name);
                                        } else {
                                            this.selectedVaccines.push(name);
                                        }
                                        $refs.vaccineInput.value = this.selectedVaccines.join(', ');
                                    }
                                }">
                                <label class="text-[10px] font-black text-slate-600 uppercase tracking-widest ml-1">Riwayat Imunisasi</label>
                                    <input type="hidden" name="vaccine_name" x-ref="vaccineInput" value="{{ old('vaccine_name') }}" :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach(['HB-0', 'Polio 0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'PCV 1', 'PCV 2', 'PCV 3', 'RV 1', 'RV 2', 'RV 3', 'IPV 1', 'IPV 2', 'MR', 'DPT-HB-Hib Lanjutan', 'MR Lanjutan', 'JE'] as $vaccine)
                                            <button type="button" 
                                                    @click="toggleVaccine('{{ $vaccine }}')"
                                                    :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                                    :class="selectedVaccines.includes('{{ $vaccine }}') ? 'border-teal-600 bg-teal-400 text-black shadow-sm' : 'border-slate-100 bg-slate-50/30 text-slate-700 hover:bg-white hover:border-teal-200'"
                                                    class="flex items-center gap-2 p-3 rounded-xl border transition-all text-[11px] font-bold text-left group/vax">
                                                <div class="w-6 h-6 rounded-lg flex items-center justify-center transition-colors"
                                                     :class="selectedVaccines.includes('{{ $vaccine }}') ? 'bg-white text-teal-600' : 'bg-white border border-slate-100 text-slate-300 group-hover/vax:text-teal-500'">
                                                     <span class="material-symbols-outlined text-[16px]">vaccines</span>
                                                </div>
                                                <span class="flex-1">{{ $vaccine }}</span>
                                            </button>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest ml-1">PMT (Makanan Tambahan)</label>
                                    <div class="relative">
                                        <input type="text" name="pmt_given" value="{{ old('pmt_given') }}" placeholder="Contoh: Biskuit, Susu..." :disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)"
                                               class="w-full h-14 px-6 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-slate-50/30">
                                        <span class="absolute right-5 top-4 text-slate-300 material-symbols-outlined">restaurant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>           </div>
                </div>
            </div>

            {{-- 3. Perkembangan & Tindakan (BOTTOM ROW) --}}
            <div :class="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category) ? 'grid-cols-1 md:grid-cols-2' : 'grid-cols-1'" class="grid gap-8 mt-8">
                {{-- A. Perkembangan (KPSP) --}}
                <div class="md:col-span-1 bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.08)]"
                     x-show="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="material-symbols-outlined text-[20px]">psychology</span>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Perkembangan (KPSP)</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status KPSP</label>
                            <x-forms.select-input name="kpsp_status" placeholder="-- Pilih Status --" :placeholderDisabled="false" value="{{ old('kpsp_status') }}" ::disabled="!['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category)">
                                <option value="Lengkap" {{ old('kpsp_status') == 'Lengkap' ? 'selected' : '' }}>✅ Lengkap / Sesuai</option>
                                <option value="Tidak Lengkap" {{ old('kpsp_status') == 'Tidak Lengkap' ? 'selected' : '' }}>⚠️ Ada Keterlambatan</option>
                            </x-forms.select-input>
                        </div>
                    </div>
                </div>

                {{-- B. Rujukan & Konseling --}}
                <div class="md:col-span-1 bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.08)]">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="material-symbols-outlined text-[20px]">assignment_turned_in</span>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Tindakan & Rujukan</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Rujukan</label>
                            <x-forms.select-input name="referral_type" placeholder="" value="{{ old('referral_type', 'None') }}" :error="$errors->has('referral_type')">
                                <option value="None" {{ old('referral_type') == 'None' ? 'selected' : '' }}>Tidak Ada Rujukan</option>
                                <option value="Pustu" {{ old('referral_type') == 'Pustu' ? 'selected' : '' }}>Pustu</option>
                                <option value="Puskesmas" {{ old('referral_type') == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="RS" {{ old('referral_type') == 'RS' ? 'selected' : '' }}>Rumah Sakit</option>
                            </x-forms.select-input>
                            @error('referral_type') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Keluhan / Riwayat Sakit</label>
                            <textarea name="complaint" rows="2" :placeholder="['bayi', 'baduta', 'balita', 'anak_sekolah', 'balita'].includes(category) ? 'Catat keluhan balita jika ada...' : (category === 'ibu_hamil' ? 'Catat keluhan ibu hamil jika ada...' : 'Catat keluhan lansia jika ada...')"
                                      class="w-full p-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30 resize-none">{{ old('complaint') }}</textarea>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Hasil Pemeriksaan / Diagnosis <span class="text-rose-500">*</span></label>
                            <x-forms.select-input name="diagnosis" placeholder="" required :error="$errors->has('diagnosis')" value="{{ old('diagnosis', 'Sehat') }}">
                                @if(request('category') === 'ibu_hamil')
                                    <option value="Sehat" {{ old('diagnosis', 'Sehat') == 'Sehat' ? 'selected' : '' }}>🟢 Sehat</option>
                                    <option value="Beresiko" {{ old('diagnosis') == 'Beresiko' ? 'selected' : '' }}>🟡 Berisiko Tinggi</option>
                                    <option value="Sakit" {{ old('diagnosis') == 'Sakit' ? 'selected' : '' }}>🤒 Sakit (Demam/Batuk/Pilek)</option>
                                    <option value="Lainnya" {{ old('diagnosis') == 'Lainnya' ? 'selected' : '' }}>Lainnya...</option>
                                @elseif(request('category') === 'lansia')
                                    <option value="Sehat" {{ old('diagnosis', 'Sehat') == 'Sehat' ? 'selected' : '' }}>🟢 Sehat</option>
                                    <option value="Hipertensi" {{ old('diagnosis') == 'Hipertensi' ? 'selected' : '' }}>🟡 Hipertensi</option>
                                    <option value="Diabetes" {{ old('diagnosis') == 'Diabetes' ? 'selected' : '' }}>🟡 Diabetes</option>
                                    <option value="Asam Urat" {{ old('diagnosis') == 'Asam Urat' ? 'selected' : '' }}>🟡 Asam Urat Tinggi</option>
                                    <option value="Kolesterol" {{ old('diagnosis') == 'Kolesterol' ? 'selected' : '' }}>🟡 Kolesterol Tinggi</option>
                                    <option value="Sakit" {{ old('diagnosis') == 'Sakit' ? 'selected' : '' }}>🤒 Sakit (Demam/Batuk/Pilek)</option>
                                    <option value="Lainnya" {{ old('diagnosis') == 'Lainnya' ? 'selected' : '' }}>Lainnya...</option>
                                @else
                                    <option value="Sehat" {{ old('diagnosis', 'Sehat') == 'Sehat' ? 'selected' : '' }}>🟢 Sehat</option>
                                    <option value="Kurang Gizi" {{ old('diagnosis') == 'Kurang Gizi' ? 'selected' : '' }}>🟡 Perlu Pemantauan Gizi</option>
                                    <option value="Indikasi Stunting" {{ old('diagnosis') == 'Indikasi Stunting' ? 'selected' : '' }}>🔴 Indikasi Stunting</option>
                                    <option value="Sakit" {{ old('diagnosis') == 'Sakit' ? 'selected' : '' }}>🤒 Sakit (Demam/Batuk/Pilek)</option>
                                    <option value="Lainnya" {{ old('diagnosis') == 'Lainnya' ? 'selected' : '' }}>Lainnya...</option>
                                @endif
                            </x-forms.select-input>
                            @error('diagnosis') <p class="text-[10px] text-rose-500 font-bold ml-1">{{ $message }}</p> @enderror
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nasihat / Konseling</label>
                            <textarea name="counseling_notes" rows="2" placeholder="Catat poin konseling yang diberikan..."
                                      class="w-full p-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30 resize-none">{{ old('counseling_notes') }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 h-20 bg-primary text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] hover:bg-primary/90 transition-all shadow-2xl shadow-primary/20 flex items-center justify-center gap-3 group">
                    <span>Simpan Pemeriksaan Lengkap</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">send</span>
                </button>
                <a href="{{ route('admin.medical-records.index') }}" class="w-full md:w-56 h-20 bg-white text-slate-400 border border-slate-200 rounded-[2rem] font-bold text-sm flex items-center justify-center hover:bg-slate-50 transition-all text-center uppercase tracking-widest hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                    Batalkan
                </a>
            </div>
        </div>
    </form>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.getElementById('patient-select');
    if (!patientSelect) return;

    // Function to fill patient data
    function fillPatientData(value) {
        if (!value) {
            // Clear fields if no patient selected
            ['father_name', 'mother_name', 'weight_at_birth', 'height_at_birth'].forEach(name => {
                const el = document.querySelector(`input[name="${name}"]`);
                if (el) el.value = '';
            });
            return;
        }
        
        // Find the original option element
        const options = Array.from(patientSelect.options);
        const selectedOption = options.find(opt => opt.value == value);
        
        if (selectedOption) {
            const data = selectedOption.dataset;
            console.log('Filling data for patient:', value, data); // Debug
            
            // Targeted fields
            const mapping = {
                'father_name': data.father,
                'mother_name': data.mother,
                'weight_at_birth': data.weightBirth,
                'height_at_birth': data.heightBirth
            };

            Object.entries(mapping).forEach(([name, val]) => {
                const el = document.querySelector(`input[name="${name}"]`);
                if (el) {
                    el.value = val || '';
                    // Visual feedback
                    el.classList.add('ring-4', 'ring-primary/20', 'border-primary', 'bg-primary/5');
                    setTimeout(() => el.classList.remove('ring-4', 'ring-primary/20', 'border-primary', 'bg-primary/5'), 1500);
                }
            });
        }
    }

    let placeholderText = "Cari nama atau NIK balita...";
    const reqCategory = '{{ request('category') }}';
    if (reqCategory === 'ibu_hamil') {
        placeholderText = "Cari nama atau NIK ibu hamil...";
    } else if (reqCategory === 'lansia') {
        placeholderText = "Cari nama atau NIK lansia...";
    }

    // Initialize TomSelect
    const ts = new TomSelect('#patient-select', {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        },
        maxOptions: 50,
        placeholder: placeholderText,
        render: {
            option: function(data, escape) {
                const parts = data.text.split(' — ');
                return `
                    <div class="flex flex-col py-1">
                        <span class="font-black text-slate-900 text-base">${escape(parts[0])}</span>
                        <span class="text-[11px] text-slate-600 font-black uppercase tracking-widest mt-0.5">${escape(parts[1] || '')}</span>
                    </div>
                `;
            },
            item: function(data, escape) {
                return `<div class="font-black text-slate-900 text-sm">${escape(data.text)}</div>`;
            }
        }
    });

    // Listen for changes
    ts.on('change', function(value) {
        fillPatientData(value);
        
        // Update Alpine category
        const options = Array.from(patientSelect.options);
        const selectedOption = options.find(opt => opt.value == value);
        if (selectedOption) {
            const cat = selectedOption.dataset.category || 'balita';
            window.dispatchEvent(new CustomEvent('category-updated', { detail: cat }));
        } else {
            window.dispatchEvent(new CustomEvent('category-updated', { detail: '{{ request('category', 'balita') }}' }));
        }
    });

    // Initial check (if value is already set by old() or query param)
    if (ts.getValue()) {
        fillPatientData(ts.getValue());
        
        const options = Array.from(patientSelect.options);
        const selectedOption = options.find(opt => opt.value == ts.getValue());
        if (selectedOption) {
            const cat = selectedOption.dataset.category || 'balita';
            setTimeout(() => {
                window.dispatchEvent(new CustomEvent('category-updated', { detail: cat }));
            }, 50);
        }
    } else {
        // Dispatch initial category based on request param if no patient is preselected
        setTimeout(() => {
            window.dispatchEvent(new CustomEvent('category-updated', { detail: '{{ request('category', 'balita') }}' }));
        }, 50);
    }

    // --- Automatic Weight Status Logic ---
    const weightInput = document.querySelector('input[name="weight"]');
    const statusSelect = document.querySelector('select[name="weight_status"]');

    weightInput.addEventListener('input', calculateWeightStatus);
    ts.on('change', calculateWeightStatus);

    function updateWeightStatusBadge(status) {
        window.dispatchEvent(new CustomEvent('weight-status-updated', { detail: { status: status } }));
    }

    function calculateWeightStatus() {
        const patientId = ts.getValue();
        if (!patientId || !weightInput.value) {
            updateWeightStatusBadge('');
            return;
        }

        const options = Array.from(patientSelect.options);
        const selectedOption = options.find(opt => opt.value == patientId);
        if (!selectedOption) return;

        const currentWeight = parseFloat(weightInput.value);
        let lastWeight = parseFloat(selectedOption.dataset.lastWeight || 0);
        const lastStatus = selectedOption.dataset.lastStatus || '';
        const birthWeight = parseFloat(selectedOption.dataset.weightBirth || 0);

        // Logic: If no last weight from records, use birth weight as baseline
        if (lastWeight === 0 && birthWeight > 0) {
            lastWeight = birthWeight;
        }

        let resultStatus = '';
        if (lastWeight === 0) {
            resultStatus = 'N'; // Truly first data ever
        } else if (currentWeight > lastWeight) {
            resultStatus = 'N';
        } else {
            if (lastStatus === 'T' || lastStatus === '2T') {
                resultStatus = '2T';
            } else {
                resultStatus = 'T';
            }
        }

        updateWeightStatusBadge(resultStatus);
    }
});
</script>
@endpush
@endsection
