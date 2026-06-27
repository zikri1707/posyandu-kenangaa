<div>
    @section('admin-title', 'Edit Jadwal: ' . $schedule->title)

    <div class="max-w-4xl mx-auto py-8 px-4 sm:px-6">
        <div class="bg-white border border-slate-200 rounded-[2.5rem] shadow-sm overflow-hidden">
            {{-- Header --}}
            <div class="px-10 py-8 border-b border-slate-50 bg-slate-50/30 flex items-center justify-between">
                <div class="flex items-center gap-5">
                    <div
                        class="w-14 h-14 bg-indigo-50 rounded-3xl flex items-center justify-center text-indigo-600 shadow-sm border border-indigo-100">
                        <span class="material-symbols-outlined text-[28px]">edit_calendar</span>
                    </div>
                    <div>
                        <h2 class="text-xl font-black text-slate-900 tracking-tight leading-tight">Perbarui Jadwal</h2>
                        <p class="text-[10px] text-slate-400 font-black uppercase tracking-[0.2em] mt-1">ID:
                            #SCH-{{ str_pad($schedule->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                </div>
                <a href="{{ route('admin.schedules.index') }}"
                    class="w-10 h-10 rounded-2xl bg-white border border-slate-100 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm">
                    <span class="material-symbols-outlined text-[20px]">close</span>
                </a>
            </div>

            <form wire:submit.prevent="save" class="p-10 space-y-10">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                    {{-- Left Column --}}
                    <div class="space-y-8">
                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Judul
                                Agenda <span class="text-red-500">*</span></label>
                            <input type="text" wire:model="title" placeholder="Contoh: Posyandu Balita RW 01"
                                class="w-full h-14 px-6 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all">
                            @error('title')
                                <p class="text-[10px] text-red-500 font-bold ml-1 uppercase tracking-wider">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Deskripsi
                                & Catatan</label>
                            <textarea wire:model="description" rows="5" placeholder="Detail kegiatan atau instruksi khusus..."
                                class="w-full px-6 py-5 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all resize-none"></textarea>
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Unit
                                Pelaksana <span class="text-red-500">*</span></label>
                            <x-forms.select-input wire:model="posyandu_id" placeholder="Pilih Posyandu"
                                :placeholderDisabled="true" value="{{ $posyandu_id }}" :error="$errors->has('posyandu_id')">
                                @foreach ($posyandus as $p)
                                    <option value="{{ $p->id }}" {{ $posyandu_id == $p->id ? 'selected' : '' }}>
                                        {{ $p->name }}</option>
                                @endforeach
                            </x-forms.select-input>
                            @error('posyandu_id')
                                <p class="text-[10px] text-red-500 font-bold ml-1 uppercase tracking-wider">
                                    {{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Right Column --}}
                    <div class="space-y-8">
                        <div class="grid grid-cols-1 gap-8">
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Waktu
                                    Mulai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model="start_time"
                                    class="w-full h-14 px-6 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all">
                                @error('start_time')
                                    <p class="text-[10px] text-red-500 font-bold ml-1 uppercase tracking-wider">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                            <div class="space-y-3">
                                <label
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Waktu
                                    Selesai <span class="text-red-500">*</span></label>
                                <input type="datetime-local" wire:model="end_time"
                                    class="w-full h-14 px-6 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all">
                                @error('end_time')
                                    <p class="text-[10px] text-red-500 font-bold ml-1 uppercase tracking-wider">
                                        {{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Lokasi
                                Kegiatan <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-5 top-1/2 -translate-y-1/2 text-slate-300">location_on</span>
                                <input type="text" wire:model="location" placeholder="Gedung Posyandu / Balai Desa"
                                    class="w-full h-14 pl-14 pr-6 rounded-2xl border border-slate-100 bg-slate-50/50 text-sm font-bold text-slate-800 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-500/5 transition-all">
                            </div>
                            @error('location')
                                <p class="text-[10px] text-red-500 font-bold ml-1 uppercase tracking-wider">
                                    {{ $message }}</p>
                            @enderror
                        </div>

                        <div class="space-y-3">
                            <label
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] ml-1">Status
                                Kegiatan</label>
                            <x-forms.select-input wire:model="status" placeholder="" value="{{ $status }}">
                                <option value="upcoming" {{ $status === 'upcoming' ? 'selected' : '' }}>Mendatang
                                    (Upcoming)</option>
                                <option value="ongoing" {{ $status === 'ongoing' ? 'selected' : '' }}>Sedang
                                    Berlangsung (Ongoing)</option>
                                <option value="completed" {{ $status === 'completed' ? 'selected' : '' }}>Telah Selesai
                                    (Completed)</option>
                                <option value="cancelled" {{ $status === 'cancelled' ? 'selected' : '' }}>Dibatalkan
                                    (Cancelled)</option>
                            </x-forms.select-input>
                        </div>
                    </div>
                </div>

                <div
                    class="pt-10 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-6">
                    <div
                        class="flex items-center gap-4 p-4 bg-indigo-50/50 rounded-2xl border border-indigo-100/50 max-w-sm">
                        <span class="material-symbols-outlined text-indigo-600">notification_important</span>
                        <p class="text-[10px] font-bold text-indigo-800 uppercase tracking-widest leading-relaxed">
                            Perubahan waktu atau lokasi akan memperbarui jadwal yang dikirimkan melalui WhatsApp
                            pengingat otomatis.</p>
                    </div>
                    <div class="flex items-center gap-4 w-full sm:w-auto">
                        <a href="{{ route('admin.schedules.index') }}"
                            class="flex-1 sm:flex-none h-14 px-10 flex items-center justify-center text-sm font-black text-slate-400 uppercase tracking-widest hover:text-slate-600 transition-colors">
                            Batalkan
                        </a>
                        <button type="submit" wire:loading.attr="disabled"
                            class="flex-1 sm:flex-none h-14 px-12 bg-indigo-600 text-white rounded-2xl text-sm font-black uppercase tracking-widest hover:bg-indigo-700 transition-all shadow-xl shadow-indigo-500/20 flex items-center justify-center gap-3 active:scale-[0.98]">
                            <span wire:loading.remove class="material-symbols-outlined text-[20px]">sync</span>
                            <div wire:loading
                                class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            <span>Perbarui Jadwal</span>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
