{{-- Grid Informasi Spesifik (Bento Style) --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- Info Utama --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">clinical_notes</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Data Keluarga</h4>
        </div>
        
        <div class="space-y-6">
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ayah</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->father_name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Nama Ibu</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->mother_name ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Buku KIA</span>
                <span @class([
                    'px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest border',
                    'bg-teal-50 text-teal-600 border-teal-100' => $patient->kia_book_ownership,
                    'bg-red-50 text-red-500 border-red-100' => !$patient->kia_book_ownership,
                ])>
                    {{ $patient->kia_book_ownership ? 'Memiliki' : 'Tidak Ada' }}
                </span>
            </div>
        </div>
    </div>

    {{-- Info Lingkungan --}}
    <div class="bg-white rounded-[3rem] border border-slate-100 p-10 shadow-[0_8px_30px_rgb(0,0,0,0.02)]">
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[20px]">real_estate_agent</span>
            </div>
            <h4 class="text-sm font-black text-slate-800 uppercase tracking-widest">Sosial Ekonomi</h4>
        </div>
        
        <div class="space-y-6">
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Kondisi Rumah</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->house_condition ?? '-' }}</span>
            </div>
            <div class="flex justify-between items-center py-3 border-b border-slate-50">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Sanitasi</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->has_latrine ? 'Jamban Sehat' : 'Tidak Ada' }}</span>
            </div>
            <div class="flex justify-between items-center py-3">
                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Ekonomi</span>
                <span class="text-sm font-black text-slate-700">{{ $patient->economic_status ?? '-' }}</span>
            </div>
        </div>
    </div>

    {{-- Card Antropometri Terakhir --}}
    <div class="bg-gradient-to-br from-emerald-50 to-teal-50 rounded-[3rem] border border-emerald-100 p-10 shadow-sm relative overflow-hidden group">
        <div class="absolute -right-10 -bottom-10 w-32 h-32 bg-emerald-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
        <div class="flex items-center gap-4 mb-8">
            <div class="w-10 h-10 rounded-xl bg-white text-emerald-600 flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[20px]">monitor_weight</span>
            </div>
            <h4 class="text-sm font-black text-emerald-800 uppercase tracking-widest">Antropometri Terakhir</h4>
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

    {{-- Card Atensi Kesehatan --}}
    <div class="bg-gradient-to-br from-rose-50 to-pink-50 rounded-[3rem] border border-rose-100 p-10 shadow-sm relative overflow-hidden group">
        <div class="absolute -left-10 -top-10 w-32 h-32 bg-rose-500/5 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
        <div class="flex items-center gap-4 mb-6">
            <div class="w-10 h-10 rounded-xl bg-white text-rose-500 flex items-center justify-center shadow-sm">
                <span class="material-symbols-outlined text-[20px]">priority_high</span>
            </div>
            <h4 class="text-sm font-black text-rose-800 uppercase tracking-widest">Atensi Kesehatan</h4>
        </div>

        @php
            $ageMonths = $patient->age_in_months;
            $missingVaccines = $patient->getMissingVaccines();
            $currentMonth = now()->month;
            $isVitaminAMonth = in_array($currentMonth, [2, 8]);
            $hasVitaminAThisPeriod = $patient->medicalRecords()
                ->whereMonth('visit_date', $currentMonth)
                ->whereYear('visit_date', now()->year)
                ->where('vitamin_a', true)
                ->exists();
            
            $isEligibleVitaminA = $ageMonths >= 6 && $ageMonths <= 59;
        @endphp

        <div class="space-y-3">
            {{-- Vitamin A logic --}}
            @if($isEligibleVitaminA)
                @if($isVitaminAMonth && !$hasVitaminAThisPeriod)
                    <div class="flex items-center gap-3 p-3 bg-white/60 rounded-2xl border border-rose-100">
                        <div class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></div>
                        <p class="text-[11px] font-bold text-rose-900">Perlu vitamin A bulan ini</p>
                    </div>
                @elseif($hasVitaminAThisPeriod)
                    <div class="flex items-center gap-3 p-3 bg-white/60 rounded-2xl border border-emerald-100 opacity-80">
                        <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                        <p class="text-[11px] font-bold text-emerald-800">Vitamin A sudah diberikan</p>
                    </div>
                @else
                    <div class="flex items-center gap-3 p-3 bg-white/40 rounded-2xl border border-slate-100">
                        <div class="w-2 h-2 rounded-full bg-slate-300"></div>
                        <p class="text-[11px] font-bold text-slate-500 italic">Jadwal Vitamin A: Feb/Agu</p>
                    </div>
                @endif
            @endif

            {{-- Immunization logic --}}
            @if(!empty($missingVaccines))
                <div class="flex items-center gap-3 p-3 bg-white/60 rounded-2xl border border-amber-100">
                    <div class="w-2 h-2 rounded-full bg-amber-500"></div>
                    <div class="flex-1">
                        <p class="text-[10px] font-black text-amber-800 uppercase leading-none mb-1">Jadwal Imunisasi</p>
                        <p class="text-[11px] font-bold text-amber-900 leading-tight">Perlu: {{ $missingVaccines[0] }}</p>
                    </div>
                </div>
            @else
                <div class="flex items-center gap-3 p-3 bg-white/60 rounded-2xl border border-emerald-100 opacity-80">
                    <div class="w-2 h-2 rounded-full bg-emerald-500"></div>
                    <p class="text-[11px] font-bold text-slate-600">Imunisasi dasar lengkap</p>
                </div>
            @endif
            
            @if(empty($missingVaccines) && !$isEligibleVitaminA)
                <p class="text-[10px] text-center font-bold text-slate-400 py-4 uppercase tracking-widest italic">Tidak ada atensi mendesak</p>
            @endif
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
                'p-6 rounded-[2rem] border transition-all duration-300',
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

{{-- Growth Chart --}}
<div class="w-full mt-12 pb-16">
    @livewire('admin.patient-management.growth-chart', ['patient' => $patient, 'isEmbedded' => true])
</div>
