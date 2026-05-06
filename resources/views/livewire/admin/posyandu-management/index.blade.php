<div class="max-w-7xl mx-auto space-y-8 pb-20">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Posyandu</h2>
            <p class="text-sm text-slate-400 font-medium mt-1">Kelola data unit posyandu dan wilayah binaan.</p>
        </div>
        <x-button href="{{ route('admin.posyandu.create') }}" variant="secondary" icon="add_home_work">Tambah Unit</x-button>
    </div>

    {{-- ── Summary Cards (Minimalist) ── --}}
    @php
        $totalPosyandu  = $posyandus->total();
        $totalPedukuhan = \App\Models\Pedukuhan::count();
        $totalWarga     = \App\Models\Patient::count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white border border-slate-100 rounded-[32px] p-7 flex items-center gap-5 transition-all">
            <div class="w-14 h-14 bg-teal-50 text-teal-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">home_health</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Total Posyandu</p>
                <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalPosyandu }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-100 rounded-[32px] p-7 flex items-center gap-5 transition-all">
            <div class="w-14 h-14 bg-blue-50 text-blue-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">location_city</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Pedukuhan</p>
                <p class="text-3xl font-black text-slate-900 leading-none">{{ $totalPedukuhan }}</p>
            </div>
        </div>
        <div class="bg-white border border-slate-100 rounded-[32px] p-7 flex items-center gap-5 transition-all">
            <div class="w-14 h-14 bg-amber-50 text-amber-600 rounded-2xl flex items-center justify-center flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">groups</span>
            </div>
            <div>
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-2">Total Warga</p>
                <p class="text-3xl font-black text-slate-900 leading-none">{{ number_format($totalWarga) }}</p>
            </div>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="bg-white rounded-[32px] border border-slate-100 p-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors pointer-events-none" style="font-variation-settings: 'wght' 300;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari nama posyandu, kode, atau alamat..." 
                class="w-full h-12 pl-12 pr-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
        </div>
    </div>

    {{-- ── Data Table (Consistent with Patients) ── --}}
    <div class="bg-white border border-slate-100 rounded-[40px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Detail Unit</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">Kode Unik</th>
                        <th class="px-6 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest">Wilayah</th>
                        <th class="px-8 py-5 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($posyandus as $posyandu)
                    @php
                        $initial = strtoupper(substr($posyandu->name, 0, 1));
                        $colors  = ['teal','blue','amber','indigo','emerald','rose','violet','orange'];
                        $color   = $colors[($posyandu->id - 1) % count($colors)];
                    @endphp
                    <tr class="group hover:bg-slate-50/30 transition-all" wire:key="posyandu-{{ $posyandu->id }}">
                        <td class="px-8 py-5">
                            <div class="flex items-center gap-4">
                                @if($posyandu->logo_photo)
                                    <img src="{{ asset('storage/' . $posyandu->logo_photo) }}" class="w-12 h-12 rounded-2xl object-cover flex-shrink-0 border border-slate-100">
                                @else
                                    <div class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 text-{{ $color }}-600 flex items-center justify-center font-black text-sm flex-shrink-0 border border-{{ $color }}-100/50">
                                        {{ $initial }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-black text-slate-800 text-[15px] leading-tight mb-1">{{ $posyandu->name }}</p>
                                    <p class="text-[11px] text-slate-400 font-bold italic truncate max-w-[200px]">"{{ $posyandu->address ?? 'Alamat belum disetel' }}"</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 text-center">
                            <span class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-black text-teal-600 font-mono tracking-wider">
                                {{ $posyandu->unique_code ?? '—' }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col">
                                <span class="text-sm font-bold text-slate-700">{{ $posyandu->pedukuhan?->name ?? '—' }}</span>
                                <span class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Bekasi Timur</span>
                            </div>
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="flex items-center justify-end gap-2.5">
                                {{-- View --}}
                                <a href="{{ route('admin.posyandu.show', $posyandu->id) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-teal-500 hover:text-white hover:shadow-lg hover:shadow-teal-500/30 transition-all duration-300"
                                   title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                
                                {{-- Edit --}}
                                <a href="{{ route('admin.posyandu.edit', $posyandu->id) }}" 
                                   class="w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 text-slate-500 hover:bg-amber-500 hover:text-white hover:shadow-lg hover:shadow-amber-500/30 transition-all duration-300"
                                   title="Edit Data">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>

                                {{-- Delete (EXACTLY like patients) --}}
                                <button wire:click="confirmDelete({{ $posyandu->id }})" 
                                        class="w-9 h-9 flex items-center justify-center rounded-xl bg-red-50 text-red-400 hover:bg-red-500 hover:text-white hover:shadow-lg hover:shadow-red-500/30 transition-all duration-300"
                                        title="Hapus Unit">
                                    <span class="material-symbols-outlined text-[18px]">delete</span>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4 text-slate-300">
                                <span class="material-symbols-outlined text-[64px]" style="font-variation-settings: 'wght' 100;">home_health</span>
                                <p class="text-sm font-black text-slate-500 uppercase tracking-widest">Tidak ada unit posyandu ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{-- Pagination --}}
        <div class="px-8 py-5 bg-white border-t border-slate-50 flex items-center justify-between">
            <div class="text-[11px] font-black text-slate-400 uppercase tracking-widest">
                {{ $posyandus->total() }} Total Unit
            </div>
            {{ $posyandus->links() }}
        </div>
    </div>

    {{-- ── Delete Confirmation Modal (EXACTLY like patients) ── --}}
    <div x-data="{ open: @entangle('showDeleteModal') }">
        <x-modals.confirm-modal
            title="Hapus Unit Posyandu"
            message="Apakah Anda yakin ingin menghapus unit posyandu ini?"
            sub-message="Tindakan ini permanen. Sistem akan memvalidasi data terkait sebelum menghapus."
            type="danger"
            confirm-text="Ya, Hapus Unit"
        >
            <x-slot:footer>
                <x-button @click="open = false" variant="outline">Batal</x-button>
                <x-button wire:click="deletePosyandu" variant="danger" icon="delete">Ya, Hapus</x-button>
            </x-slot:footer>
        </x-modals.confirm-modal>
    </div>
</div>
