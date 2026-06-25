@php $lastRecord = $patient->medicalRecords()->orderBy('visit_date', 'desc')->first(); @endphp
{{-- Card Informasi Detail Lansia (Unified Premium Card) --}}
<div class="bg-white rounded-[3rem] border border-slate-100 p-8 md:p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
    {{-- Profile Card Header (Premium Centered) --}}
    <div class="flex flex-col items-center text-center mb-10 pb-8 border-b border-slate-100">
        <div class="relative mb-4">
            <div class="w-28 h-28 rounded-[2rem] border-4 border-slate-50 bg-slate-50 shadow-md overflow-hidden relative z-10">
                @if($patient->profile_photo)
                    <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-linear-to-br from-slate-50 to-slate-100 flex items-center justify-center">
                        <span class="material-symbols-outlined text-slate-300 text-[56px]" style="font-variation-settings: 'wght' 100;">{{ $theme['avatar_icon'] }}</span>
                    </div>
                @endif
            </div>
        </div>
        
        <h3 class="text-2xl font-black text-slate-800 leading-tight mb-2 tracking-tight">{{ $patient->full_name }}</h3>
        
        <div class="flex flex-wrap justify-center items-center gap-2">
            <span class="px-3 py-1.5 bg-slate-50 border border-slate-100 text-slate-600 rounded-xl text-xs font-black font-mono tracking-wider">
                NIK: {{ $patient->id_number }}
            </span>
            <span class="px-3 py-1.5 bg-amber-50 border border-amber-100 text-amber-600 rounded-xl text-xs font-black uppercase tracking-widest">
                {{ $theme['name'] }}
            </span>
        </div>
    </div>

    {{-- Details Sections in Google Form style (Stacked Vertically) --}}
    <div class="space-y-8">
        {{-- Section 1: Identitas Pribadi --}}
        <div>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">badge</span>
                Identitas Pribadi
            </h4>
            <div class="space-y-4">
                {{-- Gender --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Jenis Kelamin</p>
                    <span @class([
                        'px-2.5 py-1 rounded-lg text-sm font-black uppercase tracking-wider border inline-block',
                        'bg-sky-50 text-sky-600 border-sky-100' => $patient->gender == 'L' || $patient->gender == 'M',
                        'bg-pink-50 text-pink-600 border-pink-100' => $patient->gender == 'F' || $patient->gender == 'P',
                    ])>
                        {{ ($patient->gender == 'L' || $patient->gender == 'M') ? 'Laki-laki' : 'Perempuan' }}
                    </span>
                </div>
                {{-- Tempat Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Tempat Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->place_of_birth ?? '-' }}</span>
                </div>
                {{-- Tanggal Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Tanggal Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ \Carbon\Carbon::parse($patient->birth_date)->translatedFormat('d F Y') }}</span>
                </div>
                {{-- Usia --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Usia</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->age }}</span>
                </div>
                {{-- Riwayat Penyakit --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Riwayat Penyakit</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->historical_diseases ?? '-' }}</span>
                </div>
                {{-- Obat Saat Ini --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Obat Saat Ini</p>
                    <span class="text-base font-black text-slate-800">{{ $lastRecord->current_medication ?? '-' }}</span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- Section 2: Domisili & Kontak --}}
        <div>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">location_on</span>
                Domisili & Kontak
            </h4>
            <div class="space-y-4">
                {{-- No HP --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">No. HP / WhatsApp</p>
                    <span class="text-base font-black text-slate-800 font-mono">{{ $patient->phone_number ?? '-' }}</span>
                </div>
                {{-- Unit Posyandu --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Unit Posyandu</p>
                    <span class="px-2.5 py-1 rounded-lg text-sm font-black uppercase tracking-wider border inline-block bg-teal-50 text-teal-600 border-teal-100">{{ $patient->posyandu->name ?? '-' }}</span>
                </div>
                {{-- RT --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">RT Domisili</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->rt_domisili ? 'RT ' . $patient->rt_domisili : '-' }}</span>
                </div>
                {{-- RW --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">RW Domisili</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->dusun_rt_rw ?? '-' }}</span>
                </div>
                {{-- Alamat --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</p>
                    <span class="text-base font-semibold text-slate-600 leading-relaxed">{{ $patient->address }}</span>
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
