<div>
    @section('admin-title')
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 border border-teal-100">
                <span class="material-symbols-outlined text-[20px]">monitor_weight</span>
            </div>
            <div>
                <h1 class="text-base font-black text-slate-900 leading-tight">Penimbangan Massal</h1>
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Bulan Penimbangan Balita</p>
            </div>
        </div>
    @endsection

    @section('admin-actions')
        <a href="{{ route('admin.medical-records.index') }}"
           class="inline-flex items-center gap-1.5 text-xs font-black text-slate-500 uppercase tracking-widest hover:text-teal-600 transition-colors">
            <span class="material-symbols-outlined text-[16px]">arrow_back</span>
            Kembali
        </a>
    @endsection

    <div class="space-y-6 pb-36">

        {{-- ── Step Indicator ── --}}
        <div class="bg-white border border-slate-100 rounded-2xl p-4 shadow-sm">
            <div class="flex items-center justify-between max-w-xl">
                @php
                    $step1 = $posyandu_id ? 'done' : 'active';
                    $step2 = (count($measurements) > 0) ? 'done' : ($posyandu_id ? 'active' : 'pending');
                    $step3 = (count($measurements) > 0) ? 'active' : 'pending';
                    $step4 = 'pending';
                    $steps = [
                        ['label' => 'Pilih Posyandu', 'state' => $step1],
                        ['label' => 'Muat Daftar', 'state' => $step2],
                        ['label' => 'Isi Data', 'state' => $step3],
                        ['label' => 'Simpan', 'state' => $step4],
                    ];
                @endphp
                @foreach($steps as $i => $s)
                    <div class="flex items-center gap-2 flex-1">
                        <div @class([
                            'w-7 h-7 rounded-full flex items-center justify-center text-xs font-black flex-shrink-0 transition-all',
                            'bg-teal-600 text-white shadow-md shadow-teal-500/20' => $s['state'] === 'done',
                            'bg-teal-100 text-teal-700 ring-2 ring-teal-400' => $s['state'] === 'active',
                            'bg-slate-100 text-slate-400' => $s['state'] === 'pending',
                        ])>
                            @if($s['state'] === 'done')
                                <span class="material-symbols-outlined text-[14px]">check</span>
                            @else
                                {{ $i + 1 }}
                            @endif
                        </div>
                        <span @class([
                            'text-[10px] font-black uppercase tracking-wider hidden sm:block',
                            'text-teal-700' => $s['state'] === 'active',
                            'text-slate-300' => $s['state'] === 'pending',
                            'text-slate-500' => $s['state'] === 'done',
                        ])>{{ $s['label'] }}</span>
                        @if(!$loop->last)
                            <div @class([
                                'flex-1 h-px mx-2',
                                'bg-teal-300' => $s['state'] === 'done',
                                'bg-slate-100' => $s['state'] !== 'done',
                            ])></div>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Pengaturan Sesi (Langkah 1 & 2) ── --}}
        <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 bg-slate-50/50">
                <h2 class="text-sm font-black text-slate-700 flex items-center gap-2">
                    <span class="material-symbols-outlined text-teal-600 text-[18px]">tune</span>
                    Pengaturan Sesi Penimbangan
                </h2>
            </div>
            <div class="p-5 grid grid-cols-1 md:grid-cols-3 gap-5 items-end">
                {{-- Tanggal --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-600 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[15px] text-slate-400">calendar_month</span>
                        Tanggal Kunjungan
                    </label>
                    <input type="date" wire:model.live="visit_date"
                        class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-slate-800 focus:ring-teal-500 focus:border-teal-500 transition-all">
                </div>

                {{-- Posyandu --}}
                <div class="space-y-2">
                    <label class="text-xs font-black text-slate-600 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[15px] text-slate-400">location_on</span>
                        Posyandu
                    </label>
                    <div class="relative">
                        <select wire:model.live="posyandu_id"
                            class="w-full h-12 pl-4 pr-10 rounded-xl border border-slate-200 bg-slate-50 text-sm font-bold text-slate-800 focus:ring-teal-500 focus:border-teal-500 appearance-none transition-all">
                            <option value="">-- Pilih Posyandu --</option>
                            @foreach($posyandus as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <span class="material-symbols-outlined absolute right-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[20px]">expand_more</span>
                    </div>
                </div>

                {{-- Tombol Muat --}}
                <button wire:click="loadAllPatients"
                        wire:loading.attr="disabled"
                        class="w-full h-12 flex items-center justify-center gap-2 bg-teal-600 hover:bg-teal-500 active:bg-teal-700 text-white rounded-xl text-sm font-black uppercase tracking-wide shadow-lg shadow-teal-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed">
                    <span class="material-symbols-outlined text-[20px]" wire:loading.remove wire:target="loadAllPatients">group_add</span>
                    <span class="material-symbols-outlined text-[20px] animate-spin" wire:loading wire:target="loadAllPatients">sync</span>
                    <span wire:loading.remove wire:target="loadAllPatients">Muat Semua Balita</span>
                    <span wire:loading wire:target="loadAllPatients">Memuat...</span>
                </button>
            </div>
        </div>

        {{-- ── Pencarian Tambahan ── --}}
        <div class="relative" x-data>
            <div class="relative">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[22px]">search</span>
                <input type="text" wire:model.live.debounce.300ms="search"
                    placeholder="Cari nama balita untuk ditambahkan secara manual..."
                    class="w-full h-14 pl-12 pr-4 rounded-2xl border border-slate-200 bg-white shadow-sm text-sm font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all">
            </div>

            @if(count($searchResults) > 0)
                <div class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden">
                    @foreach($searchResults as $result)
                        <button wire:click="addPatient({{ $result->id }})"
                                class="w-full flex items-center justify-between px-5 py-4 hover:bg-teal-50 text-left transition-all border-b border-slate-50 last:border-0 group">
                            <div class="flex items-center gap-4">
                                <div @class([
                                    'w-11 h-11 rounded-xl flex items-center justify-center font-black text-sm flex-shrink-0',
                                    'bg-blue-100 text-blue-600' => $result->gender === 'male',
                                    'bg-rose-100 text-rose-600' => $result->gender === 'female',
                                ])>
                                    {{ strtoupper(substr($result->full_name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-black text-slate-900">{{ $result->full_name }}</p>
                                    <p class="text-xs font-bold text-slate-400">
                                        {{ $result->age_in_months }} bulan &bull;
                                        {{ $result->gender === 'male' ? 'Laki-laki' : 'Perempuan' }}
                                    </p>
                                </div>
                            </div>
                            <span class="w-9 h-9 rounded-xl bg-slate-100 group-hover:bg-teal-500 group-hover:text-white text-slate-500 flex items-center justify-center transition-all">
                                <span class="material-symbols-outlined text-[18px]">add</span>
                            </span>
                        </button>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- ── Progress Counter ── --}}
        @php
            $filledCount = collect($measurements)->filter(fn($m) => !empty($m['weight']) && !empty($m['height']))->count();
            $totalCount  = count($measurements);
        @endphp
        @if($totalCount > 0)
        <div class="flex items-center justify-between bg-white border border-slate-100 rounded-2xl px-5 py-3 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="text-sm font-black text-slate-700">
                    <span class="text-teal-600 text-lg">{{ $filledCount }}</span>
                    <span class="text-slate-400 mx-1">/</span>
                    {{ $totalCount }} balita sudah terisi
                </div>
                <div class="flex-1 w-48 h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full bg-teal-500 rounded-full transition-all duration-500"
                         style="width: {{ $totalCount > 0 ? round($filledCount / $totalCount * 100) : 0 }}%">
                    </div>
                </div>
            </div>
            <span class="text-xs font-black text-slate-400 uppercase tracking-widest">
                {{ $totalCount > 0 ? round($filledCount / $totalCount * 100) : 0 }}%
            </span>
        </div>
        @endif

        {{-- ── Daftar Balita (Vertikal) ── --}}
        <div class="space-y-3">
            @forelse($measurements as $index => $m)
            <div class="bg-white border border-slate-100 rounded-2xl shadow-sm overflow-hidden hover:border-teal-200 transition-all">
                {{-- Header Baris: Identitas Balita --}}
                <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
                    <div class="flex items-center gap-4">
                        {{-- Nomor urut --}}
                        <span class="w-7 h-7 rounded-lg bg-slate-100 flex items-center justify-center text-xs font-black text-slate-500 flex-shrink-0">
                            {{ $index + 1 }}
                        </span>

                        {{-- Avatar gender --}}
                        <div @class([
                            'w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0',
                            'bg-blue-100 text-blue-600' => $m['gender'] === 'male',
                            'bg-rose-100 text-rose-600' => $m['gender'] === 'female',
                        ])>
                            <span class="material-symbols-outlined text-[24px]">
                                {{ $m['gender'] === 'male' ? 'boy' : 'girl' }}
                            </span>
                        </div>

                        {{-- Nama & info --}}
                        <div>
                            <p class="text-base font-black text-slate-900 leading-tight">{{ $m['full_name'] }}</p>
                            <div class="flex items-center gap-2 mt-0.5">
                                <span @class([
                                    'text-xs font-bold px-2 py-0.5 rounded-lg',
                                    'bg-blue-50 text-blue-700' => $m['gender'] === 'male',
                                    'bg-rose-50 text-rose-700' => $m['gender'] === 'female',
                                ])>
                                    {{ $m['age_months'] }} bulan
                                </span>
                                <span class="text-xs text-slate-400 font-medium">{{ $m['parent_name'] }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol hapus --}}
                    <button wire:click="removePatient({{ $index }})"
                            class="w-9 h-9 rounded-xl bg-slate-50 hover:bg-red-50 text-slate-300 hover:text-red-500 flex items-center justify-center transition-all flex-shrink-0"
                            title="Hapus dari daftar">
                        <span class="material-symbols-outlined text-[18px]">close</span>
                    </button>
                </div>

                {{-- Body: Input + Status --}}
                <div class="px-5 py-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        {{-- Input Berat Badan --}}
                        <div class="space-y-2">
                            <label class="text-sm font-black text-slate-600 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">monitor_weight</span>
                                Berat Badan (kg)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.01"
                                    wire:model.live.debounce.500ms="measurements.{{ $index }}.weight"
                                    tabindex="{{ ($index * 2) + 1 }}"
                                    placeholder="0.0"
                                    class="w-full h-16 text-center text-3xl font-black rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all placeholder-slate-200 {{ !empty($m['weight']) ? 'border-teal-300 bg-teal-50/30' : '' }}">
                                <span class="absolute right-4 bottom-3 text-xs font-black text-slate-400">kg</span>
                            </div>
                            @if(!empty($m['last_weight']) && $m['last_weight'] !== '-')
                            <p class="text-[11px] text-slate-400 font-medium pl-1">
                                Sebelumnya: <strong class="text-slate-600">{{ $m['last_weight'] }} kg</strong>
                            </p>
                            @endif
                        </div>

                        {{-- Input Tinggi Badan --}}
                        <div class="space-y-2">
                            <label class="text-sm font-black text-slate-600 flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[16px] text-slate-400">height</span>
                                Tinggi Badan (cm)
                            </label>
                            <div class="relative">
                                <input type="number" step="0.1"
                                    wire:model.live.debounce.500ms="measurements.{{ $index }}.height"
                                    tabindex="{{ ($index * 2) + 2 }}"
                                    placeholder="0.0"
                                    class="w-full h-16 text-center text-3xl font-black rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:border-teal-500 focus:ring-2 focus:ring-teal-100 transition-all placeholder-slate-200 {{ !empty($m['height']) ? 'border-teal-300 bg-teal-50/30' : '' }}">
                                <span class="absolute right-4 bottom-3 text-xs font-black text-slate-400">cm</span>
                            </div>
                            @if(!empty($m['last_height']) && $m['last_height'] !== '-')
                            <p class="text-[11px] text-slate-400 font-medium pl-1">
                                Sebelumnya: <strong class="text-slate-600">{{ $m['last_height'] }} cm</strong>
                            </p>
                            @endif
                        </div>
                    </div>

                    {{-- Status Gizi & Posisi Ukur --}}
                    <div class="mt-4 flex flex-wrap items-center justify-between gap-3">
                        {{-- Status Gizi Badges --}}
                        <div class="flex flex-wrap gap-2">
                            @php
                                $statusBbuIcon = isset($m['status_bbu']) ? match(true) {
                                    str_contains($m['status_bbu'], 'Baik') => ['icon' => 'check_circle', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    str_contains($m['status_bbu'], 'Kurang') => ['icon' => 'warning', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                    str_contains($m['status_bbu'], 'Buruk') => ['icon' => 'error', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                    str_contains($m['status_bbu'], 'Lebih') => ['icon' => 'info', 'class' => 'bg-purple-50 text-purple-700 border-purple-200'],
                                    default => ['icon' => 'help', 'class' => 'bg-slate-50 text-slate-500 border-slate-200'],
                                } : null;

                                $statusTbuIcon = isset($m['status_tbu']) ? match(true) {
                                    str_contains($m['status_tbu'], 'Normal') => ['icon' => 'check_circle', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200'],
                                    str_contains($m['status_tbu'], 'Pendek') => ['icon' => 'warning', 'class' => 'bg-amber-50 text-amber-700 border-amber-200'],
                                    str_contains($m['status_tbu'], 'Sangat Pendek') => ['icon' => 'error', 'class' => 'bg-red-50 text-red-700 border-red-200'],
                                    str_contains($m['status_tbu'], 'Tinggi') => ['icon' => 'info', 'class' => 'bg-blue-50 text-blue-700 border-blue-200'],
                                    default => ['icon' => 'help', 'class' => 'bg-slate-50 text-slate-500 border-slate-200'],
                                } : null;
                            @endphp

                            @if($statusBbuIcon)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $statusBbuIcon['class'] }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $statusBbuIcon['icon'] }}</span>
                                    Berat: {{ $m['status_bbu'] }}
                                </span>
                            @endif
                            @if($statusTbuIcon)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border {{ $statusTbuIcon['class'] }}">
                                    <span class="material-symbols-outlined text-[14px]">{{ $statusTbuIcon['icon'] }}</span>
                                    Tinggi: {{ $m['status_tbu'] }}
                                </span>
                            @endif
                            @if(isset($m['status_bbtb']))
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold border bg-indigo-50 text-indigo-700 border-indigo-200">
                                    <span class="material-symbols-outlined text-[14px]">fitness_center</span>
                                    BB/TB: {{ $m['status_bbtb'] }}
                                </span>
                            @endif
                        </div>

                        {{-- Posisi Pengukuran --}}
                        <div class="flex-shrink-0">
                            <select wire:model.live="measurements.{{ $index }}.measurement_method"
                                    class="text-xs font-bold text-slate-600 bg-slate-100 border-0 rounded-xl px-3 py-2 focus:ring-2 focus:ring-teal-300 cursor-pointer">
                                <option value="recumbent">📏 Posisi: Terlentang</option>
                                <option value="standing">🧍 Posisi: Berdiri</option>
                            </select>
                        </div>
                    </div>
                </div>

                {{-- Status selesai indicator --}}
                @if(!empty($m['weight']) && !empty($m['height']))
                <div class="px-5 py-2 bg-teal-50 border-t border-teal-100 flex items-center gap-2">
                    <span class="material-symbols-outlined text-teal-600 text-[16px]">check_circle</span>
                    <span class="text-xs font-black text-teal-700">Data lengkap — siap disimpan</span>
                </div>
                @endif
            </div>
            @empty
            <div class="bg-white border border-dashed border-slate-200 rounded-2xl py-20 text-center">
                <span class="material-symbols-outlined text-slate-200 text-[60px] block mb-4">group</span>
                <h3 class="text-lg font-black text-slate-700">Daftar Balita Kosong</h3>
                <p class="mt-2 text-sm text-slate-400 max-w-xs mx-auto leading-relaxed">
                    Klik <strong>"Muat Semua Balita"</strong> di atas untuk memuat semua balita dari posyandu, atau cari manual lewat kotak pencarian.
                </p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- ── Floating Save Bar ── --}}
    @if(count($measurements) > 0)
    <div class="fixed bottom-0 left-0 right-0 z-50 pointer-events-none" style="padding-left: var(--sidebar-width, 260px);">
        <div class="pointer-events-auto border-t border-slate-200 bg-white/95 backdrop-blur-md shadow-2xl shadow-slate-300/30">
            <div class="max-w-5xl mx-auto px-6 py-4 flex items-center justify-between gap-4">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-teal-50 border border-teal-200 flex items-center justify-center">
                        <span class="material-symbols-outlined text-teal-600">monitor_weight</span>
                    </div>
                    <div>
                        <p class="text-sm font-black text-slate-900">
                            {{ $filledCount }} dari {{ $totalCount }} balita siap disimpan
                        </p>
                        <p class="text-xs text-slate-400 font-medium">
                            {{ date('d F Y', strtotime($visit_date ?? now())) }}
                        </p>
                    </div>
                </div>

                <button wire:click="save"
                        wire:loading.attr="disabled"
                        @class([
                            'flex items-center gap-3 px-8 py-3.5 rounded-xl text-sm font-black uppercase tracking-wide shadow-lg transition-all',
                            'bg-teal-600 hover:bg-teal-500 text-white shadow-teal-500/20' => $filledCount > 0,
                            'bg-slate-200 text-slate-400 cursor-not-allowed' => $filledCount === 0,
                        ])
                        @if($filledCount === 0) disabled @endif>
                    <span class="material-symbols-outlined text-[20px]" wire:loading.remove wire:target="save">save</span>
                    <span class="material-symbols-outlined text-[20px] animate-spin" wire:loading wire:target="save">sync</span>
                    <span wire:loading.remove wire:target="save">Simpan {{ $filledCount }} Data</span>
                    <span wire:loading wire:target="save">Menyimpan...</span>
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
