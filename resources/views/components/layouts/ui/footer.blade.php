{{-- Posyandu Admin Footer --}}
<footer class="border-t border-outline-variant py-5 mt-auto"
        style="background:var(--color-surface-container-low, #f0f5f2); font-family:'Public Sans',sans-serif;">
    <div class="px-6 md:px-8 flex flex-col sm:flex-row justify-between items-center gap-3">

        <p class="text-[12px] font-medium text-on-surface-variant">
            &copy; {{ date('Y') }}
            <span class="font-bold text-primary">{{ config('app.name', 'Posyandu') }}</span>.
            Sistem Pengelolaan Data Posyandu ILP Kenanga Bekasi Timur.
        </p>

        <div class="flex items-center gap-3 text-[11px] font-semibold uppercase tracking-wider text-outline">
            <span>v1.0.0</span>
            <span class="w-1 h-1 rounded-full bg-outline-variant"></span>
            <span>Admin Dashboard</span>
        </div>

    </div>
</footer>
