@extends('layouts.app')

@section('title', 'Log Aktivitas')

@section('content')
<div class="space-y-10">
    {{-- ── Header & Action ── --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
        <div>
            <h2 class="text-display text-4xl md:text-5xl mb-2">Audit <span class="text-teal-600 italic">Aktivitas.</span></h2>
            <p class="text-body-md text-slate-500 font-medium">Pantau setiap jejak perubahan dan kegiatan pengguna dalam sistem.</p>
        </div>
        <div class="flex items-center gap-3 bg-white p-2 rounded-3xl shadow-sm border border-slate-100">
            <div class="w-12 h-12 rounded-2xl bg-teal-50 flex items-center justify-center text-teal-600 shadow-inner">
                <span class="material-symbols-outlined text-[26px]">history</span>
            </div>
            <div class="pr-6">
                <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest leading-none mb-1">Status Sistem</p>
                <p class="text-sm font-black text-slate-800 leading-none flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span>
                    Audit Aktif
                </p>
            </div>
        </div>
    </div>

    {{-- ── Stats Overview ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $stats = [
                ['label' => 'Total Log', 'value' => $totalStats['total'],  'icon' => 'list_alt', 'color' => 'blue'],
                ['label' => 'Create',    'value' => $totalStats['create'], 'icon' => 'add_circle', 'color' => 'emerald'],
                ['label' => 'Update',    'value' => $totalStats['update'], 'icon' => 'edit_square', 'color' => 'amber'],
                ['label' => 'Delete',    'value' => $totalStats['delete'], 'icon' => 'delete_forever', 'color' => 'red'],
            ];
        @endphp

        @foreach($stats as $s)
        <div class="bento-card p-6 group relative overflow-hidden">
            <div class="relative z-10">
                <div @class([
                    'w-12 h-12 rounded-2xl flex items-center justify-center mb-5 transition-transform group-hover:scale-110 duration-500 shadow-sm',
                    'bg-blue-50 text-blue-600' => $s['color'] === 'blue',
                    'bg-emerald-50 text-emerald-600' => $s['color'] === 'emerald',
                    'bg-amber-50 text-amber-600' => $s['color'] === 'amber',
                    'bg-red-50 text-red-600' => $s['color'] === 'red',
                ])>
                    <span class="material-symbols-outlined text-[26px]">{{ $s['icon'] }}</span>
                </div>
                <p class="text-label-lg text-slate-400 uppercase tracking-widest mb-1.5">{{ $s['label'] }}</p>
                <h3 class="text-headline-lg text-4xl!">{{ number_format($s['value']) }}</h3>
            </div>
            <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-slate-50 rounded-full opacity-0 group-hover:opacity-40 transition-opacity duration-700"></div>
        </div>
        @endforeach
    </div>

    {{-- ── Filters ── --}}
    <div class="premium-card p-6 md:p-8!">
        <form method="GET" action="{{ route('admin.activity-logs.index') }}" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 2xl:grid-cols-6 gap-6">
            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pencarian</label>
                <div class="relative">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400" style="font-size:20px;">search</span>
                    <input type="text" name="search" value="{{ request('search') }}" 
                           placeholder="Cari deskripsi..." 
                           class="w-full h-14 pl-12 pr-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Pengguna</label>
                <div class="relative">
                    <select name="user_id" class="w-full h-14 px-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all appearance-none">
                        <option value="">Semua Kader</option>
                        @foreach($users as $user)
                            <option value="{{ $user->user_id }}" {{ request('user_id') == $user->user_id ? 'selected' : '' }}>
                                {{ $user->user_name }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Jenis Aksi</label>
                <div class="relative">
                    <select name="action_type" class="w-full h-14 px-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all appearance-none">
                        <option value="">Semua Aksi</option>
                        @foreach($actionTypes as $type)
                            <option value="{{ $type }}" {{ request('action_type') == $type ? 'selected' : '' }}>
                                {{ ucfirst($type) }}
                            </option>
                        @endforeach
                    </select>
                    <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none">expand_more</span>
                </div>
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Mulai</label>
                <input type="date" name="start_date" value="{{ request('start_date') }}" 
                       class="w-full h-14 px-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
            </div>

            <div class="space-y-2">
                <label class="text-[11px] font-black text-slate-400 uppercase tracking-widest ml-1">Tgl Akhir</label>
                <input type="date" name="end_date" value="{{ request('end_date') }}" 
                       class="w-full h-14 px-4 rounded-2xl border-2 border-slate-50 bg-slate-50 text-sm font-bold text-slate-900 focus:outline-none focus:border-teal-500 focus:bg-white transition-all">
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="flex-1 h-14 bg-teal-600 text-white rounded-2xl font-black uppercase tracking-widest text-xs hover:bg-teal-700 transition-all shadow-lg shadow-teal-600/20 flex items-center justify-center gap-2">
                    <span class="material-symbols-outlined text-[18px]">filter_list</span>
                    Filter
                </button>
                <a href="{{ route('admin.activity-logs.index') }}" 
                   title="Reset Filter"
                   class="w-14 h-14 bg-slate-100 text-slate-500 rounded-2xl flex items-center justify-center hover:bg-slate-200 hover:text-slate-900 transition-all group">
                    <span class="material-symbols-outlined group-hover:rotate-180 transition-transform duration-500">restart_alt</span>
                </a>
            </div>
        </form>
    </div>

    {{-- ── Main Table Card ── --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/50 border-b border-slate-100">
                        <th class="px-4 md:px-6 py-5 text-label-lg text-slate-400 uppercase whitespace-nowrap">Waktu & Tanggal</th>
                        <th class="px-4 md:px-6 py-5 text-label-lg text-slate-400 uppercase whitespace-nowrap">Pelaku</th>
                        <th class="px-8 py-5 text-label-lg text-slate-400 uppercase whitespace-nowrap">Tipe Aksi</th>
                        <th class="px-4 md:px-6 py-5 text-label-lg text-slate-400 uppercase whitespace-nowrap">Entitas</th>
                        <th class="px-4 md:px-6 py-5 text-label-lg text-slate-400 uppercase whitespace-nowrap">IP Address</th>
                        <th class="px-4 md:px-6 py-5 text-label-lg text-slate-400 uppercase text-right whitespace-nowrap">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($activityLogs as $log)
                    <tr class="hover:bg-slate-50/80 transition-all group">
                        <td class="px-4 md:px-6 py-6 whitespace-nowrap">
                            <div class="flex flex-col">
                                <span class="text-body-md font-black text-slate-900">{{ $log->created_at->format('H:i:s') }}</span>
                                <span class="text-[11px] font-bold text-slate-400 uppercase tracking-tighter">{{ $log->created_at->format('d M Y') }}</span>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-6">
                            <div class="flex items-center gap-3 min-w-[150px]">
                                <div class="w-9 h-9 rounded-xl bg-slate-100 flex items-center justify-center text-slate-700 font-black text-xs flex-shrink-0">
                                    {{ strtoupper(substr($log->user_name ?? 'A', 0, 2)) }}
                                </div>
                                <div class="flex flex-col min-w-0">
                                    <span class="text-[13px] font-black text-slate-800 truncate block max-w-[120px] md:max-w-[180px]" title="{{ $log->user_name ?? 'System' }}">
                                        {{ $log->user_name ?? 'System' }}
                                    </span>
                                    <span class="text-[10px] font-black text-teal-600 uppercase tracking-widest">{{ $log->role ?? 'N/A' }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 whitespace-nowrap">
                            @php
                                $badgeStyle = match($log->action_type) {
                                    'create' => 'bg-emerald-50 text-emerald-600 border-emerald-100',
                                    'update' => 'bg-amber-50 text-amber-600 border-amber-100',
                                    'delete' => 'bg-red-50 text-red-600 border-red-100',
                                    default => 'bg-slate-50 text-slate-500 border-slate-100',
                                };
                            @endphp
                            <span class="inline-flex items-center px-3 py-1.5 rounded-xl border {{ $badgeStyle }} text-[10px] font-black uppercase tracking-widest">
                                <span class="w-1.5 h-1.5 rounded-full bg-current mr-2"></span>
                                {{ $log->action_type }}
                            </span>
                        </td>
                        <td class="px-4 md:px-6 py-6">
                            <div class="flex flex-col min-w-[120px]">
                                <span class="text-body-md font-bold text-slate-700 truncate block">
                                    {{ $log->entity_type ? class_basename($log->entity_type) : '-' }}
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-tighter">
                                    ID #{{ $log->entity_id ?? 'N/A' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 md:px-6 py-6 whitespace-nowrap">
                            <span class="font-mono text-xs font-bold text-slate-500 bg-slate-100 px-2 py-1 rounded-lg">
                                {{ $log->ip_address }}
                            </span>
                        </td>
                        <td class="px-4 md:px-6 py-6 text-right whitespace-nowrap">
                            <a href="{{ route('admin.activity-logs.show', $log) }}" 
                               class="inline-flex items-center justify-center w-10 h-10 rounded-xl bg-white border border-slate-100 text-slate-400 hover:text-teal-600 hover:border-teal-200 hover:shadow-lg hover:shadow-teal-600/10 transition-all duration-300">
                                <span class="material-symbols-outlined text-[20px]">visibility</span>
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

