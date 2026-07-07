{{-- ── 2. STATS SECTION (METRIC COUNTERS) ── --}}
<section class="py-16 bg-slate-50/50 dark:bg-slate-950/40 relative overflow-hidden">
    {{-- Decorative subtle background shapes for high-end look --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-primary/5 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-teal-500/5 rounded-full blur-3xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-6 md:px-12 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            
            {{-- Stat 1: Kader Aktif --}}
            <div class="group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-primary/10 text-primary flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[32px]">groups</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta">{{ count($kaders) }}</span>
                        <span class="text-sm font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest">Kader</span>
                    </div>
                    <span class="block text-xs font-black text-primary uppercase tracking-widest mt-1">Kader Aktif</span>
                    <span class="block text-sm text-slate-650 dark:text-slate-350 font-bold mt-0.5">Terlatih &amp; Berdedikasi</span>
                </div>
            </div>

            {{-- Stat 2: Sasaran Layanan --}}
            <div class="group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-teal-500/10 text-teal-600 dark:text-teal-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[32px]">family_restroom</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta">{{ $sasaranCount }}</span>
                        <span class="text-sm font-bold text-teal-600 dark:text-teal-400 uppercase tracking-widest">Warga</span>
                    </div>
                    <span class="block text-xs font-black text-teal-600 dark:text-teal-455 uppercase tracking-widest mt-1">Warga Sasaran</span>
                    <span class="block text-sm text-slate-650 dark:text-slate-350 font-bold mt-0.5">Balita, Ibu Hamil &amp; Lansia</span>
                </div>
            </div>

            {{-- Stat 3: Unit Posyandu --}}
            <div class="group bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800/80 rounded-3xl p-8 shadow-xs hover:shadow-xl hover:-translate-y-1 transition-all duration-300 flex items-center gap-6">
                <div class="w-16 h-16 rounded-2xl bg-amber-500/10 text-amber-600 dark:text-amber-400 flex items-center justify-center shrink-0 group-hover:scale-110 transition-transform duration-300">
                    <span class="material-symbols-outlined text-[32px]">home_health</span>
                </div>
                <div class="flex-1 min-w-0">
                    <div class="flex items-baseline gap-1">
                        <span class="text-4xl md:text-5xl font-black text-slate-900 dark:text-white tracking-tight font-jakarta">{{ $posyanduCount }}</span>
                        <span class="text-sm font-bold text-amber-600 dark:text-amber-400 uppercase tracking-widest">Unit</span>
                    </div>
                    <span class="block text-xs font-black text-amber-600 dark:text-amber-455 uppercase tracking-widest mt-1">Unit Posyandu</span>
                    <span class="block text-sm text-slate-650 dark:text-slate-350 font-bold mt-0.5">Integrasi Layanan Primer (ILP)</span>
                </div>
            </div>

        </div>
    </div>
</section>
