@extends('layouts.admin-layout')

@section('admin-title') Catat Rekam Medis Bulanan @endsection

@section('admin-content')
<div class="max-w-5xl mx-auto space-y-6">

    <form action="{{ route('admin.medical-records.store') }}" method="POST" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        @csrf

        {{-- ── Left Column: Main Assessment ── --}}
        <div class="lg:col-span-2 space-y-6">
            
            {{-- 1. Visit Context --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">calendar_month</span>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Informasi Kunjungan</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Patient Selection --}}
                    <div class="md:col-span-2 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Warga / Sasaran <span class="text-red-500">*</span></label>
                        <select name="patient_id" required
                                class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all appearance-none cursor-pointer bg-white">
                            <option value="">-- Pilih Warga --</option>
                            @foreach($patients as $patient)
                                <option value="{{ $patient->id }}" {{ old('patient_id', request('patient_id')) == $patient->id ? 'selected' : '' }}>
                                    {{ $patient->full_name }} — NIK: {{ $patient->id_number }}
                                </option>
                            @endforeach
                        </select>
                        @error('patient_id') <p class="text-[11px] text-red-500 font-bold">{{ $message }}</p> @enderror
                    </div>

                    {{-- Visit Date --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tanggal Periksa <span class="text-red-500">*</span></label>
                        <input type="date" name="visit_date" value="{{ old('visit_date', date('Y-m-d')) }}" required
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>

                    {{-- Nutrition Status is now auto-calculated in the backend, no need for manual input --}}

            {{-- 2. Antropometri --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">straighten</span>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Antropometri</h3>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Berat (kg) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.01" name="weight" value="{{ old('weight') }}" placeholder="0.00" required
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all @error('weight') border-red-300 bg-red-50 @enderror">
                        @error('weight') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tinggi (cm) <span class="text-red-500">*</span></label>
                        <input type="number" step="0.1" name="height" value="{{ old('height') }}" placeholder="0.0" required
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all @error('height') border-red-300 bg-red-50 @enderror">
                        @error('height') <p class="text-[10px] text-red-500 font-bold ml-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Lingkar Kepala (cm)</label>
                        <input type="number" step="0.1" name="head_circumference" value="{{ old('head_circumference') }}" placeholder="0.0"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">LiLA (cm)</label>
                        <input type="number" step="0.1" name="upper_arm_circumference" value="{{ old('upper_arm_circumference') }}" placeholder="0.0"
                               class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all">
                    </div>
                    <div class="md:col-span-3 space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Cara Ukur <span class="text-red-500">*</span></label>
                        <select name="measurement_method" required
                                class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all appearance-none cursor-pointer bg-white">
                            <option value="recumbent" {{ old('measurement_method') == 'recumbent' ? 'selected' : '' }}>📏 Telentang (Anak < 2 Thn)</option>
                            <option value="standing" {{ old('measurement_method') == 'standing' ? 'selected' : '' }}>🧍 Berdiri (Anak >= 2 Thn)</option>
                        </select>
                        <p class="text-[10px] text-slate-400 italic ml-1">* WHO menyarankan telentang untuk bayi di bawah 2 tahun.</p>
                    </div>
                </div>
            </div>

            {{-- 3. Findings --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">stethoscope</span>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Catatan & Diagnosa</h3>
                </div>

                <div class="space-y-6">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Keluhan / Temuan</label>
                        <textarea name="complaint" rows="2" placeholder="Catat keluhan jika ada..."
                                  class="w-full p-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all resize-none">{{ old('complaint') }}</textarea>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Hasil Akhir <span class="text-red-500">*</span></label>
                        <textarea name="diagnosis" rows="3" placeholder="Hasil pemeriksaan..." required
                                  class="w-full p-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all resize-none">{{ old('diagnosis') }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── Right Column ── --}}
        <div class="space-y-6">
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">pill</span>
                    </div>
                    <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Tambahan</h3>
                </div>

                <div class="space-y-6">
                    {{-- Vaccine Selection --}}
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Imunisasi (Vaksin)</label>
                        <select name="vaccine_name"
                                class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all appearance-none cursor-pointer bg-white">
                            <option value="">-- Tidak Ada --</option>
                            <optgroup label="Dasar (0-9 Bulan)">
                                <option value="Hepatitis B" {{ old('vaccine_name') == 'Hepatitis B' ? 'selected' : '' }}>Hepatitis B (HB-0)</option>
                                <option value="BCG" {{ old('vaccine_name') == 'BCG' ? 'selected' : '' }}>BCG</option>
                                <option value="Polio 1" {{ old('vaccine_name') == 'Polio 1' ? 'selected' : '' }}>Polio 1</option>
                                <option value="Polio 2" {{ old('vaccine_name') == 'Polio 2' ? 'selected' : '' }}>Polio 2</option>
                                <option value="Polio 3" {{ old('vaccine_name') == 'Polio 3' ? 'selected' : '' }}>Polio 3</option>
                                <option value="Polio 4" {{ old('vaccine_name') == 'Polio 4' ? 'selected' : '' }}>Polio 4</option>
                                <option value="DPT-HB-Hib 1" {{ old('vaccine_name') == 'DPT-HB-Hib 1' ? 'selected' : '' }}>DPT-HB-Hib 1</option>
                                <option value="DPT-HB-Hib 2" {{ old('vaccine_name') == 'DPT-HB-Hib 2' ? 'selected' : '' }}>DPT-HB-Hib 2</option>
                                <option value="DPT-HB-Hib 3" {{ old('vaccine_name') == 'DPT-HB-Hib 3' ? 'selected' : '' }}>DPT-HB-Hib 3</option>
                                <option value="PCV 1" {{ old('vaccine_name') == 'PCV 1' ? 'selected' : '' }}>PCV 1</option>
                                <option value="PCV 2" {{ old('vaccine_name') == 'PCV 2' ? 'selected' : '' }}>PCV 2</option>
                                <option value="IPV" {{ old('vaccine_name') == 'IPV' ? 'selected' : '' }}>IPV</option>
                                <option value="Campak/MR" {{ old('vaccine_name') == 'Campak/MR' ? 'selected' : '' }}>Campak/MR</option>
                            </optgroup>
                            <optgroup label="Lanjutan (>18 Bulan)">
                                <option value="DPT-HB-Hib Lanjutan" {{ old('vaccine_name') == 'DPT-HB-Hib Lanjutan' ? 'selected' : '' }}>DPT-HB-Hib Lanjutan</option>
                                <option value="Campak/MR Lanjutan" {{ old('vaccine_name') == 'Campak/MR Lanjutan' ? 'selected' : '' }}>Campak/MR Lanjutan</option>
                            </optgroup>
                        </select>
                    </div>

                    {{-- Vitamin A Color --}}
                    <div class="space-y-3">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pemberian Vitamin A</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group">
                                <input type="radio" name="vitamin_a_color" value="none" class="sr-only peer" checked>
                                <div class="w-full h-full absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-teal-500 peer-checked:bg-teal-50/30 transition-all"></div>
                                <span class="material-symbols-outlined text-slate-300 peer-checked:text-teal-600 z-10">block</span>
                                <span class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-teal-700 z-10">Tidak</span>
                            </label>
                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group">
                                <input type="radio" name="vitamin_a_color" value="biru" class="sr-only peer">
                                <div class="w-full h-full absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-blue-500 peer-checked:bg-blue-50/30 transition-all"></div>
                                <div class="w-4 h-4 rounded-full bg-blue-500 shadow-sm z-10"></div>
                                <span class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-blue-700 z-10">Biru</span>
                            </label>
                            <label class="relative flex flex-col items-center gap-2 p-3 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer group">
                                <input type="radio" name="vitamin_a_color" value="merah" class="sr-only peer">
                                <div class="w-full h-full absolute inset-0 rounded-2xl border-2 border-transparent peer-checked:border-red-500 peer-checked:bg-red-50/30 transition-all"></div>
                                <div class="w-4 h-4 rounded-full bg-red-500 shadow-sm z-10"></div>
                                <span class="text-[10px] font-black uppercase text-slate-400 peer-checked:text-red-700 z-10">Merah</span>
                            </label>
                        </div>
                    </div>

                    <div class="space-y-4 pt-2 border-t border-slate-100">
                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="is_basic_immunization_complete" value="1" {{ old('is_basic_immunization_complete') ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">Imunisasi Dasar Lengkap</span>
                                <span class="text-[10px] text-slate-400">Sudah lengkap sesuai umur</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="deworming_medicine" value="1" {{ old('deworming_medicine') ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">Obat Cacing</span>
                                <span class="text-[10px] text-slate-400">Diberikan setiap 6 bulan</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="pill_fe" value="1" {{ old('pill_fe') ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <div class="text-sm font-bold text-slate-800">Tablet Tambah Darah (FE)</div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="is_exclusive_breastfeeding" value="1" {{ old('is_exclusive_breastfeeding') ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">ASI Eksklusif</span>
                                <span class="text-[10px] text-slate-400">0 - 6 bulan</span>
                            </div>
                        </label>

                        <label class="flex items-center gap-3 p-4 rounded-2xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="mp_asi" value="1" {{ old('mp_asi') ? 'checked' : '' }}
                                   class="w-5 h-5 rounded-lg border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-800">MP-ASI</span>
                                <span class="text-[10px] text-slate-400">Makanan Pendamping (> 6 bln)</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>

            {{-- 4. KPSP (Ceklis Perkembangan) --}}
            <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center border border-teal-100">
                        <span class="material-symbols-outlined text-[20px]">child_care</span>
                    </div>
                    <div class="flex flex-col">
                        <h3 class="text-xs font-black text-slate-800 uppercase tracking-widest">Pemantauan Perkembangan</h3>
                        <span class="text-[10px] text-slate-400">Kuesioner Pra Skrining (KPSP)</span>
                    </div>
                </div>

                <div class="space-y-4">
                    <div class="space-y-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Target Usia KPSP (Bulan)</label>
                        <select name="kpsp_age_group"
                                class="w-full h-12 px-4 border border-slate-200 rounded-xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all appearance-none cursor-pointer bg-white">
                            <option value="">-- Lewati KPSP --</option>
                            <option value="3" {{ old('kpsp_age_group') == '3' ? 'selected' : '' }}>3 Bulan</option>
                            <option value="6" {{ old('kpsp_age_group') == '6' ? 'selected' : '' }}>6 Bulan</option>
                            <option value="9" {{ old('kpsp_age_group') == '9' ? 'selected' : '' }}>9 Bulan</option>
                            <option value="12" {{ old('kpsp_age_group') == '12' ? 'selected' : '' }}>12 Bulan</option>
                            <option value="15" {{ old('kpsp_age_group') == '15' ? 'selected' : '' }}>15 Bulan</option>
                            <option value="18" {{ old('kpsp_age_group') == '18' ? 'selected' : '' }}>18 Bulan</option>
                            <option value="21" {{ old('kpsp_age_group') == '21' ? 'selected' : '' }}>21 Bulan</option>
                            <option value="24" {{ old('kpsp_age_group') == '24' ? 'selected' : '' }}>24 Bulan (2 Tahun)</option>
                            <option value="36" {{ old('kpsp_age_group') == '36' ? 'selected' : '' }}>36 Bulan (3 Tahun)</option>
                            <option value="48" {{ old('kpsp_age_group') == '48' ? 'selected' : '' }}>48 Bulan (4 Tahun)</option>
                            <option value="60" {{ old('kpsp_age_group') == '60' ? 'selected' : '' }}>60 Bulan (5 Tahun)</option>
                            <option value="72" {{ old('kpsp_age_group') == '72' ? 'selected' : '' }}>72 Bulan (6 Tahun)</option>
                        </select>
                    </div>

                    <div class="space-y-3 pt-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Hasil Evaluasi 4 Aspek (Centang jika BISA/YA)</label>
                        
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="kpsp_motor_gross" value="1" {{ old('kpsp_motor_gross') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded-md border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <span class="text-xs font-bold text-slate-700">Motorik Kasar</span>
                        </label>
                        
                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="kpsp_motor_fine" value="1" {{ old('kpsp_motor_fine') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded-md border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <span class="text-xs font-bold text-slate-700">Motorik Halus</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="kpsp_language" value="1" {{ old('kpsp_language') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded-md border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <span class="text-xs font-bold text-slate-700">Bicara / Bahasa</span>
                        </label>

                        <label class="flex items-center gap-3 p-3 rounded-xl border border-slate-100 hover:bg-slate-50 transition-all cursor-pointer">
                            <input type="checkbox" name="kpsp_social" value="1" {{ old('kpsp_social') ? 'checked' : '' }}
                                   class="w-4 h-4 rounded-md border-slate-300 text-teal-600 focus:ring-teal-500/20">
                            <span class="text-xs font-bold text-slate-700">Sosialisasi / Kemandirian</span>
                        </label>
                    </div>

                    <div class="space-y-2 pt-2">
                        <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Catatan Perkembangan</label>
                        <textarea name="kpsp_note" rows="2" placeholder="Tuliskan kendala perkembangan jika ada..."
                                  class="w-full p-4 border border-slate-200 rounded-xl text-sm font-semibold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/5 transition-all resize-none">{{ old('kpsp_note') }}</textarea>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full h-14 bg-teal-600 text-white rounded-2xl font-black text-sm uppercase tracking-widest hover:bg-teal-700 transition-all shadow-xl shadow-teal-500/10">
                Simpan Rekam Medis
            </button>
            <a href="{{ route('admin.medical-records.index') }}" class="block w-full h-14 bg-slate-100 text-slate-500 rounded-2xl font-bold text-sm flex items-center justify-center hover:bg-slate-200 transition-all text-center">
                Batalkan
            </a>
        </div>
    </form>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const patientSelect = document.querySelector('select[name="patient_id"]');
    if (patientSelect) {
        patientSelect.addEventListener('change', function() {
            const patientId = this.value;
            if (patientId) {
                const url = new URL(window.location.href);
                url.searchParams.set('patient_id', patientId);
                window.location.href = url.toString();
            }
        });
    }
});
</script>
@endpush
@endsection