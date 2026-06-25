{{-- Card Informasi Detail Ibu Hamil (Unified Premium Card) --}}
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
            <span class="px-3 py-1.5 bg-rose-50 border border-rose-100 text-rose-600 rounded-xl text-xs font-black uppercase tracking-widest">
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
                {{-- Husband Name --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Nama Suami</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->husband_name ?: $patient->parent_name ?: '-' }}</span>
                </div>
                {{-- Gender --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Jenis Kelamin</p>
                    <span class="text-base font-black text-slate-800">{{ ($patient->gender == 'L' || $patient->gender == 'M') ? 'Laki-laki' : 'Perempuan' }}</span>
                </div>
                {{-- Tempat Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tempat Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->place_of_birth ?? '-' }}</span>
                </div>
                {{-- Tanggal Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Tanggal Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ \Carbon\Carbon::parse($patient->birth_date)->translatedFormat('d F Y') }}</span>
                </div>
                {{-- Usia --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Usia</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->age }}</span>
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
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">No. HP / WhatsApp</p>
                    <span class="text-base font-black text-slate-800 font-mono">{{ $patient->phone_number ?? '-' }}</span>
                </div>
                {{-- Unit Posyandu --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Unit Posyandu</p>
                    <span class="px-2.5 py-1 rounded-lg text-sm font-black uppercase tracking-wider border inline-block bg-teal-50 text-teal-600 border-teal-100">{{ $patient->posyandu->name ?? '-' }}</span>
                </div>
                {{-- RT --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">RT Domisili</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->rt_domisili ? 'RT ' . $patient->rt_domisili : '-' }}</span>
                </div>
                {{-- RW --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">RW Domisili</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->dusun_rt_rw ?? '-' }}</span>
                </div>
                {{-- Kelurahan --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Kelurahan / Desa</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->desa_kelurahan ?? '-' }}</span>
                </div>
                {{-- Kecamatan --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Kecamatan</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->kecamatan ?? '-' }}</span>
                </div>
                {{-- Alamat --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1">Alamat Lengkap</p>
                    <span class="text-base font-semibold text-slate-600 leading-relaxed">{{ $patient->address }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Pemantauan Kesehatan Ibu Hamil (Bento Grid) --}}
<div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] mt-8">
    <div class="flex items-center gap-4 mb-8">
        <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center">
            <span class="material-symbols-outlined text-[20px]">pregnant_woman</span>
        </div>
        <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pemeriksaan Kehamilan Terakhir</h4>
    </div>

    @php $lastRecord = $patient->medicalRecords()->orderBy('visit_date', 'desc')->first(); @endphp
    
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Antropometri --}}
        <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50">
            <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-2">Berat & Tinggi Badan</p>
            <div class="flex justify-between items-center py-2 border-b border-slate-100">
                <span class="text-xs font-semibold text-slate-500">Berat</span>
                <span class="text-sm font-black text-slate-800">{{ $lastRecord->weight ?? '-' }} kg</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="text-xs font-semibold text-slate-500">Tinggi</span>
                <span class="text-sm font-black text-slate-800">{{ $lastRecord->height ?? '-' }} cm</span>
            </div>
        </div>

        {{-- LILA (Lingkar Lengan Atas) --}}
        <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 flex flex-col justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">LILA (Lingkar Lengan Atas)</p>
                @if(isset($lastRecord->upper_arm_circumference))
                    <p class="text-2xl font-black text-slate-800">{{ $lastRecord->upper_arm_circumference }} <span class="text-xs font-bold text-slate-400">cm</span></p>
                @else
                    <p class="text-2xl font-black text-slate-400">—</p>
                @endif
            </div>
            
            @if(isset($lastRecord->upper_arm_circumference))
                @if($lastRecord->upper_arm_circumference < 23.5)
                    <div class="mt-2 px-3 py-1.5 rounded-xl bg-red-50 border border-red-100 text-red-600 text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">warning</span>
                        Risiko KEK
                    </div>
                @else
                    <div class="mt-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 w-max">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        Normal
                    </div>
                @endif
            @endif
        </div>

        {{-- Tablet Tambah Darah (Fe) --}}
        <div class="p-6 rounded-3xl border border-slate-100 bg-slate-50/50 flex flex-col justify-between">
            <div>
                <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest mb-1">Suplementasi Tablet Fe</p>
                @if(isset($lastRecord->pill_fe))
                    <p class="text-lg font-black text-slate-800">{{ $lastRecord->pill_fe ? 'Sudah Diberikan' : 'Belum Diberikan' }}</p>
                @else
                    <p class="text-2xl font-black text-slate-400">—</p>
                @endif
            </div>

            @if(isset($lastRecord->pill_fe))
                @if($lastRecord->pill_fe)
                    <div class="mt-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-100 text-emerald-600 text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5 w-max">
                        <span class="material-symbols-outlined text-[14px]">check_circle</span>
                        Tercukupi
                    </div>
                @else
                    <div class="mt-2 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-100 text-amber-600 text-[10px] font-black uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">priority_high</span>
                        Perlu Tablet Fe
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
