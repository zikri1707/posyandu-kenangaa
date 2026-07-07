<div class="space-y-6">
    {{-- Admin Header (Replicated from admin-layout for reactivity) --}}
    {{-- Premium Header Section --}}
    <div class="relative mb-10">
        {{-- Decorative Background Element --}}
        <div class="absolute -top-10 -left-10 w-64 h-64 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="flex flex-col md:flex-row md:items-center justify-between gap-6 relative">
            <div class="space-y-2">

                {{-- Title & Subtitle with Accent --}}
                <div class="flex items-start gap-4">
                    <div
                        class="w-1.5 h-12 bg-gradient-to-b from-teal-500 to-emerald-400 rounded-full mt-1 hidden sm:block">
                    </div>
                    <div>
                        <h1
                            class="text-4xl font-black tracking-tight leading-none text-transparent bg-clip-text bg-gradient-to-r from-teal-600 to-emerald-500">
                            Data Warga Terdaftar
                        </h1>
                        <p class="text-sm font-bold text-slate-900 mt-2">
                            Kelola data pasien posyandu di wilayah Anda secara komprehensif.
                        </p>
                    </div>
                </div>
            </div>

            {{-- Action Buttons with Better Styling --}}
            <div class="flex flex-wrap gap-3 items-center ml-auto">
                @can('create', App\Models\Patient::class)
                    <a href="{{ route('admin.patients.import') }}"
                        class="flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-white border border-slate-100 text-xs font-black uppercase tracking-widest text-slate-900 hover:text-teal-600 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-500/5 transition-all group/btn">
                        <span
                            class="material-symbols-outlined text-[20px] text-slate-400 group-hover/btn:text-teal-500 transition-colors">publish</span>
                        Import Data
                    </a>

                    <a href="{{ route('admin.patients.create') }}"
                        class="flex items-center gap-2 px-6 py-3.5 rounded-2xl bg-gradient-to-br from-teal-600 to-emerald-500 text-slate-900 text-xs font-black uppercase tracking-widest shadow-xl shadow-teal-200 hover:shadow-teal-300 hover:-translate-y-0.5 transition-all group/add">
                        <span class="material-symbols-outlined text-[20px] text-slate-900">person_add</span>
                        Tambah Warga
                    </a>
                @endcan
            </div>
        </div>
    </div>

    @if (session('import_errors') && count(session('import_errors')) > 0)
        <div
            class="p-6 bg-amber-50 border border-amber-200 rounded-[2rem] text-sm flex flex-col gap-3 shadow-sm animate-in slide-in-from-top-4 duration-300">
            <div class="flex items-center gap-3 text-amber-800 font-black">
                <span class="material-symbols-outlined text-amber-600 text-[24px]">warning</span>
                <span>Detail Catatan/Peringatan Proses Import:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 text-amber-700 font-semibold pl-2 max-h-60 overflow-y-auto">
                @foreach (session('import_errors') as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ── Summary Cards ── --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        {{-- Bayi --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#eff6ff; color:#2563eb;">
                <span class="material-symbols-outlined text-[20px]">baby_changing_station</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Bayi (0-11 bln)</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'bayi')->orWhere(fn($q) => $q->where('category', 'balita')->where('birth_date', '>=', now()->subMonths(12)))->count() }}</p>
            </div>
        </div>

        {{-- Baduta --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#dbeafe; color:#1d4ed8;">
                <span class="material-symbols-outlined text-[20px]">child_friendly</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Baduta (12-23 bln)</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'baduta')->orWhere(fn($q) => $q->where('category', 'balita')->where('birth_date', '<', now()->subMonths(12))->where('birth_date', '>=', now()->subMonths(24)))->count() }}</p>
            </div>
        </div>

        {{-- Balita --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#f0fdfa; color:#0f766e;">
                <span class="material-symbols-outlined text-[20px]">child_care</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Balita (24-59 bln)</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'balita')->where(fn($q) => $q->whereNull('birth_date')->orWhere('birth_date', '<', now()->subMonths(24)))->count() }}</p>
            </div>
        </div>

        {{-- Anak Sekolah --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#eef2ff; color:#4338ca;">
                <span class="material-symbols-outlined text-[20px]">school</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Anak Sekolah</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'anak_sekolah')->count() }}</p>
            </div>
        </div>

        {{-- Ibu Hamil --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#fdf2f8; color:#be185d;">
                <span class="material-symbols-outlined text-[20px]">pregnant_woman</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Ibu Hamil</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'ibu_hamil')->count() }}</p>
            </div>
        </div>

        {{-- Remaja --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#f5f3ff; color:#7c3aed;">
                <span class="material-symbols-outlined text-[20px]">emoji_people</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Remaja</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'remaja')->count() }}</p>
            </div>
        </div>

        {{-- Lansia --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#fff7ed; color:#c2410c;">
                <span class="material-symbols-outlined text-[20px]">elderly</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Lansia</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'lansia')->count() }}</p>
            </div>
        </div>

        {{-- Umum / Lainnya --}}
        <div class="stat-card flex items-center gap-3">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center flex-shrink-0"
                style="background:#f8fafc; color:#475569;">
                <span class="material-symbols-outlined text-[20px]">groups</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 leading-none mb-1">Umum / Lainnya</p>
                <p class="text-xl font-bold text-slate-900 leading-none" style="font-variant-numeric:tabular-nums;">
                    {{ App\Models\Patient::where('category', 'umum')->count() }}</p>
            </div>
        </div>
    </div>

    {{-- ── Search & Filter Bar ── --}}
    <section class="section-card p-4">
        <div class="flex flex-wrap items-center gap-4">
            {{-- Search Input (Livewire) --}}
            <div class="flex-1 min-w-[280px] relative group">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none">search</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari NIK atau nama..."
                    class="search-input-premium w-full">

                {{-- Loading Spinner --}}
                <div wire:loading wire:target="search" class="absolute right-12 top-1/2 -translate-y-1/2">
                    <svg class="animate-spin h-5 w-5 text-teal-600" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                        </path>
                    </svg>
                </div>

                @if ($search)
                    <button wire:click="$set('search', '')"
                        class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-red-500 transition-colors">
                        <span class="material-symbols-outlined text-[20px]">close</span>
                    </button>
                @endif
            </div>

            {{-- Category Filter --}}
            <div class="w-full sm:w-auto min-w-[200px]">
                <x-forms.select-input wire:model.live="category" placeholder="" value="{{ $category }}">
                    <option value="all">Semua Kategori</option>
                    <option value="bayi">Bayi</option>
                    <option value="baduta">Baduta</option>
                    <option value="balita">Balita</option>
                    <option value="anak_sekolah">Anak Sekolah</option>
                    <option value="ibu_hamil">Ibu Hamil</option>
                    <option value="remaja">Remaja</option>
                    <option value="lansia">Lansia</option>
                    <option value="umum">Lainnya / Umum</option>
                </x-forms.select-input>
            </div>

            @if ($search || $category !== 'all')
                <button wire:click="resetFilters"
                    class="h-11 px-4 flex items-center gap-2 text-red-500 font-semibold text-sm hover:bg-red-50 rounded-xl transition-all">
                    <span class="material-symbols-outlined text-[18px]">restart_alt</span>
                    Reset
                </button>
            @endif
        </div>
    </section>

    {{-- Flash Messages --}}
    @if (session('success'))
        <x-alert type="success" :message="session('success')" />
    @endif

    {{-- ── Data Table ── --}}
    <div class="section-card overflow-hidden">
        <x-table>
            <thead style="background:#f9fafb; border-bottom:1px solid rgba(0,0,0,0.06);">
                <tr>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                        Nama Warga</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                        Kategori</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-left">
                        Posyandu</th>
                    <th class="px-5 py-3.5 text-xs font-semibold text-slate-500 uppercase tracking-wider text-right">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($patients as $patient)
                    @php
                        $initials = strtoupper(substr($patient->full_name, 0, 2));
                        $catStyles = [
                            'bayi' => 'badge-blue',
                            'baduta' => 'badge-blue',
                            'balita' => 'badge-teal',
                            'anak_sekolah' => 'badge-purple',
                            'ibu_hamil' => 'badge-pink',
                            'remaja' => 'badge-purple',
                            'lansia' => 'badge-amber',
                            'umum' => 'badge-slate',
                        ];
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors" style="border-bottom:1px solid rgba(0,0,0,0.04);"
                        wire:key="patient-{{ $patient->id }}">
                        <td class="px-5 py-3.5">
                            <div class="flex items-center gap-3">
                                @if ($patient->profile_photo)
                                    <img src="{{ asset('storage/' . $patient->profile_photo) }}"
                                        class="h-9 w-9 rounded-xl object-cover border border-slate-100 flex-shrink-0">
                                @else
                                    <div
                                        class="h-9 w-9 rounded-xl bg-teal-50 text-teal-700 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ $initials }}
                                    </div>
                                @endif
                                <div>
                                    <div class="font-semibold text-slate-800 text-sm leading-tight">
                                        {{ $patient->full_name }}</div>
                                    <div class="text-xs text-slate-400 mt-0.5">NIK: {{ $patient->id_number }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-5 py-3.5">
                            @php
                                $dispCat = $patient->computed_category;
                            @endphp
                            <span class="badge {{ $catStyles[$dispCat] ?? 'badge-slate' }}">
                                {{ str_replace('_', ' ', $dispCat) }}
                            </span>
                        </td>
                        <td class="px-5 py-3.5">
                            <div class="text-sm font-medium text-slate-700">{{ $patient->posyandu->name ?? '—' }}
                            </div>
                            <div class="text-xs text-slate-400 mt-0.5">{{ $patient->age }}</div>
                        </td>
                        <td class="px-5 py-3.5 text-right">
                            <div class="flex items-center justify-end gap-1.5">
                                {{-- View Detail --}}
                                <a href="{{ route('admin.patients.show', $patient->id) }}"
                                    class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 hover:bg-teal-500 hover:text-white hover:border-teal-500 transition-all flex-shrink-0"
                                    title="Lihat Detail">
                                    <span class="material-symbols-outlined text-[17px]">visibility</span>
                                </a>

                                {{-- Edit Record --}}
                                @can('update', $patient)
                                    <a href="{{ route('admin.patients.edit', $patient->id) }}"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-slate-400 hover:bg-amber-500 hover:text-white hover:border-amber-500 transition-all flex-shrink-0"
                                        title="Edit Data">
                                        <span class="material-symbols-outlined text-[17px]">edit</span>
                                    </a>
                                @endcan

                                {{-- Delete --}}
                                @can('delete', $patient)
                                    <button wire:click="confirmDelete({{ $patient->id }})"
                                        type="button"
                                        class="w-9 h-9 flex items-center justify-center rounded-xl border border-slate-200 bg-slate-50 text-red-400 hover:bg-red-500 hover:text-white hover:border-red-500 transition-all flex-shrink-0"
                                        title="Hapus Data">
                                        <span class="material-symbols-outlined text-[17px]">delete</span>
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
                                <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Tidak ada warga
                                    ditemukan</p>
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

    {{-- ── Delete Confirmation Modal ── --}}
    <div x-data="{ open: @entangle('showDeleteModal') }">
        <x-modals.confirm-modal title="Hapus Data Warga" message="Apakah Anda yakin ingin menghapus data warga ini?"
            sub-message="Seluruh riwayat pemeriksaan medis warga ini juga akan dihapus secara permanen."
            type="danger" confirm-text="Ya, Hapus Permanen">
            <x-slot:footer>
                <x-button @click="open = false" variant="outline">Batal</x-button>
                <x-button wire:click="deletePatient" variant="danger" icon="delete">Ya, Hapus</x-button>
            </x-slot:footer>
        </x-modals.confirm-modal>
    </div>
</div>
