@extends('layouts.admin-layout')

@section('admin-title')
    Detail Agenda: {{ $schedule->title }}
@endsection

@section('admin-actions')
    <div class="flex items-center gap-3">
        <x-button href="{{ route('admin.schedules.index') }}" variant="outline" icon="arrow_back">
            Kembali
        </x-button>
        @can('update', $schedule)
            <x-button href="{{ route('admin.schedules.edit', $schedule->id) }}" variant="secondary" icon="edit">
                Edit Jadwal
            </x-button>
        @endcan
    </div>
@endsection

@section('admin-content')
    <div class="space-y-8 p-6 md:p-10">

        {{-- ── Main Information & Status ── --}}
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

            {{-- Left: Agenda Details --}}
            <div class="lg:col-span-8 space-y-8">
                <div
                    class="bg-white rounded-[2.5rem] border border-slate-100 p-8 md:p-10 shadow-sm relative overflow-hidden">
                    <div class="relative z-10">
                        <div class="flex items-center gap-4 mb-8">
                            @php
                                $colors = [
                                    'upcoming' => 'bg-blue-50 text-blue-600 border-blue-100',
                                    'ongoing' => 'bg-teal-50 text-teal-600 border-teal-100',
                                    'completed' => 'bg-green-50 text-green-600 border-green-100',
                                    'cancelled' => 'bg-red-50 text-red-600 border-red-100',
                                ];
                            @endphp
                            <span
                                class="px-5 py-2 rounded-2xl text-[10px] font-black uppercase tracking-[0.2em] border {{ $colors[$schedule->status] ?? 'bg-slate-50' }}">
                                {{ $schedule->status }}
                            </span>
                            <span
                                class="text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">{{ $schedule->posyandu->name ?? 'Semua Unit' }}</span>
                        </div>

                        <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight mb-6 tracking-tight">
                            {{ $schedule->title }}</h1>

                        <p class="text-slate-500 text-base leading-relaxed font-medium mb-10 max-w-2xl">
                            {{ $schedule->description ?: 'Tidak ada deskripsi tambahan untuk agenda ini.' }}
                        </p>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-8 pt-10 border-t border-slate-50">
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100">
                                        <span class="material-symbols-outlined text-[24px]">calendar_month</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Tanggal Kegiatan</p>
                                        <p class="text-base font-black text-slate-900">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->translatedFormat('d F Y') }}
                                        </p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100">
                                        <span class="material-symbols-outlined text-[24px]">schedule</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Waktu Pelaksanaan</p>
                                        <p class="text-base font-black text-slate-900">
                                            {{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }}
                                            @if ($schedule->end_time)
                                                — {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }} WIB
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="space-y-6">
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100">
                                        <span class="material-symbols-outlined text-[24px]">location_on</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Lokasi Agenda</p>
                                        <p class="text-base font-black text-slate-900">
                                            {{ $schedule->location ?? 'Posyandu Unit' }}</p>
                                    </div>
                                </div>
                                <div class="flex items-center gap-4">
                                    <div
                                        class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center border border-slate-100">
                                        <span class="material-symbols-outlined text-[24px]">account_circle</span>
                                    </div>
                                    <div>
                                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">
                                            Dibuat Oleh</p>
                                        <p class="text-base font-black text-slate-900">
                                            {{ $schedule->user->name ?? 'Sistem' }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Background decoration --}}
                    <div class="absolute -top-24 -right-24 w-64 h-64 bg-slate-50 rounded-full blur-3xl opacity-50"></div>
                </div>
            </div>

            {{-- Right: Stats & Quick Actions --}}
            <div class="lg:col-span-4 space-y-6">
                {{-- Attendance Card --}}
                <div
                    class="bg-slate-900 rounded-[2rem] p-8 text-white shadow-xl shadow-slate-200 relative overflow-hidden group">
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-8">
                            <div
                                class="w-12 h-12 rounded-2xl bg-white/10 flex items-center justify-center backdrop-blur-md">
                                <span class="material-symbols-outlined text-[24px] text-teal-400">groups</span>
                            </div>
                            <span class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Kehadiran</span>
                        </div>
                        <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Total Pasien Hadir
                        </p>
                        <h3 class="text-6xl font-black mb-6 tracking-tighter">{{ $schedule->attendances_count ?? 0 }}</h3>
                        <div class="h-1.5 w-full bg-white/10 rounded-full overflow-hidden">
                            <div class="h-full bg-teal-500 rounded-full" style="width: 65%"></div>
                        </div>
                        <p class="text-[10px] font-bold text-slate-500 mt-4 leading-relaxed">Persentase kehadiran
                            berdasarkan rata-rata kunjungan bulanan posyandu.</p>
                    </div>
                </div>

                {{-- Medical Records Card --}}
                <div class="bg-white rounded-[2rem] border border-slate-100 p-8 shadow-sm">
                    <div class="flex items-center justify-between mb-8">
                        <div
                            class="w-12 h-12 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center border border-indigo-100">
                            <span class="material-symbols-outlined text-[24px]">clinical_notes</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Medical Logs</span>
                    </div>
                    <p class="text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Rekam Medis Terinput</p>
                    <h3 class="text-5xl font-black text-slate-900 tracking-tighter mb-6">
                        {{ $schedule->medical_records_count ?? 0 }}</h3>
                    <a href="{{ route('admin.medical-records.create', ['schedule_id' => $schedule->id]) }}"
                        class="flex items-center justify-center w-full h-12 bg-slate-50 text-slate-600 rounded-2xl text-[10px] font-black uppercase tracking-widest hover:bg-indigo-600 hover:text-white transition-all">
                        Input Rekam Medis Baru
                    </a>
                </div>
            </div>
        </div>

        {{-- ── Attendance Detailed Table ── --}}
        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-10 py-8 border-b border-slate-50 flex items-center justify-between bg-slate-50/20">
                <h3 class="text-lg font-black text-slate-900 tracking-tight">Daftar Kehadiran Pasien</h3>
                <span
                    class="px-4 py-1.5 bg-slate-100 text-slate-400 rounded-xl text-[10px] font-black uppercase tracking-widest">Live
                    Updates</span>
            </div>

            <div class="overflow-x-auto">
                @if ($schedule->attendances && $schedule->attendances->isNotEmpty())
                    <table class="w-full">
                        <thead class="bg-slate-50/50">
                            <tr>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Identitas Pasien</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Waktu Hadir</th>
                                <th
                                    class="px-10 py-5 text-left text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Keterangan</th>
                                <th
                                    class="px-10 py-5 text-right text-[10px] font-black text-slate-400 uppercase tracking-widest">
                                    Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @foreach ($schedule->attendances as $attendance)
                                <tr class="group hover:bg-slate-50/30 transition-all">
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-5">
                                            <div
                                                class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-400 flex items-center justify-center font-black text-xs border border-slate-100 group-hover:bg-white group-hover:shadow-md transition-all">
                                                {{ strtoupper(substr($attendance->patient->full_name ?? 'P', 0, 2)) }}
                                            </div>
                                            <div>
                                                <p class="font-black text-slate-900 text-sm leading-tight">
                                                    {{ $attendance->patient->full_name ?? '-' }}</p>
                                                <p
                                                    class="text-[11px] font-black text-slate-400 uppercase tracking-widest mt-1">
                                                    RW: {{ $attendance->patient->rw ?? '—' }} / RT:
                                                    {{ $attendance->patient->rt ?? '—' }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <div class="flex items-center gap-2 text-slate-700 font-bold text-sm">
                                            <span
                                                class="material-symbols-outlined text-[18px] text-slate-300">schedule</span>
                                            {{ $attendance->created_at->format('H:i') }} <span
                                                class="text-[10px] text-slate-400 font-black uppercase ml-1">WIB</span>
                                        </div>
                                    </td>
                                    <td class="px-10 py-6">
                                        <p class="text-sm font-medium text-slate-500 max-w-xs truncate">
                                            {{ $attendance->notes ?: 'Hadir secara fisik di lokasi kegiatan.' }}</p>
                                    </td>
                                    <td class="px-10 py-6 text-right">
                                        <a href="{{ route('admin.patients.show', $attendance->patient_id) }}"
                                            class="text-[10px] font-black text-teal-600 uppercase tracking-widest hover:text-teal-800 transition-colors bg-teal-50 px-4 py-2 rounded-xl">
                                            Profil Pasien
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="px-10 py-32 text-center">
                        <div class="max-w-xs mx-auto">
                            <div class="w-20 h-20 rounded-full bg-slate-50 flex items-center justify-center mx-auto mb-6">
                                <span class="material-symbols-outlined text-[40px] text-slate-200">person_off</span>
                            </div>
                            <h3 class="text-lg font-black text-slate-800 mb-2">Belum Ada Kehadiran</h3>
                            <p class="text-sm text-slate-400 font-medium">Data akan terisi otomatis saat kader melakukan
                                input pemeriksaan rekam medis hari ini.</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
