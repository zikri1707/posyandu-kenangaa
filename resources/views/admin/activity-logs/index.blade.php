@extends('layouts.admin-layout')

@section('title', 'Audit Log Aktivitas')
@section('admin-title', 'Audit Log Aktivitas')

@section('admin-actions')
<div class="flex items-center gap-3 bg-white p-2 rounded-2xl shadow-sm border border-slate-100">
    <div class="w-9 h-9 rounded-xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-inner">
        <span class="material-symbols-outlined text-[20px]">history</span>
    </div>
    <div class="pr-4">
        <p class="text-[9px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Sistem</p>
        <p class="text-xs font-black text-slate-800 leading-none flex items-center gap-1.5">
            <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
            Audit Aktif
        </p>
    </div>
</div>
@endsection

@section('admin-content')
<div class="space-y-8">
    {{-- ── Stats Bento Grid ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                [
                    'label' => 'Total Log',
                    'value' => $totalStats['total'],
                    'icon' => 'list_alt',
                    'color' => 'indigo',
                    'desc' => 'Seluruh rekaman aktivitas',
                    'gradient' => 'from-indigo-500/10 to-blue-500/5',
                    'icon_bg' => 'bg-indigo-50 text-indigo-600 border border-indigo-100'
                ],
                [
                    'label' => 'Aksi Create',
                    'value' => $totalStats['create'],
                    'icon' => 'add_circle',
                    'color' => 'emerald',
                    'desc' => 'Data baru ditambahkan',
                    'gradient' => 'from-emerald-500/10 to-teal-500/5',
                    'icon_bg' => 'bg-emerald-50 text-emerald-600 border border-emerald-100'
                ],
                [
                    'label' => 'Aksi Update',
                    'value' => $totalStats['update'],
                    'icon' => 'edit_square',
                    'color' => 'amber',
                    'desc' => 'Perubahan data sistem',
                    'gradient' => 'from-amber-500/10 to-orange-500/5',
                    'icon_bg' => 'bg-amber-50 text-amber-600 border border-amber-100'
                ],
                [
                    'label' => 'Aksi Delete',
                    'value' => $totalStats['delete'],
                    'icon' => 'delete_forever',
                    'color' => 'rose',
                    'desc' => 'Penghapusan data sistem',
                    'gradient' => 'from-rose-500/10 to-red-500/5',
                    'icon_bg' => 'bg-rose-50 text-rose-600 border border-rose-100'
                ],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bento-card p-6 group relative overflow-hidden bg-white border border-slate-100 rounded-3xl transition-all duration-300 hover:shadow-xl hover:shadow-slate-200/50">
            <div class="absolute inset-0 bg-gradient-to-br {{ $s['gradient'] }} opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            <div class="relative z-10">
                <div class="flex items-start justify-between">
                    <div class="w-12 h-12 rounded-2xl flex items-center justify-center transition-transform group-hover:scale-110 duration-500 shadow-sm {{ $s['icon_bg'] }}">
                        <span class="material-symbols-outlined text-[26px]">{{ $s['icon'] }}</span>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Audit</span>
                </div>
                <div class="mt-6">
                    <p class="text-xs font-black text-slate-400 uppercase tracking-wider mb-1">{{ $s['label'] }}</p>
                    <h3 class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ number_format($s['value']) }}</h3>
                    <p class="text-[11px] text-slate-400 font-medium mt-2">{{ $s['desc'] }}</p>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- ── Filters Panel ── --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 md:p-8">
        <div class="flex items-center gap-3 mb-6">
            <div class="w-8 h-8 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center">
                <span class="material-symbols-outlined text-[18px]">manage_search</span>
            </div>
            <div>
                <h4 class="text-sm font-black text-slate-800 uppercase tracking-wider">Filter Pencarian</h4>
                <p class="text-xs text-slate-400 font-medium">Saring data log audit berdasarkan parameter spesifik</p>
            </div>
        </div>

        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-6">
            {{-- Search Input --}}
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pencarian</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:20px;">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari deskripsi..." 
                           class="w-full h-12 pl-11 pr-4 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- User Select --}}
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pengguna</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:20px; z-index:10;">person</span>
                    <select name="user_id" class="w-full h-12 pl-11 pr-4 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all appearance-none">
                        <option value="">Semua Kader / System</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                                {{ $user->user_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" style="font-size:18px;">expand_more</span>
                </div>
            </div>

            {{-- Action Type Select --}}
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Aksi</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:20px; z-index:10;">bolt</span>
                    <select name="action_type" class="w-full h-12 pl-11 pr-4 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all appearance-none">
                        <option value="">Semua Aksi</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none" style="font-size:18px;">expand_more</span>
                </div>
            </div>

            {{-- Start Date --}}
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Mulai</label>
                <div class="relative">
                    <input type="date" name="start_date" value="{{ request('start_date') }}" 
                           class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- End Date --}}
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Akhir</label>
                <div class="relative">
                    <input type="date" name="end_date" value="{{ request('end_date') }}" 
                           class="w-full h-12 px-4 rounded-xl border border-slate-200 bg-slate-50/50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            {{-- Filter buttons --}}
            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 h-12 bg-teal-600 text-white rounded-xl font-bold uppercase tracking-wider text-xs hover:bg-teal-700 transition-all shadow-md shadow-teal-600/15 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" 
                   title="Reset Filter"
                   class="w-12 h-12 bg-slate-100 text-slate-500 rounded-xl flex items-center justify-center hover:bg-slate-200 hover:text-slate-900 transition-all group">
                    <span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    {{-- ── Main Table Card ── --}}
    <div class="bg-white border border-slate-100 rounded-3xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase whitespace-nowrap text-xs font-black tracking-widest">Waktu & Tanggal</th>
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase whitespace-nowrap text-xs font-black tracking-widest">Pelaku</th>
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase whitespace-nowrap text-xs font-black tracking-widest">Tipe Aksi</th>
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase whitespace-nowrap text-xs font-black tracking-widest">Entitas</th>
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase whitespace-nowrap text-xs font-black tracking-widest">IP Address</th>
                        <th class="px-6 py-4 text-label-lg text-slate-400 uppercase text-right whitespace-nowrap text-xs font-black tracking-widest">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($activityLogs as $log)
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-6 py-5 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-sm font-black text-slate-800">{{ $log->created_at->format('H:i:s') }}</span>
                                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-tighter">{{ $log->created_at->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex items-center gap-3 min-w-[150px]">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-600 font-extrabold text-xs flex-shrink-0">
                                    {{ strtoupper(substr($log->user_name ?? 'SY', 0, 2)) }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[13px] font-black text-slate-800 truncate block max-w-[120px] md:max-w-[180px]" title="{{ $log->user_name ?? 'System' }}">
                                        {{ $log->user_name ?? 'System' }}
                                    </span>
                                    <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest">{{ $log->role ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            @php
                                $badgeStyle = match($log->action_type) {
                                    'create' => 'bg-emerald-50 text-emerald-700 border-emerald-100/80',
                                    'update' => 'bg-amber-50 text-amber-700 border-amber-100/80',
                                    'delete', 'login_failed' => 'bg-rose-50 text-rose-700 border-rose-100/80',
                                    'login', 'logout' => 'bg-indigo-50 text-indigo-700 border-indigo-100/80',
                                    default => 'bg-slate-50 text-slate-600 border-slate-100',
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-lg border {{ $badgeStyle }} text-[10px] font-black uppercase tracking-wider">
                                <span class="w-1.5 h-1.5 rounded-full bg-current mr-1.5"></span>
                                {{ $log->action_type }}
                            </span>
                        </td>
                        <td class="px-6 py-5">
                            <div class="flex flex-col min-w-[120px]">
                                <span class="text-xs font-bold text-slate-700 truncate block">
                                    {{ $log->entity_type ? class_basename($log->entity_type) : '-' }}
                                </span>
                                <span class="text-[9px] font-black text-slate-400 uppercase tracking-tighter">
                                    ID #{{ $log->entity_id ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-6 py-5 whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-slate-500 bg-slate-50 border border-slate-100 px-2 py-1 rounded-lg">
                                {{ $log->ip_address }}
                            </span>
                        </td>
                        <td class="px-6 py-5 text-right whitespace-nowrap">
                            <a href="{{ route('admin.activity-logs.show', $log) }}" 
                               class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-50 hover:bg-teal-50 border border-slate-100 hover:border-teal-100 text-slate-500 hover:text-teal-600 hover:shadow-md hover:shadow-teal-600/5 transition-all duration-200">
                                <span class="material-symbols-outlined text-[18px]">visibility</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-20 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 bg-slate-50 rounded-full flex items-center justify-center">
                                    <span class="material-symbols-outlined text-slate-200 text-[40px]">history</span>
                                </div>
                                <p class="text-slate-400 font-bold">Belum ada log aktivitas yang ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{-- ── Pagination ── --}}
        @if($activityLogs->hasPages())
        <div class="px-8 py-6 bg-slate-50/50 border-t border-slate-100">
            {{ $activityLogs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
