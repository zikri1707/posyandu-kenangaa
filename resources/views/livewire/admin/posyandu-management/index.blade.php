<div class="space-y-8 p-6 md:p-8 pt-2 md:pt-4">
    @php
        $totalPosyandu = $posyandus->total();
        $totalPedukuhan = \App\Models\Pedukuhan::count();
        $totalWarga = \App\Models\Patient::count();
    @endphp
    
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
        <div class="relative pl-6">
            {{-- Vertical Bar --}}
            <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full"></div>

            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight leading-none text-slate-900">
                        Unit &amp; Wilayah
                    </h1>
                    <p class="text-xs font-semibold text-slate-500 mt-2.5">Kelola data unit posyandu dan wilayah binaan secara terpusat.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            @can('create', App\Models\Posyandu::class)
                <a href="{{ route('admin.posyandu.create') }}"
                    class="h-13 px-6 bg-teal-600 text-white rounded-[1.5rem] font-bold text-xs uppercase tracking-widest flex items-center gap-2.5 hover:bg-teal-700 transition-all duration-300 shadow-md shadow-teal-600/10 group">
                    <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform duration-300">add_home_work</span>
                    Tambah Unit Baru
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Summary Bento & Highlight Banner ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        {{-- Stats Bento --}}
        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
            <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-teal-500/20 hover:-translate-y-1 transition-all duration-300">
                <div class="w-11 h-11 rounded-[0.875rem] bg-slate-50 text-slate-400 group-hover:bg-teal-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">home_health</span>
                </div>
                <div class="mt-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Unit</span>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalPosyandu }}</p>
                </div>
            </div>
            
            <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-indigo-500/20 hover:-translate-y-1 transition-all duration-300">
                <div class="w-11 h-11 rounded-[0.875rem] bg-slate-50 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                    <span class="material-symbols-outlined text-[20px]">location_city</span>
                </div>
                <div class="mt-4">
                    <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Pedukuhan</span>
                    <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalPedukuhan }}</p>
                </div>
            </div>
            
            <div class="col-span-2 relative overflow-hidden bg-gradient-to-br from-emerald-600 to-teal-500 rounded-[2rem] p-5 text-white shadow-md flex flex-col justify-between border border-white/10 group hover:-translate-y-1 transition-all duration-300">
                <div class="absolute -bottom-8 -left-8 w-28 h-28 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <span class="text-[10px] font-black text-emerald-50/80 uppercase tracking-widest block mb-1">Total Warga Binaan</span>
                        <p class="text-3xl font-black tracking-tight">{{ number_format($totalWarga) }}</p>
                    </div>
                    <div class="w-12 h-12 rounded-[1rem] bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/10 shadow-xs">
                        <span class="material-symbols-outlined text-[22px] text-white">groups</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero Mesh Highlight Banner --}}
        <div class="lg:col-span-7">
            <div class="h-full rounded-[2.5rem] p-6 md:p-8 relative overflow-hidden text-white group shadow-md shadow-teal-900/5 border border-teal-900/10"
                style="background-color: #0f766e; background-image: radial-gradient(at 0% 0%, hsla(168, 76%, 36%, 0.45) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.25) 0px, transparent 50%);">
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-5">
                            <span class="px-3 py-1 rounded-full bg-white/10 text-teal-50 text-[9px] font-black uppercase tracking-[0.2em] border border-white/10 backdrop-blur-md">Pusat Informasi</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-extrabold leading-tight mb-3.5 tracking-tight text-white">
                            Infrastruktur<br>Kesehatan Digital
                        </h2>
                        <p class="text-[13px] text-teal-50/85 font-semibold max-w-md leading-relaxed">
                            Pantau distribusi unit posyandu dan wilayah binaan untuk pemerataan layanan kesehatan warga secara terpadu.
                        </p>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-40 h-40 bg-teal-400/15 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="bg-white rounded-[2rem] border border-slate-200/80 p-5 flex flex-col md:flex-row gap-4 items-center shadow-xs">
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none"
                style="font-variation-settings: 'wght' 300;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari nama posyandu, kode, atau alamat..."
                class="w-full h-12 pl-12 pr-4 bg-slate-50 border border-slate-200 rounded-[1.5rem] text-sm font-semibold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-4 focus:ring-teal-50 focus:border-teal-600 transition-all shadow-xs">
        </div>
    </div>

    {{-- ── Data Table (Consistent with Patients) ── --}}
    <div class="bg-white border border-slate-200/80 rounded-[2.5rem] overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100">
                        <th class="px-8 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                            Detail Unit
                        </th>
                        <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Kode Unik
                        </th>
                        <th class="px-6 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                            Wilayah
                        </th>
                        <th class="px-8 py-4.5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
                            Tindakan
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($posyandus as $posyandu)
                        @php
                            $initial = strtoupper(substr($posyandu->name, 0, 1));
                            $colors = ['teal', 'blue', 'amber', 'indigo', 'emerald', 'rose', 'violet', 'orange'];
                            $color = $colors[($posyandu->id - 1) % count($colors)];
                            $colorMap = [
                                'teal' => ['bg' => '#f0fdfa', 'text' => '#0f766e', 'border' => '#ccfbf1'],
                                'blue' => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#dbeafe'],
                                'amber' => ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fef3c7'],
                                'indigo' => ['bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'],
                                'emerald' => ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#d1fae5'],
                                'rose' => ['bg' => '#fff1f2', 'text' => '#be123c', 'border' => '#ffe4e6'],
                                'violet' => ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'border' => '#ede9fe'],
                                'orange' => ['bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#ffedd5'],
                            ];
                            $selectedColor = $colorMap[$color] ?? $colorMap['teal'];
                        @endphp
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-200" wire:key="posyandu-{{ $posyandu->id }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    @if ($posyandu->logo_photo)
                                        <img src="{{ asset('storage/' . $posyandu->logo_photo) }}"
                                            class="w-12 h-12 rounded-[1rem] object-cover flex-shrink-0 border border-slate-200/60 shadow-xs">
                                    @else
                                        <div class="w-12 h-12 rounded-[1rem] flex items-center justify-center font-black text-sm flex-shrink-0 border shadow-xs"
                                            style="background-color: {{ $selectedColor['bg'] }}; color: {{ $selectedColor['text'] }}; border-color: {{ $selectedColor['border'] }};">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-bold text-slate-900 text-[15px] leading-tight mb-1">
                                            {{ $posyandu->name }}
                                        </p>
                                        <p class="text-xs text-slate-400 font-semibold italic truncate max-w-[200px]" title="{{ $posyandu->address }}">
                                            "{{ $posyandu->address ?? 'Alamat belum disetel' }}"
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span class="px-2.5 py-1 bg-teal-50 border border-teal-100/60 rounded-full text-[11px] font-extrabold text-teal-700 font-mono tracking-wider">
                                    {{ $posyandu->unique_code ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span class="text-sm font-bold text-slate-700">{{ $posyandu->pedukuhan?->name ?? '—' }}</span>
                                    <span class="text-[10px] text-slate-400 font-black uppercase tracking-wider mt-0.5">Bekasi Timur</span>
                                </div>
                            </td>
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    {{-- View --}}
                                    <a href="{{ route('admin.posyandu.show', $posyandu->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-2xl bg-slate-50 border border-slate-200/30 text-slate-500 hover:bg-teal-600 hover:text-white hover:border-teal-600 hover:shadow-md hover:shadow-teal-600/10 transition-all duration-200"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
 
                                    {{-- Edit --}}
                                    <a href="{{ route('admin.posyandu.edit', $posyandu->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-2xl bg-slate-50 border border-slate-200/30 text-slate-500 hover:bg-amber-600 hover:text-white hover:border-amber-600 hover:shadow-md hover:shadow-amber-600/10 transition-all duration-200"
                                        title="Edit Data">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
 
                                    {{-- Delete (EXACTLY like patients) --}}
                                    <button wire:click="confirmDelete({{ $posyandu->id }})"
                                        class="w-9 h-9 flex items-center justify-center rounded-2xl bg-rose-50 border border-rose-100/50 text-rose-500 hover:bg-rose-600 hover:text-white hover:border-rose-600 hover:shadow-md hover:shadow-rose-600/10 transition-all duration-200"
                                        title="Hapus Unit">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center gap-4 text-slate-350">
                                    <span class="material-symbols-outlined text-[64px]"
                                        style="font-variation-settings: 'wght' 100;">home_health</span>
                                    <p class="text-xs font-black text-slate-400 uppercase tracking-widest">Tidak ada unit posyandu ditemukan</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- Pagination --}}
        <div class="px-8 py-5 bg-white border-t border-slate-100 flex items-center justify-between">
            <div class="text-[10px] font-black text-slate-455 uppercase tracking-widest">
                {{ $posyandus->total() }} Total Unit
            </div>
            {{ $posyandus->links() }}
        </div>
    </div>

    {{-- ── Delete Confirmation Modal (EXACTLY like patients) ── --}}
    <div x-data="{ open: @entangle('showDeleteModal') }">
        <x-modals.confirm-modal title="Hapus Unit Posyandu"
            message="Apakah Anda yakin ingin menghapus unit posyandu ini?"
            sub-message="Tindakan ini permanen. Sistem akan memvalidasi data terkait sebelum menghapus." type="danger"
            confirm-text="Ya, Hapus Unit">
            <x-slot:footer>
                <x-button @click="open = false" variant="outline">Batal</x-button>
                <x-button wire:click="deletePosyandu" variant="danger" icon="delete">Ya, Hapus</x-button>
            </x-slot:footer>
        </x-modals.confirm-modal>
    </div>
</div>
