<div class="space-y-6">
    {{-- Admin Header (Replicated from admin-layout for reactivity) --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Data Warga Terdaftar</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data pasien posyandu di wilayah Anda</p>
        </div>
        
        <div class="flex flex-wrap gap-3 items-center">
            @can('create', App\Models\Patient::class)
            <div class="flex items-center gap-2">
                <x-button href="{{ route('admin.patients.import') }}" variant="outline" icon="publish">
                    Import Data
                </x-button>
                <x-button href="{{ route('admin.patients.create') }}" variant="secondary" icon="person_add">
                    Tambah Warga
                </x-button>
            </div>
            @endcan
        </div>
    </div>

    {{-- ── Summary Cards ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white border border-slate-200 rounded-3xl p-5 flex items-center gap-4 shadow-sm group hover:border-teal-500/30 transition-all">
            <div class="w-12 h-12 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[24px]">groups</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Total Warga</p>
                <p class="text-2xl font-black text-slate-900 leading-none">{{ number_format($patients->total()) }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-3xl p-5 flex items-center gap-4 shadow-sm group hover:border-pink-500/30 transition-all">
            <div class="w-12 h-12 bg-pink-50 text-pink-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[24px]">pregnant_woman</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Ibu Hamil</p>
                <p class="text-2xl font-black text-slate-900 leading-none">{{ App\Models\Patient::where('category', 'ibu_hamil')->count() }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-3xl p-5 flex items-center gap-4 shadow-sm group hover:border-amber-500/30 transition-all">
            <div class="w-12 h-12 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[24px]">child_care</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Balita</p>
                <p class="text-2xl font-black text-slate-900 leading-none">{{ App\Models\Patient::where('category', 'balita')->count() }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-3xl p-5 flex items-center gap-4 shadow-sm group hover:border-teal-500/30 transition-all">
            <div class="w-12 h-12 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center flex-shrink-0 group-hover:scale-110 transition-transform">
                <span class="material-symbols-outlined text-[24px]">face</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Remaja/Lansia</p>
                <p class="text-2xl font-black text-slate-900 leading-none">{{ App\Models\Patient::whereIn('category', ['remaja', 'lansia'])->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ── Search & Filter Bar ── --}}
    <section class="bg-white border border-slate-200 rounded-3xl p-6 shadow-sm">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Search Input (Livewire) --}}
            <div class="flex-1 min-w-[280px] relative group">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                       placeholder="Cari NIK atau nama..."
                       class="search-input-premium w-full">
                
                {{-- Loading Spinner --}}
                <div wire:loading wire:target="search" class="absolute right-12 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>

                @if($search)
                <button wire:click="$set('search', '')" class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </button>
                @endif
            </div>

            {{-- Category Filter --}}
            <div class="w-full sm:w-auto min-w-[200px]">
                <select wire:model.live="category"
                        class="w-full h-12 px-4 bg-slate-50 border border-slate-200 rounded-2xl text-sm font-bold text-slate-700 focus:outline-none focus:border-teal-500 focus:ring-4 focus:ring-teal-500/10 transition-all appearance-none cursor-pointer shadow-sm focus:bg-white">
                    <option value="all">Semua Kategori</option>
                    <option value="balita">Balita</option>
                    <option value="ibu_hamil">Ibu Hamil</option>
                    <option value="remaja">Remaja</option>
                    <option value="lansia">Lansia</option>
                </select>
            </div>

            @if($search || $category !== 'all')
            <button wire:click="$set('search', ''); $set('category', 'all');"
                    class="h-12 px-4 flex items-center gap-2 text-red-500 font-bold text-xs uppercase tracking-widest hover:bg-red-50 rounded-2xl transition-all">
                <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                Reset Filter
            </button>
            @endif
        </div>
    </section>

    {{-- Flash Messages --}}
    @if(session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- ── Data Table ── --}}
    <div class="bg-white border border-slate-200 rounded-3xl shadow-sm overflow-hidden">
        <x-table>
            <thead class="bg-slate-50/80 border-b border-slate-100">
                <tr>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] text-left">Detail Nama Warga</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] text-left">Kategori</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] text-left">Lokasi Posyandu</th>
                    <th class="px-6 py-4 text-[11px] font-black text-slate-500 uppercase tracking-[0.15em] text-right">Tindakan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($patients as $patient)
                @php
                    $initials = strtoupper(substr($patient->full_name, 0, 2));
                    $catStyles = [
                        'balita' => 'bg-amber-100 text-amber-700',
                        'ibu_hamil' => 'bg-pink-100 text-pink-700',
                        'remaja' => 'bg-indigo-100 text-indigo-700',
                        'lansia' => 'bg-orange-100 text-orange-700',
                    ];
                @endphp
                <tr class="group hover:bg-slate-50/50 transition-colors" wire:key="patient-{{ $patient->id }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            @if($patient->profile_photo)
                                <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="h-10 w-10 rounded-2xl object-cover border border-teal-100 flex-shrink-0">
                            @else
                                <div class="h-10 w-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center font-black text-xs border border-teal-100 flex-shrink-0">
                                    {{ $initials }}
                                </div>
                            @endif
                            <div>
                                <div class="font-black text-slate-900 text-[15px] leading-tight">{{ $patient->full_name }}</div>
                                <div class="text-[12px] text-slate-500 font-bold mt-1 tracking-tight">NIK: {{ $patient->id_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-wider {{ $catStyles[$patient->category] ?? 'bg-slate-100 text-slate-600' }}">
                            {{ str_replace('_', ' ', $patient->category) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="text-[13px] font-semibold text-slate-600">{{ $patient->posyandu->name ?? '—' }}</div>
                        <div class="text-[10px] text-slate-400 uppercase tracking-tighter">{{ $patient->age }}</div>
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-2.5">
                            {{-- View Detail --}}
                            <a href="{{ route('admin.patients.show', $patient->id) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-teal-500 hover:text-white hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-300"
                               title="Lihat Detail">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                            
                            {{-- Growth Chart (Balita Only) --}}
                            @if($patient->category === 'balita')
                            <a href="{{ route('admin.patients.growth-chart', $patient->id) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white hover:shadow-lg hover:shadow-blue-600/30 transition-all duration-300"
                               title="Grafik Pertumbuhan">
                                <span class="material-symbols-outlined text-[18px]">show_chart</span>
                            </a>
                            @endif

                            {{-- Edit Record --}}
                            @can('update', $patient)
                            <a href="{{ route('admin.patients.edit', $patient->id) }}" 
                               class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-500/30 transition-all duration-300"
                               title="Edit Data">
                                <span class="material-symbols-outlined text-[18px]">edit</span>
                            </a>
                            @endcan

                            {{-- Delete --}}
                            @can('delete', $patient)
                            <button wire:click="confirmDelete({{ $patient->id }})" 
                                    class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300"
                                    title="Hapus Data">
                                <span class="material-symbols-outlined text-[18px]">delete</span>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-24 text-center">
                        <div class="flex flex-col items-center gap-4 text-slate-300">
                            <span class="material-symbols-outlined text-[64px]">person_off</span>
                            <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada warga ditemukan</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </x-table>
    </div>

    {{-- ── Pagination ── --}}
    <div class="px-6 py-4 bg-white border-t border-slate-100">
        <x-layouts.ui.pagination :paginator="$patients" />
    </div>
</div>
