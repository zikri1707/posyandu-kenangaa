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
            <div
                class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full">
            </div>

            <div class="flex flex-col gap-4">

                <div>
                    <h1
                        class="text-4xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">
                        Unit & Wilayah
                    </h1>
                    <p class="text-sm font-bold text-slate-900 mt-3">Kelola data unit posyandu dan wilayah binaan secara
                        terpusat.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            @can('create', App\Models\Posyandu::class)
                <a href="{{ route('admin.posyandu.create') }}"
                    class="h-16 px-8 bg-primary text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest flex items-center gap-3 hover:bg-primary/90 transition-all shadow-xl shadow-primary/20 group">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform">add_home_work</span>
                    Tambah Unit Baru
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Summary Bento & Highlight Banner ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">
        {{-- Stats Bento --}}
        <div class="lg:col-span-5 grid grid-cols-2 gap-4">
            <div
                class="bg-white rounded-[1.5rem] border border-slate-100 p-5 shadow-sm flex flex-col justify-between group hover:border-primary/20 transition-all">
                <div
                    class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-primary group-hover:text-white flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-[18px]">home_health</span>
                </div>
                <div class="mt-4">
                    <span class="text-[11px] font-black text-slate-900 uppercase tracking-widest block mb-1">Total
                        Unit</span>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $totalPosyandu }}</p>
                </div>
            </div>
            <div
                class="bg-white rounded-[1.5rem] border border-slate-100 p-5 shadow-sm flex flex-col justify-between group hover:border-indigo-500/20 transition-all">
                <div
                    class="w-9 h-9 rounded-xl bg-slate-50 text-slate-400 group-hover:bg-indigo-600 group-hover:text-white flex items-center justify-center transition-all">
                    <span class="material-symbols-outlined text-[18px]">location_city</span>
                </div>
                <div class="mt-4">
                    <span
                        class="text-[11px] font-black text-slate-900 uppercase tracking-widest block mb-1">Pedukuhan</span>
                    <p class="text-2xl font-black text-slate-900 tracking-tighter">{{ $totalPedukuhan }}</p>
                </div>
            </div>
            <div
                class="col-span-2 bg-emerald-500 rounded-[1.5rem] p-5 text-white shadow-xl shadow-emerald-100 flex flex-col justify-between relative overflow-hidden group">
                <div class="absolute -bottom-8 -left-8 w-28 h-28 bg-white/10 rounded-full blur-3xl"></div>
                <div class="relative z-10 flex items-center justify-between">
                    <div>
                        <span class="text-[11px] font-black text-emerald-50 uppercase tracking-widest block mb-1">Total
                            Warga Binaaan</span>
                        <p class="text-3xl font-black tracking-tighter">{{ number_format($totalWarga) }}</p>
                    </div>
                    <div
                        class="w-12 h-12 rounded-[1.25rem] bg-white/20 backdrop-blur-md flex items-center justify-center">
                        <span class="material-symbols-outlined text-[24px]">groups</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Hero Mesh Highlight Banner --}}
        <div class="lg:col-span-7">
            <div class="h-full rounded-[2rem] p-6 md:p-8 relative overflow-hidden text-white group shadow-2xl shadow-emerald-100"
                style="background-color: #064e3b; background-image: radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.5) 0px, transparent 50%), radial-gradient(at 50% 0%, hsla(168, 76%, 36%, 0.5) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.3) 0px, transparent 50%);">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5">
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-4">
                            <span
                                class="px-3 py-1 rounded-full bg-white/10 text-white text-[10px] font-black uppercase tracking-[0.2em] border border-white/10 backdrop-blur-md">Pusat
                                Informasi</span>
                        </div>
                        <h2 class="text-2xl md:text-3xl font-black leading-tight mb-3 tracking-tighter text-white">
                            Infrastruktur <br>Kesehatan Digital</h2>
                        <p class="text-[13px] text-white/70 font-medium max-w-md leading-relaxed">Pantau distribusi unit
                            posyandu dan wilayah binaan untuk pemerataan layanan kesehatan warga.</p>
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-40 h-40 bg-teal-500/20 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>

    {{-- ── Search Bar ── --}}
    <div class="bg-white rounded-[32px] border border-slate-100 p-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full group">
            <span
                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors pointer-events-none"
                style="font-variation-settings: 'wght' 300;">search</span>
            <input type="text" wire:model.live.debounce.300ms="search"
                placeholder="Cari nama posyandu, kode, atau alamat..."
                class="w-full h-12 pl-12 pr-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
        </div>
    </div>

    {{-- ── Data Table (Consistent with Patients) ── --}}
    <div class="bg-white border border-slate-100 rounded-[40px] overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-slate-50/50">
                        <th
                            class="px-8 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                            Detail Unit</th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                            Kode Unik</th>
                        <th
                            class="px-6 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                            Wilayah</th>
                        <th
                            class="px-8 py-5 text-[10px] font-black text-slate-900 uppercase tracking-widest text-center">
                            Tindakan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($posyandus as $posyandu)
                        @php
                            $initial = strtoupper(substr($posyandu->name, 0, 1));
                            $colors = ['teal', 'blue', 'amber', 'indigo', 'emerald', 'rose', 'violet', 'orange'];
                            $color = $colors[($posyandu->id - 1) % count($colors)];
                        @endphp
                        <tr class="group hover:bg-slate-50/30 transition-all" wire:key="posyandu-{{ $posyandu->id }}">
                            <td class="px-8 py-5">
                                <div class="flex items-center gap-4">
                                    @if ($posyandu->logo_photo)
                                        <img src="{{ asset('storage/' . $posyandu->logo_photo) }}"
                                            class="w-12 h-12 rounded-2xl object-cover flex-shrink-0 border border-slate-100">
                                    @else
                                        <div
                                            class="w-12 h-12 rounded-2xl bg-{{ $color }}-50 text-{{ $color }}-600 flex items-center justify-center font-black text-sm flex-shrink-0 border border-{{ $color }}-100/50">
                                            {{ $initial }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-black text-slate-800 text-[15px] leading-tight mb-1">
                                            {{ $posyandu->name }}</p>
                                        <p class="text-[11px] text-slate-400 font-bold italic truncate max-w-[200px]">
                                            "{{ $posyandu->address ?? 'Alamat belum disetel' }}"</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-5 text-center">
                                <span
                                    class="px-3 py-1 bg-slate-50 border border-slate-100 rounded-xl text-[11px] font-black text-teal-600 font-mono tracking-wider">
                                    {{ $posyandu->unique_code ?? '—' }}
                                </span>
                            </td>
                            <td class="px-6 py-5">
                                <div class="flex flex-col">
                                    <span
                                        class="text-sm font-bold text-slate-700">{{ $posyandu->pedukuhan?->name ?? '—' }}</span>
                                    <span
                                        class="text-[10px] text-slate-400 font-black uppercase tracking-tighter">Bekasi
                                        Timur</span>
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
                                    <span class="material-symbols-outlined text-[64px]"
                                        style="font-variation-settings: 'wght' 100;">home_health</span>
                                    <p class="text-sm font-black text-slate-500 uppercase tracking-widest">Tidak ada
                                        unit posyandu ditemukan</p>
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
