@extends('layouts.admin-layout')

@section('admin-content')
<div class="max-w-3xl mx-auto space-y-8 pb-20">
    {{-- Breadcrumb & Date --}}
    <x-breadcrumb />

    {{-- Header --}}
    <div class="relative pl-6 mb-8">
        {{-- Vertical Bar --}}
        <div class="absolute left-0 top-1 bottom-1 w-1.5 bg-gradient-to-b from-teal-500 via-emerald-400 to-transparent rounded-full"></div>
        
        <h2 class="text-3xl font-black text-slate-800 tracking-tight leading-none">Tambah Folder Baru</h2>
        <p class="text-sm font-bold text-slate-400 mt-3">Buat album kegiatan baru untuk mengorganisir foto dan video Posyandu.</p>
    </div>

    <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-[0_8px_30px_rgb(0,0,0,0.015)]">
        <form action="{{ route('admin.gallery.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="space-y-8">
                <!-- Cover Photo Upload (Optional) -->
                <div>
                    <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Foto Sampul Folder <span class="text-red-500">*</span></label>
                    <div class="flex flex-col items-center p-8 bg-slate-50 border-3 border-dashed border-slate-200 rounded-[2rem] hover:border-teal-500 hover:bg-slate-50/50 transition-all group relative cursor-pointer" style="min-height: 180px;">
                        
                        <div id="imagePreview" class="hidden mb-4 max-w-full">
                            <img src="" alt="Preview" class="h-40 rounded-2xl shadow-md border-4 border-white object-cover mx-auto">
                        </div>

                        <div id="placeholder" class="flex flex-col items-center text-center">
                            <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center text-teal-600 shadow-md mb-3 group-hover:scale-105 transition-transform">
                                <span class="material-symbols-outlined text-[30px]">folder_special</span>
                            </div>
                            <p class="text-sm font-black text-slate-700">Pilih Foto Sampul Folder</p>
                            <p class="text-xs text-slate-400 mt-1 font-bold uppercase tracking-wider">Format: JPG, PNG, WEBP (Maks. 10MB)</p>
                        </div>

                        <input type="file" name="cover_photo" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" id="coverUpload" accept="image/*" onchange="previewFile(event)">
                    </div>
                    @error('cover_photo') 
                        <p class="mt-3 text-xs text-red-500 font-bold flex items-center gap-1">
                            <span class="material-symbols-outlined text-[16px]">error</span>
                            {{ $message }}
                        </p> 
                    @enderror
                </div>
                
                <div class="space-y-6">
                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Nama Folder / Kegiatan</label>
                        <input type="text" name="name" value="{{ old('name') }}" placeholder="Contoh: Imunisasi Bulan April 2026" class="w-full h-12 bg-slate-50 border-transparent focus:bg-white rounded-2xl px-5 text-sm font-semibold text-slate-700 focus:ring-0 focus:border-teal-500 transition-all border-2 border-slate-100" required>
                        @error('name') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Deskripsi Singkat Folder</label>
                        <textarea name="description" rows="3" placeholder="Ceritakan singkat mengenai tujuan dokumentasi di folder ini..." class="w-full bg-slate-50 border-transparent focus:bg-white rounded-2xl px-5 py-4 text-sm font-semibold text-slate-700 focus:ring-0 focus:border-teal-500 transition-all border-2 border-slate-100">{{ old('description') }}</textarea>
                        @error('description') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if(auth()->user()->isSuperAdmin())
                        <div>
                            <label class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-3">Unit Posyandu <span class="text-red-500">*</span></label>
                            <x-forms.select-input name="posyandu_id" placeholder="Pilih Unit Posyandu" :placeholderDisabled="true" value="{{ old('posyandu_id') }}" class="!bg-slate-50 !border-slate-100 !rounded-2xl !h-12 focus:!ring-0 focus:!border-teal-500 focus:!bg-white !shadow-none !border-2">
                                <option value="">Semua Posyandu (Global)</option>
                                @foreach($posyandus as $posyandu)
                                    <option value="{{ $posyandu->id }}" {{ old('posyandu_id') == $posyandu->id ? 'selected' : '' }}>{{ $posyandu->name }}</option>
                                @endforeach
                            </x-forms.select-input>
                            @error('posyandu_id') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>
            </div>

            <div class="flex justify-end mt-12 gap-4">
                <a href="{{ route('admin.gallery.index') }}" class="px-8 py-3.5 bg-slate-100 text-slate-600 font-black rounded-2xl hover:bg-slate-200 transition-all text-xs uppercase tracking-widest">Batal</a>
                <button type="submit" class="px-8 py-3.5 bg-gradient-to-r from-teal-600 to-emerald-600 text-white font-black rounded-2xl hover:from-teal-700 hover:to-emerald-700 hover:shadow-lg hover:shadow-teal-600/20 active:scale-95 transition-all text-xs uppercase tracking-widest flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">save</span>
                    Simpan Folder
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    function previewFile(event) {
        const input = event.target;
        const reader = new FileReader();
        const imagePreview = document.getElementById('imagePreview');
        const placeholder = document.getElementById('placeholder');

        if(input.files[0]) {
            reader.onload = function(){
                const img = imagePreview.querySelector('img');
                img.src = reader.result;
                imagePreview.classList.remove('hidden');
                placeholder.classList.add('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
