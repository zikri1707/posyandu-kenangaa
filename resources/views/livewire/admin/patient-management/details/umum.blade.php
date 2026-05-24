{{-- Grid Informasi Spesifik (Bento Style) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Info Sosial & Profil --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">badge</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Pendidikan & Pekerjaan</h4>
        </div>
        
        <div class="space-y-6">
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pendidikan</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->education ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Pekerjaan</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->job ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Info Ekonomi & Rumah --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">real_estate_agent</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Sosial Ekonomi</h4>
        </div>
        
        <div class="space-y-6">
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Rumah</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->house_condition ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sanitasi</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->has_latrine ? 'Jamban Sehat' : 'Tidak Ada' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Ekonomi</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->economic_status ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Card Antropometri Terakhir --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[3rem] border border-emerald-100 p-10 shadow-sm relative overflow-hidden group md:col-span-2">
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
</div>
