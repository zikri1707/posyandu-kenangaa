{{-- Card Informasi Detail Balita (Unified Premium Card) --}}
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
            <span class="px-3 py-1.5 bg-teal-50 border border-teal-100 text-teal-600 rounded-xl text-xs font-black uppercase tracking-widest">
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

        {{-- Section 2: Keluarga & Lahir --}}
        <div>
            <h4 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[18px]">clinical_notes</span>
                Keluarga & Lahir
            </h4>
            <div class="space-y-4">
                {{-- Father --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Ayah</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->father_name ?? '-' }}</span>
                </div>
                {{-- Mother --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Nama Ibu</p>
                    <span class="text-base font-black text-slate-800">{{ $patient->mother_name ?? '-' }}</span>
                </div>
                {{-- BB Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">BB Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ isset($patient->weight_at_birth) ? $patient->weight_at_birth . ' kg' : '-' }}</span>
                </div>
                {{-- PB Lahir --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">PB Lahir</p>
                    <span class="text-base font-black text-slate-800">{{ isset($patient->height_at_birth) ? $patient->height_at_birth . ' cm' : '-' }}</span>
                </div>
                {{-- NIK Ibu --}}
                <div class="pb-3 border-b border-slate-100">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">NIK Ibu</p>
                    <span class="text-base font-black text-slate-800 font-mono">{{ $patient->mother_nik ?? '-' }}</span>
                </div>
                {{-- KIA Ownership --}}
                <div class="pb-3 last:border-b-0 last:pb-0">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest mb-1.5">Buku KIA</p>
                    <span @class([
                        'px-2.5 py-1 rounded-lg text-sm font-black uppercase tracking-wider border inline-block',
                        'bg-teal-50 text-teal-600 border-teal-100' => $patient->kia_book_ownership,
                        'bg-red-50 text-red-600 border-red-100' => !$patient->kia_book_ownership,
                    ])>
                        {{ $patient->kia_book_ownership ? 'Memiliki' : 'Tidak Memiliki' }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="border-slate-100">

        {{-- Section 3: Domisili & Kontak --}}
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
    </div>
</div>

@php
    $immunizationStatus = $patient->getImmunizationStatus();
    $totalCount = 0;
    $receivedCount = 0;
    foreach ($immunizationStatus as $group) {
        foreach ($group['vaccines'] as $vax) {
            $totalCount++;
            if ($vax['received']) {
                $receivedCount++;
            }
        }
    }
@endphp

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
        <div class="bg-indigo-50 border border-indigo-100 rounded-2xl px-5 py-2.5 flex flex-col items-end shadow-xs">
            <span class="text-[9px] font-black text-indigo-600 uppercase tracking-widest mb-0.5">Capaian Imunisasi</span>
            <span class="text-lg font-black text-slate-800">{{ $receivedCount }} <span class="text-xs font-bold text-slate-400">/ {{ $totalCount }}</span></span>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        @foreach($immunizationStatus as $group)
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
