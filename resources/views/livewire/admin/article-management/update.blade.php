<div
    x-data="articleEditorUpdate(@js($article->content ?? ''), @js($article->title), @js($article->status), @js($article->category_id), @js($article->category?->name ?? ''), @js($existingCover ? asset('storage/'.$existingCover) : null))"
    x-init="init()"
    class="min-h-screen bg-[#f8f8f7]"
    @keydown.ctrl.b.window.prevent="formatText('bold')"
    @keydown.ctrl.i.window.prevent="formatText('italic')"
    @keydown.ctrl.u.window.prevent="formatText('underline')"
>

{{-- HIDDEN LIVEWIRE FORM --}}
<form wire:submit.prevent="save" x-ref="lwForm" class="sr-only" aria-hidden="true">
    <input type="text"   wire:model="title"       x-ref="lwTitle">
    <textarea            wire:model="content"     x-ref="lwContent"></textarea>
    <input type="text"   wire:model="status"      x-ref="lwStatus">
    <input type="number" wire:model="category_id" x-ref="lwCategoryId">
    <button type="submit" x-ref="lwSubmit">OK</button>
</form>
<div class="sr-only" aria-hidden="true">
    <input type="file" wire:model="cover" accept="image/*" x-ref="lwCoverInput"
           @change="handleCoverChange($event)">
</div>

{{-- Hidden file inputs --}}
<input type="file" accept="image/*" class="sr-only" x-ref="imageInsertInput"
       @change="insertImageBlock($event, pendingInsertIndex)">
<input type="file" accept="video/*" class="sr-only" x-ref="videoUploadInput"
       @change="insertVideoUploadBlock($event, pendingInsertIndex)">

{{-- CANVAS --}}
<div class="max-w-[860px] mx-auto px-8 pt-8 pb-40">

    {{-- Tombol Kembali --}}
    <div class="mb-8 flex items-center justify-between">
        <a href="{{ route('admin.articles.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-sm font-bold text-slate-700 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
        <span class="text-xs font-bold text-outline-variant uppercase tracking-widest">Edit Artikel</span>
    </div>

    {{-- 1. JUDUL --}}
    <div class="mb-8">
        <textarea
            x-ref="titleInput"
            @input="titleValue = $el.value; isDirty = true; autoResize($el)"
            @keydown.enter.prevent="focusFirstBlock()"
            placeholder="Judul artikel…"
            rows="1"
            x-init="$el.value = titleValue; $nextTick(() => autoResize($el))"
            class="w-full resize-none bg-transparent border-none outline-none text-4xl md:text-5xl font-black text-on-surface leading-tight tracking-tight placeholder:text-slate-300 overflow-hidden"
            style="font-family:'Georgia',serif;"
        ></textarea>
        @error('title')
            <p class="mt-1 text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- 2. FOTO SAMPUL --}}
        <div class="mb-10">
            <div class="relative w-full max-w-full aspect-video rounded-2xl overflow-hidden border-2 cursor-pointer transition-all group"
             :class="coverPreview ? 'border-transparent shadow-xl' : 'border-dashed border-outline-variant bg-white hover:border-indigo-400 hover:bg-secondary-container/20'"
             @click="$refs.lwCoverInput.click()">
            <img x-show="coverPreview" :src="coverPreview"
                 class="absolute inset-0 w-full h-full object-cover">
            <div x-show="coverPreview"
                 class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white text-sm font-bold">
                <span class="material-symbols-outlined text-[22px]">photo_camera</span>
                Ganti Foto Sampul
            </div>
            <div x-show="!coverPreview && !coverUploading"
                 class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-outline-variant">
                <div class="w-14 h-14 rounded-xl bg-surface-container flex items-center justify-center">
                    <span class="material-symbols-outlined text-[26px]">image</span>
                </div>
                <div class="text-center">
                    <p class="text-sm font-bold text-on-surface-variant">Foto sampul saat ini dipertahankan</p>
                    <p class="text-xs text-outline-variant mt-0.5">Klik untuk mengganti</p>
                </div>
            </div>
            <div x-show="coverUploading"
                 class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center gap-3 z-10">
                <div class="w-8 h-8 border-[3px] border-indigo-600 border-t-transparent rounded-lg animate-spin"></div>
                <p class="text-xs font-bold text-secondary uppercase tracking-widest">Mengunggah…</p>
            </div>
        </div>
        @error('cover')
            <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- 3. BLOCK EDITOR --}}
    <div id="blocks-container-update" wire:ignore class="relative mb-12">

        <template x-for="(block, index) in blocks" :key="block.id">
            <div class="relative group/row -ml-12 pl-12"
                 @mouseenter="hoveredIndex = index"
                 @mouseleave="hoveredIndex = (hoveredIndex === index) ? -1 : hoveredIndex">

                {{-- PARAGRAPH --}}
                <div x-show="block.type === 'paragraph'">
                    <div style="position: absolute; left: 6px; top: 8px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => {block.content = $el.innerHTML;});"
                         @keydown="handleKeydown($event, index)"
                         @focus="focusedIndex = index; activeBlockId = block.id"
                         @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()"
                         :data-placeholder="index === 0 ? 'Mulai menulis, atau klik + untuk tambah kontenâ€¦' : 'Tulis paragrafâ€¦'"
                         class="flex-1 min-h-[1.8em] py-2 text-[1.15rem] leading-[1.9] text-on-surface-variant ce-placeholder"
                         style="font-family:'Georgia',serif; outline: none; border: none; box-shadow: none;"></div>
                </div>

                {{-- HEADING 1 --}}
                <div x-show="block.type === 'h1'">
                    <div style="position: absolute; left: 6px; top: 10px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => {block.content = $el.innerHTML;});"
                         @keydown="handleKeydown($event, index)"
                         @focus="focusedIndex = index; activeBlockId = block.id"
                         @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()"
                         :data-placeholder="'Heading 1'"
                         class="flex-1 min-h-[1.2em] py-1 font-black text-on-surface ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:2rem; line-height:1.25; outline: none; border: none; box-shadow: none;"></div>
                </div>

                {{-- HEADING 2 --}}
                <div x-show="block.type === 'h2'">
                    <div style="position: absolute; left: 6px; top: 8px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)"
                         @focus="focusedIndex = index; activeBlockId = block.id"
                         @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()"
                         :data-placeholder="'Heading 2'"
                         class="flex-1 min-h-[1.2em] py-1 font-black text-on-surface ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:1.5rem; line-height:1.35; outline: none; border: none; box-shadow: none;"></div>
                </div>

                {{-- HEADING 3 --}}
                <div x-show="block.type === 'h3'">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)"
                         @focus="focusedIndex = index; activeBlockId = block.id"
                         @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()"
                         :data-placeholder="'Heading 3'"
                         class="flex-1 min-h-[1.2em] py-1 font-bold text-on-surface ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:1.25rem; line-height:1.4; outline: none; border: none; box-shadow: none;"></div>
                </div>

                {{-- QUOTE --}}
                <div x-show="block.type === 'quote'" class="my-2">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div class="flex-1 flex gap-3">
                        <div class="w-1 rounded-lg bg-inverse-surface flex-shrink-0 self-stretch"></div>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => {block.content = $el.innerHTML;});"
                             @keydown="handleKeydown($event, index)"
                             @focus="focusedIndex = index; activeBlockId = block.id"
                             @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()"
                             :data-placeholder="'Kutipanâ€¦'"
                             class="flex-1 min-h-[1.8em] py-1 text-[1.15rem] leading-[1.9] text-on-surface-variant italic ce-placeholder"
                             style="font-family:'Georgia',serif; outline: none; border: none; box-shadow: none;"></div>
                    </div>
                </div>

                {{-- CALLOUT --}}
                <div x-show="block.type === 'callout'" class="my-2">
                    <div style="position: absolute; left: 6px; top: 14px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div class="flex-1 bg-gradient-to-r from-amber-50 to-orange-50 border-l-4 border-amber-500 rounded-2xl px-5 py-4 shadow-sm hover:shadow-md transition-all">
                        <span class="material-symbols-outlined text-amber-600">tips_and_updates</span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                             @keydown="handleKeydown($event, index)"
                             @focus="focusedIndex = index; activeBlockId = block.id"
                             @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()"
                             :data-placeholder="'Catatan pentingâ€¦'"
                             class="flex-1 min-h-[1.8em] py-0.5 text-amber-900 ce-placeholder"
                             style="font-family:'Georgia',serif; outline: none; border: none; box-shadow: none;"></div>
                    </div>
                </div>

                {{-- BULLETED LIST --}}
                <div x-show="block.type === 'bullet'">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div class="flex-1 flex items-start gap-2 py-0.5">
                        <span class="w-1.5 h-1.5 rounded-lg bg-slate-700 flex-shrink-0 mt-3"></span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => {block.content = $el.innerHTML;});"
                             @keydown="handleKeydown($event, index)"
                             @focus="focusedIndex = index; activeBlockId = block.id"
                             @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()"
                             :data-placeholder="'Item listâ€¦'"
                             class="flex-1 min-h-[1.8em] text-[1.15rem] leading-[1.9] text-on-surface-variant ce-placeholder"
                             style="font-family:'Georgia',serif; outline: none; border: none; box-shadow: none;"></div>
                    </div>
                </div>

                {{-- NUMBERED LIST --}}
                <div x-show="block.type === 'numbered'">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <div class="flex-1 flex items-start gap-2 py-0.5">
                        <span class="text-sm font-bold text-outline flex-shrink-0 w-5 text-right mt-1.5" x-text="getNumberedIndex(index) + '.'"></span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => {block.content = $el.innerHTML;});"
                             @keydown="handleKeydown($event, index)"
                             @focus="focusedIndex = index; activeBlockId = block.id"
                             @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()"
                             :data-placeholder="'Item bernomorâ€¦'"
                             class="flex-1 min-h-[1.8em] text-[1.15rem] leading-[1.9] text-on-surface-variant ce-placeholder"
                             style="font-family:'Georgia',serif; outline: none; border: none; box-shadow: none;"></div>
                    </div>
                </div>

                {{-- IMAGE --}}
                <div x-show="block.type === 'image'"
                     class="my-4"
                     tabindex="-1"
                     @keydown.delete.prevent="removeBlock(index)"
                     @keydown.backspace.prevent="removeBlock(index)"
                     @focus="focusedIndex = index">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>
                    <figure class="rounded-2xl overflow-hidden shadow-lg w-full">
                        <img :src="block.src" class="w-full h-auto block object-contain" alt="" style="max-width:100%;height:auto;">
                    </figure>
                    <input type="text" x-model="block.caption"
                           placeholder="Keterangan gambar (opsional)â€¦"
                           class="w-full mt-2 text-center text-sm text-outline-variant italic bg-transparent border-none outline-none placeholder:text-slate-300">
                    <button type="button" @click="removeBlock(index)"
                            class="absolute top-2 right-2 w-8 h-8 bg-black/60 hover:bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-all">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>

                {{-- VIDEO EMBED --}}
                <div x-show="block.type === 'video'"
                    class="my-3"
                    tabindex="-1"
                    @keydown.delete.prevent="removeBlock(index)"
                    @keydown.backspace.prevent="removeBlock(index)"
                    @focus="focusedIndex = index">

                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10;">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-9 h-9 rounded-full border-2 border-teal-600 flex items-center justify-center transition-all bg-white text-teal-800 shadow-md hover:bg-teal-600 hover:text-white"
                                :class="blockMenuAt === index ? 'bg-teal-600 text-white rotate-45' : ''"
                                style="min-width: unset; min-height: unset; width: 36px; height: 36px;">
                            <span class="material-symbols-outlined text-[20px]" style="font-weight: 900; line-height: 1;">add</span>
                        </button>
                    </div>

                    {{-- Form input URL (tampil saat belum ada video) --}}
                    <div x-show="!block.embedSrc && !block.localSrc"
                         class="rounded-xl bg-surface-container-low border border-dashed border-outline-variant p-4">
                        <p class="text-[11px] font-black text-outline-variant uppercase tracking-widest mb-3 text-center">YouTube, Google Drive, atau Upload Video</p>
                        <div class="flex gap-2 mb-3">
                            <input type="text" x-model="block.url"
                                placeholder="https://youtube.com/watch?v=..."
                                @keydown.enter.prevent="embedVideo(block)"
                                @click.stop
                                class="flex-1 h-9 px-3 rounded-lg border border-outline-variant text-sm text-on-surface-variant focus:outline-none focus:border-indigo-400 bg-white">
                                                        <button type="button" @click.stop="embedVideo(block)"
                                    class="h-9 px-5 bg-teal-600 hover:bg-teal-700 text-white rounded-lg text-xs font-black uppercase tracking-wider transition-all shadow-sm hover:-translate-y-0.5 active:translate-y-0"
                                    style="min-width: unset; min-height: unset; height: 36px;">
                                Sematkan
                            </button>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-outline-variant mb-2">
                            <div class="flex-1 h-px bg-surface-container-high"></div>
                            <span class="font-bold uppercase tracking-widest">atau</span>
                            <div class="flex-1 h-px bg-surface-container-high"></div>
                        </div>
                        <button type="button"
                                @click.stop="pendingInsertIndex = index; $refs.videoUploadInput.click()"
                                class="w-full h-9 border border-dashed border-outline-variant hover:border-indigo-400 hover:bg-secondary-container rounded-lg text-xs font-bold text-outline hover:text-secondary transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">upload</span>
                            Upload Video dari Perangkat
                        </button>
                        <div class="mt-2 flex justify-center">
                            <button type="button" @click.stop="removeBlock(index)"
                                    class="text-xs text-outline-variant hover:text-red-500 flex items-center gap-1 font-bold transition-colors">
                                <span class="material-symbols-outlined text-[14px]">delete</span> Hapus blok
                            </button>
                        </div>
                    </div>

                    {{-- Preview YouTube iframe (x-show lebih reliable dari x-if bersarang) --}}
                    <div x-show="block.embedSrc" style="max-width:480px;">
                        <div style="position:relative;padding-bottom:56.25%;height:0;border-radius:12px;overflow:hidden;background:#000;">
                            <iframe
                                :src="block.embedSrc"
                                style="position:absolute;top:0;left:0;width:100%;height:100%;border:0;"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                allowfullscreen
                                frameborder="0"
                            ></iframe>
                        </div>
                        <div class="mt-1 flex items-center justify-between px-1">
                            <span class="text-[10px] text-outline-variant">Klik lalu tekan Delete untuk hapus</span>
                            <button type="button" @click="removeBlock(index)"
                                    class="text-xs text-red-400 hover:text-red-600 font-bold transition-colors">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                            </button>
                        </div>
                    </div>

                    {{-- Preview video lokal --}}
                    <div x-show="block.localSrc && !block.embedSrc" style="max-width:480px;">
                        <video controls class="w-full h-auto rounded-xl" :src="block.localSrc"></video>
                        <div class="mt-1 flex items-center justify-between px-1">
                            <span class="text-[10px] text-outline-variant">Klik lalu tekan Delete untuk hapus</span>
                            <button type="button" @click="removeBlock(index)"
                                    class="text-xs text-red-400 hover:text-red-600 font-bold transition-colors">
                                <span class="material-symbols-outlined text-[14px]">delete</span>
                            </button>
                        </div>
                    </div>
                </div>

                {{-- DIVIDER --}}
                <div x-show="block.type === 'divider'"
                    :tabindex="0"
                    @keydown.delete.prevent="removeBlock(index)"
                    @keydown.backspace.prevent="removeBlock(index)"
                    @keydown.enter.prevent="
                        const nb = { id: nextId++, type: 'paragraph', content: '' };
                        blocks.splice(index + 1, 0, nb);
                        isDirty = true;
                        $nextTick(() => {
                            setTimeout(() => {
                                const el = document.getElementById('block-' + nb.id);
                                if (el) el.focus();
                            }, 50);
                        });
                    "
                    @focus="focusedIndex = index"
                    class="my-6 cursor-pointer focus:outline-none"
                    @click="$el.focus()">
                    <div style="position: absolute; left: 6px; top: 6px; width: 36px; z-index: 10; display: flex; justify-content: center;">
                        <button type="button" @click.stop="removeBlock(index)"
                                class="w-5 h-5 flex items-center justify-center rounded-full bg-red-50 hover:bg-red-100 text-red-400 transition-all flex-shrink-0"
                                style="min-width: unset; min-height: unset; width: 20px; height: 20px;">
                            <span class="material-symbols-outlined text-[12px]">close</span>
                        </button>
                    </div>
                    <div class="flex items-center gap-2">
                        <div class="flex-1 border-t border-dashed border-outline-variant"></div>
                    </div>
                </div>
            </div>
        </template>
        {{-- INLINE FORMATTING TOOLBAR --}}
        <div x-show="showFormatBar"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-end="opacity-0 scale-95"
             class="fixed z-[200] bg-slate-900 rounded-xl shadow-2xl flex items-center gap-1.5 p-1.5 border border-slate-700" style="display:none;"
             :style="formatBarStyle"
             style="display:none"
             @mousedown.prevent>
            <button type="button" @click="formatText('bold')"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors font-bold text-sm" style="min-width: unset; min-height: unset;">B</button>
            <button type="button" @click="formatText('italic')"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors italic text-sm" style="min-width: unset; min-height: unset;">I</button>
            <button type="button" @click="formatText('underline')"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors underline text-sm" style="min-width: unset; min-height: unset;">U</button>
            <button type="button" @click="formatText('strikethrough')"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors line-through text-sm" style="min-width: unset; min-height: unset;">S</button>
            <div class="w-px h-5 bg-slate-700 mx-0.5"></div>
            <button type="button" @click="convertBlockType('h1')"
                    class="px-2 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors text-xs font-black" style="min-width: unset; min-height: unset;">H1</button>
            <button type="button" @click="convertBlockType('h2')"
                    class="px-2 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors text-xs font-black" style="min-width: unset; min-height: unset;">H2</button>
            <button type="button" @click="convertBlockType('quote')"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-white hover:bg-slate-700 transition-colors text-sm" style="min-width: unset; min-height: unset;">"</button>
        </div>

        {{-- BLOCK TYPE MENU --}}
        <div x-show="blockMenuAt !== null"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="blockMenuAt = null"
             class="fixed z-[100] bg-white rounded-2xl border border-outline-variant shadow-2xl w-56 overflow-y-auto max-h-[min(420px,70vh)]"
             :style="menuStyle"
             style="display:none">
            <div class="p-1">
                <p class="px-3 pt-2 pb-1 text-[10px] font-black text-outline-variant uppercase tracking-widest">Teks</p>
                <button type="button" @click="changeBlockType(blockMenuAt, 'paragraph'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">text_fields</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Teks</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h1'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="text-[14px] font-black text-outline w-[18px] text-center">H1</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Heading 1</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h2'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="text-[14px] font-black text-outline w-[18px] text-center">H2</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Heading 2</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h3'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="text-[14px] font-black text-outline w-[18px] text-center">H3</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Heading 3</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'quote'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">format_quote</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Kutipan</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'callout'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-amber-500">lightbulb</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Callout</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'bullet'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">format_list_bulleted</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Bulleted List</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'numbered'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">format_list_numbered</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Numbered List</p>
                </button>
                <p class="px-3 pt-2 pb-1 text-[10px] font-black text-outline-variant uppercase tracking-widest">Media</p>
                <label class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-outline">image</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Foto</p>
                    <input type="file" accept="image/*" class="sr-only"
                           @change="insertImageBlockFromMenu($event, blockMenuAt); blockMenuAt = null">
                </label>
                <button type="button" @click="insertVideoBlock(blockMenuAt); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">play_circle</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Video</p>
                </button>
                <button type="button" @click="insertDivider(blockMenuAt); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-surface-container-low transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-outline">horizontal_rule</span>
                    <p class="text-sm font-semibold text-on-surface-variant">Pemisah</p>
                </button>
            </div>
        </div>

        @error('content')
            <p class="mt-4 text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- 4. KATEGORI & STATUS --}}
    <div class="border-t-2 border-dashed border-outline-variant pt-8 space-y-5">
        <p class="text-[11px] font-black text-outline-variant uppercase tracking-[0.2em]">Pengaturan Artikel</p>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-outline uppercase tracking-wide">
                Kategori <span class="text-red-500">*</span>
            </label>
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between h-11 px-4 rounded-xl border-2 text-sm font-bold transition-all"
                        :class="selectedCategoryId
                            ? 'border-secondary bg-secondary-container text-indigo-700'
                            : (showCategoryError ? 'border-red-400 bg-red-50 text-red-500' : 'border-outline-variant bg-white text-outline hover:border-outline-variant')">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">folder</span>
                        <span x-text="selectedCategoryName || 'Pilih kategori artikel'"></span>
                    </div>
                    <span class="material-symbols-outlined text-[18px]"
                          :class="open ? 'rotate-180' : ''" style="transition:transform 0.2s">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute top-[calc(100%+4px)] left-0 right-0 bg-white rounded-xl border border-outline-variant shadow-xl z-30 overflow-hidden max-h-52 overflow-y-auto">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="selectedCategoryId = {{ $cat->id }}; selectedCategoryName = '{{ addslashes($cat->name) }}'; open = false; isDirty = true; showCategoryError = false"
                                class="w-full text-left px-4 py-3 text-sm font-medium transition-colors border-b border-slate-50 last:border-0
                                       {{ $article->category_id == $cat->id
                                           ? 'bg-secondary-container text-indigo-700 font-bold'
                                           : 'text-on-surface-variant hover:bg-secondary-container hover:text-indigo-700' }}">
                            {{ $cat->name }}
                        </button>
                    @endforeach
                </div>
            </div>
            <p x-show="showCategoryError" class="text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> Kategori wajib dipilih
            </p>
            @error('category_id')
                <p class="text-xs text-red-500 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
                </p>
            @enderror
        </div>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-outline uppercase tracking-wide">
                Status Publikasi <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="currentStatus = 'draft'; isDirty = true; showStatusError = false"
                        class="h-11 flex items-center justify-center gap-2 rounded-xl border-2 text-label-sm tracking-wide transition-all"
                        :class="currentStatus === 'draft'
                            ? 'border-slate-800 bg-inverse-surface text-white'
                            : (showStatusError ? 'border-red-300 text-red-400 bg-red-50' : 'border-outline-variant text-outline hover:border-outline-variant bg-white')">
                    <span class="material-symbols-outlined text-[15px]">draft</span>
                    Simpan Draf
                </button>
                <button type="button" @click="currentStatus = 'published'; isDirty = true; showStatusError = false"
                        class="h-11 flex items-center justify-center gap-2 rounded-xl border-2 text-label-sm tracking-wide transition-all"
                        :class="currentStatus === 'published'
                            ? 'border-emerald-500 bg-primary text-white'
                            : (showStatusError ? 'border-red-300 text-red-400 bg-red-50' : 'border-outline-variant text-outline hover:border-outline-variant bg-white')">
                    <span class="material-symbols-outlined text-[15px]">publish</span>
                    Terbitkan
                </button>
            </div>
            <p x-show="showStatusError" class="text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> Status wajib dipilih
            </p>
            @error('status')
                <p class="text-xs text-red-500 font-bold flex items-center gap-1">
                    <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
                </p>
            @enderror
        </div>

        <button type="button" @click="submitToLivewire()" :disabled="isSaving" class="w-full h-14 bg-gradient-to-r from-teal-600 to-emerald-600 hover:from-teal-700 hover:to-emerald-700 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-xl shadow-teal-600/20 transition-all active:scale-[0.99] hover:-translate-y-0.5 disabled:opacity-60 flex items-center justify-center gap-3 mt-2">
            <div x-show="isSaving" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-lg animate-spin"></div>
            <span x-show="!isSaving" class="material-symbols-outlined text-[20px]">sync</span>
            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
        </button>
    </div>

</div>

<script>
function articleEditorUpdate(contentJson, title, status, categoryId, categoryName, coverUrl) {
    return {
        titleValue: title || '',
        currentStatus: status || 'draft',
        selectedCategoryId: categoryId || null,
        selectedCategoryName: categoryName || '',
        coverPreview: coverUrl || null,
        coverUploading: false,
        isDirty: false,
        isSaving: false,
        blocks: [],
        focusedIndex: -1,
        hoveredIndex: -1,
        activeBlockId: null,
        blockMenuAt: null,
        menuStyle: '',
        nextId: 1,
        pendingInsertIndex: 0,
        showFormatBar: false,
        formatBarStyle: '',
        showCategoryError: false,
        showStatusError: false,

        init() {
            if (contentJson) {
                try {
                    this.blocks = JSON.parse(contentJson);
                    this.blocks.forEach(b => {
                        if (!b.id) b.id = this.nextId++;
                    });
                } catch(e) {
                    this.blocks = [{ id: this.nextId++, type: 'paragraph', content: contentJson }];
                }
            } else {
                this.blocks = [{ id: this.nextId++, type: 'paragraph', content: '' }];
            }
        },

        closeAllMenus(e) {
            // handled by @click.away on menus
        },

        handleCoverChange(event) {
            const file = event.target.files[0];
            if (!file) return;
            this.coverUploading = true;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.coverPreview = e.target.result;
                setTimeout(() => { this.coverUploading = false; }, 3000);
            };
            reader.readAsDataURL(file);
            this.isDirty = true;
        },

        autoResize(el) {
            el.style.height = 'auto';
            el.style.height = el.scrollHeight + 'px';
        },

        focusFirstBlock() {
            this.$nextTick(() => {
                const el = document.getElementById('block-' + this.blocks[0]?.id);
                if (el) { el.focus(); placeCaretAtEnd(el); }
            });
        },

        handleKeydown(event, index) {
            const block = this.blocks[index];
            if (!block) return;

            if (event.key === 'Enter') {
                event.preventDefault();
                const continueTypes = ['bullet', 'numbered'];
                const nextType = continueTypes.includes(block.type) ? block.type : 'paragraph';
                const nb = { id: this.nextId++, type: nextType, content: '' };
                this.blocks.splice(index + 1, 0, nb);
                this.isDirty = true;
                this.$nextTick(() => {
                    setTimeout(() => {
                        const el = document.getElementById('block-' + nb.id);
                        if (el) {
                            el.focus();
                            placeCaretAtEnd(el);
                        }
                    }, 50);
                });
                return;
            }

            if (event.key === 'Backspace') {
                const el = document.getElementById('block-' + block.id);
                const isEmpty = !el || el.innerText.trim() === '';
                const sel = window.getSelection();
                const atStart = sel && sel.anchorOffset === 0 && sel.focusOffset === 0;

                // Merge ke block sebelumnya jika caret di awal baris
                if (atStart && index > 0) {
                    event.preventDefault();
                    const prevBlock = this.blocks[index - 1];
                    const textTypes = ['paragraph', 'h1', 'h2', 'h3', 'quote', 'callout', 'bullet', 'numbered'];

                    if (textTypes.includes(prevBlock.type) && textTypes.includes(block.type)) {
                        const prevEl = document.getElementById('block-' + prevBlock.id);
                        const currentEl = document.getElementById('block-' + block.id);
                        const prevContent = prevEl ? prevEl.innerHTML : prevBlock.content;
                        const currentContent = currentEl ? currentEl.innerHTML : block.content;
                        prevBlock.content = prevContent + currentContent;
                        this.blocks.splice(index, 1);
                        this.isDirty = true;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                const el = document.getElementById('block-' + prevBlock.id);
                                if (el) {
                                    el.innerHTML = prevBlock.content;
                                    placeCaretAtEnd(el);
                                }
                            }, 50);
                        });
                        return;
                    }
                }

                // Jika heading/quote/callout kosong atau kursor di awal, ubah ke paragraf dulu
                const convertTypes = ['h1', 'h2', 'h3', 'quote', 'callout'];
                if (convertTypes.includes(block.type) && (isEmpty || atStart)) {
                    if (isEmpty) {
                        event.preventDefault();
                        this.blocks[index] = { ...block, type: 'paragraph', content: '' };
                        this.isDirty = true;
                        this.$nextTick(() => {
                            setTimeout(() => {
                                const el = document.getElementById('block-' + block.id);
                                if (el) {
                                    el.focus();
                                    placeCaretAtEnd(el);
                                }
                            }, 50);
                        });
                        return;
                    }
                }

                // Normal: hapus block kosong
                if (isEmpty && this.blocks.length > 1) {
                    event.preventDefault();
                    const prevBlock = this.blocks[index - 1] || this.blocks[0];
                    this.blocks.splice(index, 1);
                    this.isDirty = true;
                    this.$nextTick(() => {
                        setTimeout(() => {
                            const el = document.getElementById('block-' + prevBlock.id);
                            if (el) {
                                el.focus();
                                placeCaretAtEnd(el);
                            }
                        }, 50);
                    });
                }
                return;
            }

            if (event.key === 'Delete') {
                const el = document.getElementById('block-' + block.id);
                const isEmpty = !el || el.innerText.trim() === '';
                if (isEmpty && this.blocks.length > 1) {
                    event.preventDefault();
                    this.blocks.splice(index, 1);
                    this.isDirty = true;
                    this.$nextTick(() => {
                        const next = this.blocks[Math.min(this.blocks.length - 1, index)];
                        if (next) {
                            const nel = document.getElementById('block-' + next.id);
                            if (nel) nel.focus();
                        }
                    });
                }
            }
        },

        handleBlur(index) {
            setTimeout(() => {
                if (this.focusedIndex === index) this.focusedIndex = -1;
            }, 150);
        },

        checkSelection() {
            setTimeout(() => {
                const sel = window.getSelection();
                if (!sel || sel.isCollapsed || sel.toString().trim() === '') {
                    this.showFormatBar = false;
                    return;
                }
                const range = sel.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                if (rect.width === 0 && rect.height === 0) {
                    this.showFormatBar = false;
                    return;
                }

                const barWidth = 300;
                const barHeight = 40;
                const margin = 8;

                let x = rect.left + rect.width / 2 - barWidth / 2;
                x = Math.max(margin, Math.min(x, window.innerWidth - barWidth - margin));

                let y = rect.top - barHeight - margin;
                if (y < margin) {
                    y = rect.bottom + margin;
                }

                this.formatBarStyle = `top:${y}px;left:${x}px;`;
                this.showFormatBar = true;
            }, 10);
        },

        formatText(command) {
            document.execCommand(command, false, null);
            this.showFormatBar = false;
            this.isDirty = true;
        },

        convertBlockType(type) {
            const idx = this.focusedIndex >= 0 ? this.focusedIndex : 0;
            this.changeBlockType(idx, type);
            this.showFormatBar = false;
        },

        toggleBlockMenu(index, event) {
            if (this.blockMenuAt === index) { this.blockMenuAt = null; return; }
            this.blockMenuAt = index;
            this.$nextTick(() => {
                const btn = event.target.closest('button');
                if (btn) {
                    const r = btn.getBoundingClientRect();
                    const menuW = 224;
                    const menuH = 380;
                    const spaceBelow = window.innerHeight - r.bottom - 8;
                    const spaceAbove = r.top - 8;
                    let top = (spaceBelow >= menuH || spaceBelow >= spaceAbove)
                        ? r.bottom + 6
                        : r.top - menuH - 6;
                    const maxTop = window.innerHeight - Math.min(menuH, window.innerHeight * 0.85) - 8;
                    top = Math.min(top, maxTop);
                    top = Math.max(top, 8);
                    let left = r.left - 4;
                    if (left + menuW > window.innerWidth - 8) left = window.innerWidth - menuW - 8;
                    if (left < 8) left = 8;
                    const maxHeight = window.innerHeight - top - 8;
                    this.menuStyle = `position:fixed;top:${top}px;left:${left}px;max-height:${maxHeight}px;`;
                }
            });
        },

        changeBlockType(index, type) {
            const block = this.blocks[index];
            if (!block) return;
            const el = document.getElementById('block-' + block.id);
            const content = el ? el.innerHTML : block.content;
            this.blocks[index] = { ...block, type, content };
            this.isDirty = true;
            this.$nextTick(() => {
                const newEl = document.getElementById('block-' + block.id);
                if (newEl) { newEl.focus(); placeCaretAtEnd(newEl); }
            });
        },

        insertImageBlockFromMenu(event, afterIndex) {
            const file = event.target.files[0];
            if (!file) return;
            const reader = new FileReader();
            reader.onload = (e) => {
                this.blocks.splice(afterIndex + 1, 0, {
                    id: this.nextId++, type: 'image', src: e.target.result, caption: ''
                });
                const nb = { id: this.nextId++, type: 'paragraph', content: '' };
                this.blocks.splice(afterIndex + 2, 0, nb);
                this.isDirty = true;
                this.$nextTick(() => {
                    const el = document.getElementById('block-' + nb.id);
                    if (el) el.focus();
                });
            };
            reader.readAsDataURL(file);
        },

        insertImageBlock(event, afterIndex) {
            this.insertImageBlockFromMenu(event, afterIndex);
        },

        insertVideoUploadBlock(event, afterIndex) {
            const file = event.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            this.blocks.splice(afterIndex + 1, 0, {
                id: this.nextId++, type: 'video', url: '', embedSrc: null, localSrc: url
            });
            const nb = { id: this.nextId++, type: 'paragraph', content: '' };
            this.blocks.splice(afterIndex + 2, 0, nb);
            this.isDirty = true;
            this.$refs.videoUploadInput.value = '';
            this.blocks = [...this.blocks];
        },

        insertVideoBlock(afterIndex) {
            this.blocks.splice(afterIndex + 1, 0, {
                id: this.nextId++, type: 'video', url: '', embedSrc: null, localSrc: null
            });
            const nb = { id: this.nextId++, type: 'paragraph', content: '' };
            this.blocks.splice(afterIndex + 2, 0, nb);
            this.isDirty = true;
        },

        insertDivider(afterIndex) {
            this.blocks.splice(afterIndex + 1, 0, { id: this.nextId++, type: 'divider' });
            const nb = { id: this.nextId++, type: 'paragraph', content: '' };
            this.blocks.splice(afterIndex + 2, 0, nb);
            this.isDirty = true;
        },

        embedVideo(block) {
            const url = (block.url || '').trim();

            // YouTube
            const yt = url.match(/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/))([^&?/\s]+)/i);
            if (yt) {
                const embedUrl = 'https://www.youtube.com/embed/' + yt[1] + '?rel=0&modestbranding=1';
                const idx = this.blocks.findIndex(b => b.id === block.id);
                if (idx !== -1) {
                    this.blocks[idx] = { ...this.blocks[idx], embedSrc: embedUrl, localSrc: '', url: '' };
                    this.blocks = [...this.blocks];
                }
                this.isDirty = true;
                return;
            }

            // Google Drive
            const gd = url.match(/drive\.google\.com\/file\/d\/([^/?]+)/i);
            if (gd) {
                const embedUrl = 'https://drive.google.com/file/d/' + gd[1] + '/preview';
                const idx = this.blocks.findIndex(b => b.id === block.id);
                if (idx !== -1) {
                    this.blocks[idx] = { ...this.blocks[idx], embedSrc: embedUrl, localSrc: '', url: '' };
                    this.blocks = [...this.blocks];
                }
                this.isDirty = true;
                return;
            }
            alert('Format URL tidak dikenali.\nGunakan: youtube.com/watch?v=… atau drive.google.com/file/d/…');
        },

        removeBlock(index) {
            this.blocks.splice(index, 1);
            if (!this.blocks.length)
                this.blocks.push({ id: this.nextId++, type: 'paragraph', content: '' });
            this.isDirty = true;
        },

        getNumberedIndex(index) {
            let count = 0;
            for (let i = 0; i <= index; i++) {
                if (this.blocks[i]?.type === 'numbered') count++;
            }
            return count;
        },

        serializeContent() {
            return JSON.stringify(this.blocks.map(b => {
                if (['paragraph','h1','h2','h3','quote','callout','bullet','numbered'].includes(b.type)) {
                    const el = document.getElementById('block-' + b.id);
                    return { type: b.type, content: el ? el.innerHTML : (b.content || '') };
                }
                if (b.type === 'image')   return { type: 'image', src: b.src, caption: b.caption || '' };
                if (b.type === 'video')   return { type: 'video', src: b.embedSrc || b.localSrc || '' };
                if (b.type === 'divider') return { type: 'divider' };
                return b;
            }));
        },

        async submitToLivewire() {
            let valid = true;

            if (!this.titleValue.trim()) {
                this.$refs.titleInput.focus();
                this.$refs.titleInput.classList.add('ring-2','ring-red-400');
                setTimeout(() => this.$refs.titleInput.classList.remove('ring-2','ring-red-400'), 2000);
                valid = false;
            }
            if (!this.selectedCategoryId) { this.showCategoryError = true; valid = false; }
            if (!this.currentStatus)      { this.showStatusError = true;   valid = false; }

            const hasContent = this.blocks.some(b => {
                const textTypes = ['paragraph','h1','h2','h3','quote','callout','bullet','numbered'];
                if (!textTypes.includes(b.type)) return true;
                const el = document.getElementById('block-' + b.id);
                return el ? el.innerText.trim().length > 0 : (b.content || '').trim().length > 0;
            });
            if (!hasContent) {
                const el = document.getElementById('block-' + this.blocks[0]?.id);
                if (el) el.focus();
                valid = false;
            }

            if (!valid) return;

            this.isSaving = true;

            this.blocks.forEach(b => {
                const textTypes = ['paragraph','h1','h2','h3','quote','callout','bullet','numbered'];
                if (textTypes.includes(b.type)) {
                    const el = document.getElementById('block-' + b.id);
                    if (el) b.content = el.innerHTML;
                }
            });

            const contentJson = this.serializeContent();

            try {
                const allWireEls = [...document.querySelectorAll('[wire\\:id]')];
                const wireEl = allWireEls.find(el => {
                    const snap = el.getAttribute('wire:snapshot') || '';
                    return snap.includes('article-update') || snap.includes('ArticleUpdate');
                }) ?? allWireEls[allWireEls.length - 1];
                const wireId = wireEl ? wireEl.getAttribute('wire:id') : null;

                if (wireId && window.Livewire) {
                    await window.Livewire.find(wireId).call(
                        'saveFromAlpine',
                        this.titleValue,
                        contentJson,
                        this.currentStatus,
                        this.selectedCategoryId
                    );
                } else {
                    console.error('Livewire component tidak ditemukan');
                    this.isSaving = false;
                }
            } catch(e) {
                console.error('Save error:', e);
                this.isSaving = false;
            }
        }
    };
}

function placeCaretAtEnd(el) {
    el.focus();
    const range = document.createRange();
    range.selectNodeContents(el);
    range.collapse(false);
    const sel = window.getSelection();
    sel.removeAllRanges();
    sel.addRange(range);
}
</script>



</div>