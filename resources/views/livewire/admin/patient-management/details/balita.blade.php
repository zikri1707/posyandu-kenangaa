{{-- Grid Informasi Detail Balita --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    {{-- 1. Identitas Pribadi --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 flex items-center justify-center">
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

    {{-- 2. Informasi Keluarga & Lahir --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[20px]">clinical_notes</span>
                </div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Informasi Keluarga & Lahir</h4>
            </div>
            
            <div class="space-y-4">
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ayah</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->father_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ibu</span>
                    <span class="text-sm font-black text-slate-700 text-right ml-4">{{ $patient->mother_name ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">BB Lahir (kg)</span>
                    <span class="text-sm font-black text-slate-700">{{ isset($patient->weight_at_birth) ? $patient->weight_at_birth . ' kg' : '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">PB Lahir (cm)</span>
                    <span class="text-sm font-black text-slate-700">{{ isset($patient->height_at_birth) ? $patient->height_at_birth . ' cm' : '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5 border-b border-slate-50">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">NIK Ibu</span>
                    <span class="text-sm font-black text-slate-700 font-mono">{{ $patient->mother_nik ?? '-' }}</span>
                </div>
                <div class="flex justify-between items-center py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kepemilikan Buku KIA</span>
                    <span @class([
                        'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border',
                        'bg-teal-50 text-teal-600 border-teal-100' => $patient->kia_book_ownership,
                        'bg-red-50 text-red-500 border-red-100' => !$patient->kia_book_ownership,
                    ])>
                        {{ $patient->kia_book_ownership ? 'Ya, Memiliki' : 'Tidak Memiliki' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. Domisili & Kontak --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.02)] flex flex-col justify-between">
        <div>
            <div class="flex items-center gap-4 mb-8">
                <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
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
                <div class="flex flex-col py-2.5">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Alamat Lengkap</span>
                    <p class="text-xs font-bold text-slate-600 bg-slate-50 p-4 rounded-2xl border border-slate-100 leading-relaxed">{{ $patient->address }}</p>
                </div>
            </div>
        </div>
    </div>

</div>

{{-- Kartu Imunisasi --}}
<div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)] transition-all duration-500 hover:shadow-[0_20px_50px_rgba(0,108,73,0.05)] mt-8">
    <div class="flex items-center justify-between mb-10">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[24px]">vaccines</span>
            </div>
            <div>
                <h3 class="text-sm font-black text-slate-800 uppercase tracking-[0.2em]">Kartu Imunisasi</h3>
                <p class="text-xs font-bold text-slate-400 mt-0.5">Status kelengkapan vaksinasi anak</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($patient->getImmunizationStatus() as $group)
            <div @class([
                'p-6 rounded-4xl border transition-all duration-300',
                'bg-slate-50 border-slate-100 opacity-60' => !$group['vaccines'][0]['is_due'],
                'bg-white border-slate-100 shadow-sm' => $group['vaccines'][0]['is_due']
            ])>
                <div class="flex items-center justify-between mb-4">
                    <span class="text-[11px] font-black text-indigo-600 bg-indigo-50 px-3 py-1 rounded-full uppercase tracking-widest">
                        {{ $group['label'] }}
                    </span>
                    @php
                        $allReceived = collect($group['vaccines'])->every('received', true);
                        $anyDue = collect($group['vaccines'])->contains('is_due', true);
                    @endphp
                    @if($allReceived)
                        <span class="material-symbols-outlined text-emerald-500">check_circle</span>
                    @elseif($anyDue)
                        <span class="material-symbols-outlined text-amber-400 animate-pulse">priority_high</span>
                    @endif
                </div>
                
                <div class="space-y-3">
                    @foreach($group['vaccines'] as $vax)
                        <div class="flex items-center justify-between p-3 rounded-xl {{ $vax['received'] ? 'bg-emerald-50/50' : 'bg-slate-100/30' }}">
                            <div class="flex flex-col">
                                <span class="text-[11px] font-black text-slate-700">{{ $vax['name'] }}</span>
                                <span class="text-[9px] font-bold text-slate-400">{{ $vax['prevent'] }}</span>
                            </div>
                            @if($vax['received'])
                                <div class="w-6 h-6 rounded-lg bg-emerald-500 text-white flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[14px]">done</span>
                                </div>
                            @else
                                <div class="w-6 h-6 rounded-lg bg-slate-200 text-slate-400 flex items-center justify-center">
                                    <span class="material-symbols-outlined text-[14px]">close</span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </div>
</div>
