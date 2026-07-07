<div class="space-y-8 p-6 md:p-8 pt-2 md:pt-4">
    {{-- Header Section --}}
    <div class="flex flex-col md:flex-row md:items-start justify-between gap-6">
        <div class="relative pl-6">
            <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full"></div>
            <div class="flex flex-col gap-4">
                <div>
                    <h1 class="text-4xl font-extrabold tracking-tight leading-none text-slate-900">
                        Unit Posyandu
                    </h1>
                    <p class="text-xs font-semibold text-slate-500 mt-2.5">Kelola data unit posyandu dan distribusi warga per kategori secara terpusat.</p>
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

    {{-- ── Summary Stats (5 cards) ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-4">

        {{-- Total Unit --}}
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-teal-500/20 hover:-translate-y-1 transition-all duration-300">
            <div class="w-10 h-10 rounded-xl bg-teal-50 text-teal-600 group-hover:bg-teal-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                <span class="material-symbols-outlined text-[18px]">home_health</span>
            </div>
            <div class="mt-4">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total Unit</span>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $totalPosyandu }}</p>
            </div>
        </div>

        {{-- Total Balita --}}
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-violet-500/20 hover:-translate-y-1 transition-all duration-300">
            <div class="w-10 h-10 rounded-xl bg-violet-50 text-violet-600 group-hover:bg-violet-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                <span class="material-symbols-outlined text-[18px]">child_care</span>
            </div>
            <div class="mt-4">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Balita</span>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalBalita) }}</p>
            </div>
        </div>

        {{-- Ibu Hamil --}}
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-rose-500/20 hover:-translate-y-1 transition-all duration-300">
            <div class="w-10 h-10 rounded-xl bg-rose-50 text-rose-600 group-hover:bg-rose-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                <span class="material-symbols-outlined text-[18px]">pregnant_woman</span>
            </div>
            <div class="mt-4">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Ibu Hamil</span>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalBumil) }}</p>
            </div>
        </div>

        {{-- Lansia (putih) --}}
        <div class="relative overflow-hidden bg-white rounded-[2rem] border border-slate-200/80 p-5 shadow-xs flex flex-col justify-between group hover:shadow-md hover:border-emerald-500/20 hover:-translate-y-1 transition-all duration-300">
            <div class="w-10 h-10 rounded-xl bg-emerald-50 text-emerald-600 group-hover:bg-emerald-600 group-hover:text-white group-hover:scale-105 group-hover:shadow-sm flex items-center justify-center transition-all duration-300">
                <span class="material-symbols-outlined text-[18px]">elderly</span>
            </div>
            <div class="mt-4">
                <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest block mb-1">Lansia</span>
                <p class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalLansia) }}</p>
            </div>
        </div>

        {{-- Total Warga (hijau gradient) --}}
        <div class="relative overflow-hidden bg-gradient-to-br from-emerald-600 to-teal-500 rounded-[2rem] p-5 text-white shadow-md border border-white/10 group hover:-translate-y-1 transition-all duration-300">
            <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-white/10 rounded-full blur-3xl"></div>
            <div class="relative z-10 w-10 h-10 rounded-xl bg-white/15 backdrop-blur-md flex items-center justify-center border border-white/10">
                <span class="material-symbols-outlined text-[18px] text-white">groups</span>
            </div>
            <div class="relative z-10 mt-4">
                <span class="text-[9px] font-black text-emerald-50/80 uppercase tracking-widest block mb-1">Total Warga</span>
                <p class="text-3xl font-black tracking-tight">{{ number_format($totalWarga) }}</p>
                <span class="text-[9px] text-emerald-50/60 font-bold uppercase tracking-widest mt-1 block">semua kategori</span>
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

    {{-- ── Data Table ── --}}
    <div class="bg-white border border-slate-200/80 rounded-[2.5rem] overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/75 border-b border-slate-100">
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                            Unit Posyandu
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-center">
                            Kode Unik
                        </th>
                        <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-left">
                            Distribusi Warga
                        </th>
                        <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest text-right">
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
                                'teal'    => ['bg' => '#f0fdfa', 'text' => '#0f766e', 'border' => '#ccfbf1'],
                                'blue'    => ['bg' => '#eff6ff', 'text' => '#1d4ed8', 'border' => '#dbeafe'],
                                'amber'   => ['bg' => '#fffbeb', 'text' => '#b45309', 'border' => '#fef3c7'],
                                'indigo'  => ['bg' => '#eef2ff', 'text' => '#4338ca', 'border' => '#e0e7ff'],
                                'emerald' => ['bg' => '#ecfdf5', 'text' => '#047857', 'border' => '#d1fae5'],
                                'rose'    => ['bg' => '#fff1f2', 'text' => '#be123c', 'border' => '#ffe4e6'],
                                'violet'  => ['bg' => '#f5f3ff', 'text' => '#6d28d9', 'border' => '#ede9fe'],
                                'orange'  => ['bg' => '#fff7ed', 'text' => '#c2410c', 'border' => '#ffedd5'],
                            ];
                            $selectedColor = $colorMap[$color] ?? $colorMap['teal'];
                            $total = $posyandu->patients_count ?? 0;
                        @endphp
                        <tr class="group hover:bg-slate-50/50 transition-colors duration-200" wire:key="posyandu-{{ $posyandu->id }}">
                            {{-- Unit Info --}}
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

                            {{-- Kode Unik --}}
                            <td class="px-6 py-5 text-center">
                                <span class="px-2.5 py-1 bg-teal-50 border border-teal-100/60 rounded-full text-[11px] font-extrabold text-teal-700 font-mono tracking-wider">
                                    {{ $posyandu->unique_code ?? '—' }}
                                </span>
                            </td>

                            {{-- Distribusi Warga per Kategori --}}
                            <td class="px-6 py-5">
                                <div class="flex flex-col gap-2">
                                    {{-- Total badge --}}
                                    <div class="flex items-center gap-1.5">
                                        <span class="text-[11px] font-black text-slate-600">{{ number_format($total) }}</span>
                                        <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">total warga</span>
                                    </div>
                                    {{-- Category pills --}}
                                    <div class="flex flex-wrap gap-1.5">
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-violet-50 text-violet-700 border border-violet-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-violet-500 shrink-0"></span>
                                            {{ number_format($posyandu->balita_count ?? 0) }} Balita
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-rose-50 text-rose-700 border border-rose-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500 shrink-0"></span>
                                            {{ number_format($posyandu->ibu_hamil_count ?? 0) }} Bumil
                                        </span>
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-700 border border-emerald-100">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 shrink-0"></span>
                                            {{ number_format($posyandu->lansia_count ?? 0) }} Lansia
                                        </span>
                                    </div>
                                </div>
                            </td>

                            {{-- Tindakan --}}
                            <td class="px-8 py-5 text-right">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.posyandu.show', $posyandu->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-2xl bg-slate-50 border border-slate-200/30 text-slate-500 hover:bg-teal-600 hover:text-white hover:border-teal-600 hover:shadow-md hover:shadow-teal-600/10 transition-all duration-200"
                                        title="Lihat Detail">
                                        <span class="material-symbols-outlined text-[18px]">visibility</span>
                                    </a>
                                    <a href="{{ route('admin.posyandu.edit', $posyandu->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-2xl bg-slate-50 border border-slate-200/30 text-slate-500 hover:bg-amber-600 hover:text-white hover:border-amber-600 hover:shadow-md hover:shadow-amber-600/10 transition-all duration-200"
                                        title="Edit Data">
                                        <span class="material-symbols-outlined text-[18px]">edit</span>
                                    </a>
                                    <button wire:click="confirmDelete({{ $posyandu->id }})"
                                        class="w-9 h-9 min-w-0 min-h-0 flex items-center justify-center rounded-2xl bg-slate-50 border border-slate-200/30 text-slate-500 hover:bg-rose-600 hover:text-white hover:border-rose-600 hover:shadow-md hover:shadow-rose-600/10 transition-all duration-200 cursor-pointer"
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

    {{-- ── Delete Confirmation Modal ── --}}
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
