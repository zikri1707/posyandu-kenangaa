<div class="space-y-8 p-6 md:p-8 pt-2 md:pt-4">
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
                        Jadwal & Kegiatan
                    </h1>
                    <p class="text-sm font-bold text-slate-900 mt-3">Atur jadwal kegiatan posyandu dan imunisasi secara
                        efisien.</p>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap gap-3 items-center">
            @can('create', App\Models\Schedule::class)
                <a href="{{ route('admin.schedules.create') }}"
                    class="h-16 px-8 bg-primary text-white rounded-[1.5rem] font-black text-xs uppercase tracking-widest flex items-center gap-3 hover:bg-primary/90 transition-all shadow-xl shadow-primary/20 group">
                    <span
                        class="material-symbols-outlined text-[20px] group-hover:rotate-90 transition-transform">calendar_add_on</span>
                    Tambah Jadwal Baru
                </a>
            @endcan
        </div>
    </div>

    {{-- ── Header & Summary Bento ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                <div class="flex items-center gap-3 mb-6">
                    <div class="w-10 h-10 rounded-2xl bg-teal-50 text-teal-600 flex items-center justify-center">
                        <span class="material-symbols-outlined text-[20px]">analytics</span>
                    </div>
                    <h3 class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Ikhtisar Bulan Ini</h3>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div class="p-4 rounded-3xl bg-slate-50/50">
                        <span
                            class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-1">Total</span>
                        <p class="text-2xl font-black text-slate-900">{{ $stats['total_month'] }}</p>
                    </div>
                    <div class="p-4 rounded-3xl bg-blue-50/50">
                        <span
                            class="text-[10px] font-black text-blue-600 uppercase tracking-widest block mb-1">Mendatang</span>
                        <p class="text-2xl font-black text-slate-900">{{ $stats['upcoming'] }}</p>
                    </div>
                    <div class="p-4 rounded-3xl bg-green-50/50">
                        <span
                            class="text-[10px] font-black text-green-600 uppercase tracking-widest block mb-1">Selesai</span>
                        <p class="text-2xl font-black text-slate-900">{{ $stats['completed'] }}</p>
                    </div>
                    <div class="p-4 rounded-3xl bg-amber-50/50">
                        <span
                            class="text-[10px] font-black text-amber-600 uppercase tracking-widest block mb-1">Berlangsung</span>
                        <p class="text-2xl font-black text-slate-900">{{ $stats['ongoing'] }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next Agenda Highlight --}}
        <div class="lg:col-span-8">
            <div class="h-full rounded-[2.5rem] p-10 md:p-12 relative overflow-hidden text-white group shadow-2xl shadow-emerald-100"
                style="background-color: #064e3b; background-image: radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.5) 0px, transparent 50%), radial-gradient(at 50% 0%, hsla(168, 76%, 36%, 0.5) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.3) 0px, transparent 50%);">
                <div
                    class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5">
                </div>
                <div class="relative z-10 flex flex-col h-full justify-between">
                    <div>
                        <div class="flex items-center gap-3 mb-8">
                            <span
                                class="px-5 py-2 rounded-full bg-teal-500/20 text-teal-100 text-[11px] font-black uppercase tracking-[0.2em] border border-teal-500/20 backdrop-blur-md">Agenda
                                Terdekat</span>
                        </div>
                        @if ($agendaTerdekat)
                            <h2 class="text-4xl md:text-5xl font-black leading-tight mb-8 tracking-tighter">
                                {{ $agendaTerdekat->title }}</h2>
                            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                                        <span
                                            class="material-symbols-outlined text-[24px] text-teal-300">calendar_month</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-teal-100/50 uppercase tracking-widest">
                                            Tanggal</p>
                                        <p class="text-base font-black">
                                            {{ \Carbon\Carbon::parse($agendaTerdekat->start_time)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                                        <span
                                            class="material-symbols-outlined text-[24px] text-teal-300">schedule</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-teal-100/50 uppercase tracking-widest">
                                            Waktu</p>
                                        <p class="text-base font-black">
                                            {{ \Carbon\Carbon::parse($agendaTerdekat->start_time)->format('H:i') }} WIB
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                                        <span
                                            class="material-symbols-outlined text-[24px] text-teal-300">location_on</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-teal-100/50 uppercase tracking-widest">
                                            Lokasi</p>
                                        <p class="text-base font-black truncate max-w-[150px]">
                                            {{ $agendaTerdekat->location ?: 'Posyandu Unit' }}</p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="py-12">
                                <p class="text-teal-100/50 font-black text-xl italic">Belum ada agenda mendatang.</p>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="absolute -right-20 -bottom-20 w-64 h-64 bg-teal-500/20 rounded-full blur-3xl"></div>
            </div>
        </div>
    </div>

    {{-- ── Filters ── --}}
    <div
        class="bg-white rounded-3xl border border-slate-100 p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-1">
            <div class="relative min-w-[300px] group">
                <span
                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none text-[20px]">search</span>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari agenda atau lokasi..."
                    class="search-input-premium w-full">
            </div>

            <x-forms.select-input wire:model.live="status" placeholder="Semua Status" :placeholderDisabled="false"
                value="{{ $status }}" class="min-w-[150px]">
                <option value="upcoming">Mendatang</option>
                <option value="ongoing">Berlangsung</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </x-forms.select-input>

            @if (auth()->user()->isSuperAdmin())
                <x-forms.select-input wire:model.live="posyandu_id" placeholder="Seluruh Unit" :placeholderDisabled="false"
                    value="{{ $posyandu_id }}" class="min-w-[150px]">
                    @foreach ($posyandus as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </x-forms.select-input>
            @endif
        </div>

        @if ($search || $status || $posyandu_id)
            <button wire:click="$set('search', ''); $set('status', ''); $set('posyandu_id', '');"
                class="text-[10px] font-black text-red-500 uppercase tracking-[0.2em] hover:text-red-600 transition-colors px-4">
                Reset Filter
            </button>
        @endif
    </div>

    {{-- ── Data Table ── --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <table class="w-full">
            <thead class="bg-slate-50/50 border-b border-slate-100">
                <tr>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Informasi Kegiatan</th>
                    <th class="px-8 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Waktu & Lokasi</th>
                    <th class="px-8 py-5 text-center text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Status</th>
                    <th class="px-8 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                        Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-50">
                @forelse($schedules as $schedule)
                    <tr class="group hover:bg-slate-50/30 transition-all duration-300">
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-5">
                                <div
                                    class="h-14 w-14 rounded-3xl bg-slate-50 text-slate-400 flex items-center justify-center group-hover:bg-white group-hover:shadow-md transition-all border border-slate-100">
                                    @php
                                        $icon = 'event';
                                        $title_lower = strtolower($schedule->title);
                                        if (str_contains($title_lower, 'balita')) {
                                            $icon = 'child_care';
                                        } elseif (str_contains($title_lower, 'hamil')) {
                                            $icon = 'pregnant_woman';
                                        } elseif (str_contains($title_lower, 'lansia')) {
                                            $icon = 'elderly';
                                        }
                                    @endphp
                                    <span class="material-symbols-outlined text-[24px]">{{ $icon }}</span>
                                </div>
                                <div>
                                    <div class="font-black text-slate-900 text-base mb-1">{{ $schedule->title }}</div>
                                    <div class="flex items-center gap-2">
                                        <span
                                            class="text-[10px] font-black text-teal-600 uppercase tracking-widest bg-teal-50 px-2.5 py-1 rounded-lg">{{ $schedule->posyandu->name ?? 'Semua Unit' }}</span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex flex-col gap-1.5">
                                <div class="flex items-center gap-2 text-slate-800 font-bold text-sm">
                                    <span
                                        class="material-symbols-outlined text-[18px] text-slate-300">calendar_today</span>
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d M Y') }} —
                                    {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                </div>
                                <div class="flex items-center gap-2 text-slate-400 text-xs font-semibold">
                                    <span
                                        class="material-symbols-outlined text-[18px] text-slate-300">location_on</span>
                                    {{ $schedule->location ?: '—' }}
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            @php
                                $colors = [
                                    'upcoming' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'ongoing' => 'bg-teal-50 text-teal-600 border-teal-100',
                                    'completed' => 'bg-green-50 text-green-600 border-green-100',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                ];
                            @endphp
                            <span
                                class="px-4 py-2 rounded-2xl text-[10px] font-black uppercase tracking-widest border {{ $colors[$schedule->status] ?? 'bg-slate-50' }}">
                                {{ $schedule->status }}
                            </span>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div
                                class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all duration-300 -translate-x-2 group-hover:translate-x-0">
                                <a href="{{ route('admin.schedules.show', $schedule->id) }}"
                                    class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-teal-600 hover:border-teal-200 hover:shadow-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                @can('update', $schedule)
                                    <a href="{{ route('admin.schedules.edit', $schedule->id) }}"
                                        class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-[20px]">edit</span>
                                    </a>
                                @endcan
                                @can('delete', $schedule)
                                    <button wire:click="deleteSchedule({{ $schedule->id }})"
                                        wire:confirm="Hapus jadwal ini secara permanen?"
                                        class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-red-600 hover:border-red-200 hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-8 py-32 text-center">
                            <div class="max-w-xs mx-auto flex flex-col items-center text-slate-400">
                                <span
                                    class="material-symbols-outlined text-[64px] text-slate-200 mb-4">calendar_today</span>
                                <p class="text-lg font-black text-slate-600">Belum ada agenda</p>
                                <p class="text-sm mb-6">Mulai dengan menambahkan jadwal baru.</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        @if ($schedules->hasPages())
            <div class="px-8 py-6 border-t border-slate-50 bg-slate-50/30">
                {{ $schedules->links() }}
            </div>
        @endif
    </div>

</div>
