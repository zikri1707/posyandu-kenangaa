@extends('layouts.admin-layout')

@section('admin-title') Edit Rekam Medis @endsection

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

    /* Lansia & Ibu Hamil custom TomSelect heights to match standard inputs */
    #lansia-select + .ts-wrapper {
        border: none !important;
        background: transparent !important;
        height: auto !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
    #lansia-select + .ts-wrapper .ts-control {
        min-height: 44px !important;
        height: 44px !important;
        border-radius: 0.75rem !important;
        padding: 0.375rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        border: 1px solid #D9D9D9 !important;
        background-color: #ffffff !important;
    }
    #lansia-select + .ts-wrapper.focus .ts-control {
        border-color: #006C49 !important;
        box-shadow: 0 0 0 4px rgba(0, 108, 73, 0.05) !important;
    }

    #ibu-hamil-select + .ts-wrapper {
        border: none !important;
        background: transparent !important;
        height: auto !important;
        padding: 0 !important;
        box-shadow: none !important;
    }
    #ibu-hamil-select + .ts-wrapper .ts-control {
        min-height: 48px !important;
        height: 48px !important;
        border-radius: 0.75rem !important;
        padding: 0.5rem 1rem !important;
        font-weight: 600 !important;
        font-size: 0.875rem !important;
        border: 1px solid #e2e8f0 !important;
        background-color: #ffffff !important;
    }
    #ibu-hamil-select + .ts-wrapper.focus .ts-control {
        border-color: #14b8a6 !important;
        box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.05) !important;
    }
</style>
@endpush

@section('admin-content')
@php
    $patient = $record->patient;
    $category = $patient->category;
    $isChild = in_array($category, ['bayi', 'baduta', 'balita', 'anak_sekolah']);
    $isPregnancy = ($category === 'ibu_hamil');
    $isLansia = ($category === 'lansia');
@endphp

<div class="w-full pb-12 px-4">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-3xl bg-primary/10 flex items-center justify-center border border-primary/20 shadow-sm">
                <span class="material-symbols-outlined text-primary text-3xl">edit_note</span>
            </div>
            <div>
                <h1 class="text-2xl font-black text-slate-800 tracking-tight">Edit Pemeriksaan</h1>
                <p class="text-sm font-medium text-slate-500">
                    @if($isPregnancy)
                        Memperbarui data kesehatan ibu hamil & nifas
                    @elseif($isLansia)
                        Memperbarui data kesehatan lansia
                    @else
                        Memperbarui data kesehatan balita
                    @endif
                </p>
            </div>
        </div>
        
        <div class="flex items-center gap-3">
            <div class="px-4 py-2 rounded-2xl bg-white border border-slate-200 shadow-sm flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-primary animate-pulse"></span>
                <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">ID REKAM: #{{ $record->id }}</span>
            </div>
        </div>
    </div>

    <form action="{{ route('admin.medical-records.update', $record->id) }}" method="POST" class="flex flex-col gap-8 min-w-0">
        @csrf
        @method('PUT')

        {{-- Global Validation Errors --}}
        @if($errors->any())
            <div class="bg-rose-50 border-2 border-rose-200 rounded-[2rem] p-6 flex items-start gap-4 mb-2">
                <div class="w-12 h-12 rounded-2xl bg-rose-500 text-white flex items-center justify-center shadow-lg shrink-0">
                    <span class="material-symbols-outlined">warning</span>
                </div>
                <div>
                    <h4 class="text-rose-900 font-black uppercase text-xs tracking-widest">Ada Kesalahan Input</h4>
                    <ul class="list-disc list-inside text-rose-700 text-xs mt-1.5 space-y-1 font-bold">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        @endif

        {{-- ---------------------------------------------------- --}}
        {{-- CASE 1: IBU HAMIL & NIFAS --}}
        {{-- ---------------------------------------------------- --}}
        @if($isPregnancy)
            <input type="hidden" name="category" value="ibu_hamil">
            
            {{-- SECTION 1: Data Identitas Pasien --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-emerald-50 text-emerald-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-emerald-100">Section 1</span>
                        <h2 class="text-lg font-bold text-slate-800">Data Identitas Pasien</h2>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Lengkap Ibu *</label>
                        <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
                        <select id="ibu-hamil-select" disabled required placeholder="Cari nama atau NIK ibu hamil..."
                                class="w-full h-12 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 bg-slate-50 cursor-not-allowed">
                            <option value="{{ $record->patient_id }}">{{ $record->patient->full_name }} — NIK: {{ $record->patient->id_number }}</option>
                        </select>
                        <p class="text-[10px] text-slate-400 italic ml-1">Nama sasaran tidak dapat diubah setelah rekam dibuat.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">NIK</label>
                        <input type="text" name="id_number" placeholder="16 digit NIK" value="{{ old('id_number', $record->patient->id_number) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="birth_date" value="{{ old('birth_date', $record->patient->birth_date?->format('Y-m-d')) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Umur (Tahun)</label>
                        <input type="number" id="age_display" name="age" readonly placeholder="Mencari umur..."
                               class="w-full h-12 px-4 border border-slate-200 bg-slate-50 text-slate-500 cursor-not-allowed rounded-xl text-sm font-semibold focus:outline-none">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">No HP / WhatsApp</label>
                        <input type="text" name="phone_number" placeholder="08..." value="{{ old('phone_number', $record->patient->phone_number) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Nama Suami</label>
                        <input type="text" name="husband_name" placeholder="Nama lengkap suami" value="{{ old('husband_name', $record->patient->husband_name) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                </div>

                {{-- Address Card --}}
                <div class="p-6 rounded-2xl border border-slate-100 bg-slate-50/50 space-y-4">
                    <h3 class="text-xs font-bold text-slate-600 uppercase tracking-wider flex items-center gap-2">
                        <span class="material-symbols-outlined text-sm">home</span>
                        Alamat Domisili Ibu
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="md:col-span-4 space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Alamat Lengkap</label>
                            <input type="text" name="address" placeholder="Jl. Contoh No. 123..." value="{{ old('address', $record->patient->address) }}"
                                   class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Dusun / RT / RW</label>
                            <input type="text" name="dusun_rt_rw" placeholder="Contoh: RT 03/RW 01" value="{{ old('dusun_rt_rw', $record->patient->dusun_rt_rw) }}"
                                   class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Desa / Kelurahan / Nagari</label>
                            <input type="text" name="desa_kelurahan" placeholder="Nama desa/kelurahan" value="{{ old('desa_kelurahan', $record->patient->desa_kelurahan) }}"
                                   class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Kecamatan</label>
                            <input type="text" name="kecamatan" placeholder="Nama kecamatan" value="{{ old('kecamatan', $record->patient->kecamatan) }}"
                                   class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-white">
                        </div>
                        <div class="space-y-2">
                            <label class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Tanggal Periksa *</label>
                            <input type="date" name="visit_date" value="{{ old('visit_date', $record->visit_date?->format('Y-m-d')) }}" required
                                   class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-white">
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Anak Ke</label>
                        <input type="number" name="pregnancy_number" placeholder="Anak ke-" value="{{ old('pregnancy_number', $record->pregnancy_number) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jarak Kehamilan Sebelumnya</label>
                        <input type="text" name="pregnancy_spacing" placeholder="Contoh: 3 Tahun" value="{{ old('pregnancy_spacing', $record->pregnancy_spacing) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Berat Badan Awal (kg)</label>
                        <input type="number" step="0.1" name="starting_weight" placeholder="BB sebelum hamil" value="{{ old('starting_weight', $record->starting_weight) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="starting_height" placeholder="Tinggi badan awal" value="{{ old('starting_height', $record->starting_height) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Tanggal Bersalin (HPL / Nyata)</label>
                        <input type="date" name="delivery_date" value="{{ old('delivery_date', $record->delivery_date?->format('Y-m-d')) }}"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2 md:col-span-2">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Cara Bersalin</label>
                        <div class="flex gap-4 pt-2">
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-600">
                                <input type="radio" name="delivery_method" value="Normal" {{ old('delivery_method', $record->delivery_method) == 'Normal' ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-slate-300 focus:ring-teal-500">
                                <span>Persalinan Normal</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-600">
                                <input type="radio" name="delivery_method" value="Tindakan Medis" {{ old('delivery_method', $record->delivery_method) == 'Tindakan Medis' ? 'checked' : '' }} class="w-4 h-4 text-teal-600 border-slate-300 focus:ring-teal-500">
                                <span>Tindakan Medis (SC/Dll)</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 2: Pemeriksaan Ibu Hamil (ANC) --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-pink-50 text-pink-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-pink-100">Section 2</span>
                        <h2 class="text-lg font-bold text-slate-800">Pemeriksaan Ibu Hamil (ANC)</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider shrink-0">Usia Kehamilan (Minggu)</label>
                        <div class="flex flex-col">
                            <select name="gestational_age" class="h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                <option value="">-- Pilih Minggu Kehamilan --</option>
                                @foreach(['<4 minggu', '4-8 minggu', '8-12 minggu', '12-16 minggu', '16-20 minggu', '20-24 minggu', '24-28 minggu', '28-32 minggu', '32-36 minggu', '36-40 minggu'] as $opt)
                                    <option value="{{ $opt }}" {{ old('gestational_age', $record->gestational_age) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Penimbangan & Pengukuran Card --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Skrining Penimbangan & Pengukuran</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">BB (kg)</label>
                                <input type="number" step="0.1" name="weight" placeholder="BB sekarang" value="{{ old('weight', $record->weight) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">LILA (cm)</label>
                                <input type="number" step="0.1" name="upper_arm_circumference" placeholder="Lengan atas" value="{{ old('upper_arm_circumference', $record->upper_arm_circumference) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Tekanan Darah (mmHg)</label>
                                <input type="text" name="blood_pressure" placeholder="120/80" value="{{ old('blood_pressure', $record->blood_pressure) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Plotting IMT (KIA)</label>
                                <input type="text" name="imt_plotting_status" placeholder="Plotting IMT" value="{{ old('imt_plotting_status', $record->imt_plotting_status) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Plotting LILA (KIA)</label>
                                <input type="text" name="lila_plotting_status" placeholder="Plotting LILA" value="{{ old('lila_plotting_status', $record->lila_plotting_status) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Plotting TD (KIA)</label>
                                <input type="text" name="bp_plotting_status" placeholder="Plotting TD" value="{{ old('bp_plotting_status', $record->bp_plotting_status) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                        </div>
                    </div>

                    {{-- Skrining Gejala TBC --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Skrining Gejala TBC</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach([
                                'tbc_screening_cough' => 'Batuk Terus',
                                'tbc_screening_fever' => 'Demam >2 Minggu',
                                'tbc_screening_weight_loss' => 'BB Turun (2 Bulan)',
                                'tbc_screening_contact' => 'Kontak Pasien TBC'
                            ] as $name => $label)
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">{{ $label }}</label>
                                    <select name="{{ $name }}" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                        <option value="">-</option>
                                        <option value="1" {{ old($name, $record->$name) === true || old($name, $record->$name) === 1 || old($name, $record->$name) === '1' ? 'selected' : '' }}>Ya</option>
                                        <option value="0" {{ old($name, $record->$name) === false || old($name, $record->$name) === 0 || old($name, $record->$name) === '0' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pelayanan Kesehatan --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4 lg:col-span-2">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Pelayanan Kesehatan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Nakes beri TTD/MMS</label>
                                <select name="nakes_gives_fe_mms" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('nakes_gives_fe_mms', $record->nakes_gives_fe_mms) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('nakes_gives_fe_mms', $record->nakes_gives_fe_mms) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Konsumsi TTD/MMS Rutin</label>
                                <select name="consumes_fe_mms_regularly" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('consumes_fe_mms_regularly', $record->consumes_fe_mms_regularly) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('consumes_fe_mms_regularly', $record->consumes_fe_mms_regularly) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Nakes beri MT Bumil KEK</label>
                                <select name="nakes_gives_mt_kek" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('nakes_gives_mt_kek', $record->nakes_gives_mt_kek) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('nakes_gives_mt_kek', $record->nakes_gives_mt_kek) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Komposisi & Jumlah Paket</label>
                                <input type="text" name="mt_package_details" placeholder="Contoh: Biskuit 2 Paket, Susu 1 Kotak" value="{{ old('mt_package_details', $record->mt_package_details) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Rutin Konsumsi MT Bumil KEK</label>
                                <select name="consumes_mt_kek_regularly" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('consumes_mt_kek_regularly', $record->consumes_mt_kek_regularly) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('consumes_mt_kek_regularly', $record->consumes_mt_kek_regularly) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Penyuluhan & Rujukan ANC --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4 lg:col-span-2">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Penyuluhan & Catatan Rujukan</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-4">
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Topik Penyuluhan</label>
                                    <input type="text" name="counseling_topic" placeholder="Topik materi penyuluhan" value="{{ old('counseling_topic', $record->counseling_topic) }}"
                                           class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                                </div>
                                <div class="space-y-1">
                                    <label class="text-[10px] font-bold text-slate-400 uppercase">Ikut Kelas Ibu Hamil</label>
                                    <select name="joins_pregnant_class" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                        <option value="">-</option>
                                        <option value="Ya" {{ old('joins_pregnant_class', $record->joins_pregnant_class) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                        <option value="Tidak" {{ old('joins_pregnant_class', $record->joins_pregnant_class) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Rujukan ANC</label>
                                <textarea name="anc_referral" rows="4" placeholder="Tuliskan catatan rujukan atau tindak lanjut spesifik jika ada..."
                                          class="w-full p-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500 resize-none">{{ old('anc_referral', $record->anc_referral) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECTION 3: Pemeriksaan Ibu Nifas/Menyusui --}}
            <div class="bg-white rounded-3xl border border-slate-100 p-8 shadow-sm space-y-6">
                <div class="flex flex-col md:flex-row justify-between md:items-center gap-4 pb-4 border-b border-slate-100">
                    <div class="flex items-center gap-3">
                        <span class="px-3 py-1 bg-orange-50 text-orange-700 text-[10px] font-black uppercase tracking-widest rounded-full border border-orange-100">Section 3</span>
                        <h2 class="text-lg font-bold text-slate-800">Pemeriksaan Ibu Nifas/Menyusui</h2>
                    </div>
                    <div class="flex items-center gap-3">
                        <label class="text-[11px] font-bold text-slate-500 uppercase tracking-wider shrink-0">Periode Pemeriksaan</label>
                        <div class="flex flex-col">
                            <select name="postpartum_period" class="h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                <option value="">-- Pilih Periode --</option>
                                @foreach(['<7 Hari', '7-28 Hari', '28-42 Hari', 'Bulan 2', 'Bulan 3', 'Bulan 4', 'Bulan 5', 'Bulan 6', 'Bulan 7', 'Bulan 8', 'Bulan 9', 'Bulan 10', 'Bulan 11', 'Bulan 12', 'Bulan 13', 'Bulan 14', 'Bulan 15', 'Bulan 16', 'Bulan 17', 'Bulan 18', 'Bulan 19', 'Bulan 20', 'Bulan 21', 'Bulan 22', 'Bulan 23', 'Bulan 24'] as $opt)
                                    <option value="{{ $opt }}" {{ old('postpartum_period', $record->postpartum_period) == $opt ? 'selected' : '' }}>{{ $opt }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    {{-- Fisik & Antropometri --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Skrining Fisik & Antropometri</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">BB (kg)</label>
                                <input type="number" step="0.1" id="postpartum_weight" placeholder="BB sekarang" value="{{ old('weight', $record->weight) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Tekanan Darah</label>
                                <input type="text" id="postpartum_bp" placeholder="Tekanan darah" value="{{ old('blood_pressure', $record->blood_pressure) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Plotting IMT</label>
                                <input type="text" name="postpartum_imt_plotting" placeholder="Plotting IMT" value="{{ old('postpartum_imt_plotting', $record->postpartum_imt_plotting) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Plotting TD</label>
                                <input type="text" name="postpartum_bp_plotting" placeholder="Plotting TD" value="{{ old('postpartum_bp_plotting', $record->postpartum_bp_plotting) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                        </div>
                    </div>

                    {{-- Pelayanan Kesehatan & KB --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Pelayanan Kesehatan & KB</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Nakes beri Vit A</label>
                                <select name="nakes_gives_vit_a" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('nakes_gives_vit_a', $record->nakes_gives_vit_a) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('nakes_gives_vit_a', $record->nakes_gives_vit_a) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Jumlah Kapsul</label>
                                <input type="text" name="vit_a_capsule_count" placeholder="Jumlah kapsul" value="{{ old('vit_a_capsule_count', $record->vit_a_capsule_count) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Rutin Konsumsi Vit A</label>
                                <select name="consumes_vit_a_regularly" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('consumes_vit_a_regularly', $record->consumes_vit_a_regularly) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('consumes_vit_a_regularly', $record->consumes_vit_a_regularly) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                            <div class="space-y-1 md:col-span-2">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">KB Pasca Persalinan</label>
                                <input type="text" name="postpartum_kb" placeholder="Jenis kontrasepsi" value="{{ old('postpartum_kb', $record->postpartum_kb) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Masih Menyusui?</label>
                                <select name="is_breastfeeding" class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-600 focus:outline-none focus:border-teal-500">
                                    <option value="">-</option>
                                    <option value="Ya" {{ old('is_breastfeeding', $record->is_breastfeeding) == 'Ya' ? 'selected' : '' }}>Ya</option>
                                    <option value="Tidak" {{ old('is_breastfeeding', $record->is_breastfeeding) == 'Tidak' ? 'selected' : '' }}>Tidak</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Penyuluhan & Catatan Nifas --}}
                    <div class="p-6 rounded-2xl border border-slate-100 space-y-4 lg:col-span-2">
                        <h3 class="text-sm font-bold text-slate-700 border-b border-slate-50 pb-2">Penyuluhan & Catatan Nifas</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Topik Penyuluhan</label>
                                <input type="text" name="postpartum_counseling_topic" placeholder="Topik yang dibahas..." value="{{ old('postpartum_counseling_topic', $record->postpartum_counseling_topic) }}"
                                       class="w-full h-10 px-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500">
                            </div>
                            <div class="space-y-1">
                                <label class="text-[10px] font-bold text-slate-400 uppercase">Rujukan Nifas/Menyusui</label>
                                <textarea name="postpartum_referral" rows="3" placeholder="Catatan medis tambahan..."
                                          class="w-full p-3 border border-slate-200 rounded-lg text-xs font-semibold text-slate-700 focus:outline-none focus:border-teal-500 resize-none">{{ old('postpartum_referral', $record->postpartum_referral) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 h-16 bg-teal-600 text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-teal-700 transition-all shadow-lg flex items-center justify-center gap-3 group">
                    <span>Simpan Perubahan Rekam Medis</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">save</span>
                </button>
                <a href="{{ route('admin.medical-records.index') }}" class="w-full md:w-56 h-16 bg-white text-slate-400 border border-slate-200 rounded-2xl font-bold text-sm flex items-center justify-center hover:bg-slate-50 transition-all text-center uppercase tracking-widest hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                    Batalkan
                </a>
            </div>

        {{-- ---------------------------------------------------- --}}
        {{-- CASE 2: LANSIA (POSBINDU) --}}
        {{-- ---------------------------------------------------- --}}
        @elseif($isLansia)
            <input type="hidden" name="category" value="lansia">

            {{-- CARD 1: Data Identitas --}}
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="material-symbols-outlined text-[#006C49] text-2xl">assignment</span>
                    <h2 class="text-lg font-bold text-slate-800">Data Identitas</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600">Nama Lengkap</label>
                        <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
                        <select id="lansia-select" disabled required placeholder="Cari nama atau NIK lansia..."
                                class="w-full h-[44px] px-4 border border-slate-200 bg-slate-50 cursor-not-allowed rounded-xl text-sm font-semibold text-slate-700 focus:outline-none">
                            <option value="{{ $record->patient_id }}">{{ $record->patient->full_name }} — NIK: {{ $record->patient->id_number }}</option>
                        </select>
                        <p class="text-[10px] text-slate-400 italic ml-1">Nama sasaran tidak dapat diubah setelah rekam dibuat.</p>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="id_number" placeholder="16 digit nomor identitas" value="{{ old('id_number', $record->patient->id_number) }}" required
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600">Tanggal Lahir</label>
                        <input type="date" name="birth_date" id="lansia_birth_date" value="{{ old('birth_date', $record->patient->birth_date?->format('Y-m-d')) }}" required
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600 block mb-2">Jenis Kelamin</label>
                        <div class="flex gap-6 pt-1.5">
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700">
                                <input type="radio" name="gender" value="L" {{ old('gender', $record->patient->gender) === 'L' || old('gender', $record->patient->gender) === 'M' ? 'checked' : '' }} class="w-4 h-4 text-[#006C49] border-[#D9D9D9] focus:ring-[#006C49] accent-[#006C49]">
                                <span>Laki-laki</span>
                            </label>
                            <label class="flex items-center gap-2 cursor-pointer text-sm font-semibold text-slate-700">
                                <input type="radio" name="gender" value="P" {{ old('gender', $record->patient->gender) === 'P' || old('gender', $record->patient->gender) === 'F' ? 'checked' : '' }} class="w-4 h-4 text-[#006C49] border-[#D9D9D9] focus:ring-[#006C49] accent-[#006C49]">
                                <span>Perempuan</span>
                            </label>
                        </div>
                    </div>
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Alamat Lengkap</label>
                        <textarea name="address" rows="3" placeholder="Jalan, No. Rumah, RT/RW, Kelurahan"
                                  class="w-full p-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white resize-none">{{ old('address', $record->patient->address) }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-xs font-bold text-slate-600">Nomor HP / WhatsApp</label>
                        <input type="text" name="phone_number" placeholder="08xx-xxxx-xxxx" value="{{ old('phone_number', $record->patient->phone_number) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                </div>
            </div>

            @php
                $familyHistory = $record->family_disease_history;
                if (is_string($familyHistory)) {
                    $familyHistory = json_decode($familyHistory, true) ?: [];
                } else {
                    $familyHistory = $familyHistory ?: [];
                }
                
                $riskBehaviors = $record->risk_behaviors;
                if (is_string($riskBehaviors)) {
                    $riskBehaviors = json_decode($riskBehaviors, true) ?: [];
                } else {
                    $riskBehaviors = $riskBehaviors ?: [];
                }
            @endphp

            {{-- Riwayat Penyakit & Perilaku Berisiko --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- CARD 2: Riwayat Penyakit Keluarga --}}
                <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#006C49] text-2xl">medical_services</span>
                        <h2 class="text-lg font-bold text-slate-800">Riwayat Penyakit Keluarga</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            'Hipertensi' => 'Hipertensi',
                            'DM' => 'DM (Diabetes)',
                            'Stroke' => 'Stroke',
                            'Jantung' => 'Jantung',
                            'Kanker' => 'Kanker',
                            'Kolesterol' => 'Kolesterol'
                        ] as $val => $label)
                            <label class="flex items-center gap-3 p-3.5 border border-[#D9D9D9] rounded-xl cursor-pointer hover:bg-slate-50/50 transition-all select-none bg-white has-[:checked]:border-[#006C49] has-[:checked]:bg-[#006C49]/5">
                                <input type="checkbox" name="family_disease_history[]" value="{{ $val }}" 
                                       {{ (is_array(old('family_disease_history')) && in_array($val, old('family_disease_history'))) || (is_array($familyHistory) && in_array($val, $familyHistory)) ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#006C49] border-slate-300 rounded focus:ring-[#006C49] accent-[#006C49]">
                                <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                {{-- CARD 3: Perilaku Berisiko Mandiri --}}
                <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-[#006C49] text-2xl">accessibility_new</span>
                        <h2 class="text-lg font-bold text-slate-800">Perilaku Berisiko Mandiri</h2>
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        @foreach([
                            'Merokok' => 'Merokok',
                            'Kurang Sayur/Buah' => 'Kurang Sayur/Buah',
                            'Kurang Aktivitas' => 'Kurang Aktivitas',
                            'Alkohol' => 'Alkohol',
                            'Gula Berlebih' => 'Gula Berlebih',
                            'Garam Berlebih' => 'Garam Berlebih'
                        ] as $val => $label)
                            <label class="flex items-center gap-3 p-3.5 border border-[#D9D9D9] rounded-xl cursor-pointer hover:bg-slate-50/50 transition-all select-none bg-white has-[:checked]:border-[#006C49] has-[:checked]:bg-[#006C49]/5">
                                <input type="checkbox" name="risk_behaviors[]" value="{{ $val }}" 
                                       {{ (is_array(old('risk_behaviors')) && in_array($val, old('risk_behaviors'))) || (is_array($riskBehaviors) && in_array($val, $riskBehaviors)) ? 'checked' : '' }}
                                       class="w-4 h-4 text-[#006C49] border-slate-300 rounded focus:ring-[#006C49] accent-[#006C49]">
                                <span class="text-sm font-semibold text-slate-700">{{ $label }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- CARD 4: Data Pemeriksaan --}}
            <div class="bg-white rounded-xl border border-slate-200 p-8 shadow-sm space-y-6">
                <div class="flex items-center gap-3 pb-4 border-b border-slate-100">
                    <span class="material-symbols-outlined text-[#006C49] text-2xl">analytics</span>
                    <h2 class="text-lg font-bold text-slate-800">Data Pemeriksaan</h2>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-12 gap-6">
                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Waktu ke Posyandu</label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', $record->visit_date?->format('Y-m-d')) }}" required
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Berat Badan (kg)</label>
                        <input type="number" step="0.1" name="weight" id="lansia_weight" placeholder="00" value="{{ old('weight', $record->weight) }}" required
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Tinggi Badan (cm)</label>
                        <input type="number" step="0.1" name="height" id="lansia_height" placeholder="000" value="{{ old('height', $record->height) }}" required
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>

                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">IMT</label>
                        <input type="text" name="imt" id="lansia_imt" readonly placeholder="00.0" value="{{ old('imt', $record->imt) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 bg-slate-50/70 cursor-not-allowed outline-none">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Lingkar Perut (cm)</label>
                        <input type="number" step="0.1" name="waist_circumference" placeholder="00" value="{{ old('waist_circumference', $record->waist_circumference) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Tekanan Darah (mmHg)</label>
                        <input type="text" name="blood_pressure" placeholder="120/80" value="{{ old('blood_pressure', $record->blood_pressure) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">GDS (mg/dL)</label>
                        <input type="number" name="blood_sugar" placeholder="000" value="{{ old('blood_sugar', $record->blood_sugar) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>

                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Asam Urat (mg/dL)</label>
                        <input type="number" step="0.1" name="uric_acid" placeholder="0.0" value="{{ old('uric_acid', $record->uric_acid) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Kolesterol (mg/dL)</label>
                        <input type="number" name="cholesterol" placeholder="000" value="{{ old('cholesterol', $record->cholesterol) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>
                    <div class="md:col-span-6 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Tes Mata</label>
                        <select name="eye_test" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Normal" {{ old('eye_test', $record->eye_test) === 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Gangguan Mata Kanan" {{ old('eye_test', $record->eye_test) === 'Gangguan Mata Kanan' ? 'selected' : '' }}>Gangguan Mata Kanan</option>
                            <option value="Gangguan Mata Kiri" {{ old('eye_test', $record->eye_test) === 'Gangguan Mata Kiri' ? 'selected' : '' }}>Gangguan Mata Kiri</option>
                        </select>
                    </div>

                    <div class="md:col-span-6 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Tes Telinga</label>
                        <select name="ear_test" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Normal" {{ old('ear_test', $record->ear_test) === 'Normal' ? 'selected' : '' }}>Normal</option>
                            <option value="Gangguan Telinga Kanan" {{ old('ear_test', $record->ear_test) === 'Gangguan Telinga Kanan' ? 'selected' : '' }}>Gangguan Telinga Kanan</option>
                            <option value="Gangguan Telinga Kiri" {{ old('ear_test', $record->ear_test) === 'Gangguan Telinga Kiri' ? 'selected' : '' }}>Gangguan Telinga Kiri</option>
                        </select>
                    </div>
                    <div class="md:col-span-6"></div>

                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Skrining PUMA</label>
                        <select name="puma_screening" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Tidak" {{ old('puma_screening', $record->puma_screening) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('puma_screening', $record->puma_screening) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Skrining TBC</label>
                        <select name="tbc_screening_status" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Tidak" {{ old('tbc_screening_status', $record->tbc_screening_status) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('tbc_screening_status', $record->tbc_screening_status) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-4 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Skrining Jiwa</label>
                        <select name="mental_screening" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Tidak" {{ old('mental_screening', $record->mental_screening) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('mental_screening', $record->mental_screening) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>

                    <div class="md:col-span-6 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Kontrasepsi</label>
                        <select name="contraception" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="Tidak" {{ old('contraception', $record->contraception) === 'Tidak' ? 'selected' : '' }}>Tidak</option>
                            <option value="Ya" {{ old('contraception', $record->contraception) === 'Ya' ? 'selected' : '' }}>Ya</option>
                        </select>
                    </div>
                    <div class="md:col-span-6 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Edukasi</label>
                        <input type="text" name="education" placeholder="Catatan edukasi..." value="{{ old('education', $record->education) }}"
                               class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white">
                    </div>

                    <div class="md:col-span-12 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Rujuk</label>
                        <select name="referral_type" class="w-full h-[44px] px-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 bg-white appearance-none pr-10">
                            <option value="None" {{ old('referral_type', $record->referral_type) === 'None' ? 'selected' : '' }}>Tidak Rujuk</option>
                            <option value="Puskesmas" {{ old('referral_type', $record->referral_type) === 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                            <option value="RS" {{ old('referral_type', $record->referral_type) === 'RS' ? 'selected' : '' }}>Rumah Sakit</option>
                        </select>
                    </div>
                    
                    <div class="md:col-span-12 space-y-2">
                        <label class="text-xs font-bold text-slate-600">Obat yang Sedang Diminum</label>
                        <textarea name="current_medication" rows="2" placeholder="Contoh: Amlodipine 5mg 1x1, Metformin 500mg 2x1..."
                                  class="w-full p-4 border border-[#D9D9D9] rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-[#006C49] focus:ring-4 focus:ring-[#006C49]/5 transition-all bg-white resize-none">{{ old('current_medication', $record->current_medication) }}</textarea>
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-1 h-16 bg-[#006C49] text-white rounded-2xl font-black text-sm uppercase tracking-[0.2em] hover:bg-[#005439] transition-all shadow-lg flex items-center justify-center gap-3 group">
                    <span>Simpan Perubahan Rekam Medis</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">save</span>
                </button>
                <a href="{{ route('admin.medical-records.index') }}" class="w-full md:w-56 h-16 bg-white text-slate-400 border border-slate-200 rounded-2xl font-bold text-sm flex items-center justify-center hover:bg-slate-50 transition-all text-center uppercase tracking-widest hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                    Batalkan
                </a>
            </div>

        {{-- ---------------------------------------------------- --}}
        {{-- CASE 3: BALITA / RUTIN --}}
        {{-- ---------------------------------------------------- --}}
        @else
            <input type="hidden" name="category" value="{{ $record->patient->category }}">

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
                    {{-- Patient Selection (Disabled during edit for data integrity) --}}
                    <div class="md:col-span-8 space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Balita / Sasaran <span class="text-primary">*</span></label>
                        <div class="relative group/select">
                            <input type="hidden" name="patient_id" value="{{ $record->patient_id }}">
                            <select id="patient-select" required placeholder="Cari nama atau NIK balita..."
                                    class="w-full h-16 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none bg-slate-50/30 cursor-not-allowed">
                                <option value="{{ $record->patient_id }}" 
                                        data-nik="{{ $record->patient->id_number }}" 
                                        data-father="{{ $record->patient->father_name }}"
                                        data-mother="{{ $record->patient->mother_name }}"
                                        data-weight-birth="{{ $record->patient->weight_at_birth }}"
                                        data-height-birth="{{ $record->patient->height_at_birth }}"
                                        data-last-weight="{{ $record->patient->medicalRecords->where('id', '!=', $record->id)->first()->weight ?? 0 }}"
                                        data-last-status="{{ $record->patient->medicalRecords->where('id', '!=', $record->id)->first()->weight_status ?? '' }}"
                                        data-category="{{ $record->patient->category }}">
                                    {{ $record->patient->full_name }} — NIK: {{ $record->patient->id_number }}
                                </option>
                            </select>
                        </div>
                        <p class="text-[10px] text-slate-400 italic ml-1">Nama sasaran tidak dapat diubah setelah rekam dibuat.</p>
                    </div>

                    {{-- Visit Date --}}
                    <div class="md:col-span-4 space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Pengisian <span class="text-primary">*</span></label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', $record->visit_date?->format('Y-m-d')) }}" required
                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                    </div>

                    {{-- Identity & Birth History --}}
                    <div class="md:col-span-12 grid grid-cols-1 md:grid-cols-4 gap-6 pt-10 mt-2 border-t border-slate-100/60">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ayah</label>
                            <input type="text" name="father_name" value="{{ old('father_name', $record->patient->father_name) }}" placeholder="Nama ayah..."
                                   class="w-full h-14 px-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Nama Ibu</label>
                            <input type="text" name="mother_name" value="{{ old('mother_name', $record->patient->mother_name) }}" placeholder="Nama ibu..."
                                   class="w-full h-14 px-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">BB Lahir (kg)</label>
                            <div class="relative">
                                <input type="number" step="0.01" name="weight_at_birth" value="{{ old('weight_at_birth', $record->patient->weight_at_birth) }}" placeholder="0.00"
                                       class="w-full h-14 pl-5 pr-10 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-4 top-4 text-[10px] font-black text-slate-300">KG</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">PB Lahir (cm)</label>
                            <div class="relative">
                                <input type="number" step="0.1" name="height_at_birth" value="{{ old('height_at_birth', $record->patient->height_at_birth) }}" placeholder="0.0"
                                       class="w-full h-14 pl-5 pr-10 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-4 top-4 text-[10px] font-black text-slate-300">CM</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Antropometri, Skrining & Nutrisi --}}
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

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 relative">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Berat Badan <span class="text-primary">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.01" name="weight" value="{{ old('weight', $record->weight) }}" placeholder="0.00" required
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">KG</span>
                            </div>
                        </div>
                        
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi Badan <span class="text-primary">*</span></label>
                            <div class="relative">
                                <input type="number" step="0.1" name="height" value="{{ old('height', $record->height) }}" placeholder="0.0" required
                                       class="w-full h-16 pl-6 pr-14 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                <span class="absolute right-6 top-5 text-[11px] font-black text-slate-300">CM</span>
                            </div>
                        </div>

                        <div class="space-y-3" x-data="{ 
                            status: '{{ $record->weight_status }}',
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
                            <input type="hidden" name="weight_status" :value="status">
                            <div :class="getStatusClass()" 
                                 class="w-full h-16 px-6 rounded-[1.25rem] border flex items-center justify-between transition-all duration-500 shadow-sm">
                                <span class="text-sm font-black uppercase tracking-widest" x-text="getStatusLabel()"></span>
                                <span class="material-symbols-outlined text-[20px]" x-text="status ? 'verified' : 'hourglass_empty'"></span>
                            </div>
                        </div>

                        <div class="md:col-span-3 grid grid-cols-1 md:grid-cols-2 gap-8 pt-8 mt-2 border-t border-slate-100/60">
                            <div class="space-y-3">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Cara Ukur <span class="text-primary">*</span></label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="measurement_method" value="recumbent" {{ old('measurement_method', $record->measurement_method) == 'recumbent' ? 'checked' : '' }} class="sr-only peer" required>
                                        <div class="h-16 flex items-center justify-center rounded-[1.25rem] border-2 border-slate-100 bg-slate-50 text-slate-400 transition-all peer-checked:border-secondary peer-checked:bg-secondary peer-checked:text-white font-black text-[11px] uppercase tracking-widest">
                                            Telentang
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer group">
                                        <input type="radio" name="measurement_method" value="standing" {{ old('measurement_method', $record->measurement_method) == 'standing' ? 'checked' : '' }} class="sr-only peer">
                                        <div class="h-16 flex items-center justify-center rounded-[1.25rem] border-2 border-slate-100 bg-slate-50 text-slate-400 transition-all peer-checked:border-secondary peer-checked:bg-secondary peer-checked:text-white font-black text-[11px] uppercase tracking-widest">
                                            Berdiri
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-4">
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lingkar Kepala</label>
                                    <div class="relative">
                                        <input type="number" step="0.1" name="head_circumference" value="{{ old('head_circumference', $record->head_circumference) }}" placeholder="0.0"
                                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                        <span class="absolute right-5 top-5 text-slate-300 material-symbols-outlined">analytics</span>
                                    </div>
                                </div>
                                <div class="space-y-3">
                                    <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">LiLA (cm)</label>
                                    <div class="relative">
                                        <input type="number" step="0.1" name="upper_arm_circumference" value="{{ old('upper_arm_circumference', $record->upper_arm_circumference) }}" placeholder="0.0"
                                               class="w-full h-16 px-6 border border-slate-200 rounded-[1.25rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-secondary focus:ring-4 focus:ring-secondary/5 transition-all bg-slate-50/30">
                                        <span class="absolute right-5 top-5 text-slate-300 material-symbols-outlined">straighten</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Status Gizi & Hasil Diagnosis (Otomatis) --}}
                <div class="bg-teal-50/50 border border-teal-100 rounded-[3rem] p-8 space-y-6">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-teal-600">verified</span>
                        <h3 class="text-sm font-black text-slate-800 uppercase tracking-widest">Status Gizi & Hasil Diagnosis (Otomatis)</h3>
                    </div>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Status Gizi (BB/U)</span>
                            <span class="text-sm font-black text-slate-700">{{ $record->nutrition_status ?? 'Belum Dihitung' }}</span>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Status Tinggi (TB/U)</span>
                            <span class="text-sm font-black text-slate-700">{{ $record->stunting_status ?? 'Belum Dihitung' }}</span>
                        </div>
                        <div class="bg-white p-5 rounded-2xl border border-slate-100 shadow-sm">
                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block mb-1">Hasil Diagnosis</span>
                            <span class="text-sm font-black text-slate-700">{{ $record->diagnosis ?? 'Sehat' }}</span>
                        </div>
                    </div>
                    <div class="bg-white p-6 rounded-2xl border border-slate-100 shadow-sm space-y-2">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-wider block">Nasihat / Konseling</span>
                        <p class="text-sm font-bold text-slate-600 leading-relaxed">{{ $record->counseling_notes ?? '-' }}</p>
                    </div>
                    <p class="text-[10px] text-teal-600/70 font-semibold italic ml-1">*) Data di atas dihitung otomatis oleh sistem berdasarkan berat badan, tinggi badan, jenis kelamin, dan usia anak pada tanggal pemeriksaan.</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
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
                                        </div>
                                        <input type="checkbox" name="{{ $name }}" value="1" {{ old($name, $record->$name) ? 'checked' : '' }}
                                               class="w-6 h-6 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20 transition-all accent-teal-600">
                                    </label>
                                @endforeach
                            </div>

                            <div class="space-y-3 pt-2">
                                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Gejala / Temuan Lainnya</label>
                                <textarea name="other_symptoms" rows="10" placeholder="Sebutkan gejala lain jika ada secara detail..."
                                          class="w-full p-6 border border-slate-200 rounded-[2rem] text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-slate-50/30 resize-none">{{ old('other_symptoms', $record->other_symptoms) }}</textarea>
                            </div>
                        </div>
                    </div>

                    {{-- C. Nutrisi, Vitamin & Imunisasi --}}
                    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(20,184,166,0.06)] relative overflow-hidden group">
                        <div class="absolute -top-24 -right-24 w-64 h-64 bg-teal-50/5 rounded-full blur-3xl group-hover:bg-teal-50/10 transition-colors"></div>
                        
                        <div class="flex items-center gap-4 mb-8 relative">
                            <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-teal-50 to-teal-600 text-white flex items-center justify-center shadow-lg shadow-teal-200">
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
                                                    <input type="radio" name="{{ $name }}" value="{{ $val }}" {{ old($name, $record->$name) == $val ? 'checked' : '' }} class="sr-only peer">
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
                                    <div x-data="{ checked: {{ old($name, $record->$name) ? 'true' : 'false' }} }" class="space-y-3">
                                        <label class="relative flex flex-col items-center justify-center p-5 rounded-[2rem] border border-slate-100 bg-slate-50/30 hover:bg-white hover:border-teal-200 transition-all cursor-pointer group/toggle has-[:checked]:border-teal-500/50 has-[:checked]:bg-teal-50/30">
                                            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-3">{{ $info[0] }}</span>
                                            <div class="relative inline-flex items-center">
                                                <input type="checkbox" name="{{ $name }}" value="1" x-model="checked" {{ old($name, $record->$name) ? 'checked' : '' }} class="sr-only peer">
                                                <div class="w-12 h-7 bg-slate-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[3px] after:left-[3px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-teal-500 shadow-inner transition-colors"></div>
                                            </div>
                                            <div class="mt-3 px-3 py-1 rounded-full text-[9px] font-black tracking-tighter transition-all bg-slate-100 text-slate-800 peer-checked:bg-teal-100 peer-checked:text-slate-800 uppercase" 
                                                 x-text="checked ? 'DIBERIKAN' : 'TIDAK'">
                                                {{ old($name, $record->$name) ? 'DIBERIKAN' : 'TIDAK' }}
                                            </div>
                                        </label>
 
                                        {{-- Conditional Color Selector for Vitamin A --}}
                                        @if($name === 'vitamin_a')
                                            <div x-show="checked" x-transition class="pt-2">
                                                <x-forms.select-input name="vitamin_a_color" placeholder="-- Pilih Warna Kapsul --" :placeholderDisabled="false" value="{{ old('vitamin_a_color', $record->vitamin_a_color) }}">
                                                    <option value="biru" {{ old('vitamin_a_color', $record->vitamin_a_color) == 'biru' ? 'selected' : '' }}>🔵 Kapsul Biru (6-11 bln)</option>
                                                    <option value="merah" {{ old('vitamin_a_color', $record->vitamin_a_color) == 'merah' ? 'selected' : '' }}>🔴 Kapsul Merah (1-5 thn)</option>
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
                                        this.selectedVaccines = '{{ old('vaccine_name', $record->vaccine_name) }}' ? '{{ old('vaccine_name', $record->vaccine_name) }}'.split(', ') : [];
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
                                    <input type="hidden" name="vaccine_name" x-ref="vaccineInput" value="{{ old('vaccine_name', $record->vaccine_name) }}">
                                    
                                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                                        @foreach(['HB-0', 'Polio 0', 'BCG', 'Polio 1', 'Polio 2', 'Polio 3', 'Polio 4', 'DPT-HB-Hib 1', 'DPT-HB-Hib 2', 'DPT-HB-Hib 3', 'PCV 1', 'PCV 2', 'PCV 3', 'RV 1', 'RV 2', 'RV 3', 'IPV 1', 'IPV 2', 'MR', 'DPT-HB-Hib Lanjutan', 'MR Lanjutan', 'JE'] as $vaccine)
                                            <button type="button" 
                                                    @click="toggleVaccine('{{ $vaccine }}')"
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
                                         <input type="text" name="pmt_given" value="{{ old('pmt_given', $record->pmt_given) }}" placeholder="Contoh: Biskuit, Susu..."
                                                class="w-full h-14 px-6 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all bg-slate-50/30">
                                         <span class="absolute right-5 top-4 text-slate-300 material-symbols-outlined">restaurant</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Perkembangan & Tindakan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-8">
                {{-- A. Perkembangan (KPSP) --}}
                <div class="md:col-span-1 bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.08)]">
                    <div class="flex items-center gap-3 mb-8">
                        <div class="w-10 h-10 rounded-2xl bg-primary/10 text-primary flex items-center justify-center border border-primary/20">
                            <span class="material-symbols-outlined text-[20px]">psychology</span>
                        </div>
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Perkembangan (KPSP)</h3>
                    </div>

                    <div class="space-y-6">
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Status KPSP</label>
                            <x-forms.select-input name="kpsp_status" placeholder="-- Pilih Status --" :placeholderDisabled="false" value="{{ old('kpsp_status', $record->kpsp_status) }}">
                                <option value="Lengkap" {{ old('kpsp_status', $record->kpsp_status) == 'Lengkap' ? 'selected' : '' }}>✅ Lengkap / Sesuai</option>
                                <option value="Tidak Lengkap" {{ old('kpsp_status', $record->kpsp_status) == 'Tidak Lengkap' ? 'selected' : '' }}>⚠️ Ada Keterlambatan</option>
                            </x-forms.select-input>
                        </div>
                    </div>
                </div>

                {{-- B. Rujukan & Tindakan --}}
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
                            <x-forms.select-input name="referral_type" placeholder="" value="{{ old('referral_type', $record->referral_type) }}">
                                <option value="None" {{ old('referral_type', $record->referral_type) == 'None' ? 'selected' : '' }}>Tidak Ada Rujukan</option>
                                <option value="Pustu" {{ old('referral_type', $record->referral_type) == 'Pustu' ? 'selected' : '' }}>Pustu</option>
                                <option value="Puskesmas" {{ old('referral_type', $record->referral_type) == 'Puskesmas' ? 'selected' : '' }}>Puskesmas</option>
                                <option value="RS" {{ old('referral_type', $record->referral_type) == 'RS' ? 'selected' : '' }}>Rumah Sakit</option>
                            </x-forms.select-input>
                        </div>
                        <div class="space-y-3">
                            <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Keluhan / Riwayat Sakit</label>
                            <textarea name="complaint" rows="4" placeholder="Catat keluhan balita jika ada..."
                                      class="w-full p-5 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/30 resize-none">{{ old('complaint', $record->complaint) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Submit Actions --}}
            <div class="flex flex-col md:flex-row gap-4 pt-8">
                <button type="submit" class="flex-1 h-20 bg-primary text-white rounded-[2rem] font-black text-sm uppercase tracking-[0.2em] hover:bg-primary/90 transition-all shadow-2xl shadow-primary/20 flex items-center justify-center gap-3 group">
                    <span>Simpan Perubahan Rekam Medis</span>
                    <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">save</span>
                </button>
                <a href="{{ route('admin.medical-records.index') }}" class="w-full md:w-56 h-20 bg-white text-slate-400 border border-slate-200 rounded-[2rem] font-bold text-sm flex items-center justify-center hover:bg-slate-50 transition-all text-center uppercase tracking-widest hover:text-red-500 hover:bg-red-50 hover:border-red-200 transition-all duration-300">
                    Batalkan
                </a>
            </div>
        @endif

    </form>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>
@if($isPregnancy)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const birthDateInput = document.getElementById('birth_date');
        const ageDisplay = document.getElementById('age_display');
        const postpartumWeight = document.getElementById('postpartum_weight');
        const postpartumBp = document.getElementById('postpartum_bp');
        const mainWeight = document.querySelector('input[name="weight"]');
        const mainBp = document.querySelector('input[name="blood_pressure"]');

        // Sync Section 3 BB and TD to standard fields
        if (postpartumWeight && mainWeight) {
            postpartumWeight.addEventListener('input', function() {
                mainWeight.value = postpartumWeight.value;
            });
            mainWeight.addEventListener('input', function() {
                postpartumWeight.value = mainWeight.value;
            });
        }

        if (postpartumBp && mainBp) {
            postpartumBp.addEventListener('input', function() {
                mainBp.value = postpartumBp.value;
            });
            mainBp.addEventListener('input', function() {
                postpartumBp.value = mainBp.value;
            });
        }

        // Function to calculate age from birth date string
        function calculateAge(birthDateString) {
            if (!birthDateString) return '';
            const today = new Date();
            const birthDate = new Date(birthDateString);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            return age >= 0 ? age : '';
        }

        // Function to calculate birth date from age (sets to Jan 1st of that year for consistency)
        function calculateBirthDate(ageVal) {
            if (!ageVal || isNaN(ageVal)) return '';
            const today = new Date();
            const birthYear = today.getFullYear() - parseInt(ageVal);
            return `${birthYear}-01-01`;
        }

        if (birthDateInput) {
            birthDateInput.addEventListener('change', function() {
                const calculated = calculateAge(this.value);
                if (calculated !== '') {
                    ageDisplay.value = calculated;
                }
            });
        }

        if (ageDisplay) {
            ageDisplay.addEventListener('input', function() {
                const ageVal = this.value;
                if (ageVal) {
                    const calculatedDate = calculateBirthDate(ageVal);
                    if (calculatedDate && birthDateInput) {
                        birthDateInput.value = calculatedDate;
                    }
                } else {
                    if (birthDateInput) birthDateInput.value = '';
                }
            });
        }

        // Check if initial value is set (e.g. from session or old input)
        if (birthDateInput && birthDateInput.value) {
            ageDisplay.value = calculateAge(birthDateInput.value);
        }

        // Initialize TomSelect (disabled)
        new TomSelect('#ibu-hamil-select', {
            create: false,
            placeholder: "Cari nama atau NIK ibu hamil...",
        }).disable();
    });
    </script>
@elseif($isLansia)
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const weightInput = document.getElementById('lansia_weight');
        const heightInput = document.getElementById('lansia_height');
        const imtInput = document.getElementById('lansia_imt');

        function calculateIMT() {
            if (!weightInput || !heightInput || !imtInput) return;
            const w = parseFloat(weightInput.value);
            const h = parseFloat(heightInput.value);
            if (w && h) {
                const hM = h / 100;
                const imt = w / (hM * hM);
                imtInput.value = imt.toFixed(1);
            } else {
                imtInput.value = '';
            }
        }

        if (weightInput && heightInput) {
            weightInput.addEventListener('input', calculateIMT);
            heightInput.addEventListener('input', calculateIMT);
            calculateIMT();
        }

        // Initialize TomSelect (disabled)
        new TomSelect('#lansia-select', {
            create: false,
            placeholder: "Cari nama atau NIK lansia...",
        }).disable();
    });
    </script>
@else
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const patientSelect = document.getElementById('patient-select');
        if (!patientSelect) return;

        // Initialize TomSelect (disabled)
        const ts = new TomSelect('#patient-select', {
            create: false,
            placeholder: "Cari nama atau NIK balita...",
        });
        ts.disable();

        // --- Automatic Weight Status Logic ---
        const weightInput = document.querySelector('input[name="weight"]');
        if (weightInput) {
            weightInput.addEventListener('input', calculateWeightStatus);
        }
        
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
@endif
@endpush
