<div class="max-w-7xl mx-auto space-y-8 pb-20">
    
    {{-- ── Header ── --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <h2 class="text-3xl font-black text-slate-800 tracking-tight">Manajemen Galeri</h2>
            <p class="text-sm text-slate-400 font-medium mt-1">Dokumentasi kegiatan dan momen bermakna di Posyandu.</p>
        </div>
        <x-button href="{{ route('admin.gallery.create') }}" variant="secondary" icon="add_photo_alternate">Tambah Foto</x-button>
    </div>

    {{-- ── Search & Filter ── --}}
    <div class="bg-white rounded-[32px] border border-slate-100 p-6 flex flex-col md:flex-row gap-4 items-center">
        <div class="relative flex-1 w-full group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors pointer-events-none" style="font-variation-settings: 'wght' 300;">search</span>
            <input type="text" wire:model.live="search" placeholder="Cari judul kegiatan atau foto..." 
                class="w-full h-12 pl-12 pr-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2">
        </div>
        {{-- Optional: Add Posyandu Filter here if needed --}}
    </div>

    {{-- ── Gallery Grid ── --}}
    @if($galleries->isEmpty())
        <div class="bg-white rounded-[40px] border border-slate-100 py-24 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-slate-200 text-[40px]" style="font-variation-settings: 'wght' 100;">image_not_supported</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Belum ada Foto</h3>
            <p class="text-sm text-slate-400 font-medium mb-8">Dokumentasikan kegiatan Posyandu Anda di sini.</p>
            <x-button href="{{ route('admin.gallery.create') }}" variant="secondary" icon="add">Tambah Sekarang</x-button>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($galleries as $gallery)
                <div class="bg-white rounded-[32px] border border-slate-100 overflow-hidden group hover:border-teal-500/30 transition-all duration-500">
                    {{-- Image Container --}}
                    <div class="aspect-[4/3] relative overflow-hidden bg-slate-100">
                        <img src="{{ asset('storage/' . $gallery->photo) }}" 
                             alt="{{ $gallery->title }}"
                             class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                        
                        {{-- Featured Badge --}}
                        @if($gallery->is_featured)
                            <div class="absolute top-4 left-4">
                                <div class="bg-white/90 backdrop-blur px-3 py-1.5 rounded-full flex items-center gap-1.5 border border-white/50 shadow-sm">
                                    <span class="material-symbols-outlined text-amber-500 text-[14px]">star</span>
                                    <span class="text-[9px] font-black text-slate-800 uppercase tracking-widest">Featured</span>
                                </div>
                            </div>
                        @endif

                        {{-- Action Overlay (Minimalist) --}}
                        <div class="absolute inset-0 bg-slate-900/40 opacity-0 group-hover:opacity-100 transition-all duration-300 backdrop-blur-[2px] flex items-center justify-center gap-3">
                            <a href="{{ route('admin.gallery.edit', $gallery->id) }}" 
                               class="w-11 h-11 bg-white text-slate-700 rounded-full flex items-center justify-center shadow-lg hover:bg-teal-500 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 delay-75">
                                <span class="material-symbols-outlined text-[20px]">edit</span>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $gallery->id) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus foto ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-11 h-11 bg-white text-red-500 rounded-full flex items-center justify-center shadow-lg hover:bg-red-500 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 delay-150">
                                    <span class="material-symbols-outlined text-[20px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Content --}}
                    <div class="p-7 space-y-3">
                        <div class="flex items-center gap-2">
                            <span class="px-2.5 py-1 bg-slate-50 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-tighter">
                                {{ $gallery->posyandu->name ?? 'Semua Posyandu' }}
                            </span>
                        </div>
                        <h3 class="text-base font-black text-slate-800 leading-snug line-clamp-1" title="{{ $gallery->title }}">
                            {{ $gallery->title }}
                        </h3>
                        <p class="text-xs font-medium text-slate-400 line-clamp-2 leading-relaxed italic">
                            "{{ $gallery->description }}"
                        </p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12 px-4">
            {{ $galleries->links() }}
        </div>
    @endif
</div>
