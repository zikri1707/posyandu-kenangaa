<div class="min-h-screen bg-white">
    
    {{-- ── Header Navigation ── --}}
    <div class="bg-white border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 md:px-6 py-4 flex items-center justify-between">
            <a href="{{ route('admin.articles.index') }}" 
               class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-slate-100 text-slate-600 transition-all">
                <span class="material-symbols-outlined text-[20px]">arrow_back</span>
            </a>
            <div class="flex items-center gap-2">
                <a href="{{ route('public.articles.show', $article->slug) }}" 
                   target="_blank"
                   class="h-10 px-4 flex items-center gap-2 bg-white border border-slate-200 hover:border-slate-300 rounded-lg text-sm font-bold text-slate-700 transition-all">
                    <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                    Lihat di Web
                </a>
                <a href="{{ route('admin.articles.edit', $article) }}" 
                   class="h-10 px-4 flex items-center gap-2 bg-indigo-600 hover:bg-indigo-700 rounded-lg text-sm font-bold text-white transition-all">
                    <span class="material-symbols-outlined text-[18px]">edit</span>
                    Edit
                </a>
            </div>
        </div>
    </div>

    {{-- ── Article Content ── --}}
    <article class="py-12">
        <div class="max-w-4xl mx-auto px-4 md:px-6 space-y-8">

            {{-- ── Header Image (Full Width) ── --}}
            @if($article->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($article->thumbnail))
                <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-md">
                    <img src="{{ asset('storage/'.$article->thumbnail) }}" 
                         alt="{{ $article->title }}" 
                         class="w-full h-full object-cover">
                </div>
            @endif

            {{-- ── Article Header (Title, Meta, Description) ── --}}
            <div class="space-y-4">
                <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight">
                    {{ $article->title }}
                </h1>

                {{-- Article Meta Info (Author, Date, Reading Time) --}}
                <div class="flex flex-wrap items-center gap-4 py-4 border-y border-slate-200">
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($article->user->name, 0, 1) }}
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-900">{{ $article->user->name }}</p>
                            <p class="text-xs text-slate-500">{{ $article->created_at->format('d M Y') }}</p>
                        </div>
                    </div>

                    <div class="h-8 border-l border-slate-300"></div>

                    <div class="flex items-center gap-4 text-sm">
                        <div class="flex items-center gap-1 text-slate-600">
                            <span class="material-symbols-outlined text-[16px]">schedule</span>
                            <span class="font-bold">{{ $article->reading_time }}</span>
                        </div>
                        <div class="flex items-center gap-1 text-slate-600">
                            <span class="material-symbols-outlined text-[16px]">article</span>
                            <span class="font-bold">{{ str_word_count(strip_tags($article->content)) }} kata</span>
                        </div>
                    </div>

                    @if($article->category)
                        <div class="h-8 border-l border-slate-300"></div>
                        <a href="#" 
                           class="inline-flex items-center gap-1 px-3 py-1 bg-slate-100 hover:bg-slate-200 rounded-full text-sm font-bold text-slate-700 transition-all">
                            <span class="material-symbols-outlined text-[14px]">label</span>
                            {{ $article->category->name }}
                        </a>
                    @endif
                </div>

                {{-- Article Description (Ringkasan) --}}
                @if($article->description)
                    <p class="text-lg text-slate-600 leading-relaxed font-medium italic border-l-4 border-indigo-600 pl-6">
                        {{ $article->description }}
                    </p>
                @endif
            </div>

            {{-- ── Article Body Content ── --}}
            <div class="prose prose-lg max-w-none text-slate-800">
                @if($article->content_blocks && count($article->content_blocks) > 0)
                    <div class="space-y-6">
                        @foreach($article->content_blocks as $block)
                            @if($block['type'] === 'text' && isset($block['data']['content']))
                                <p class="leading-8 text-base md:text-lg text-slate-700 whitespace-pre-line mb-6">
                                    {{ $block['data']['content'] }}
                                </p>

                            @elseif($block['type'] === 'image' && isset($block['data']['url']) && !empty($block['data']['url']))
                                <figure class="my-8 space-y-3">
                                    <img src="{{ asset('storage/' . $block['data']['url']) }}" 
                                         alt="{{ $block['data']['caption'] ?? 'Gambar Artikel' }}" 
                                         class="w-full rounded-xl shadow-md">
                                    @if(!empty($block['data']['caption']))
                                        <figcaption class="text-sm text-slate-500 italic text-center">
                                            {{ $block['data']['caption'] }}
                                        </figcaption>
                                    @endif
                                </figure>

                            @elseif($block['type'] === 'video' && isset($block['data']['url']) && !empty($block['data']['url']))
                                @php
                                    $ytEmbed = \App\Models\Article::getYoutubeEmbedUrl($block['data']['url']);
                                    $gdEmbed = \App\Models\Article::getGoogleDriveEmbedUrl($block['data']['url']);
                                @endphp
                                <div class="my-8 space-y-3">
                                    <div class="relative w-full bg-slate-900 rounded-xl overflow-hidden shadow-md" style="padding-bottom: 56.25%;">
                                        @if($ytEmbed)
                                            <iframe class="absolute inset-0 w-full h-full" 
                                                    src="{{ $ytEmbed }}" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                        @elseif($gdEmbed)
                                            <iframe class="absolute inset-0 w-full h-full" 
                                                    src="{{ $gdEmbed }}" 
                                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                                    allowfullscreen>
                                            </iframe>
                                        @else
                                            <div class="absolute inset-0 flex flex-col items-center justify-center bg-slate-800 text-slate-400 p-4">
                                                <span class="material-symbols-outlined text-[48px] text-red-400">link_off</span>
                                                <p class="text-sm font-bold mt-2">Video link tidak valid</p>
                                                <a href="{{ $block['data']['url'] }}" target="_blank" class="text-xs text-indigo-400 underline mt-1 break-all">{{ $block['data']['url'] }}</a>
                                            </div>
                                        @endif
                                    </div>
                                    @if(!empty($block['data']['caption']))
                                        <p class="text-sm text-slate-500 italic text-center">
                                            {{ $block['data']['caption'] }}
                                        </p>
                                    @endif
                                </div>

                            @elseif($block['type'] === 'divider')
                                <div class="flex items-center gap-4 py-8">
                                    <div class="flex-1 border-t border-slate-200"></div>
                                    <span class="material-symbols-outlined text-slate-300">more_horiz</span>
                                    <div class="flex-1 border-t border-slate-200"></div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                @else
                    {{-- Fallback untuk artikel lama tanpa blocks --}}
                    <div class="whitespace-pre-line leading-8 text-base md:text-lg text-slate-700">
                        {!! nl2br(e($article->content)) !!}
                    </div>
                @endif
            </div>

            {{-- ── Article Footer (Author Bio) ── --}}
            <div class="bg-gradient-to-r from-indigo-50 to-blue-50 rounded-2xl border border-indigo-200 p-6 md:p-8 space-y-4">
                <div class="flex items-start gap-4">
                    <div class="w-16 h-16 md:w-20 md:h-20 rounded-full bg-gradient-to-br from-indigo-400 to-blue-500 flex items-center justify-center text-white font-black text-3xl flex-shrink-0">
                        {{ substr($article->user->name, 0, 1) }}
                    </div>
                    <div class="space-y-2 flex-1">
                        <h3 class="text-lg md:text-xl font-black text-slate-900">
                            {{ $article->user->name }}
                        </h3>
                        <p class="text-sm text-slate-600 leading-relaxed">
                            Penulis artikel di Kenanga Posyandu. Berbagi pengetahuan kesehatan dan gizi untuk masyarakat.
                        </p>
                    </div>
                </div>
            </div>

            {{-- ── Article Status Footer ── --}}
            <div class="flex flex-wrap items-center justify-between gap-4 py-6 border-t border-slate-200">
                <div class="flex items-center gap-3">
                    @if($article->status === 'published')
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">
                            <span class="material-symbols-outlined text-[16px]">check_circle</span>
                            Dipublikasikan
                        </span>
                    @else
                        <span class="inline-flex items-center gap-2 px-4 py-2 bg-amber-100 text-amber-700 rounded-full font-bold text-sm">
                            <span class="material-symbols-outlined text-[16px]">draft</span>
                            Draf
                        </span>
                    @endif

                    <span class="text-xs text-slate-500">
                        Dibuat {{ $article->created_at->diffForHumans() }}
                    </span>
                </div>

                <div class="flex items-center gap-2">
                    <a href="{{ route('public.articles.show', $article->slug) }}" 
                       target="_blank"
                       class="h-10 px-4 flex items-center gap-2 bg-slate-100 hover:bg-slate-200 rounded-lg text-sm font-bold text-slate-700 transition-all">
                        <span class="material-symbols-outlined text-[18px]">open_in_new</span>
                        Baca di Web
                    </a>
                </div>
            </div>
        </div>
    </article>
</div>

                </a>
            </div>
    </article>
</div>
