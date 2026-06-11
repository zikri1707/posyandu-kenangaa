<div
    x-data="articleEditorUpdate(@js($article->content ?? ''), @js($article->title), @js($article->status), @js($article->category_id), @js($article->category?->name ?? ''), @js($existingCover ? asset('storage/'.$existingCover) : null))"
    x-init="init()"
    class="min-h-screen bg-[#f8f8f7]"
>

{{-- TOPBAR --}}
<div class="bg-white border-b border-slate-200 px-6 lg:px-10">
    <div class="max-w-[860px] mx-auto h-14 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3 min-w-0">
            <a href="{{ route('admin.articles.index') }}"
               class="flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg hover:bg-slate-100 text-slate-500 transition-all">
                <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            </a>
            <span class="text-sm font-bold text-slate-800">Perbarui Artikel</span>
            <span class="text-slate-300 hidden sm:block">·</span>
            <span class="hidden sm:block text-xs text-slate-400 font-medium">
                #ART-{{ str_pad($article->id, 4, '0', STR_PAD_LEFT) }}
            </span>
        </div>
        <div class="flex items-center gap-2 flex-shrink-0">
            <div class="hidden sm:flex items-center gap-1.5 h-8 px-3 rounded-lg bg-slate-50 border border-slate-200 text-xs font-bold uppercase tracking-wide">
                <span class="w-1.5 h-1.5 rounded-full"
                      :class="currentStatus === 'published' ? 'bg-emerald-400' : 'bg-amber-400'"></span>
                <span :class="currentStatus === 'published' ? 'text-emerald-600' : 'text-amber-600'"
                      x-text="currentStatus === 'published' ? 'Terbit' : 'Draf'"></span>
            </div>
            <button type="button" @click="submitToLivewire()" :disabled="isSaving"
                    class="h-9 px-5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-xs font-bold uppercase tracking-wide transition-all disabled:opacity-50 flex items-center gap-1.5">
                <div x-show="isSaving" class="w-3 h-3 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                <span x-show="!isSaving" class="material-symbols-outlined text-[15px]">sync</span>
                <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
            </button>
        </div>
    </div>
</div>

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
<div class="max-w-[720px] mx-auto px-5 md:px-0 pt-12 pb-40">

    {{-- 1. JUDUL --}}
    <div class="mb-8">
        <textarea
            x-ref="titleInput"
            @input="titleValue = $el.value; isDirty = true; autoResize($el)"
            @keydown.enter.prevent="focusFirstBlock()"
            placeholder="Judul artikel…"
            rows="1"
            x-init="$el.value = titleValue; $nextTick(() => autoResize($el))"
            class="w-full resize-none bg-transparent border-none outline-none text-4xl md:text-5xl font-black text-slate-900 leading-tight tracking-tight placeholder:text-slate-300 overflow-hidden"
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
        <div class="relative w-full aspect-[21/9] rounded-2xl overflow-hidden border-2 cursor-pointer transition-all group"
             :class="coverPreview ? 'border-transparent shadow-xl' : 'border-dashed border-slate-300 bg-white hover:border-indigo-400 hover:bg-indigo-50/20'"
             @click="$refs.lwCoverInput.click()">
            <img x-show="coverPreview" :src="coverPreview"
                 class="absolute inset-0 w-full h-full object-cover">
            <div x-show="coverPreview"
                 class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center gap-2 text-white text-sm font-bold">
                <span class="material-symbols-outlined text-[22px]">photo_camera</span>
                Ganti Foto Sampul
            </div>
            <div x-show="!coverPreview && !coverUploading"
                 class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-slate-400">
                <div class="w-14 h-14 rounded-xl bg-slate-100 flex items-center justify-center">
                    <span class="material-symbols-outlined text-[26px]">image</span>
                </div>
                <div class="text-center">
                    <p class="text-sm font-bold text-slate-600">Foto sampul saat ini dipertahankan</p>
                    <p class="text-xs text-slate-400 mt-0.5">Klik untuk mengganti</p>
                </div>
            </div>
            <div x-show="coverUploading"
                 class="absolute inset-0 bg-white/80 backdrop-blur-sm flex flex-col items-center justify-center gap-3 z-10">
                <div class="w-8 h-8 border-[3px] border-indigo-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-xs font-bold text-indigo-600 uppercase tracking-widest">Mengunggah…</p>
            </div>
        </div>
        @error('cover')
            <p class="mt-2 text-xs text-red-500 font-bold flex items-center gap-1">
                <span class="material-symbols-outlined text-[13px]">error</span> {{ $message }}
            </p>
        @enderror
    </div>

    {{-- 3. BLOCK EDITOR --}}
    <div id="blocks-container-update" class="relative mb-12">

        <template x-for="(block, index) in blocks" :key="block.id">
            <div class="relative group/row"
                 @mouseenter="hoveredIndex = index"
                 @mouseleave="hoveredIndex = (hoveredIndex === index) ? -1 : hoveredIndex">

                {{-- PARAGRAPH --}}
                <div x-show="block.type === 'paragraph'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[6px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)"
                                x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)"
                         @focus="focusedIndex = index; activeBlockId = block.id"
                         @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()"
                         :data-placeholder="index === 0 ? 'Mulai menulis…' : 'Tulis paragraf…'"
                         class="flex-1 min-h-[1.8em] py-0.5 outline-none text-[1.15rem] leading-[1.9] text-slate-700 ce-placeholder"
                         style="font-family:'Georgia',serif; direction:ltr; unicode-bidi:normal; text-align:left;"></div>
                </div>

                {{-- HEADING 1 --}}
                <div x-show="block.type === 'h1'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[4px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Heading 1'"
                         class="flex-1 min-h-[1.2em] py-1 outline-none font-black text-slate-900 ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:2rem; line-height:1.25;"></div>
                </div>

                {{-- HEADING 2 --}}
                <div x-show="block.type === 'h2'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[4px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Heading 2'"
                         class="flex-1 min-h-[1.2em] py-1 outline-none font-black text-slate-900 ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:1.5rem; line-height:1.35;"></div>
                </div>

                {{-- HEADING 3 --}}
                <div x-show="block.type === 'h3'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[4px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div :id="'block-' + block.id" contenteditable="true"
                         x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                         @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                         @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Heading 3'"
                         class="flex-1 min-h-[1.2em] py-1 outline-none font-bold text-slate-900 ce-placeholder"
                         style="font-family:'Georgia',serif; font-size:1.25rem; line-height:1.4;"></div>
                </div>

                {{-- QUOTE --}}
                <div x-show="block.type === 'quote'" class="flex items-start gap-2 my-2">
                    <div class="flex-shrink-0 w-7 mt-[6px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div class="flex-1 flex gap-3">
                        <div class="w-1 rounded-full bg-slate-900 flex-shrink-0 self-stretch"></div>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                             @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Kutipan…'"
                             class="flex-1 min-h-[1.8em] py-1 outline-none text-[1.15rem] leading-[1.9] text-slate-600 italic ce-placeholder"
                             style="font-family:'Georgia',serif;"></div>
                    </div>
                </div>

                {{-- CALLOUT --}}
                <div x-show="block.type === 'callout'" class="flex items-start gap-2 my-2">
                    <div class="flex-shrink-0 w-7 mt-[6px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div class="flex-1 bg-amber-50 border border-amber-200 rounded-xl px-4 py-3 flex gap-3">
                        <span class="text-xl flex-shrink-0 mt-0.5">💡</span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                             @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Catatan penting…'"
                             class="flex-1 min-h-[1.8em] py-0.5 outline-none text-[1.05rem] leading-[1.8] text-amber-900 ce-placeholder"
                             style="font-family:'Georgia',serif;"></div>
                    </div>
                </div>

                {{-- BULLET --}}
                <div x-show="block.type === 'bullet'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[6px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div class="flex-1 flex items-start gap-2 py-0.5">
                        <span class="self-center w-1.5 h-1.5 rounded-full bg-slate-700 flex-shrink-0"></span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                             @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Item list…'"
                             class="flex-1 min-h-[1.8em] outline-none text-[1.15rem] leading-[1.9] text-slate-700 ce-placeholder"
                             style="font-family:'Georgia',serif;"></div>
                    </div>
                </div>

                {{-- NUMBERED --}}
                <div x-show="block.type === 'numbered'" class="flex items-start gap-2">
                    <div class="flex-shrink-0 w-7 mt-[6px]">
                        <button type="button" @click.stop="toggleBlockMenu(index, $event)" x-show="hoveredIndex === index || blockMenuAt === index"
                                class="w-7 h-7 rounded-full border-2 flex items-center justify-center transition-all bg-white shadow-sm"
                                :class="blockMenuAt === index ? 'border-indigo-500 text-indigo-500 rotate-45' : 'border-slate-300 text-slate-400 hover:border-slate-700 hover:text-slate-700'">
                            <span class="material-symbols-outlined text-[16px] leading-none">add</span>
                        </button>
                        <div x-show="hoveredIndex !== index && blockMenuAt !== index" class="w-7 h-7"></div>
                    </div>
                    <div class="flex-1 flex items-start gap-2 py-0.5">
                        <span class="mt-[0.45rem] text-sm font-bold text-slate-500 flex-shrink-0 w-5 text-right" x-text="getNumberedIndex(index) + '.'"></span>
                        <div :id="'block-' + block.id" contenteditable="true"
                             x-init="$el.innerHTML = block.content || ''; $el.addEventListener('input', () => { block.content = $el.innerHTML; isDirty = true; });"
                             @keydown="handleKeydown($event, index)" @focus="focusedIndex = index; activeBlockId = block.id" @blur="handleBlur(index)"
                             @mouseup="checkSelection()" @keyup="checkSelection()" :data-placeholder="'Item bernomor…'"
                             class="flex-1 min-h-[1.8em] outline-none text-[1.15rem] leading-[1.9] text-slate-700 ce-placeholder"
                             style="font-family:'Georgia',serif;"></div>
                    </div>
                </div>

                {{-- IMAGE --}}
                <div x-show="block.type === 'image'" class="my-4 ml-9 relative group/img"
                     tabindex="-1"
                     @keydown.delete.prevent="removeBlock(index)"
                     @keydown.backspace.prevent="removeBlock(index)"
                     @focus="focusedIndex = index">
                    <figure class="rounded-2xl overflow-hidden shadow-lg w-full">
                        <img<img :src="block.src"class=" w-full
 max-w-full h-auto object-contain block"> class="w-full h-auto block object-cover" alt="">
                    </figure>
                    <input type="text" x-model="block.caption"
                           placeholder="Keterangan gambar (opsional)…"
                           class="w-full mt-2 text-center text-sm text-slate-400 italic bg-transparent border-none outline-none placeholder:text-slate-300">
                    <button type="button" @click="removeBlock(index)"
                            class="absolute top-2 right-2 w-8 h-8 bg-black/60 hover:bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover/img:opacity-100 transition-all">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>

                {{-- VIDEO --}}
                <div x-show="block.type === 'video'" class="my-4 ml-9 relative group/vid"
                     tabindex="-1"
                     @keydown.delete.prevent="removeBlock(index)"
                     @keydown.backspace.prevent="removeBlock(index)"
                     @focus="focusedIndex = index">
                    <div x-show="!block.embedSrc && !block.localSrc" class="rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 p-6">
                        <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-3 text-center">YouTube, Google Drive, atau Upload Video</p>
                        <div class="flex gap-2 mb-3">
                            <input type="text" x-model="block.url"
                                   placeholder="https://youtube.com/watch?v=…"
                                   @keydown.enter.prevent="embedVideo(block)"
                                   class="flex-1 h-10 px-3 rounded-lg border border-slate-200 text-sm text-slate-700 focus:outline-none focus:border-indigo-400 bg-white">
                            <button type="button" @click="embedVideo(block)"
                                    class="h-10 px-4 bg-slate-900 text-white rounded-lg text-xs font-bold uppercase tracking-wide hover:bg-indigo-600 transition-colors">
                                Embed
                            </button>
                        </div>
                        <div class="flex items-center gap-3 text-xs text-slate-400 mb-2">
                            <div class="flex-1 h-px bg-slate-200"></div>
                            <span class="font-bold uppercase tracking-widest">atau</span>
                            <div class="flex-1 h-px bg-slate-200"></div>
                        </div>
                        <button type="button"
                                @click="pendingInsertIndex = index; $refs.videoUploadInput.click()"
                                class="w-full h-10 border-2 border-dashed border-slate-300 hover:border-indigo-400 hover:bg-indigo-50 rounded-xl text-xs font-bold text-slate-500 hover:text-indigo-600 transition-all flex items-center justify-center gap-2">
                            <span class="material-symbols-outlined text-[16px]">upload</span>
                            Upload Video dari Perangkat
                        </button>
                        <div class="mt-3 flex justify-center">
                            <button type="button" @click="removeBlock(index)"
                                    class="text-xs text-slate-400 hover:text-red-500 flex items-center gap-1 font-bold transition-colors">
                                <span class="material-symbols-outlined text-[14px]">delete</span> Hapus blok
                            </button>
                        </div>
                    </div>
                    <div x-show="block.embedSrc" class="rounded-2xl overflow-hidden aspect-video shadow-lg">
                        <iframe :src="block.embedSrc" class="w-full h-full" allowfullscreen frameborder="0"></iframe>
                    </div>
                    <div x-show="block.localSrc" class="rounded-2xl overflow-hidden shadow-lg">
                        <video :src="block.localSrc" controls class="w-full h-auto rounded-2xl"></video>
                    </div>
                    <button type="button" @click="removeBlock(index)"
                            class="absolute top-2 right-2 w-8 h-8 bg-black/60 hover:bg-red-500 text-white rounded-lg flex items-center justify-center opacity-0 group-hover/vid:opacity-100 transition-all"
                            x-show="block.embedSrc || block.localSrc">
                        <span class="material-symbols-outlined text-[16px]">delete</span>
                    </button>
                </div>

                {{-- DIVIDER --}}
                <div x-show="block.type === 'divider'"
                     tabindex="-1"
                     @keydown.delete.prevent="removeBlock(index)"
                     @keydown.backspace.prevent="removeBlock(index)"
                     @focus="focusedIndex = index"
                     class="my-10 ml-9 flex items-center gap-5 text-slate-300 text-xl group/dv cursor-pointer"
                     @click="$el.focus()">
                    <span>✦</span><span>✦</span><span>✦</span>
                    <button type="button" @click.stop="removeBlock(index)"
                            class="ml-2 opacity-0 group-hover/dv:opacity-100 hover:text-red-400 transition-all">
                        <span class="material-symbols-outlined text-[15px]">close</span>
                    </button>
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
             class="fixed z-[200] bg-slate-900 rounded-xl shadow-2xl flex items-center gap-0.5 p-1"
             :style="formatBarStyle"
             style="display:none"
             @mousedown.prevent>
            <button type="button" @click="formatText('bold')"
                    class="w-8 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors font-bold text-sm">B</button>
            <button type="button" @click="formatText('italic')"
                    class="w-8 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors italic text-sm">I</button>
            <button type="button" @click="formatText('underline')"
                    class="w-8 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors underline text-sm">U</button>
            <button type="button" @click="formatText('strikethrough')"
                    class="w-8 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors line-through text-sm">S</button>
            <div class="w-px h-5 bg-white/20 mx-0.5"></div>
            <button type="button" @click="convertBlockType('h1')"
                    class="px-2 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xs font-black">H1</button>
            <button type="button" @click="convertBlockType('h2')"
                    class="px-2 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xs font-black">H2</button>
            <button type="button" @click="convertBlockType('quote')"
                    class="w-8 h-7 rounded-lg flex items-center justify-center text-white hover:bg-white/20 transition-colors text-sm">"</button>
        </div>

        {{-- BLOCK TYPE MENU --}}
        <div x-show="blockMenuAt !== null"
             x-transition:enter="transition ease-out duration-100"
             x-transition:enter-start="opacity-0 translate-y-1 scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 scale-100"
             x-transition:leave="transition ease-in duration-75"
             x-transition:leave-end="opacity-0 scale-95"
             @click.away="blockMenuAt = null"
             class="fixed z-[100] bg-white rounded-2xl border border-slate-200 shadow-2xl w-56 overflow-y-auto max-h-[min(420px,70vh)]"
             :style="menuStyle"
             style="display:none">
            <div class="p-1">
                <p class="px-3 pt-2 pb-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Teks</p>
                <button type="button" @click="changeBlockType(blockMenuAt, 'paragraph'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">text_fields</span>
                    <p class="text-sm font-semibold text-slate-700">Teks</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h1'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="text-[14px] font-black text-slate-500 w-[18px] text-center">H1</span>
                    <p class="text-sm font-semibold text-slate-700">Heading 1</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h2'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="text-[14px] font-black text-slate-500 w-[18px] text-center">H2</span>
                    <p class="text-sm font-semibold text-slate-700">Heading 2</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'h3'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="text-[14px] font-black text-slate-500 w-[18px] text-center">H3</span>
                    <p class="text-sm font-semibold text-slate-700">Heading 3</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'quote'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">format_quote</span>
                    <p class="text-sm font-semibold text-slate-700">Kutipan</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'callout'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-amber-500">lightbulb</span>
                    <p class="text-sm font-semibold text-slate-700">Callout</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'bullet'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">format_list_bulleted</span>
                    <p class="text-sm font-semibold text-slate-700">Bulleted List</p>
                </button>
                <button type="button" @click="changeBlockType(blockMenuAt, 'numbered'); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">format_list_numbered</span>
                    <p class="text-sm font-semibold text-slate-700">Numbered List</p>
                </button>
                <p class="px-3 pt-2 pb-1 text-[10px] font-black text-slate-400 uppercase tracking-widest">Media</p>
                <label class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left cursor-pointer">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">image</span>
                    <p class="text-sm font-semibold text-slate-700">Foto</p>
                    <input type="file" accept="image/*" class="sr-only"
                           @change="insertImageBlockFromMenu($event, blockMenuAt); blockMenuAt = null">
                </label>
                <button type="button" @click="insertVideoBlock(blockMenuAt); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">play_circle</span>
                    <p class="text-sm font-semibold text-slate-700">Video</p>
                </button>
                <button type="button" @click="insertDivider(blockMenuAt); blockMenuAt = null"
                        class="w-full flex items-center gap-3 px-3 py-2 rounded-xl hover:bg-slate-50 transition-colors text-left">
                    <span class="material-symbols-outlined text-[18px] text-slate-500">horizontal_rule</span>
                    <p class="text-sm font-semibold text-slate-700">Pemisah</p>
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
    <div class="border-t-2 border-dashed border-slate-200 pt-8 space-y-5">
        <p class="text-[11px] font-black text-slate-400 uppercase tracking-[0.2em]">Pengaturan Artikel</p>

        <div class="space-y-1.5">
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">
                Kategori <span class="text-red-500">*</span>
            </label>
            <div class="relative" x-data="{ open: false }">
                <button type="button" @click="open = !open"
                        class="w-full flex items-center justify-between h-11 px-4 rounded-xl border-2 text-sm font-bold transition-all"
                        :class="selectedCategoryId
                            ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                            : (showCategoryError ? 'border-red-400 bg-red-50 text-red-500' : 'border-slate-200 bg-white text-slate-500 hover:border-slate-300')">
                    <div class="flex items-center gap-2">
                        <span class="material-symbols-outlined text-[16px]">folder</span>
                        <span x-text="selectedCategoryName || 'Pilih kategori artikel'"></span>
                    </div>
                    <span class="material-symbols-outlined text-[18px]"
                          :class="open ? 'rotate-180' : ''" style="transition:transform 0.2s">expand_more</span>
                </button>
                <div x-show="open" @click.away="open = false" x-transition
                     class="absolute top-[calc(100%+4px)] left-0 right-0 bg-white rounded-xl border border-slate-200 shadow-xl z-30 overflow-hidden max-h-52 overflow-y-auto">
                    @foreach($categories as $cat)
                        <button type="button"
                                @click="selectedCategoryId = {{ $cat->id }}; selectedCategoryName = '{{ addslashes($cat->name) }}'; open = false; isDirty = true; showCategoryError = false"
                                class="w-full text-left px-4 py-3 text-sm font-medium transition-colors border-b border-slate-50 last:border-0
                                       {{ $article->category_id == $cat->id
                                           ? 'bg-indigo-50 text-indigo-700 font-bold'
                                           : 'text-slate-700 hover:bg-indigo-50 hover:text-indigo-700' }}">
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
            <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">
                Status Publikasi <span class="text-red-500">*</span>
            </label>
            <div class="grid grid-cols-2 gap-2">
                <button type="button" @click="currentStatus = 'draft'; isDirty = true; showStatusError = false"
                        class="h-11 flex items-center justify-center gap-2 rounded-xl border-2 text-xs font-bold uppercase tracking-wide transition-all"
                        :class="currentStatus === 'draft'
                            ? 'border-slate-800 bg-slate-800 text-white'
                            : (showStatusError ? 'border-red-300 text-red-400 bg-red-50' : 'border-slate-200 text-slate-500 hover:border-slate-300 bg-white')">
                    <span class="material-symbols-outlined text-[15px]">draft</span>
                    Simpan Draf
                </button>
                <button type="button" @click="currentStatus = 'published'; isDirty = true; showStatusError = false"
                        class="h-11 flex items-center justify-center gap-2 rounded-xl border-2 text-xs font-bold uppercase tracking-wide transition-all"
                        :class="currentStatus === 'published'
                            ? 'border-emerald-500 bg-emerald-500 text-white'
                            : (showStatusError ? 'border-red-300 text-red-400 bg-red-50' : 'border-slate-200 text-slate-500 hover:border-slate-300 bg-white')">
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

        <button type="button" @click="submitToLivewire()" :disabled="isSaving"
                class="w-full h-14 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-black uppercase tracking-widest shadow-lg transition-all active:scale-[0.99] disabled:opacity-60 flex items-center justify-center gap-3 mt-2">
            <div x-show="isSaving" class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
            <span x-show="!isSaving" class="material-symbols-outlined text-[20px]">sync</span>
            <span x-text="isSaving ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
        </button>
    </div>

</div>

<script>
function articleEditor() {
    return {
        titleValue: '',
        currentStatus: null,
        selectedCategoryId: null,
        selectedCategoryName: '',
        coverPreview: null,
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
            this.blocks = [{ id: this.nextId++, type: 'paragraph', content: '' }];
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
            if (event.key === 'Enter') {
            event.preventDefault();
            let nextType = block.type;
            if (
                !['bullet', 'numbered'].includes(block.type)
            ) {
                nextType = 'paragraph';
            }

            const nb = {
                id: this.nextId++,
                type: nextType,
                content: ''
            };

            this.blocks.splice(index + 1, 0, nb);

            this.$nextTick(() => {
                const el = document.getElementById('block-' + nb.id);
                if (el) {
                    el.focus();
                    placeCaretAtEnd(el);
                }
            });

            return;
        }
            } else if (event.key === 'Backspace') {
                const block = this.blocks[index];
                const el = document.getElementById('block-' + block.id);
                const isEmpty = !el || el.innerText.trim() === '';
                if (isEmpty && this.blocks.length > 1) {
                    event.preventDefault();
                    this.blocks.splice(index, 1);
                    this.$nextTick(() => {
                        const prev = this.blocks[Math.max(0, index - 1)];
                        if (prev) {
                            const pel = document.getElementById('block-' + prev.id);
                            if (pel) { pel.focus(); placeCaretAtEnd(pel); }
                        }
                    });
                }
            } else if (event.key === 'Delete') {
                const block = this.blocks[index];
                const el = document.getElementById('block-' + block.id);
                const isEmpty = !el || el.innerText.trim() === '';
                if (isEmpty && this.blocks.length > 1) {
                    event.preventDefault();
                    this.blocks.splice(index, 1);
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
            this.$nextTick(() => {
                const sel = window.getSelection();
                if (!sel || sel.isCollapsed || sel.toString().trim() === '') {
                    this.showFormatBar = false;
                    return;
                }
                const range = sel.getRangeAt(0);
                const rect = range.getBoundingClientRect();
                if (rect.width === 0) { this.showFormatBar = false; return; }
                const x = rect.left + rect.width / 2 - 150;
                const y = rect.top - 48;
                this.formatBarStyle = `position:fixed;top:${y}px;left:${Math.max(8, x)}px;`;
                this.showFormatBar = true;
            });
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
            const yt = url.match(/(?:youtube\.com.*[?]v=|youtu\.be\/)([^&?#]+)/i);/(?:youtube\.com.*[?]v=|youtu\.be\/)([^&?#]+)/i);/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|shorts\/|live\/))([^&?/]+)/i);
            if (yt) { block.embedSrc = 'https://www.youtube.com/embed/' + yt[1]; return; }
            const gd = url.match(/drive\.google\.com\/file\/d\/([^/?]+)/i);
            if (gd) { block.embedSrc = `https://drive.google.com/file/d/${gd[1]}/preview`;
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
                await $wire.call(
                    'saveFromAlpine',
                    this.titleValue,
                    contentJson,
                    this.currentStatus,
                    this.selectedCategoryId
                );
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

<style>
.ce-placeholder:empty::before {
    content: attr(data-placeholder);
    color: #cbd5e1;
    pointer-events: none;
    font-style: italic;
    display: block;
}
[contenteditable] {
    direction: ltr !important;
    unicode-bidi: normal !important;
    text-align: left !important;
}
[contenteditable]:focus { outline: none; }

#blocks-container-update img,
#blocks-container-update video,
#blocks-container-update iframe {
    max-width: 100%;
    height: auto;
}
#blocks-container-update figure {
    width: 100%;
}
#blocks-container-update .aspect-video {
    position: relative;
    aspect-ratio: 16/9;
}
#blocks-container-update .aspect-video iframe {
    position: absolute;
    inset: 0;
    width: 100%;
    height: 100%;
}
</style>

</div>