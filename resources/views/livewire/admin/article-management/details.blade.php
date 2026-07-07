<div class="min-h-screen bg-[#f8f8f7]">
<style>
    .prose p, .prose span, .prose li {
        text-align: justify !important;
    }
</style>

    {{-- ── Top Nav ── --}}
    <div class="max-w-[860px] mx-auto px-4 md:px-8 pt-2 md:pt-6 flex items-center gap-3">
        <a href="{{ route('admin.articles.index') }}"
        class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-sm font-bold text-slate-700 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">arrow_back</span>
            Kembali
        </a>
        <div class="flex items-center gap-2 ml-auto">
            @if($article->status === 'published')
            <a href="{{ route('public.articles.show', $article->slug) }}" target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-white border border-slate-200 hover:bg-slate-50 text-sm font-bold text-slate-700 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[16px]">open_in_new</span>
                Lihat di Web
            </a>
            @endif
            <a href="{{ route('admin.articles.edit', $article) }}"
            class="inline-flex items-center gap-2 px-4 py-2 rounded-xl text-sm font-bold text-white transition-all hover:bg-slate-800"
            style="background-color: #0f172a;">
                <span class="material-symbols-outlined text-[16px]">edit</span>
                Edit
            </a>
        </div>
    </div>

    {{-- ── Article ── --}}
    <article class="max-w-[860px] mx-auto px-4 md:px-8 py-4 space-y-6 md:space-y-8">

        {{-- Status Badge --}}
        <div class="flex items-center gap-3">
            @if($article->status === 'published')
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-emerald-100 text-emerald-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                    Dipublikasikan
                </span>
            @else
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-amber-100 text-amber-700">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    Draft
                </span>
            @endif
            @if($article->category)
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-widest bg-indigo-50 text-indigo-600 border border-indigo-100">
                    {{ $article->category->name }}
                </span>
            @endif
            <span class="text-xs text-slate-400 ml-auto">{{ $article->created_at->diffForHumans() }}</span>
        </div>

        {{-- Title --}}
        <h1 class="text-4xl md:text-5xl font-black text-slate-900 leading-tight tracking-tight"
            style="font-family:'Georgia',serif;">
            {{ $article->title }}
        </h1>

        {{-- Author Meta --}}
        <div class="flex items-center gap-4 py-4 border-y border-slate-100">
            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                {{ substr($article->user->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <p class="text-sm font-bold text-slate-900">{{ $article->user->name ?? 'Admin' }}</p>
                <p class="text-xs text-slate-400">{{ $article->created_at->translatedFormat('d F Y') }}</p>
            </div>
            <div class="flex items-center gap-4 ml-auto text-xs text-slate-400 font-bold">
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">schedule</span>
                    {{ $article->reading_time }}
                </span>
                <span class="flex items-center gap-1">
                    <span class="material-symbols-outlined text-[14px]">article</span>
                    {{ str_word_count(strip_tags($article->content)) }} kata
                </span>
            </div>
        </div>

        {{-- Cover Image --}}
        @if($article->thumbnail && \Illuminate\Support\Facades\Storage::disk('public')->exists($article->thumbnail))
            <div class="w-full aspect-video rounded-2xl overflow-hidden shadow-lg">
                <img src="{{ asset('storage/'.$article->thumbnail) }}"
                     alt="{{ $article->title }}"
                     class="w-full h-full object-cover">
            </div>
        @endif

        {{-- Body --}}
        <div class="prose prose-lg max-w-none
                    prose-headings:font-black prose-headings:text-slate-900
                    prose-p:text-slate-700 prose-p:leading-relaxed prose-p:text-justify
                    prose-blockquote:border-slate-300 prose-blockquote:text-slate-600">
            {!! App\Services\ArticleService::renderContent($article->content) !!}
        </div>

        {{-- Author Card --}}
        <div class="bg-white rounded-2xl border border-slate-100 shadow-sm p-6 flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-black text-xl flex-shrink-0">
                {{ substr($article->user->name ?? 'A', 0, 1) }}
            </div>
            <div>
                <p class="font-black text-slate-900">{{ $article->user->name ?? 'Admin' }}</p>
                <p class="text-sm text-slate-500 mt-0.5">Penulis artikel di Kenanga Posyandu. Berbagi pengetahuan kesehatan dan gizi untuk masyarakat.</p>
            </div>
        </div>

    </article>
</div>