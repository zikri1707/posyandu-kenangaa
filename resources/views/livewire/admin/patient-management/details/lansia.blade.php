{{-- Grid Informasi Spesifik (Bento Style) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Info Utama & Riwayat --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">medical_services</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Riwayat Penyakit</h4>
        </div>
        
        <div class="space-y-6">
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RT Domisili</span>
                <span class="text-sm font-black text-slate-700">RT {{ $patient->rt_domisili ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RW Domisili</span>
                <span class="text-sm font-black text-slate-700">RW {{ $patient->dusun_rt_rw ?? '-' }}</span>
            </div>
            <div class="flex flex-col py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Riwayat Penyakit Dahulu</span>
                <span class="text-sm font-bold text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 min-h-[60px] block leading-relaxed">
                    {{ $patient->historical_diseases ?? 'Tidak ada riwayat penyakit yang dilaporkan' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Obat-obatan --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">pill</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Obat yang Dikonsumsi</h4>
        </div>
        @php $lastRecord = $patient->medicalRecords()->orderBy('visit_date', 'desc')->first(); @endphp
        <div class="space-y-6">
            <div class="flex flex-col py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Konsumsi Obat Saat Ini</span>
                <span class="text-sm font-bold text-slate-700 bg-slate-50 p-4 rounded-2xl border border-slate-100 min-h-[60px] block leading-relaxed">
                    {{ $lastRecord->current_medication ?? 'Tidak sedang mengonsumsi obat khusus' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Informasi Orang Tua Kandung --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] md:col-span-2">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">family_restroom</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Orang Tua Kandung</h4>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <div class="flex justify-between items-center py-4 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ayah Kandung</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->father_name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-4 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ibu Kandung</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->mother_name ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Posbindu Metrics Monitor (Premium Bento) --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] md:col-span-2">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">monitoring</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pengukuran Posbindu Terakhir</h4>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            {{-- Blood Pressure --}}
            <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl transition-all duration-300">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Tekanan Darah</p>
                @if(isset($lastRecord->systolic_bp) && isset($lastRecord->diastolic_bp))
                    <p class="text-xl font-black text-slate-800">{{ $lastRecord->systolic_bp }}/{{ $lastRecord->diastolic_bp }} <span class="text-[10px] text-slate-400">mmHg</span></p>
                    @php
                        $isHighBp = $lastRecord->systolic_bp >= 140 || $lastRecord->diastolic_bp >= 90;
                        $isLowBp = $lastRecord->systolic_bp < 90 || $lastRecord->diastolic_bp < 60;
                    @endphp
                    @if($isHighBp)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">Hipertensi</span>
                    @elseif($isLowBp)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">Hipotensi</span>
                    @else
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">Normal</span>
                    @endif
                @else
                    <p class="text-xl font-black text-slate-400">-</p>
                @endif
            </div>

            {{-- Blood Sugar --}}
            <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl transition-all duration-300">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Gula Darah</p>
                @if(isset($lastRecord->blood_sugar))
                    <p class="text-xl font-black text-slate-800">{{ $lastRecord->blood_sugar }} <span class="text-[10px] text-slate-400">mg/dL</span></p>
                    @if($lastRecord->blood_sugar >= 200)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">Tinggi</span>
                    @elseif($lastRecord->blood_sugar < 70)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">Rendah</span>
                    @else
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">Normal</span>
                    @endif
                @else
                    <p class="text-xl font-black text-slate-400">-</p>
                @endif
            </div>

            {{-- Uric Acid --}}
            <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl transition-all duration-300">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Asam Urat</p>
                @if(isset($lastRecord->uric_acid))
                    <p class="text-xl font-black text-slate-800">{{ $lastRecord->uric_acid }} <span class="text-[10px] text-slate-400">mg/dL</span></p>
                    @php
                        $isHighUric = ($patient->gender == 'L' || $patient->gender == 'M') ? ($lastRecord->uric_acid >= 7.0) : ($lastRecord->uric_acid >= 6.0);
                    @endphp
                    @if($isHighUric)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">Tinggi</span>
                    @else
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">Normal</span>
                    @endif
                @else
                    <p class="text-xl font-black text-slate-400">-</p>
                @endif
            </div>

            {{-- Cholesterol --}}
            <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 hover:bg-white hover:shadow-xl transition-all duration-300">
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Kolesterol</p>
                @if(isset($lastRecord->cholesterol))
                    <p class="text-xl font-black text-slate-800">{{ $lastRecord->cholesterol }} <span class="text-[10px] text-slate-400">mg/dL</span></p>
                    @if($lastRecord->cholesterol >= 200)
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-red-50 text-red-600 border border-red-100">Tinggi</span>
                    @else
                        <span class="inline-block mt-2 px-2.5 py-0.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-emerald-50 text-emerald-600 border border-emerald-100">Normal</span>
                    @endif
                @else
                    <p class="text-xl font-black text-slate-400">-</p>
                @endif
            </div>
        </div>
    </div>
</div>

