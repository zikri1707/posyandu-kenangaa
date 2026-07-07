<div class="space-y-8 p-6 md:p-8 pt-0 md:pt-0">
    
    {{-- Breadcrumb & Date --}}
    <x-breadcrumb />

    {{-- â”€â”€ Modern Header with Hero Mesh Banner â”€â”€ --}}
    <div class="relative rounded-[2rem] p-8 md:p-10 overflow-hidden text-white shadow-2xl shadow-emerald-100"
         style="background-color: #064e3b; background-image: radial-gradient(at 0% 0%, hsla(161, 84%, 39%, 0.5) 0px, transparent 50%), radial-gradient(at 50% 0%, hsla(168, 76%, 36%, 0.5) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(172, 66%, 50%, 0.3) 0px, transparent 50%);">
        {{-- Decorative Elements --}}
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12">
            <div class="space-y-4">
                <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-white/10 backdrop-blur-md border border-white/10 text-[10px] font-black uppercase tracking-[0.3em] text-white -mt-4 mb-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    Documentation & Media
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight text-white flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-[28px] text-emerald-300">collections</span>
                    Manajemen Galeri
                </h1>
                <p class="text-sm text-white font-medium max-w-2xl leading-relaxed">
                    Dokumentasikan momen-momen, foto kegiatan, dan rekaman video penting di Posyandu Kenanga secara rapi dalam folder album.
                </p>
            </div>

            <div class="flex items-center shrink-0">
                <a href="{{ route('admin.gallery.create') }}"
                   class="h-12 px-6 flex items-center gap-2 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all hover:-translate-y-0.5 active:scale-95 group">
                    <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform">create_new_folder</span>
                    Tambah Folder Baru
                </a>
            </div>
        </div>
    </div>

    {{-- â”€â”€ Search Bar â”€â”€ --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 p-6 flex flex-col lg:flex-row gap-6 items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
        <div class="relative w-full group">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-300 group-focus-within:text-teal-500 transition-colors pointer-events-none">search</span>
            <input type="text" wire:model.live="search" placeholder="Cari nama folder kegiatan..." 
                class="w-full h-12 pl-12 pr-4 bg-slate-50 border-transparent rounded-2xl text-sm font-semibold text-slate-700 focus:bg-white focus:ring-0 focus:border-teal-500 transition-all border-2 border-slate-100">
        </div>
    </div>

    {{-- â”€â”€ Gallery Folders Grid â”€â”€ --}}
    @if($folders->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-slate-100 py-24 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-slate-200 text-[40px]">folder_off</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Belum ada Folder</h3>
            <p class="text-sm text-slate-400 font-medium mb-8">Buat folder baru terlebih dahulu untuk mulai mendokumentasikan kegiatan Posyandu.</p>
            <a href="{{ route('admin.gallery.create') }}" 
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-black rounded-2xl hover:from-teal-700 hover:to-emerald-700 transition-all text-xs uppercase tracking-widest shadow-md">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Tambah Folder Baru
            </a>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 pt-6">
            @foreach($folders as $folder)
                <div class="relative bg-white rounded-[2rem] border border-slate-100 group hover:border-teal-200 hover:shadow-[0_20px_50px_rgba(13,148,136,0.08)] transition-all duration-500 flex flex-col h-full mt-4">
                    
                    {{-- Folder Tab Top-Left Shape --}}
                    <div class="absolute -top-3 left-6 h-4 w-28 bg-white border-t border-x border-slate-100 rounded-t-xl group-hover:border-teal-200 transition-colors z-0"></div>
                    {{-- Folder Tab Border Mask --}}
                    <div class="absolute -top-[1px] left-[25px] h-[3px] w-[110px] bg-white z-10"></div>
                    
                    {{-- Folder Cover / Preview --}}
                    <div class="aspect-[4/3] relative overflow-hidden bg-slate-55 flex items-center justify-center rounded-[1.5rem] m-3 mb-0 border border-slate-100/40 z-20">
                        @if($folder->cover_photo)
                            <img src="{{ asset('storage/' . $folder->cover_photo) }}" 
                                 alt="{{ $folder->name }}"
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                        @else
                            {{-- Folder Placeholder Graphic (Gradient Pastel) --}}
                            <div class="absolute inset-0 bg-gradient-to-tr from-teal-50/60 to-slate-50/20"></div>
                            <div class="flex flex-col items-center justify-center text-teal-600/80 group-hover:scale-110 transition-transform duration-500 relative z-10">
                                <span class="material-symbols-outlined text-[80px]" style="font-variation-settings: 'FILL' 1; font-weight:200;">folder</span>
                            </div>
                        @endif

                        {{-- High Contrast Media Count Badge --}}
                        <div class="absolute top-4 right-4 z-20 shadow-md">
                            <div class="bg-gradient-to-r from-teal-600 to-emerald-600 text-white px-3 py-1.5 rounded-xl flex items-center gap-1.5 border border-white/20">
                                <span class="material-symbols-outlined text-[14px] text-white">photo_library</span>
                                <span class="text-[10px] font-black uppercase tracking-widest text-white">{{ $folder->galleries_count }} Media</span>
                            </div>
                        </div>

                        {{-- Action Overlay --}}
                        <div class="absolute inset-0 bg-slate-900/60 opacity-0 lg:group-hover:opacity-100 transition-all duration-300 backdrop-blur-[2px] hidden lg:flex items-center justify-center gap-4 z-30">
                            <a href="{{ route('admin.gallery.show', $folder->id) }}" 
                               class="w-12 h-12 bg-white text-slate-700 rounded-2xl flex items-center justify-center shadow-lg hover:bg-teal-600 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300"
                               title="Buka Folder">
                                <span class="material-symbols-outlined text-[22px]">folder_open</span>
                            </a>
                            <a href="{{ route('admin.gallery.edit', $folder->id) }}" 
                               class="w-12 h-12 bg-white text-slate-700 rounded-2xl flex items-center justify-center shadow-lg hover:bg-indigo-600 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300"
                               title="Edit Folder">
                                <span class="material-symbols-outlined text-[22px]">edit</span>
                            </a>
                            <form action="{{ route('admin.gallery.destroy', $folder->id) }}" method="POST" 
                                  onsubmit="return confirm('Menghapus folder ini akan menghapus SELURUH foto dan video di dalamnya. Yakin ingin melanjutkan?');">
                                @csrf @method('DELETE')
                                <button type="submit" 
                                        class="w-12 h-12 bg-white text-red-500 rounded-2xl flex items-center justify-center shadow-lg hover:bg-red-500 hover:text-white transition-all transform translate-y-4 group-hover:translate-y-0 duration-300"
                                        title="Hapus Folder">
                                    <span class="material-symbols-outlined text-[22px]">delete</span>
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Folder Info --}}
                    <div class="p-6 pt-4 flex-1 flex flex-col justify-between space-y-4 z-20">
                        <div class="space-y-2">
                            <span class="px-2.5 py-1 bg-slate-50 text-slate-500 rounded-lg text-[9px] font-black uppercase tracking-widest border border-slate-100">
                                {{ $folder->posyandu->name ?? 'Semua Posyandu' }}
                            </span>
                            <a href="{{ route('admin.gallery.show', $folder->id) }}" class="block">
                                <h3 class="text-lg font-black text-slate-800 leading-snug line-clamp-1 hover:text-teal-600 transition-colors" title="{{ $folder->name }}">
                                    {{ $folder->name }}
                                </h3>
                            </a>
                            <p class="text-xs font-semibold text-slate-400 line-clamp-2 leading-relaxed">
                                {{ $folder->description ?? 'Tidak ada deskripsi folder.' }}
                            </p>
                        </div>
                        <div class="text-[10px] text-slate-400 font-bold flex items-center justify-between pt-4 border-t border-slate-50 flex-wrap gap-2">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                {{ \Carbon\Carbon::parse($folder->created_at)->translatedFormat('d M Y') }}
                            </div>
                            
                            {{-- Mobile & Tablet Action Buttons --}}
                            <div class="flex items-center gap-1 lg:hidden">
                                <a href="{{ route('admin.gallery.edit', $folder->id) }}" class="p-1 text-slate-500 hover:text-indigo-600 flex items-center justify-center transition-colors" title="Edit Folder">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.gallery.destroy', $folder->id) }}" method="POST" class="inline" onsubmit="return confirm('Menghapus folder ini akan menghapus SELURUH foto dan video di dalamnya. Yakin ingin melanjutkan?');">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-1 text-red-500 hover:text-red-700 flex items-center justify-center transition-colors" title="Hapus Folder">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>

                            <a href="{{ route('admin.gallery.show', $folder->id) }}" class="text-teal-600 hover:text-teal-800 flex items-center gap-0.5 font-black uppercase tracking-wider text-[9px] ml-auto lg:ml-0">
                                Buka
                                <span class="material-symbols-outlined text-[12px]">arrow_forward</span>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $folders->links() }}
        </div>
    @endif
</div>