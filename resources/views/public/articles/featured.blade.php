<section class="mb-20 grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
    <div class="lg:col-span-7 space-y-8">
        <div class="inline-flex items-center gap-3 px-5 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600">
            <span class="material-symbols-outlined text-[18px] animate-pulse">star</span>
            <span class="text-[10px] font-black uppercase tracking-[0.2em]">Artikel Terpopuler</span>
        </div>
        
        <h1 class="text-4xl md:text-6xl font-black text-slate-900 leading-tight tracking-tight">
            {{ $featured->title }}
        </h1>
        
        <p class="text-lg text-slate-500 font-medium leading-relaxed max-w-2xl">
            {{ \App\Services\ArticleService::getExcerpt($featured->content, 180) }}
        </p>

        <div class="flex items-center gap-6 pt-6">
            <a href="{{ route('public.articles.show', $featured->slug) }}" 
               class="h-14 px-10 flex items-center justify-center bg-slate-900 text-white rounded-2xl text-[11px] font-black uppercase tracking-[0.2em] hover:bg-indigo-600 transition-all shadow-xl active:scale-95">
                Mulai Membaca
            </a>
            <span class="text-slate-400 text-[11px] font-bold uppercase tracking-widest flex items-center gap-2">
                {{ ceil(str_word_count(\App\Services\ArticleService::getExcerpt($featured->content, 999999)) / 200) }} mnt baca
            </span>
        </div>
    </div>
    <div class="lg:col-span-5">
        <div class="relative group">
            <div class="absolute inset-0 bg-indigo-600 rounded-[3rem] rotate-3 opacity-10 group-hover:rotate-6 transition-transform"></div>
            <div class="relative aspect-[4/3] rounded-[3rem] overflow-hidden shadow-2xl border-4 border-white">
                <img src="{{ $featured->thumbnail ? asset('storage/'.$featured->thumbnail) : 'https://images.unsplash.com/photo-1576091160550-217359f4ecf8?q=80&w=1200&auto=format&fit=crop' }}" 
                     alt="{{ $featured->title }}" 
                     fetchpriority="high"
                     loading="eager"
                     decoding="sync"
                     class="w-full h-full object-cover transition-transform duration-[3s] group-hover:scale-110">
            </div>
        </div>
    </div>
</section>
