@extends('layouts.admin-layout')

@section('admin-content')
<div class="space-y-8 p-6 md:p-8 pt-0 md:pt-0" 
     x-data="{ 
         mediaFilter: 'all', 
         lightboxOpen: false, 
         isEditing: false,
         activeId: '',
         activeMedia: '', 
         activeType: 'image', 
         activeTitle: '', 
         activeDesc: '', 
         activeDate: '',
         openLightbox(id, src, type, title, desc, date) {
             this.activeId = id;
             this.activeMedia = src;
             this.activeType = type;
             this.activeTitle = title;
             this.activeDesc = desc;
             this.activeDate = date;
             this.isEditing = false;
             this.lightboxOpen = true;
         }
     }">
    
    {{-- Breadcrumb & Date --}}
    <x-breadcrumb />

    {{-- ── Modern Header with Hero Mesh Banner (Dark Slate Blue theme for Album view) ── --}}
    <div class="relative rounded-[2rem] p-8 md:p-10 overflow-hidden text-white shadow-2xl shadow-emerald-100"
         style="background-color: #0b1a30; background-image: radial-gradient(at 0% 0%, hsla(168, 76%, 36%, 0.4) 0px, transparent 50%), radial-gradient(at 100% 0%, hsla(180, 60%, 50%, 0.2) 0px, transparent 50%);">
        {{-- Decorative Elements --}}
        <div class="absolute -right-20 -top-20 w-80 h-80 bg-white/10 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/carbon-fibre.png')] opacity-5"></div>
        
        <div class="relative z-10 flex flex-col lg:flex-row lg:items-center justify-between gap-12">
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <a href="{{ route('admin.gallery.index') }}" class="text-teal-400 hover:text-teal-300 flex items-center gap-1.5 text-xs font-black uppercase tracking-wider transition-colors">
                        <span class="material-symbols-outlined text-[16px] leading-none">arrow_back</span>
                        Semua Folder
                    </a>
                </div>
                <h1 class="text-2xl md:text-3xl font-black tracking-tight leading-tight text-white flex items-center gap-2.5">
                    <span class="material-symbols-outlined text-[28px] text-teal-400">folder_open</span>
                    {{ $folder->name }}
                </h1>
                <p class="text-sm text-white/80 font-medium max-w-2xl leading-relaxed">
                    {{ $folder->description ?? 'Tidak ada deskripsi folder.' }}
                </p>
                <div class="flex flex-wrap items-center gap-3 pt-2">
                    <span class="px-3 py-1 bg-white/10 text-white rounded-full text-[9px] font-black uppercase tracking-widest border border-white/10">
                        Posyandu: {{ $folder->posyandu->name ?? 'Semua Posyandu' }}
                    </span>
                    <span class="px-3 py-1 bg-teal-500/20 text-teal-300 rounded-full text-[9px] font-black uppercase tracking-widest border border-teal-500/20">
                        Kader: {{ $folder->user->full_name ?? 'Petugas Posyandu' }}
                    </span>
                </div>
            </div>

            <div class="flex flex-wrap gap-3 shrink-0">
                <a href="{{ route('admin.gallery.edit', $folder->id) }}" 
                   class="h-12 px-5 flex items-center gap-2 border border-white/20 bg-white/5 hover:bg-white/10 text-white rounded-2xl text-xs font-black uppercase tracking-wider transition-all hover:-translate-y-0.5 active:scale-95">
                    <span class="material-symbols-outlined text-[18px] leading-none">edit</span>
                    Edit Folder
                </a>
                <a href="{{ route('admin.gallery.media.create', $folder->id) }}" 
                   class="h-12 px-6 flex items-center gap-2 bg-gradient-to-r from-teal-500 to-emerald-600 hover:from-teal-600 hover:to-emerald-700 text-white rounded-2xl text-xs font-black uppercase tracking-wider shadow-lg shadow-teal-500/20 transition-all hover:-translate-y-0.5 active:scale-95 group">
                    <span class="material-symbols-outlined text-[18px] group-hover:rotate-90 transition-transform">add_to_photos</span>
                    Unggah Media Baru
                </a>
            </div>
        </div>
    </div>

    {{-- ── Filter Tab Bar (Premium Styling) ── --}}
    <div class="bg-white rounded-[2rem] border border-slate-100 p-6 flex flex-col md:flex-row gap-6 items-center justify-between shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
        <p class="text-xs text-slate-400 font-black uppercase tracking-widest">Filter Media Album:</p>
        
        <div class="flex items-center bg-slate-100 p-1 rounded-2xl border border-slate-200">
            <button @click="mediaFilter = 'all'" :class="mediaFilter === 'all' ? 'bg-slate-900 text-white shadow-md' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                Semua ({{ $galleries->count() }})
            </button>
            <button @click="mediaFilter = 'photo'" :class="mediaFilter === 'photo' ? 'bg-teal-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                Foto
            </button>
            <button @click="mediaFilter = 'video'" :class="mediaFilter === 'video' ? 'bg-indigo-600 text-white shadow-md' : 'text-slate-600 hover:text-slate-900'" class="px-5 py-2.5 rounded-xl text-xs font-black uppercase tracking-wider transition-all">
                Video
            </button>
        </div>
    </div>

    {{-- ── Media Grid (Visual-First Gallery Grid) ── --}}
    @if($galleries->isEmpty())
        <div class="bg-white rounded-[2.5rem] border border-slate-100 py-24 text-center">
            <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <span class="material-symbols-outlined text-slate-200 text-[40px]">collections</span>
            </div>
            <h3 class="text-lg font-black text-slate-800 mb-1">Folder Masih Kosong</h3>
            <p class="text-sm text-slate-400 font-medium mb-8">Unggah foto atau video pertama untuk mengisi album kegiatan ini.</p>
            <a href="{{ route('admin.gallery.media.create', $folder->id) }}" 
               class="inline-flex items-center gap-2 px-6 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-black rounded-2xl hover:from-teal-700 hover:to-emerald-700 transition-all text-xs uppercase tracking-widest shadow-md">
                <span class="material-symbols-outlined text-[18px]">add</span>
                Unggah Media Sekarang
            </a>
        </div>
    @else
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @foreach($galleries as $gallery)
                @php
                    $isVideo = ($gallery->type === 'video');
                @endphp
                <div x-show="mediaFilter === 'all' || (mediaFilter === 'photo' && !{{ $isVideo ? 'true' : 'false' }}) || (mediaFilter === 'video' && {{ $isVideo ? 'true' : 'false' }})"
                     class="bg-white rounded-[2.25rem] overflow-hidden group hover:shadow-[0_20px_40px_rgba(13,148,136,0.1)] border border-slate-100 transition-all duration-500 flex flex-col h-full relative cursor-pointer"
                     x-data="{ playing: false }"
                     @click="openLightbox('{{ $gallery->id }}', '{{ asset('storage/' . $gallery->photo) }}', '{{ $gallery->type }}', {{ \Illuminate\Support\Js::from($gallery->title) }}, {{ \Illuminate\Support\Js::from($gallery->description ?? '') }}, '{{ \Carbon\Carbon::parse($gallery->created_at)->translatedFormat('d M Y, H:i') }}')">
                    
                    {{-- Media Card Container --}}
                    <div class="aspect-square relative overflow-hidden bg-slate-100 flex items-center justify-center w-full h-full">
                        @if($isVideo)
                            <video x-ref="vid" src="{{ asset('storage/' . $gallery->photo) }}#t=0.5" 
                                   preload="metadata"
                                   muted loop playsinline class="w-full h-full object-cover transition-all duration-500"
                                   @mouseenter="$refs.vid.play(); playing = true" 
                                   @mouseleave="$refs.vid.pause(); $refs.vid.currentTime = 0.5; playing = false"></video>
                            
                            {{-- Video Indicator Badge (Always Visible) --}}
                            <div class="absolute top-4 right-4 z-20 transition-opacity duration-300" :class="playing ? 'opacity-0' : 'opacity-100'">
                                <div class="bg-indigo-600/90 text-white backdrop-blur-md px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-indigo-400/20 shadow-md">
                                    <span class="material-symbols-outlined text-[12px] leading-none">videocam</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest leading-none">Video</span>
                                </div>
                            </div>
                            
                            {{-- Play Icon Overlay (Always Visible When Not Hovered) --}}
                            <div class="absolute inset-0 bg-black/25 flex items-center justify-center pointer-events-none transition-opacity duration-300" :class="playing ? 'opacity-0' : 'opacity-100'">
                                <div class="w-12 h-12 rounded-full bg-white/20 backdrop-blur-md border border-white/40 flex items-center justify-center shadow-lg text-white">
                                    <span class="material-symbols-outlined text-[24px] fill-current">play_arrow</span>
                                </div>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $gallery->photo) }}" 
                                 alt="{{ $gallery->title }}"
                                 class="w-full h-full object-cover transition-transform duration-1000 group-hover:scale-105">
                            
                            {{-- Photo Indicator Badge --}}
                            <div class="absolute top-4 right-4 z-20">
                                <div class="bg-teal-600/90 text-white backdrop-blur-md px-2.5 py-1 rounded-full flex items-center gap-1.5 border border-teal-400/20 shadow-md">
                                    <span class="material-symbols-outlined text-[12px] leading-none">image</span>
                                    <span class="text-[8px] font-black uppercase tracking-widest leading-none">Foto</span>
                                </div>
                            </div>
                        @endif

                        {{-- Top-Left Delete Button (Separated from FOTO/VIDEO Badge) --}}
                        <div class="absolute top-4 left-4 z-30 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                            <form action="{{ route('admin.gallery.media.destroy', [$folder->id, $gallery->id]) }}" method="POST" 
                                  onsubmit="return confirm('Yakin ingin menghapus item media ini?');">
                                @csrf @method('DELETE')
                                <button type="submit" @click.stop
                                        class="w-8 h-8 bg-red-500 hover:bg-red-600 text-white rounded-xl flex items-center justify-center shadow-lg transition-all active:scale-90"
                                        title="Hapus Media">
                                    <span class="material-symbols-outlined text-[16px]">delete</span>
                                </button>
                            </form>
                        </div>

                        {{-- Gradient Overlay on Hover --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent opacity-0 group-hover:opacity-100 transition-all duration-300 z-10 flex flex-col justify-end p-5">
                            {{-- Bottom Info Row --}}
                            <div class="space-y-1 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                <h4 class="text-xs font-black text-white line-clamp-1 leading-snug" title="{{ $gallery->title }}">
                                    {{ $gallery->title }}
                                </h4>
                                @if($gallery->description)
                                    <p class="text-[9px] font-bold text-slate-300 line-clamp-1">
                                        {{ $gallery->description }}
                                    </p>
                                @else
                                    <p class="text-[9px] font-bold text-slate-400 italic">
                                        Ketuk untuk memperbesar...
                                    </p>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            @endforeach
        </div>

        <div class="mt-12">
            {{ $galleries->links() }}
        </div>
    @endif

    {{-- ── Lightbox Modal Overlay (Premium Center-Float & Teal-Consistent Theme) ── --}}
    <div x-show="lightboxOpen" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex flex-col items-center justify-between bg-teal-950/70 backdrop-blur-md p-4 sm:p-8"
         style="display: none;"
         @keydown.escape.window="lightboxOpen = false"
         @click="lightboxOpen = false">
        
        {{-- Top Row: Type Badge, Edit/Delete, and Close Button --}}
        <div class="w-full flex items-center justify-between z-50" @click.stop>
            <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 bg-white/90 text-teal-800 rounded-full text-xs font-black uppercase tracking-widest border border-slate-100 shadow-sm">
                <span class="material-symbols-outlined text-[16px] leading-none" x-text="activeType === 'video' ? 'videocam' : 'image'"></span>
                <span class="leading-none" x-text="activeType === 'video' ? 'Video' : 'Foto'"></span>
            </span>
            
            <div class="flex items-center gap-3">
                <!-- Edit Button -->
                <button @click="isEditing = !isEditing" class="w-12 h-12 rounded-full bg-white hover:bg-slate-100 text-slate-700 flex items-center justify-center transition-all shadow-lg active:scale-95" title="Edit Judul & Keterangan">
                    <span class="material-symbols-outlined text-[24px]" x-text="isEditing ? 'edit_off' : 'edit'"></span>
                </button>
                
                <!-- Delete Button -->
                <form :action="'{{ route('admin.gallery.media.destroy', [$folder->id, ':id']) }}'.replace(':id', activeId)" method="POST" onsubmit="return confirm('Yakin ingin menghapus media ini secara permanen?');">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-12 h-12 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-all shadow-lg active:scale-95" title="Hapus Media">
                        <span class="material-symbols-outlined text-[24px]">delete</span>
                    </button>
                </form>

                <button @click="lightboxOpen = false" class="w-12 h-12 rounded-full bg-teal-600 hover:bg-teal-700 text-white flex items-center justify-center transition-all shadow-lg shadow-teal-900/20 active:scale-95">
                    <span class="material-symbols-outlined text-[24px]">close</span>
                </button>
            </div>
        </div>

        {{-- Center Row: Unified Media & Caption Card (Shrinks dynamically to fit aspect ratio!) ── --}}
        <div class="flex-1 w-full flex items-center justify-center p-2 my-4 z-40">
            <div class="bg-white rounded-[2rem] overflow-hidden shadow-2xl border-4 border-white flex flex-col w-fit max-w-[95vw] md:max-w-3xl max-h-[85vh] transition-all duration-300" @click.stop>
                
                {{-- Media Viewport (Auto-scaling canvas) --}}
                <div class="bg-slate-50/50 flex items-center justify-center p-2 relative overflow-hidden">
                    <img :src="activeMedia" 
                         x-show="activeType !== 'video'" 
                         class="w-auto h-auto max-w-full max-h-[55vh] object-contain rounded-xl select-none shadow-sm">
                    
                    <video :src="activeMedia" 
                           x-show="activeType === 'video'" 
                           controls 
                           autoplay 
                           class="w-auto h-auto max-w-full max-h-[55vh] object-contain rounded-xl bg-transparent mx-auto shadow-sm"></video>
                </div>

                {{-- Caption Container (Matches the media width exactly with a safe min-width) --}}
                <div class="bg-white p-6 border-t border-slate-100 flex flex-col justify-between gap-4 min-w-[300px] md:min-w-[500px]">
                    <div x-show="!isEditing" class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-6 w-full">
                        <div class="space-y-1.5 flex-1 min-w-0">
                            <h3 class="text-lg font-black text-slate-800 leading-snug truncate" x-text="activeTitle || 'Tanpa Judul'"></h3>
                            <p class="text-sm text-slate-500 font-semibold leading-relaxed line-clamp-2" x-text="activeDesc || 'Tidak ada keterangan tambahan.'"></p>
                        </div>
                        <div class="shrink-0 flex flex-col items-start sm:items-end gap-1 text-[10px] text-slate-400 font-bold border-t sm:border-t-0 pt-3 sm:pt-0 w-full sm:w-auto">
                            <div class="flex items-center gap-1.5">
                                <span class="material-symbols-outlined text-[14px]">schedule</span>
                                <span>Diunggah:</span>
                            </div>
                            <span class="text-slate-600 font-black" x-text="activeDate"></span>
                        </div>
                    </div>
                    
                    <div x-show="isEditing" class="w-full">
                        <form :action="'{{ route('admin.gallery.media.update', [$folder->id, ':id']) }}'.replace(':id', activeId)" method="POST" class="w-full space-y-4">
                            @csrf @method('PUT')
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Judul Media</label>
                                <input type="text" name="title" x-model="activeTitle" class="w-full h-11 bg-slate-50 focus:bg-white rounded-xl px-4 text-sm font-semibold text-slate-700 border-2 border-slate-100 focus:border-teal-500 focus:ring-0 transition-all" required>
                            </div>
                            <div class="space-y-2">
                                <label class="block text-xs font-black text-slate-400 uppercase tracking-widest">Keterangan Media</label>
                                <textarea name="description" x-model="activeDesc" rows="2" class="w-full bg-slate-50 focus:bg-white rounded-xl px-4 py-3 text-sm font-semibold text-slate-700 border-2 border-slate-100 focus:border-teal-500 focus:ring-0 transition-all"></textarea>
                            </div>
                            <div class="flex justify-end gap-3 pt-2">
                                <button type="button" @click="isEditing = false" class="px-5 py-2.5 bg-slate-100 text-slate-600 hover:bg-slate-200 font-black rounded-xl text-xs uppercase tracking-wider transition-all">Batal</button>
                                <button type="submit" class="px-5 py-2.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-black rounded-xl text-xs uppercase tracking-wider transition-all shadow-md">Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>

    </div>

</div>
@endsection