<div class="min-h-screen bg-white py-8">
    <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-6 pb-20">

        {{-- ── Header Navigation ── --}}
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.articles.index') }}" 
               class="w-12 h-12 flex items-center justify-center rounded-full bg-slate-100 hover:bg-slate-200 text-slate-600 transition-all">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div>
                <h1 class="text-2xl font-black text-slate-900">Tulis Artikel Baru</h1>
                <p class="text-xs text-slate-400 uppercase tracking-wider font-bold">Bagikan pengetahuan kesehatan Anda</p>
            </div>
        </div>

        {{-- ── Main Editor Form ── --}}
        <form wire:submit.prevent="save" class="space-y-0">

            {{-- ✨ Section 1: Title ── --}}
            <div class="bg-white border border-slate-200 rounded-t-2xl overflow-hidden pt-8 px-8 md:px-12 pb-6">
                <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em] mb-2">
                    Judul Artikel *
                </label>
                <input type="text" wire:model="title" 
                       placeholder="Tuliskan judul artikel yang menarik..."
                       class="w-full text-4xl font-black text-slate-900 bg-transparent placeholder:text-slate-300 focus:outline-none border-none p-0 mb-6">
                @error('title') 
                    <p class="text-sm text-red-500 font-bold">{{ $message }}</p> 
                @enderror

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-6 pt-6 border-t border-slate-100 text-sm text-slate-500">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">schedule</span>
                        <span>Waktu baca: <span class="font-bold text-slate-900" id="reading-time">—</span></span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[18px]">article</span>
                        <span><span id="word-count">0</span> kata</span>
                    </div>
                </div>
            </div>

            {{-- ✨ Section 2: Header Image ── --}}
            <div class="bg-white border-x border-slate-200 px-8 md:px-12 py-8 space-y-4">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        <span class="material-symbols-outlined text-[16px] align-middle mr-1">image</span>
                        Gambar Sampul (Header) *
                    </label>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Wajib Diisi</span>
                </div>

                <div class="relative group">
                    <div class="w-full aspect-video rounded-xl bg-slate-50 border-2 border-dashed border-slate-300 flex flex-col items-center justify-center overflow-hidden cursor-pointer hover:border-indigo-400 hover:bg-indigo-50/30 transition-all">
                        @if($thumbnail)
                            <img src="{{ $thumbnail->temporaryUrl() }}" class="w-full h-full object-cover" alt="Header preview">
                        @else
                            <div wire:loading.remove wire:target="thumbnail" class="flex flex-col items-center gap-3">
                                <span class="material-symbols-outlined text-slate-300 text-[48px]">photo_library</span>
                                <div class="text-center">
                                    <p class="text-sm font-bold text-slate-600">Klik untuk unggah gambar header</p>
                                    <p class="text-xs text-slate-400 mt-1">Rasio: 16:9 (landscape), Max 2MB</p>
                                </div>
                            </div>
                        @endif
                        <div wire:loading wire:target="thumbnail" class="absolute inset-0 bg-white/80 backdrop-blur-sm flex items-center justify-center">
                            <div class="flex flex-col items-center gap-2">
                                <div class="w-8 h-8 border-2 border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                                <p class="text-xs text-indigo-600 font-bold">Mengunggah gambar...</p>
                            </div>
                        </div>
                        <input type="file" wire:model="thumbnail" class="absolute inset-0 opacity-0 cursor-pointer z-10">
                    </div>
                </div>

                @error('thumbnail') 
                    <p class="text-sm text-red-500 font-bold">{{ $message }}</p> 
                @enderror
            </div>

            {{-- ✨ Section 3: Description ── --}}
            <div class="bg-white border-x border-slate-200 px-8 md:px-12 py-8 space-y-4">
                <div class="flex items-center justify-between">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        <span class="material-symbols-outlined text-[16px] align-middle mr-1">description</span>
                        Ringkasan/Deskripsi *
                    </label>
                    <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Wajib Diisi</span>
                </div>

                <textarea wire:model="description" 
                          placeholder="Tuliskan ringkasan singkat artikel Anda (10-500 karakter)..."
                          rows="3"
                          class="w-full px-4 py-3 text-sm text-slate-700 placeholder:text-slate-400 border border-slate-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent resize-none">{{ $description }}</textarea>
                
                <div class="flex justify-between items-center text-xs text-slate-500">
                    <p>Min 10 - Max 500 karakter</p>
                    <span class="font-bold">{{ strlen($description) }}/500</span>
                </div>
                @error('description') 
                    <p class="text-sm text-red-500 font-bold">{{ $message }}</p> 
                @enderror
            </div>

{{-- ✨ Section 4: Content Builder --}}
<div class="bg-white border-x border-slate-200 px-8 md:px-12 py-8">

    <div class="flex items-center justify-between mb-6">
        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em]">
            Isi Artikel *
        </label>

        <div class="flex gap-2">
            <button
                type="button"
                wire:click="addBlock('text')"
                class="px-3 py-2 rounded-lg border text-sm font-bold">
                + Teks
            </button>

            <button
                type="button"
                wire:click="addBlock('image')"
                class="px-3 py-2 rounded-lg border text-sm font-bold">
                + Gambar
            </button>

            <button
                type="button"
                wire:click="addBlock('video')"
                class="px-3 py-2 rounded-lg border text-sm font-bold">
                + Video
            </button>

            <button
                type="button"
                wire:click="addBlock('divider')"
                class="px-3 py-2 rounded-lg border text-sm font-bold">
                + Divider
            </button>
        </div>
    </div>

    <div class="space-y-5">

        @foreach($content_blocks as $index => $block)

            <div
                wire:key="block-{{ $block['id'] }}"
                class="border rounded-xl p-5 bg-slate-50">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">

                    <div class="font-bold text-sm text-slate-700">
                        {{ strtoupper($block['type']) }}
                    </div>

                    <button
                        type="button"
                        wire:click="removeBlock('{{ $block['id'] }}')"
                        class="text-red-500">
                        Hapus
                    </button>

                </div>

                {{-- TEXT --}}
                @if($block['type'] === 'text')

                    <textarea
                        wire:model.live="content_blocks.{{ $index }}.data.content"
                        rows="8"
                        class="w-full border rounded-lg p-4"
                        placeholder="Tulis isi artikel..."></textarea>

                @endif


                {{-- IMAGE --}}
                @if($block['type'] === 'image')

                    <input
                        type="file"
                        wire:model="blockImages.{{ $index }}"
                        class="w-full">

                    @if(isset($block['data']['url']) && $block['data']['url'])

                        <img
                            src="{{ asset('storage/'.$block['data']['url']) }}"
                            class="mt-4 rounded-lg w-full">

                    @endif

                    <input
                        type="text"
                        wire:model.live="content_blocks.{{ $index }}.data.caption"
                        placeholder="Caption gambar"
                        class="mt-3 w-full border rounded-lg p-3">

                @endif


                {{-- VIDEO --}}
                @if($block['type'] === 'video')

                    <input
                        type="text"
                        wire:model.live="content_blocks.{{ $index }}.data.url"
                        placeholder="Link YouTube"
                        class="w-full border rounded-lg p-3">

                    <input
                        type="text"
                        wire:model.live="content_blocks.{{ $index }}.data.caption"
                        placeholder="Caption video"
                        class="mt-3 w-full border rounded-lg p-3">

                @endif


                {{-- DIVIDER --}}
                @if($block['type'] === 'divider')

                    <hr class="border-slate-300">

                @endif

            </div>

        @endforeach

    </div>

</div>
            {{-- ✨ Section 5: Settings ── --}}
            <div class="bg-white border-x border-slate-200 px-8 md:px-12 py-8 grid grid-cols-1 md:grid-cols-2 gap-6">
                
                {{-- Category --}}
                <div class="space-y-3">
                    <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em]">
                        <span class="material-symbols-outlined text-[16px] align-middle mr-1]">label</span>
                        Kategori *
                    </label>
                    <x-forms.select-input wire:model="category_id" 
                                         placeholder="Pilih kategori" 
                                         :placeholderDisabled="true" 
                                         value="{{ $category_id }}" 
                                         class="!h-11 !rounded-lg !text-sm !font-bold">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </x-forms.select-input>
                    @error('category_id') 
                        <p class="text-sm text-red-500 font-bold">{{ $message }}</p> 
                    @enderror
                </div>

                {{-- Status --}}
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <label class="block text-[11px] font-black text-slate-500 uppercase tracking-[0.2em]">
                            <span class="material-symbols-outlined text-[16px] align-middle mr-1]">publish</span>
                            Status Publikasi *
                        </label>
                        <span class="text-xs font-bold text-amber-600 bg-amber-50 px-3 py-1 rounded-full">Wajib</span>
                    </div>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model="status" name="status" value="draft" class="sr-only peer">
                            <div class="h-11 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center text-sm font-bold text-slate-600 peer-checked:border-amber-400 peer-checked:bg-amber-50 transition-all group-hover:border-slate-300">
                                <span class="material-symbols-outlined text-[18px] mr-2">draft</span>
                                Draft
                            </div>
                        </label>
                        <label class="cursor-pointer group">
                            <input type="radio" wire:model="status" name="status" value="published" class="sr-only peer">
                            <div class="h-11 rounded-lg border-2 border-slate-200 bg-white flex items-center justify-center text-sm font-bold text-slate-600 peer-checked:border-emerald-400 peer-checked:bg-emerald-50 transition-all group-hover:border-slate-300">
                                <span class="material-symbols-outlined text-[18px] mr-2">publish</span>
                                Terbit
                            </div>
                        </label>
                    </div>
                    @error('status') 
                        <p class="text-sm text-red-500 font-bold">{{ $message }}</p> 
                    @enderror
                </div>
            </div>

            {{-- ── Footer Actions ── --}}
            <div class="bg-slate-50 border border-slate-200 rounded-b-2xl px-8 md:px-12 py-6 flex items-center justify-end gap-4">
                <a href="{{ route('admin.articles.index') }}" 
                   class="h-11 px-6 flex items-center justify-center text-sm font-bold text-slate-600 hover:text-slate-900 hover:bg-slate-100 rounded-lg transition-all">
                    Batal
                </a>
                <button type="submit" 
                        wire:loading.attr="disabled"
                        class="h-11 px-8 flex items-center justify-center gap-2 bg-indigo-600 hover:bg-indigo-700 disabled:opacity-50 disabled:cursor-not-allowed text-white rounded-lg text-sm font-black uppercase tracking-wide transition-all">
                    <span wire:loading.remove class="material-symbols-outlined text-[18px]">check_circle</span>
                    <div wire:loading class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                    <span>Simpan Artikel</span>
                </button>
            </div>
        </form>
    </div>
</div>
