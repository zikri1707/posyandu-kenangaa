{{-- Card Informasi Detail Umum (Unified Premium Card) --}}
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
            <span class="px-3 py-1.5 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-xl text-xs font-black uppercase tracking-widest">
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
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Usia</p>
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
                {{-- Alamat --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Alamat Lengkap</p>
                    <span class="text-base font-semibold text-slate-600 leading-relaxed">{{ $patient->address }}</span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- Section 3: Sosial Ekonomi --}}
        <div>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">real_estate_agent</span>
                Sosial Ekonomi
            </h4>
            <div class="space-y-4">
                {{-- Pendidikan --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Pendidikan</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->education ?? '-' }}</span>
                </div>
                {{-- Pekerjaan --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Pekerjaan</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->job ?? '-' }}</span>
                </div>
                {{-- Rumah --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Kondisi Rumah</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->house_condition ?? '-' }}</span>
                </div>
                {{-- Sanitasi --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Sanitasi</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->has_latrine ? 'Jamban Sehat' : 'Tidak Ada' }}</span>
                </div>
                {{-- Ekonomi --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Status Ekonomi</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->economic_status ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Card Antropometri Terakhir --}}
<div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[3rem] border border-emerald-100 p-8 shadow-sm relative overflow-hidden group mt-8">
    <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
    <div class="flex items-center gap-4 mb-8">
        <div class="w-10 h-10 rounded-xl bg-white text-emerald-600 flex items-center justify-center shadow-sm">
            <span class="material-symbols-outlined text-[20px]">monitor_weight</span>
        </div>
        <h4 class="text-sm font-black text-emerald-800 uppercase tracking-widest">Pengukuran Terakhir</h4>
    </div>
    @php $lastRecord = $patient->medicalRecords()->orderBy('visit_date', 'desc')->first(); @endphp
    <div class="grid grid-cols-2 gap-4">
        <div class="p-4 bg-white/60 backdrop-blur-md rounded-2xl border border-emerald-100">
            <p class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Berat</p>
            <p class="text-xl font-black text-emerald-900">{{ $lastRecord->weight ?? '-' }} <span class="text-[10px]">kg</span></p>
        </div>
        <div class="p-4 bg-white/60 backdrop-blur-md rounded-2xl border border-emerald-100">
            <p class="text-[9px] font-black text-emerald-600/60 uppercase tracking-widest mb-1">Tinggi</p>
            <p class="text-xl font-black text-emerald-900">{{ $lastRecord->height ?? '-' }} <span class="text-[10px]">cm</span></p>
        </div>
    </div>
</div>
