<div class="relative" x-data="{ notifOpen: false }" @close-dropdowns.window="if ($event.detail !== 'notif') notifOpen = false" wire:poll.30s="calculateUnread">
    <button @click="notifOpen = !notifOpen; if(notifOpen) { $dispatch('close-dropdowns', 'notif'); $wire.markAsRead(); }" 
        class="w-10 h-10 flex items-center justify-center rounded-xl
               text-slate-500 border border-slate-200 hover:bg-slate-100 hover:text-teal-600
               active:scale-95 transition-all duration-150 relative shadow-sm bg-white">
        <i class="fas fa-bell" style="font-size:14px;"></i>
        
        @if($unreadCount > 0)
        <span class="absolute top-2 right-2 w-2.5 h-2.5 rounded-full bg-red-500 border-2 border-white animate-pulse"></span>
        @endif
    </button>
 
    {{-- Notification dropdown --}}
    <div x-show="notifOpen" 
        @click.away="notifOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        class="absolute right-0 mt-3 w-80 md:w-96 bg-white rounded-3xl shadow-2xl
               border border-slate-100 overflow-hidden z-50 py-2"
        x-cloak>

        {{-- Header --}}
        <div class="flex items-center justify-between px-5 py-4 border-b border-slate-50">
            <span class="text-slate-900 font-black text-sm">Notifikasi Sistem</span>
            @if($unreadCount > 0)
            <span class="text-[10px] font-black text-white px-2.5 py-1 rounded-full bg-red-500 uppercase tracking-widest">{{ $unreadCount }} Baru</span>
            @endif
        </div>

        {{-- Items --}}
        <div class="divide-y divide-slate-50 max-h-96 overflow-y-auto custom-scrollbar">
            @forelse($notifications as $n)
            <a href="{{ $n['url'] }}" class="flex items-start gap-4 px-5 py-4 hover:bg-slate-50 transition-colors cursor-pointer {{ $n['unread'] ? 'bg-blue-50/10' : '' }}">
                <div class="w-10 h-10 rounded-2xl flex items-center justify-center flex-shrink-0 {{ $n['color'] }} shadow-sm border border-current/10">
                    <i class="fas {{ $n['icon'] }}" style="font-size:14px;"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-slate-900 font-black text-[13px] leading-tight">{{ $n['title'] }}</p>
                    <p class="text-slate-500 text-[12px] mt-1 line-clamp-2 font-medium leading-relaxed">{{ $n['desc'] }}</p>
                    <p class="text-slate-400 text-[10px] mt-2 font-bold uppercase tracking-wider flex items-center gap-1.5">
                        <span class="material-symbols-outlined text-[12px]">schedule</span>
                        {{ $n['time'] }}
                    </p>
                </div>
                @if($n['unread'])
                <div class="w-2.5 h-2.5 rounded-full bg-blue-500 flex-shrink-0 mt-2 shadow-sm shadow-blue-500/30"></div>
                @endif
            </a>
            @empty
            <div class="px-5 py-12 text-center">
                <div class="w-16 h-16 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-4 text-slate-300">
                    <i class="fas fa-bell-slash text-2xl"></i>
                </div>
                <p class="text-slate-400 font-bold text-sm">Belum ada notifikasi baru</p>
            </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-5 py-4 border-t border-slate-50 text-center">
            <a href="{{ route('admin.activity-logs.index') }}" class="text-[12px] font-black text-teal-600 hover:text-teal-800 transition-colors uppercase tracking-widest">
                Lihat Semua Log Aktivitas
            </a>
        </div>
    </div>
</div>
