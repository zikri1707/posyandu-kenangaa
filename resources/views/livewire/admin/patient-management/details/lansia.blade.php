@php $lastRecord = $patient->medicalRecords()->orderBy('visit_date', 'desc')->first(); @endphp
{{-- Grid Informasi Detail Lansia --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- 1. Identitas Pribadi --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">badge</span>
                </div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Identitas Pribadi</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NIK / No. Identitas</span>
                    <span class="text-sm font-black text-slate-700 font-mono">{{ $patient->id_number }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Lengkap</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->full_name }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ayah Kandung</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->father_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ibu Kandung</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->mother_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Jenis Kelamin</span>
                    <span class="text-sm font-black text-slate-700">{{ ($patient->gender == 'L' || $patient->gender == 'M') ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tempat Lahir</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->place_of_birth ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal Lahir</span>
                    <span class="text-sm font-black text-slate-700">{{ \Carbon\Carbon::parse($patient->birth_date)->translatedFormat('d F Y') }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Usia</span>
                    <span class="text-sm font-black text-slate-700">{{ $patient->age }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Riwayat Penyakit</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->historical_diseases ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Obat Saat Ini</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $lastRecord->current_medication ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. Domisili & Kontak --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">location_on</span>
                </div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Domisili & Kontak</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">No. HP / WhatsApp</span>
                    <span class="text-sm font-black text-slate-700 font-mono">{{ $patient->phone_number ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Unit Posyandu</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->posyandu->name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RT Domisili</span>
                    <span class="text-sm font-black text-slate-700">{{ $patient->rt_domisili ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">RW Domisili</span>
                    <span class="text-sm font-black text-slate-700">{{ $patient->dusun_rt_rw ?? '-' }}</span>
                </div>
                <div class="flex flex-col py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</span>
                    <p class="text-xs font-bold text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 leading-relaxed">{{ $patient->address }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Posbindu Metrics Monitor (Premium Bento) --}}
<div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mt-8">
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
