<div class="relative w-full lg:max-w-md" x-data="{ open: false }" @click.away="open = false">
    {{-- Search Input Group --}}
    <div class="relative group">
        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors pointer-events-none" style="font-size:12px;"></i>
        <input 
            wire:model.live.debounce.300ms="search"
            @focus="open = true"
            @keydown.slash.window.prevent="$el.focus()"
            type="text" 
            placeholder="Cari pasien, jadwal, atau artikel... [/]"
            class="w-full h-10 pl-9 pr-4 rounded-xl border border-slate-200 bg-slate-50 text-slate-700 placeholder-slate-400 text-[14px] font-bold focus:outline-none focus:ring-4 focus:ring-teal-500/10 focus:border-teal-400 focus:bg-white transition-all duration-300"
        >
        
        {{-- Loading Spinner --}}
        <div wire:loading class="absolute right-3 top-1/2 -translate-y-1/2">
            <svg class="animate-spin h-4 w-4 text-teal-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>

    {{-- Dropdown Results --}}
    <div 
        x-show="open && $wire.search.length >= 2"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-cloak
        class="absolute mt-2 w-full bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50"
    >
        @if(count($results['patients']) > 0 || count($results['schedules']) > 0 || count($results['articles']) > 0)
            <div class="max-h-[400px] overflow-y-auto divide-y divide-slate-50">
                {{-- Patients Section --}}
                @if(count($results['patients']) > 0)
                    <div class="p-2">
                        <div class="px-3 py-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Pasien</div>
                        @foreach($results['patients'] as $patient)
                            <a href="{{ route('admin.patients.show', $patient->id) }}" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-xl transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 font-bold text-xs group-hover:bg-teal-100 transition-colors">
                                    {{ substr($patient->name, 0, 1) }}
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-800">{{ $patient->name }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $patient->nik }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Schedules Section --}}
                @if(count($results['schedules']) > 0)
                    <div class="p-2">
                        <div class="px-3 py-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Jadwal</div>
                        @foreach($results['schedules'] as $schedule)
                            <a href="{{ route('admin.schedules.index') }}" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-xl transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600 group-hover:bg-blue-100 transition-colors">
                                    <i class="fas fa-calendar-alt text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-800">{{ $schedule->title }}</p>
                                    <p class="text-[11px] text-slate-500">{{ $schedule->location }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif

                {{-- Articles Section --}}
                @if(count($results['articles']) > 0)
                    <div class="p-2">
                        <div class="px-3 py-1.5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Artikel</div>
                        @foreach($results['articles'] as $article)
                            <a href="{{ route('admin.articles.index') }}" class="flex items-center gap-3 p-2 hover:bg-slate-50 rounded-xl transition-colors group">
                                <div class="w-8 h-8 rounded-lg bg-amber-50 flex items-center justify-center text-amber-600 group-hover:bg-amber-100 transition-colors">
                                    <i class="fas fa-newspaper text-xs"></i>
                                </div>
                                <div>
                                    <p class="text-[13px] font-bold text-slate-800 truncate max-w-[200px]">{{ $article->title }}</p>
                                    <p class="text-[11px] text-slate-500">Edukasi Kesehatan</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        @else
            <div class="p-8 text-center">
                <div class="w-12 h-12 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-search text-slate-300"></i>
                </div>
                <p class="text-[13px] font-bold text-slate-600">Tidak ada hasil ditemukan</p>
                <p class="text-[11px] text-slate-400 mt-1">Coba kata kunci lain atau periksa ejaan Anda</p>
            </div>
        @endif
    </div>
</div>
