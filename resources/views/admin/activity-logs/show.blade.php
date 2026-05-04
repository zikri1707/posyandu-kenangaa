@extends('layouts.app')

@section('title', 'Detail Log Aktivitas')

@section('content')
<div class="space-y-10">
    {{-- ── Header ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <a href="{{ route('admin.activity-logs.index') }}" class="inline-flex items-center gap-2 text-xs font-black text-slate-400 uppercase tracking-widest hover:text-teal-600 transition-colors mb-4 group">
                <span class="material-symbols-outlined text-[18px] group-hover:-translate-x-1 transition-transform">arrow_back</span>
                Kembali ke Daftar
            </a>
            <h2 class="text-display text-4xl md:text-5xl mb-2">Detail <span class="text-teal-600 italic">Audit.</span></h2>
            <p class="text-body-md text-slate-500 font-medium">Informasi mendalam mengenai perubahan data sistem.</p>
        </div>
        
        @php
            $badgeStyle = match($activityLog->action_type) {
                'create' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                'update' => 'bg-amber-50 text-amber-600 border-amber-100',
                'delete' => 'bg-red-50 text-red-600 border-red-100',
                default => 'bg-slate-50 text-slate-500 border-slate-100',
            };
        @endphp
        <div class="inline-flex items-center px-6 py-3 rounded-2xl border-2 {{ $badgeStyle }} shadow-sm">
            <span class="w-2.5 h-2.5 rounded-full bg-current mr-3 animate-pulse"></span>
            <span class="text-sm font-black uppercase tracking-[0.2em]">{{ $activityLog->action_type }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        {{-- ── Left Side: General Info ── --}}
        <div class="lg:col-span-2 space-y-8">
            <div class="premium-card p-8!">
                <h3 class="text-headline-md mb-8 flex items-center gap-3">
                    <span class="material-symbols-outlined text-teal-600">info</span>
                    Informasi Umum
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-12 gap-y-8">
                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Waktu Kejadian</p>
                        <p class="text-lg font-black text-slate-900 leading-tight">{{ $activityLog->created_at->format('d M Y, H:i:s') }}</p>
                        <p class="text-xs font-bold text-slate-400 italic">{{ $activityLog->created_at->diffForHumans() }}</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">IP Address</p>
                        <p class="text-lg font-black text-slate-900 leading-tight font-mono">{{ $activityLog->ip_address }}</p>
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-widest">Akses Jaringan</p>
                    </div>

                    <div class="space-y-1">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Entitas Terdampak</p>
                        <div class="flex items-center gap-3 mt-1">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500">
                                <span class="material-symbols-outlined">database</span>
                            </div>
                            <div>
                                <p class="text-base font-black text-slate-800">{{ $activityLog->entity_type ? class_basename($activityLog->entity_type) : 'Global System' }}</p>
                                <p class="text-xs font-bold text-teal-600 uppercase tracking-widest">ID #{{ $activityLog->entity_id ?? 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-1 md:col-span-2">
                        <p class="text-[11px] font-black text-slate-400 uppercase tracking-widest">Deskripsi Perubahan</p>
                        <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100 mt-1">
                            <p class="text-body-md font-bold text-slate-700 leading-relaxed">{{ $activityLog->description }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Data Comparison --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                {{-- Old Values --}}
                @if($activityLog->old_values)
                <div class="premium-card p-6 border-red-50!">
                    <h3 class="text-sm font-black text-red-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">history</span>
                        Data Lama
                    </h3>
                    <div class="bg-slate-900 rounded-2xl p-4 overflow-hidden">
                        <pre class="text-[11px] font-mono text-slate-300 overflow-x-auto custom-scrollbar leading-relaxed"><code>{{ json_encode($activityLog->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>
                @endif

                {{-- New Values --}}
                @if($activityLog->new_values)
                <div class="premium-card p-6 border-emerald-50!">
                    <h3 class="text-sm font-black text-emerald-600 uppercase tracking-[0.2em] mb-6 flex items-center gap-2">
                        <span class="material-symbols-outlined text-[20px]">update</span>
                        Data Baru
                    </h3>
                    <div class="bg-slate-900 rounded-2xl p-4 overflow-hidden">
                        <pre class="text-[11px] font-mono text-slate-300 overflow-x-auto custom-scrollbar leading-relaxed"><code>{{ json_encode($activityLog->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</code></pre>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ── Right Side: User & Agent Info ── --}}
        <div class="space-y-8">
            <div class="bento-card p-8">
                <h3 class="text-headline-sm mb-6 flex items-center gap-3">
                    <span class="material-symbols-outlined text-teal-600">person</span>
                    Pelaku Aksi
                </h3>
                <div class="flex flex-col items-center text-center space-y-4">
                    <div class="w-24 h-24 rounded-[2rem] bg-gradient-to-br from-slate-800 to-slate-900 text-white flex items-center justify-center text-3xl font-black shadow-2xl">
                        {{ strtoupper(substr($activityLog->user_name ?? 'A', 0, 2)) }}
                    </div>
                    <div>
                        <h4 class="text-xl font-black text-slate-900">{{ $activityLog->user_name ?? 'System' }}</h4>
                        <p class="text-xs font-black text-teal-600 uppercase tracking-widest mt-1">{{ $activityLog->role ?? 'N/A' }}</p>
                    </div>
                    <div class="w-full pt-4 border-t border-slate-50 space-y-3">
                        <div class="flex justify-between text-xs font-bold">
                            <span class="text-slate-400 uppercase tracking-widest">Metode</span>
                            <span class="text-slate-900">Web Dashboard</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="premium-card p-6 bg-slate-50/50">
                <h3 class="text-xs font-black text-slate-400 uppercase tracking-[0.2em] mb-4 flex items-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">devices</span>
                    Informasi Perangkat
                </h3>
                <div class="p-4 bg-white rounded-2xl border border-slate-100 shadow-sm">
                    <p class="text-[11px] font-mono text-slate-500 break-all leading-relaxed italic">
                        {{ $activityLog->user_agent }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

