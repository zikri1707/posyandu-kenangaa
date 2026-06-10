<div class="space-y-6" wire:key="medical-records-root">
    {{-- Header Section (Replicated style) --}}
    <div class="relative mb-10">
        {{-- Decorative Background Element --}}
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>
        
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative">
            <div class="space-y-2">

                {{-- Title & Subtitle with Accent --}}
                <div class="flex items-start gap-4">
                    <div class="w-1.5 h-12 bg-gradient-to-b from-teal-500 to-emerald-400 rounded-full mt-1 hidden sm:block"></div>
                    <div>
                        <h1 class="text-3xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">
                            Manajemen Rekam Medis
                        </h1>
                        <p class="text-sm font-bold text-slate-900 mt-2 flex items-center gap-2">
                            Kelola data kunjungan dan rekam kesehatan warga secara sistematis.
                        </p>
                    </div>
                </div>
            </div>
            
            {{-- Action Buttons with Better Styling --}}
            <div class="flex flex-wrap gap-3 items-center justify-end flex-1">
                @can('create', App\Models\MedicalRecord::class)
                <a href="{{ route('admin.medical-records.bulk') }}" 
                   class="flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white border border-slate-100 text-xs font-black uppercase tracking-widest text-slate-600 hover:text-teal-600 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-500/5 transition-all group/btn">
                    <span class="material-symbols-outlined text-[20px] text-slate-400 group-hover/btn:text-teal-500 transition-colors">assignment_turned_in</span>
                    Bulan Penimbangan
                </a>
                @endcan
                
                @can('create', App\Models\MedicalRecord::class)
                <a href="{{ route('admin.medical-records.create') }}" 
                   class="flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-500 text-white text-xs font-black uppercase tracking-widest shadow-xl shadow-teal-200 hover:shadow-teal-300 hover:-translate-y-0.5 transition-all group/add">
                    <span class="material-symbols-outlined text-[20px] group-hover/add:rotate-90 transition-transform duration-500">add_circle</span>
                    Tambah Rekam Medis
                </a>
                @endcan
            </div>
        </div>
    </div>

    {{-- ── Search & Filter Bento ── --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 p-4 shadow-sm flex flex-col gap-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-3 flex-1">
                {{-- Search Input --}}
                <div class="relative min-w-[300px] flex-1 group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-500 transition-colors pointer-events-none text-[20px]">search</span>
                    <input type="text" wire:model.live.debounce.150ms="search" 
                           placeholder="Cari nama warga atau NIK..."
                           class="search-input-premium w-full">
                </div>

                {{-- Posyandu Filter --}}
                @if(auth()->user()->isSuperAdmin())
                <div class="w-48">
                    <x-forms.select-input wire:model.live="posyandu_id" placeholder="Seluruh Unit" :placeholderDisabled="false" value="{{ $posyandu_id }}" class="!h-12 !rounded-2xl !bg-slate-50/50 !border-slate-100 !text-xs !font-black !uppercase !tracking-widest !text-slate-700 focus:!border-primary pr-10">
                        @foreach(\App\Models\Posyandu::all() as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </x-forms.select-input>
                </div>
                @endif
            </div>
            
            @if($search || $posyandu_id)
                <button wire:click="$set('search', ''); $set('posyandu_id', '');"
                        class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em] hover:text-red-600 transition-colors px-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                    Reset Filter
                </button>
            @endif
        </div>

        {{-- Sort Options Row --}}
        <div class="flex items-center gap-2 flex-wrap pb-2 border-t border-slate-50 pt-4">
            <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mr-2">Urutkan:</span>
            
            {{-- Sort by Patient Name --}}
            <div class="flex gap-1">
                <button wire:click="$set('sortBy', 'patient_name_asc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'patient_name_asc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'patient_name_asc'])
                        title="Nama A-Z">
                    <span class="material-symbols-outlined text-[12px]">sort_by_alpha</span>
                </button>
                <button wire:click="$set('sortBy', 'patient_name_desc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'patient_name_desc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'patient_name_desc'])
                        title="Nama Z-A">
                    <span class="material-symbols-outlined text-[12px]">sort_by_alpha</span><span class="text-[8px] ml-0.5">↓</span>
                </button>
            </div>

            {{-- Sort by Visit Date --}}
            <div class="flex gap-1">
                <button wire:click="$set('sortBy', 'visit_date_asc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'visit_date_asc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'visit_date_asc'])
                        title="Tanggal Lama - Baru">
                    <span class="material-symbols-outlined text-[12px]">calendar_month</span>
                </button>
                <button wire:click="$set('sortBy', 'visit_date_desc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'visit_date_desc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'visit_date_desc'])
                        title="Tanggal Baru - Lama">
                    <span class="material-symbols-outlined text-[12px]">calendar_month</span><span class="text-[8px] ml-0.5">↓</span>
                </button>
            </div>

            {{-- Sort by Updated Date --}}
            <div class="flex gap-1">
                <button wire:click="$set('sortBy', 'updated_at_asc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'updated_at_asc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'updated_at_asc'])
                        title="Edit Lama - Baru">
                    <span class="material-symbols-outlined text-[12px]">update</span>
                </button>
                <button wire:click="$set('sortBy', 'updated_at_desc')"
                        @class(['px-3 py-1.5 rounded-lg text-[9px] font-black uppercase tracking-widest transition-all', 
                                'bg-teal-100 text-teal-600 ring-1 ring-teal-200' => $sortBy === 'updated_at_desc',
                                'bg-slate-50 text-slate-600 hover:bg-slate-100' => $sortBy !== 'updated_at_desc'])
                        title="Edit Baru - Lama">
                    <span class="material-symbols-outlined text-[12px]">update</span><span class="text-[8px] ml-0.5">↓</span>
                </button>
            </div>
        </div>
    </div>

    {{-- ── Data Table ── --}}
    <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-900 uppercase tracking-widest">Waktu Kunjungan</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-900 uppercase tracking-widest">Pasien</th>
                        <th class="px-8 py-5 text-left text-[10px] font-black text-slate-900 uppercase tracking-widest">Antropometri</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-widest">Petugas</th>
                        <th class="px-8 py-5 text-center text-[10px] font-black text-slate-900 uppercase tracking-widest">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($medicalRecords as $record)
                    <tr class="group hover:bg-teal-50/30 transition-all duration-300" wire:key="record-{{ $record->id }}">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="font-black text-slate-900">
                                    {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : '-' }}
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Visit ID: #{{ $record->id }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-sm border border-teal-100 group-hover:bg-teal-600 group-hover:text-white transition-all duration-500">
                                    {{ strtoupper(substr($record->patient->full_name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="font-black text-slate-900">{{ $record->patient->full_name ?? 'Tidak Diketahui' }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest bg-teal-50 px-2 py-0.5 rounded-md">
                                            {{ $record->patient->category ?? '-' }}
                                        </span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Age: {{ $record->patient->age ?? '?' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Weight</span>
                                    <span class="text-sm font-black text-slate-800">{{ $record->weight ?? '-' }} <small class="text-slate-400 font-bold ml-0.5">kg</small></span>
                                </div>
                                <div class="w-px h-8 bg-slate-100"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Height</span>
                                    <span class="text-sm font-black text-slate-800">{{ $record->height ?? '-' }} <small class="text-slate-400 font-bold ml-0.5">cm</small></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="font-bold text-slate-700">{{ $record->user->name ?? '-' }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Kader</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-1 group-hover:translate-y-0">
                                <a href="{{ route('admin.medical-records.show', $record->id) }}" 
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-teal-600 hover:border-teal-200 hover:shadow-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                @can('update', $record)
                                <a href="{{ route('admin.medical-records.edit', $record->id) }}" 
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                @endcan
                                
                                @can('delete', $record)
                                <form action="{{ route('admin.medical-records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-200 hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <span class="material-symbols-outlined text-[48px]">medical_information</span>
                                </div>
                                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Tidak ada rekam medis ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if($medicalRecords->hasPages())
        <div class="px-8 py-6 bg-slate-50 border-t border-slate-100">
            {{ $medicalRecords->links() }}
        </div>
        @endif
    </div>
</div>