<div>
    @section('admin-title') 
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-teal-500">assignment_turned_in</span>
            <span>Bulan Penimbangan Balita</span>
        </div>
    @endsection

    @section('admin-actions')
        <x-button href="{{ route('admin.medical-records.index') }}" variant="outline" icon="arrow_back">
            Kembali
        </x-button>
        <button wire:click="save" class="flex items-center gap-2 px-6 py-2.5 bg-teal-600 hover:bg-teal-700 text-white text-xs font-black uppercase tracking-widest rounded-xl shadow-lg shadow-teal-200 transition-all active:scale-95">
            <span class="material-symbols-outlined text-[18px]">save</span>
            Simpan Semua Data
        </button>
    @endsection

    <div class="space-y-6">
        {{-- Filters & Search Bento Card --}}
        <div class="bg-white rounded-[2rem] p-6 shadow-sm border border-slate-100">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 p-6 bg-slate-50/50 rounded-3xl border border-dashed border-slate-200">
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">calendar_month</span>
                        Tanggal
                    </label>
                    <input type="date" wire:model.live="visit_date" class="w-full bg-white border-slate-200 rounded-2xl text-sm font-bold focus:ring-teal-500 focus:border-teal-500 transition-all shadow-sm">
                </div>
                <div class="space-y-2">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">location_on</span>
                        Posyandu
                    </label>
                    <select wire:model.live="posyandu_id" class="w-full bg-white border-slate-200 rounded-2xl text-sm font-bold focus:ring-teal-500 focus:border-teal-500 transition-all shadow-sm">
                        <option value="">-- Semua --</option>
                        @foreach($posyandus as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2 md:col-span-2 relative">
                    <label class="text-[10px] font-black text-slate-500 uppercase tracking-widest ml-1 flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[14px]">search</span>
                        Cari Balita (Nama / NIK)
                    </label>
                    <div class="relative">
                        <input type="text" wire:model.live.debounce.300ms="search" placeholder="Ketik nama balita untuk menambahkan ke daftar..." 
                            class="w-full bg-white border-slate-200 rounded-2xl text-sm font-bold focus:ring-teal-500 focus:border-teal-500 transition-all shadow-sm pl-10">
                        <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400">
                            <span class="material-symbols-outlined text-[20px]">person_search</span>
                        </div>
                    </div>
                    
                    @if(count($searchResults) > 0)
                        <div class="absolute z-50 left-0 right-0 mt-2 bg-white rounded-2xl shadow-xl border border-slate-100 overflow-hidden ring-4 ring-teal-500/10">
                            @foreach($searchResults as $result)
                                <button wire:click="addPatient({{ $result->id }})" class="w-full flex items-center justify-between p-4 hover:bg-teal-50 text-left transition-colors border-b border-slate-50 last:border-0 group">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-lg bg-teal-50 flex items-center justify-center text-teal-600 group-hover:scale-110 transition-transform">
                                            <span class="material-symbols-outlined text-[18px]">child_care</span>
                                        </div>
                                        <div>
                                            <p class="text-sm font-black text-slate-900">{{ $result->full_name }}</p>
                                            <p class="text-[10px] font-bold text-slate-400">NIK: {{ $result->id_number }} | Ortu: {{ $result->parent_name }}</p>
                                        </div>
                                    </div>
                                    <span class="material-symbols-outlined text-teal-500 group-hover:scale-125 transition-transform">add_circle</span>
                                </button>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Main Table Bento Card --}}
        <div class="bg-white rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-slate-900 text-white">
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest text-center w-12">No</th>
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest">Identitas Balita</th>
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest text-center">Usia</th>
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest">Ref (Terakhir)</th>
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest text-center">BB Baru (Kg)</th>
                            <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest text-center">TB Baru (cm)</th>
                                <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest">Cara Ukur</th>
                                <th class="py-5 px-6 text-[9px] font-black uppercase tracking-widest text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($measurements as $index => $m)
                                <tr class="hover:bg-slate-50/50 transition-all group">
                                    <td class="py-4 px-6 text-xs font-black text-slate-300 text-center">{{ $index + 1 }}</td>
                                    <td class="py-4 px-6">
                                        <div class="flex flex-col">
                                            <span class="text-sm font-black text-slate-900 group-hover:text-teal-600 transition-colors">{{ $m['full_name'] }}</span>
                                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-tight">Ortu: {{ $m['parent_name'] }}</span>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <span class="inline-flex px-3 py-1 rounded-lg bg-teal-50 text-teal-600 text-[10px] font-black border border-teal-100">
                                            {{ $m['age_months'] }} <span class="ml-0.5 text-[8px] opacity-70">Bln</span>
                                        </span>
                                    </td>
                                    <td class="py-4 px-6">
                                        <div class="flex gap-3">
                                            <div class="text-[9px] font-black px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase tracking-tighter">
                                                BB: <span class="text-slate-900">{{ $m['last_weight'] }}</span>
                                            </div>
                                            <div class="text-[9px] font-black px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase tracking-tighter">
                                                TB: <span class="text-slate-900">{{ $m['last_height'] }}</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <input type="number" step="0.01" wire:model.live.debounce.500ms="measurements.{{ $index }}.weight" 
                                                class="w-20 bg-slate-50 border-slate-200 rounded-xl text-xs font-black focus:ring-teal-500 focus:border-teal-500 text-center transition-all hover:border-teal-300"
                                                placeholder="0.00">
                                            
                                            @if(isset($m['status_bbu']))
                                                <span @class([
                                                    'text-[7px] font-black uppercase px-1.5 py-0.5 rounded-md border',
                                                    'bg-emerald-50 text-emerald-600 border-emerald-100' => str_contains($m['status_bbu'], 'Baik'),
                                                    'bg-amber-50 text-amber-600 border-amber-100' => str_contains($m['status_bbu'], 'Kurang'),
                                                    'bg-red-50 text-red-600 border-red-100' => str_contains($m['status_bbu'], 'Buruk'),
                                                    'bg-blue-50 text-blue-600 border-blue-100' => str_contains($m['status_bbu'], 'Lebih'),
                                                ])>
                                                    BB/U: {{ $m['status_bbu'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex flex-col items-center gap-2">
                                            <input type="number" step="0.1" wire:model.live.debounce.500ms="measurements.{{ $index }}.height" 
                                                class="w-20 bg-slate-50 border-slate-200 rounded-xl text-xs font-black focus:ring-teal-500 focus:border-teal-500 text-center transition-all hover:border-teal-300"
                                                placeholder="0.0">
                                            
                                            @if(isset($m['status_bbtb']))
                                                <span @class([
                                                    'text-[7px] font-black uppercase px-1.5 py-0.5 rounded-md border',
                                                    'bg-emerald-50 text-emerald-600 border-emerald-100' => str_contains($m['status_bbtb'], 'Normal'),
                                                    'bg-amber-50 text-amber-600 border-amber-100' => str_contains($m['status_bbtb'], 'Kurus'),
                                                    'bg-red-50 text-red-600 border-red-100' => str_contains($m['status_bbtb'], 'Sangat Kurus'),
                                                    'bg-purple-50 text-purple-600 border-purple-100' => str_contains($m['status_bbtb'], 'Gemuk'),
                                                ])>
                                                    BB/TB: {{ $m['status_bbtb'] }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="py-4 px-6">
                                        <select wire:model="measurements.{{ $index }}.measurement_method" 
                                            class="bg-slate-50 border-slate-200 rounded-xl text-[9px] font-black uppercase focus:ring-teal-500 focus:border-teal-500 transition-all hover:border-teal-300">
                                            <option value="Terlentang">Terlentang</option>
                                            <option value="Berdiri">Berdiri</option>
                                        </select>
                                    </td>
                                    <td class="py-4 px-6 text-center">
                                        <button wire:click="removePatient({{ $index }})" class="w-8 h-8 rounded-lg bg-red-50 text-red-500 hover:bg-red-500 hover:text-white transition-all flex items-center justify-center mx-auto group/del">
                                            <span class="material-symbols-outlined text-[18px] group-hover/del:scale-110 transition-transform">delete</span>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="py-32 text-center">
                                        <div class="flex flex-col items-center gap-6">
                                            <div class="relative">
                                                <div class="w-24 h-24 rounded-[2rem] bg-teal-50 flex items-center justify-center text-teal-200 animate-pulse">
                                                    <span class="material-symbols-outlined text-[48px]">child_care</span>
                                                </div>
                                                <div class="absolute -right-2 -bottom-2 w-10 h-10 rounded-2xl bg-white shadow-xl flex items-center justify-center text-teal-500">
                                                    <span class="material-symbols-outlined text-[20px]">search</span>
                                                </div>
                                            </div>
                                            <div class="max-w-xs">
                                                <p class="text-base font-black text-slate-800">Daftar Masih Kosong</p>
                                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-2 leading-relaxed">
                                                    Cari nama balita di atas untuk menambahkannya ke dalam daftar penimbangan massal.
                                                </p>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                    </tbody>
                </table>
            </div>
            
            @if(count($measurements) > 0)
                <div class="p-8 bg-slate-900 text-white flex items-center justify-between">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center">
                            <span class="material-symbols-outlined text-teal-400">info</span>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Informasi</p>
                            <p class="text-xs font-bold text-slate-300">Pastikan semua data sudah terisi dengan benar sebelum menekan tombol simpan.</p>
                        </div>
                    </div>
                    <button wire:click="save" class="group flex items-center gap-3 px-8 py-3 bg-teal-500 hover:bg-teal-400 text-white text-xs font-black uppercase tracking-widest rounded-2xl shadow-xl shadow-teal-500/20 transition-all active:scale-95">
                        Simpan Semua ({{ count($measurements) }} Data)
                        <span class="material-symbols-outlined group-hover:translate-x-1 transition-transform">arrow_forward</span>
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
