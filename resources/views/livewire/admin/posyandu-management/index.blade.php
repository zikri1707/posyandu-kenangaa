@extends('layouts.app')

@section('title', 'Data Posyandu')

@section('content')
<div class="max-w-[1280px] mx-auto space-y-6">

    {{-- ── Page Header ── --}}
    <div class="flex flex-col md:flex-row md:items-end justify-between gap-4">
        <div>
            <nav class="flex text-xs text-slate-400 mb-1.5 gap-1.5 items-center">
                <a href="{{ route('dashboard') }}" class="hover:text-teal-600 transition-colors">Beranda</a>
                <span class="material-symbols-outlined text-[12px]">chevron_right</span>
                <span class="text-teal-600 font-semibold">Data Posyandu</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900">Manajemen Unit Posyandu</h1>
            <p class="text-sm text-slate-500 mt-0.5">Data unit posyandu di Kecamatan Bekasi Timur.</p>
        </div>
        <a href="{{ route('admin.posyandu.create') }}"
           class="h-11 px-5 flex items-center gap-2 bg-teal-600 text-white rounded-xl text-sm font-bold hover:bg-teal-700 active:scale-95 transition-all shadow-sm">
            <span class="material-symbols-outlined text-[18px]">add_home_work</span>
            Tambah Posyandu Baru
        </a>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
    <div class="flex items-center gap-3 px-4 py-3 bg-green-50 border border-green-200 text-green-800 rounded-xl text-sm font-medium">
        <span class="material-symbols-outlined text-green-600 text-[20px]" style="font-variation-settings:'FILL' 1;">check_circle</span>
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="flex items-center gap-3 px-4 py-3 bg-red-50 border border-red-200 text-red-800 rounded-xl text-sm font-medium">
        <span class="material-symbols-outlined text-red-600 text-[20px]">error</span>
        {{ session('error') }}
    </div>
    @endif

    {{-- ── Summary Cards ── --}}
    @php
        $totalPosyandu  = $posyandus->total();
        $totalPedukuhan = \App\Models\Pedukuhan::count();
        $totalWarga     = \App\Models\Patient::count();
    @endphp
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 bg-teal-50 rounded-2xl flex items-center justify-center text-teal-600 flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">home_health</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Posyandu</p>
                <p class="text-3xl font-black text-slate-900">{{ $totalPosyandu }}</p>
                <p class="text-xs text-green-600 font-semibold mt-0.5">Aktif Seluruhnya</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">location_city</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Wilayah Pedukuhan</p>
                <p class="text-3xl font-black text-slate-900">{{ $totalPedukuhan }}</p>
                <p class="text-xs text-slate-400 mt-0.5">Rata-rata {{ $totalPedukuhan > 0 ? round($totalPosyandu / $totalPedukuhan, 1) : 0 }} per Pedukuhan</p>
            </div>
        </div>
        <div class="bg-white border border-slate-200 rounded-2xl p-5 flex items-center gap-4 shadow-sm">
            <div class="w-14 h-14 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-600 flex-shrink-0">
                <span class="material-symbols-outlined text-[28px]" style="font-variation-settings:'FILL' 1;">groups</span>
            </div>
            <div>
                <p class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Warga Terdata</p>
                <p class="text-3xl font-black text-slate-900">{{ number_format($totalWarga) }}</p>
                <p class="text-xs text-green-600 font-semibold mt-0.5">Total terdaftar</p>
            </div>
        </div>
    </div>

    {{-- ── Table ── --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden">

        {{-- Table Header --}}
        <div class="px-6 py-4 border-b border-slate-200 flex flex-wrap items-center justify-between gap-3 bg-slate-50/50">
            <h3 class="text-sm font-bold text-slate-700">Daftar Unit Posyandu</h3>
            <form method="GET" action="{{ route('admin.posyandu.index') }}" class="flex items-center gap-2">
                <div class="relative group">
                    <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 group-focus-within:text-teal-600 transition-colors pointer-events-none text-[18px]">search</span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari posyandu..."
                           class="search-input-premium h-10 pl-11 pr-4">
                </div>
                <button type="submit"
                        class="h-9 px-4 bg-white border border-slate-200 rounded-lg text-sm font-semibold text-slate-600 hover:bg-slate-50 transition-colors">
                    Cari
                </button>
                @if(request('search'))
                <a href="{{ route('admin.posyandu.index') }}"
                   class="h-9 px-3 flex items-center bg-white border border-slate-200 rounded-lg text-slate-400 hover:text-red-500 transition-colors">
                    <span class="material-symbols-outlined text-[16px]">restart_alt</span>
                </a>
                @endif
            </form>
        </div>

        {{-- Table Content --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-white border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Nama Posyandu</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Pedukuhan</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest">Alamat</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Kode Unik</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-center">Warga</th>
                        <th class="px-6 py-4 text-xs font-bold text-slate-400 uppercase tracking-widest text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($posyandus as $posyandu)
                    @php
                        $initial = strtoupper(substr($posyandu->name, 0, 1));
                        $colors  = ['teal','blue','amber','indigo','emerald','rose','violet','orange'];
                        $color   = $colors[($posyandu->id - 1) % count($colors)];
                        $jumlahWarga = $posyandu->patients()->count();
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors">
                        {{-- Nama --}}
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($posyandu->logo_photo)
                                    <img src="{{ asset('storage/' . $posyandu->logo_photo) }}"
                                         class="w-9 h-9 rounded-xl object-cover flex-shrink-0 border border-slate-200">
                                @else
                                    <div class="w-9 h-9 rounded-xl bg-{{ $color }}-100 text-{{ $color }}-700 flex items-center justify-center font-bold text-sm flex-shrink-0">
                                        {{ $initial }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-slate-800 text-sm">{{ $posyandu->name }}</p>
                                </div>
                            </div>
                        </td>
                        {{-- Pedukuhan --}}
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center gap-1.5 text-sm text-slate-600">
                                <span class="material-symbols-outlined text-teal-500 text-[15px]">location_city</span>
                                {{ $posyandu->pedukuhan?->name ?? '—' }}
                            </span>
                        </td>
                        {{-- Alamat --}}
                        <td class="px-6 py-4 text-sm text-slate-500 max-w-[200px]">
                            <span class="truncate block">{{ $posyandu->address ?? '—' }}</span>
                        </td>
                        {{-- Kode Unik --}}
                        <td class="px-6 py-4 text-center">
                            <span class="px-2.5 py-1 bg-slate-100 rounded-lg text-xs font-bold text-slate-700 font-mono">
                                {{ $posyandu->unique_code ?? '—' }}
                            </span>
                        </td>
                        {{-- Warga --}}
                        <td class="px-6 py-4 text-center text-sm font-semibold text-slate-700">
                            {{ number_format($jumlahWarga) }}
                        </td>
                        {{-- Aksi --}}
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-1">
                                <a href="{{ route('admin.posyandu.show', $posyandu->id) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-teal-600 hover:bg-teal-50 transition-colors"
                                   title="Detail">
                                    <span class="material-symbols-outlined text-[18px]">visibility</span>
                                </a>
                                <a href="{{ route('admin.posyandu.edit', $posyandu->id) }}"
                                   class="w-8 h-8 flex items-center justify-center rounded-lg text-blue-600 hover:bg-blue-50 transition-colors"
                                   title="Edit">
                                    <span class="material-symbols-outlined text-[18px]">edit</span>
                                </a>
                                <form action="{{ route('admin.posyandu.destroy', $posyandu->id) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Hapus posyandu \'{{ addslashes($posyandu->name) }}\'? Semua data terkait akan terpengaruh.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-8 h-8 flex items-center justify-center rounded-lg text-red-500 hover:bg-red-50 transition-colors"
                                            title="Hapus">
                                        <span class="material-symbols-outlined text-[18px]">delete</span>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3 text-slate-400">
                                <span class="material-symbols-outlined text-[48px] text-slate-200">home_health</span>
                                <p class="text-sm font-semibold text-slate-500">Belum ada data posyandu</p>
                                <a href="{{ route('admin.posyandu.create') }}"
                                   class="text-sm text-teal-600 hover:underline font-medium flex items-center gap-1">
                                    <span class="material-symbols-outlined text-[16px]">add</span>
                                    Tambah Posyandu Pertama
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-6 py-4 bg-slate-50 border-t border-slate-200 flex flex-wrap items-center justify-between gap-3">
            <p class="text-xs font-medium text-slate-500">
                Menampilkan {{ $posyandus->firstItem() ?? 0 }}–{{ $posyandus->lastItem() ?? 0 }}
                dari {{ $posyandus->total() }} Posyandu
            </p>
            @if($posyandus->hasPages())
            <div class="flex items-center gap-1">
                @if($posyandus->onFirstPage())
                    <span class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-lg text-slate-300 cursor-not-allowed bg-white">
                        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    </span>
                @else
                    <a href="{{ $posyandus->previousPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-white transition-colors bg-white">
                        <span class="material-symbols-outlined text-[16px]">chevron_left</span>
                    </a>
                @endif

                @foreach($posyandus->getUrlRange(max(1, $posyandus->currentPage()-1), min($posyandus->lastPage(), $posyandus->currentPage()+2)) as $page => $url)
                    <a href="{{ $url }}"
                       class="w-8 h-8 flex items-center justify-center rounded-lg text-sm font-semibold transition-colors
                           {{ $page === $posyandus->currentPage()
                               ? 'bg-teal-600 text-white shadow-sm'
                               : 'border border-slate-200 text-slate-600 hover:bg-white bg-white' }}">
                        {{ $page }}
                    </a>
                @endforeach

                @if($posyandus->hasMorePages())
                    <a href="{{ $posyandus->nextPageUrl() }}"
                       class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-lg text-slate-500 hover:bg-white transition-colors bg-white">
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </a>
                @else
                    <span class="w-8 h-8 flex items-center justify-center border border-slate-200 rounded-lg text-slate-300 cursor-not-allowed bg-white">
                        <span class="material-symbols-outlined text-[16px]">chevron_right</span>
                    </span>
                @endif
            </div>
            @endif
        </div>
    </div>

    {{-- ── Footer Banner ── --}}
    <div class="bg-teal-900 rounded-2xl overflow-hidden relative min-h-[140px] flex items-center p-8">
        <div class="absolute inset-0 bg-gradient-to-r from-teal-800 to-teal-600 opacity-80"></div>
        <div class="relative z-10">
            <h4 class="text-white font-bold text-lg mb-1">Visualisasi Wilayah</h4>
            <p class="text-teal-100 text-sm mb-4">Lihat sebaran unit posyandu dan kepadatan penduduk di seluruh pedukuhan Bekasi Timur.</p>
            <a href="{{ route('admin.pedukuhans.index') }}"
               class="inline-flex items-center gap-2 bg-white/10 hover:bg-white/20 text-white border border-white/30 px-4 py-2 rounded-lg text-sm font-bold backdrop-blur-sm transition-colors">
                <span class="material-symbols-outlined text-[16px]">location_city</span>
                Lihat Data Pedukuhan
            </a>
        </div>
    </div>

</div>
@endsection
