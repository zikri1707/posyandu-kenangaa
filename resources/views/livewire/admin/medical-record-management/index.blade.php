@extends('layouts.admin-layout')

@section('admin-title') Rekam Medis Bulanan @endsection

@section('admin-actions')
    @can('create', App\Models\MedicalRecord::class)
    <x-button href="{{ route('admin.medical-records.create') }}" variant="secondary" icon="note_add">
        Tambah Rekam Medis
    </x-button>
    @endcan
@endsection

@section('admin-content')
<div class="space-y-8 p-6 md:p-8" wire:key="medical-records-root">
    
    {{-- ── Page Header ── --}}
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
        <div>
            <h1 class="text-headline-lg text-high-contrast mb-2">Manajemen Rekam Medis</h1>
            <p class="text-body-md text-contrast-safe">Kelola data kunjungan dan rekam kesehatan warga secara efisien.</p>
        </div>
    </div>

    {{-- ── Search & Filter Bento ── --}}
    <div class="bg-white rounded-[2.5rem] border border-slate-100 p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
        <div class="flex flex-wrap items-center gap-3 flex-1">
            {{-- Search Input --}}
            <div class="relative min-w-[300px] flex-1 md:flex-none">
                <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-[20px]">search</span>
                <input type="text" wire:model.live.debounce.150ms="search" 
                       placeholder="Cari nama warga atau NIK..."
                       class="w-full h-12 pl-12 pr-4 border border-slate-100 rounded-2xl text-sm font-bold text-slate-700 placeholder:text-slate-400 focus:outline-none focus:border-primary focus:ring-4 focus:ring-primary/5 transition-all bg-slate-50/50">
            </div>

            {{-- Posyandu Filter --}}
            @if(auth()->user()->isSuperAdmin())
            <div class="relative">
                <select wire:model.live="posyandu_id"
                        class="h-12 px-6 border border-slate-100 rounded-2xl text-xs font-black uppercase tracking-widest text-slate-700 focus:outline-none focus:border-primary transition-all appearance-none cursor-pointer bg-slate-50/50">
                    <option value="">Seluruh Unit</option>
                    @foreach(\App\Models\Posyandu::all() as $p)
                        <option value="{{ $p->id }}">{{ $p->name }}</option>
                    @endforeach
                </select>
                <span class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none text-[18px]">expand_more</span>
            </div>
            @endif
        </div>
        
        @if($search || $posyandu_id)
            <button wire:click="$set('search', ''); $set('posyandu_id', '');"
                    class="text-[10px] font-black text-error uppercase tracking-[0.2em] hover:text-error/80 transition-colors px-4 flex items-center gap-2">
                <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                Reset Filter
            </button>
        @endif
    </div>

    {{-- ── Data Table ── --}}
    <div class="premium-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-surface-container/30 border-b border-outline-variant/30">
                        <th class="px-8 py-5 text-left text-label-lg uppercase tracking-widest text-slate-500">Waktu Kunjungan</th>
                        <th class="px-8 py-5 text-left text-label-lg uppercase tracking-widest text-slate-500">Pasien</th>
                        <th class="px-8 py-5 text-left text-label-lg uppercase tracking-widest text-slate-500">Antropometri</th>
                        <th class="px-8 py-5 text-center text-label-lg uppercase tracking-widest text-slate-500">Petugas</th>
                        <th class="px-8 py-5 text-right text-label-lg uppercase tracking-widest text-slate-500">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-surface-container-low">
                    @forelse($medicalRecords as $record)
                    <tr class="group hover:bg-primary/5 transition-all duration-300" wire:key="record-{{ $record->id }}">
                        <td class="px-8 py-6">
                            <div class="flex flex-col">
                                <span class="text-data-tabular font-black text-slate-900">
                                    {{ $record->visit_date ? \Carbon\Carbon::parse($record->visit_date)->format('d M Y') : '-' }}
                                </span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Visit ID: #{{ $record->id }}</span>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex items-center gap-4">
                                <div class="h-12 w-12 rounded-2xl bg-primary/10 text-primary flex items-center justify-center font-black text-sm border border-primary/10 group-hover:bg-primary group-hover:text-white transition-all duration-500">
                                    {{ strtoupper(substr($record->patient->full_name ?? 'P', 0, 1)) }}
                                </div>
                                <div>
                                    <div class="text-data-tabular font-black text-slate-900">{{ $record->patient->full_name ?? 'Tidak Diketahui' }}</div>
                                    <div class="flex items-center gap-2 mt-1">
                                        <span class="text-[10px] font-black text-primary uppercase tracking-widest bg-primary/5 px-2 py-0.5 rounded-md">
                                            {{ $record->patient->category ?? '-' }}
                                        </span>
                                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                            Age: {{ $record->patient->age ?? '?' }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6">
                            <div class="flex gap-4">
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Weight</span>
                                    <span class="text-sm font-black text-slate-800">{{ $record->weight ?? '-' }} <small class="text-slate-400 font-bold ml-0.5">kg</small></span>
                                </div>
                                <div class="w-px h-8 bg-slate-100"></div>
                                <div class="flex flex-col">
                                    <span class="text-[9px] font-black text-slate-400 uppercase tracking-widest">Height</span>
                                    <span class="text-sm font-black text-slate-800">{{ $record->height ?? '-' }} <small class="text-slate-400 font-bold ml-0.5">cm</small></span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-center">
                            <div class="flex flex-col items-center">
                                <span class="text-data-tabular font-bold text-slate-700">{{ $record->user->name ?? '-' }}</span>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest mt-1">Kader</span>
                            </div>
                        </td>
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-2 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-x-2 group-hover:translate-x-0">
                                <a href="{{ route('admin.medical-records.show', $record->id) }}" 
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-primary hover:border-primary/30 hover:shadow-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">visibility</span>
                                </a>
                                @can('update', $record)
                                <a href="{{ route('admin.medical-records.edit', $record->id) }}" 
                                   class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-secondary hover:border-secondary/30 hover:shadow-lg transition-all">
                                    <span class="material-symbols-outlined text-[20px]">edit</span>
                                </a>
                                @endcan
                                
                                @can('delete', $record)
                                <form action="{{ route('admin.medical-records.destroy', $record->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis ini?')" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-400 hover:text-error hover:border-error/30 hover:shadow-lg transition-all">
                                        <span class="material-symbols-outlined text-[20px]">delete</span>
                                    </button>
                                </form>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-8 py-32 text-center">
                            <div class="flex flex-col items-center gap-4">
                                <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center text-slate-200">
                                    <span class="material-symbols-outlined text-[48px]">medical_information</span>
                                </div>
                                <p class="text-label-lg text-slate-400 uppercase tracking-[0.2em]">Tidak ada rekam medis ditemukan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- ── Pagination ── --}}
        @if($medicalRecords->hasPages())
        <div class="px-8 py-6 bg-surface-container-low/50 border-t border-outline-variant/30">
            {{ $medicalRecords->links() }}
        </div>
        @endif
    </div>
</div>
@endsection