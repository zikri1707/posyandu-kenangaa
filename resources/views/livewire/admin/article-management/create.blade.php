<div class="max-w-[1200px] mx-auto space-y-10 pb-20">

    {{-- ── Simplified Header ── --}}
    <div class="flex items-center justify-between gap-6">
        <div class="flex items-center gap-6">
            <a href="{{ route('admin.articles.index') }}" 
               class="w-14 h-14 flex items-center justify-center rounded-2xl bg-white border border-slate-200 text-slate-400 hover:text-indigo-600 hover:border-indigo-200 transition-all shadow-sm active:scale-95">
                <span class="material-symbols-outlined text-[28px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-3xl font-black text-slate-900 tracking-tight">Tulis Edukasi Baru</h1>
                <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] mt-1">Bagikan pengetahuan kesehatan kepada warga</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-[3rem] border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
        <form wire:submit.prevent="save" class="p-8 md:p-14 space-y-12">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-14">
                
                {{-- Left Side: Main Editor --}}
                <div class="lg:col-span-8 space-y-10">
                    {{-- Title Input --}}
                    <div class="space-y-4">
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Judul Konten <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" wire:model="title" 
                               placeholder="Contoh: Tips Menjaga Gizi Anak di Musim Hujan"
                               class="w-full h-20 px-8 rounded-3xl border-2 border-slate-50 bg-slate-50/50 text-2xl font-black text-slate-900 placeholder:text-slate-300 focus:outline-none focus:border-indigo-500 focus:bg-white focus:ring-8 focus:ring-indigo-500/5 transition-all">
                        @error('title') <p class="text-[11px] text-red-500 font-bold ml-4 uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>

                    {{-- Body Editor --}}
                    <div class="space-y-4" wire:ignore>
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Isi Artikel Edukasi <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea wire:model="content" id="article-editor" rows="15" 
                                  placeholder="Tuliskan isi artikel Anda di sini secara detail dan mudah dipahami..."
                                  class="w-full px-8 py-8 rounded-[2.5rem] border-2 border-slate-50 bg-slate-50/50 text-xl font-medium text-slate-700 leading-relaxed focus:outline-none focus:border-indigo-500 focus:bg-white transition-all resize-none shadow-inner"></textarea>
                    </div>
                    @error('content') <p class="text-[11px] text-red-500 font-bold ml-4 uppercase tracking-wider">{{ $message }}</p> @enderror
                </div>

                {{-- Right Side: Settings & Media --}}
                <div class="lg:col-span-4 space-y-10">
                    {{-- Thumbnail Section --}}
                    <div class="space-y-6">
                        <label class="flex items-center gap-2 text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] ml-2">
                            <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                            Gambar Sampul
                        </label>
                        <div class="relative group">
                            <div class="w-full aspect-[4/3] rounded-[2.5rem] bg-slate-50 border-4 border-dashed border-slate-200 flex flex-col items-center justify-center overflow-hidden transition-all group-hover:border-indigo-400 group-hover:bg-indigo-50/30">
                                {{-- Preview Image --}}
                                @if($thumbnail)
                                    <img src="{{ $thumbnail->temporaryUrl() }}" class="w-full h-full object-cover">
                                @else
                                    <div wire:loading.remove wire:target="thumbnail" class="flex flex-col items-center gap-4 text-slate-300">
                                        <div class="w-20 h-20 rounded-3xl bg-white flex items-center justify-center shadow-sm">
                                            <span class="material-symbols-outlined text-[48px]">add_photo_alternate</span>
                                        </div>
                                        <p class="text-[11px] font-black uppercase tracking-[0.2em]">Klik untuk Unggah</p>
                                    </div>
                                @endif

                                {{-- Loading State --}}
                                <div wire:loading wire:target="thumbnail" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center gap-4 z-20">
                                    <div class="w-12 h-12 border-4 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                                    <p class="text-[11px] font-black uppercase tracking-[0.2em] text-indigo-600">Sedang Mengunggah...</p>
                                </div>

                                <input type="file" wire:model="thumbnail" class="absolute inset-0 opacity-0 cursor-pointer z-30">
                            </div>
                        </div>
                        <div class="p-6 rounded-2xl bg-amber-50 border border-amber-100/50 flex items-start gap-4">
                            <span class="material-symbols-outlined text-amber-500 text-[20px]">info</span>
                            <p class="text-[10px] font-bold text-amber-700 leading-relaxed uppercase tracking-wide">
                                Gunakan gambar landscape dengan resolusi tinggi agar terlihat bagus di layar pembaca.
                            </p>
                        </div>
                        @error('thumbnail') <p class="text-[11px] text-red-500 font-bold text-center uppercase tracking-wider">{{ $message }}</p> @enderror
                    </div>

                    {{-- Classification Bento Card --}}
                    <div class="p-8 bg-slate-900 rounded-[2.5rem] text-white space-y-8 shadow-2xl relative overflow-hidden">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-indigo-500/20 rounded-full blur-3xl"></div>
                        
                        <div class="space-y-4 relative z-10">
                            <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.3em] ml-1">Pilih Kategori</label>
                            <div class="relative">
                                <select wire:model="category_id"
                                        class="w-full h-16 px-6 rounded-2xl bg-white/10 border border-white/10 text-sm font-black uppercase tracking-widest text-white focus:outline-none focus:border-indigo-400 transition-all appearance-none cursor-pointer backdrop-blur-md">
                                    <option value="" class="bg-slate-900">Pilih Kategori</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" class="bg-slate-900">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-indigo-300 pointer-events-none">expand_more</span>
                            </div>
                            @error('category_id') <p class="text-[10px] text-red-400 font-bold ml-1 uppercase tracking-wider">{{ $message }}</p> @enderror
                        </div>

                        <div class="space-y-4 relative z-10">
                            <label class="block text-[10px] font-black text-indigo-300 uppercase tracking-[0.3em] ml-1">Status Publikasi</label>
                            <div class="grid grid-cols-2 gap-3">
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="status" name="status" value="published" class="sr-only peer">
                                    <div class="w-full h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-white/5 text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-emerald-500 peer-checked:text-white peer-checked:border-emerald-500 peer-checked:shadow-[0_0_20px_rgba(16,185,129,0.3)] transition-all">
                                        Terbit
                                    </div>
                                </label>
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="status" name="status" value="draft" class="sr-only peer">
                                    <div class="w-full h-14 flex items-center justify-center rounded-2xl bg-white/5 border border-white/5 text-[10px] font-black uppercase tracking-widest text-slate-400 peer-checked:bg-white peer-checked:text-slate-900 peer-checked:border-white transition-all">
                                        Draf
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sticky Footer Actions --}}
            <div class="pt-10 border-t border-slate-50 flex flex-col md:flex-row items-center justify-end gap-6">
                <a href="{{ route('admin.articles.index') }}" 
                   class="w-full md:w-auto h-16 px-10 flex items-center justify-center text-[11px] font-black text-slate-400 uppercase tracking-[0.2em] hover:text-slate-600 transition-colors">
                    Batalkan Perubahan
                </a>
                <button type="submit" wire:loading.attr="disabled"
                        class="w-full md:w-80 h-20 bg-indigo-600 hover:bg-indigo-700 text-white rounded-[1.5rem] text-sm font-black uppercase tracking-[0.2em] shadow-2xl shadow-indigo-600/30 flex items-center justify-center gap-4 transition-all active:scale-95 group">
                    <span wire:loading.remove class="material-symbols-outlined text-[28px] group-hover:translate-x-1 transition-transform">send</span>
                    <div wire:loading class="w-6 h-6 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <span>Simpan & Terbitkan</span>
                </button>
            </div>
        </form>
    </div>
</div>