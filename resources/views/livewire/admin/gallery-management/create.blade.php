@extends('layouts.admin-layout')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-8 pb-20">
    {{-- Breadcrumb & Date --}}
    <x-breadcrumb />

    {{-- Header --}}
    <div class="relative pl-6 mb-8">
        {{-- Vertical Bar --}}
        <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full"></div>
        
        <h2 class="text-3xl font-black text-slate-800 tracking-tight leading-none">Unggah Media Baru</h2>
        <div class="flex flex-wrap items-center gap-2 mt-3">
            <span class="text-xs font-bold text-slate-400">Folder Tujuan:</span>
            <span class="px-3 py-1 bg-teal-50 border border-teal-100 rounded-xl text-xs font-black text-teal-700 shadow-sm">
                {{ $folder->name }}
            </span>
        </div>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
        <form action="{{ route('admin.gallery.media.store', $folder->id) }}" method="POST" enctype="multipart/form-data" onsubmit="handleFormSubmit(event)">
            @csrf
            <div class="space-y-8">
                <!-- Upload Area (Drag & Drop Zone Premium) -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Pilih File Media</label>
                    <div class="flex flex-col items-center p-10 bg-slate-50 border-3 border-dashed border-slate-200 rounded-[2rem] hover:border-teal-500 hover:bg-slate-50/50 transition-all group relative cursor-pointer">
                        
                        {{-- Bulk Media Previews Grid --}}
                        <div id="mediaPreviewsGrid" class="hidden grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4 w-full mb-6 max-h-[360px] overflow-y-auto p-4 bg-white rounded-2xl border border-slate-100 shadow-inner">
                            <!-- Previews dynamic content will go here -->
                        </div>

                        <div id="placeholder" class="flex flex-col items-center text-center">
                            <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-teal-600 shadow-md mb-4 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[32px]">photo_camera_back</span>
                            </div>
                            <p class="text-base font-black text-slate-700">Klik atau geser file gambar/video ke sini</p>
                            <p class="text-xs text-slate-400 mt-2 font-bold uppercase tracking-wider">Bisa pilih sekaligus banyak file | Maks. 1GB per file</p>
                        </div>

                        <input type="file" name="photos[]" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="imageUpload" accept="image/*,video/*" multiple onchange="previewFiles(event)">
                    </div>
                    @error('photos') 
                        <p class="mt-3 text-xs text-red-500 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Judul Media (Opsional)</label>
                        <input type="text" name="title" value="{{ old('title') }}" placeholder="Kosongkan untuk menggunakan nama file asli masing-masing media" class="w-full h-12 bg-slate-50 border-transparent focus:bg-white rounded-2xl px-5 text-sm font-semibold text-slate-700 focus:ring-0 focus:border-teal-500 transition-all border-2 border-slate-100">
                        @error('title') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Keterangan Tambahan (Opsional)</label>
                        <textarea name="description" rows="3" placeholder="Ceritakan detail singkat mengenai foto/video kegiatan ini..." class="w-full bg-slate-50 border-transparent focus:bg-white rounded-2xl px-5 py-4 text-sm font-semibold text-slate-700 focus:ring-0 focus:border-teal-500 transition-all border-2 border-slate-100">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>

            <div class="flex justify-end mt-12 gap-4">
                <a href="{{ route('admin.gallery.show', $folder->id) }}" class="px-8 py-3.5 bg-slate-100 text-slate-600 font-black rounded-2xl hover:bg-slate-200 transition-all text-xs uppercase tracking-widest">Batal</a>
                <button type="submit" id="submitBtn" class="px-8 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-black rounded-2xl hover:from-teal-700 hover:to-emerald-700 hover:shadow-lg hover:shadow-teal-600/20 active:scale-95 transition-all text-xs uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">publish</span>
                    Unggah Media
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    let selectedFiles = [];

    function handleFormSubmit(event) {
        if (selectedFiles.length === 0) {
            event.preventDefault();
            alert('Wajib memilih minimal satu file media.');
            return;
        }

        // Sync selectedFiles back to the input element files
        const input = document.getElementById('imageUpload');
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => {
            dataTransfer.items.add(file);
        });
        input.files = dataTransfer.files;

        const btn = document.getElementById('submitBtn');
        if (btn) {
            btn.disabled = true;
            btn.style.opacity = '0.7';
            btn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Mengunggah...
            `;
        }
    }

    function previewFiles(event) {
        const input = event.target;
        
        if (input.files && input.files.length > 0) {
            Array.from(input.files).forEach(file => {
                const isDuplicate = selectedFiles.some(f => f.name === file.name && f.size === file.size);
                if (!isDuplicate) {
                    selectedFiles.push(file);
                }
            });
        }
        
        // Reset the value so the input's onchange fires even if selecting the same file
        input.value = '';
        
        renderPreviews();
    }

    function renderPreviews() {
        const previewsGrid = document.getElementById('mediaPreviewsGrid');
        const placeholder = document.getElementById('placeholder');
        
        previewsGrid.innerHTML = '';
        
        if (selectedFiles.length > 0) {
            previewsGrid.classList.remove('hidden');
            placeholder.classList.add('hidden');
            
            selectedFiles.forEach((file, index) => {
                const reader = new FileReader();
                const isVideo = file.type.startsWith('video/');
                
                reader.onload = function(e) {
                    const card = document.createElement('div');
                    card.className = 'relative aspect-square rounded-xl overflow-hidden border border-slate-100 shadow-sm bg-slate-950 flex items-center justify-center group/preview';
                    
                    if (isVideo) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.className = 'w-full h-full object-cover';
                        video.preload = 'metadata';
                        video.muted = true;
                        video.playsInline = true;
                        
                        video.addEventListener('loadeddata', () => {
                            video.currentTime = 0.5;
                        });
                        
                        card.appendChild(video);
                        
                        const badge = document.createElement('div');
                        badge.className = 'absolute top-1.5 right-1.5 bg-indigo-600 text-white rounded-md p-1 flex items-center justify-center';
                        badge.innerHTML = '<span class="material-symbols-outlined text-[12px]">videocam</span>';
                        card.appendChild(badge);
                    } else {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className = 'w-full h-full object-cover';
                        card.appendChild(img);
                    }
                    
                    // Delete Button
                    const deleteBtn = document.createElement('button');
                    deleteBtn.type = 'button';
                    deleteBtn.className = 'absolute top-1.5 right-1.5 w-6 h-6 bg-red-500 hover:bg-red-600 text-white rounded-full flex items-center justify-center shadow-md transition-all opacity-90 hover:scale-110 z-20';
                    deleteBtn.innerHTML = '<span class="material-symbols-outlined text-[14px] font-bold">close</span>';
                    deleteBtn.onclick = function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        removeFile(index);
                    };
                    card.appendChild(deleteBtn);
                    
                    previewsGrid.appendChild(card);
                };
                
                reader.readAsDataURL(file);
            });
        } else {
            previewsGrid.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }
    }

    function removeFile(index) {
        selectedFiles.splice(index, 1);
        renderPreviews();
    }
</script>
@endsection