{{-- Grid Informasi Detail Ibu Hamil --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">

    {{-- 1. Identitas Pribadi --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-pink-50 text-pink-600 flex items-center justify-center">
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
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Suami</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->husband_name ?: $patient->parent_name ?: '-' }}</span>
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
                <div class="flex justify-between items-center py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Usia</span>
                    <span class="text-sm font-black text-slate-700">{{ $patient->age }}</span>
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
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kelurahan / Desa</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->desa_kelurahan ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kecamatan</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->kecamatan ?? '-' }}</span>
                </div>
                <div class="flex flex-col py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</span>
                    <p class="text-xs font-bold text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 leading-relaxed">{{ $patient->address }}</p>
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
