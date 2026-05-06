@extends('layouts.admin-layout')

@section('admin-title') Detail Warga - {{ $patient->full_name }} @endsection

@section('admin-content')
<div class="max-w-5xl mx-auto space-y-8 pb-20">

    {{-- ── Header & Actions ── --}}
    <div class="flex items-center justify-between px-2">
        <div>
            <x-button href="{{ route('admin.patients.index') }}" variant="ghost" icon="arrow_back">Kembali</x-button>
        </div>
        <div class="flex gap-2">
            <x-button href="{{ route('admin.patients.edit', $patient->id) }}" variant="secondary" icon="edit">Edit Profil</x-button>
            <form action="{{ route('admin.patients.destroy', $patient->id) }}" method="POST" onsubmit="return confirm('Hapus data warga ini?')">
                @csrf @method('DELETE')
                <x-button type="submit" variant="ghost" class="text-red-500 hover:bg-red-50" icon="delete">Hapus</x-button>
            </form>
        </div>
    </div>

    {{-- ── Bento Grid Detail ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
        
        {{-- Card 1: Identitas Visual (Minimalist) --}}
        <div class="lg:col-span-4 space-y-6">
            <div class="bg-white rounded-[40px] border border-slate-100 p-10 flex flex-col items-center text-center">
                <div class="relative mb-8">
                    <div class="w-48 h-48 rounded-full border-2 border-slate-50 bg-slate-50/50 p-1">
                        @if($patient->profile_photo)
                            <img src="{{ asset('storage/' . $patient->profile_photo) }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <div class="w-full h-full bg-white rounded-full flex items-center justify-center border border-slate-100">
                                <span class="material-symbols-outlined text-slate-200 text-[96px]" style="font-variation-settings: 'wght' 100;">account_circle</span>
                            </div>
                        @endif
                    </div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 px-4 py-1.5 bg-white border border-slate-100 text-slate-700 text-[10px] font-black rounded-full uppercase tracking-widest shadow-sm">
                        {{ str_replace('_', ' ', $patient->category) }}
                    </div>
                </div>

                <h2 class="text-2xl font-black text-slate-800 leading-tight mb-2">{{ $patient->full_name }}</h2>
                <p class="text-xs font-bold text-teal-600 bg-teal-50 px-3 py-1 rounded-full uppercase tracking-widest inline-block mb-6">{{ $patient->id_number }}</p>

                <div class="w-full space-y-4 pt-6 border-t border-slate-50">
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Gender</span>
                        <span class="text-sm font-bold text-slate-700">{{ $patient->gender == 'M' ? 'Laki-laki' : 'Perempuan' }}</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Umur</span>
                        <span class="text-sm font-bold text-slate-700">{{ $patient->birth_date->age }} Tahun</span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest">Wilayah</span>
                        <span class="text-sm font-bold text-slate-700">Posyandu {{ $patient->posyandu->name }}</span>
                    </div>
                </div>
            </div>

            {{-- Kontak --}}
            <div class="bg-white rounded-[32px] border border-slate-100 p-8 space-y-4">
                <div class="flex items-center gap-3 mb-2">
                    <span class="material-symbols-outlined text-slate-400" style="font-variation-settings: 'wght' 300;">contact_page</span>
                    <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Kontak & Alamat</h4>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase">No. WhatsApp</p>
                    <p class="text-sm font-bold text-teal-600">{{ $patient->phone_number }}</p>
                </div>
                <div class="space-y-1">
                    <p class="text-[10px] font-black text-slate-400 uppercase">Alamat</p>
                    <p class="text-sm font-semibold text-slate-600 leading-relaxed italic">"{{ $patient->address }}"</p>
                </div>
            </div>
        </div>

        {{-- Card 2: Data Medis & Sosial --}}
        <div class="lg:col-span-8 space-y-8">
            
            {{-- Grid Informasi Spesifik --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Info Kategori --}}
                <div class="bg-white rounded-[32px] border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-teal-500">info</span>
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Data Spesifik</h4>
                    </div>
                    <div class="grid grid-cols-1 gap-5">
                        @if(in_array($patient->category, ['bayi','baduta','balita','anak_sekolah']))
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Nama Ibu</p>
                                <p class="text-sm font-bold text-slate-800">{{ $patient->parent_name ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Buku KIA</p>
                                <p class="text-sm font-bold {{ $patient->kia_book_ownership ? 'text-green-600' : 'text-red-500' }}">
                                    {{ $patient->kia_book_ownership ? 'Ada (Memiliki)' : 'Tidak Ada' }}
                                </p>
                            </div>
                        @endif

                        @if(in_array($patient->category, ['ibu_hamil', 'remaja', 'umum', 'lansia']))
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Pendidikan</p>
                                <p class="text-sm font-bold text-slate-800">{{ $patient->education ?? '-' }}</p>
                            </div>
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Pekerjaan</p>
                                <p class="text-sm font-bold text-slate-800">{{ $patient->job ?? '-' }}</p>
                            </div>
                        @endif

                        @if($patient->category == 'lansia')
                            <div>
                                <p class="text-[10px] font-black text-slate-400 uppercase">Kemandirian</p>
                                <p class="text-sm font-bold text-slate-800">{{ $patient->independence_status ?? '-' }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Info Lingkungan --}}
                <div class="bg-white rounded-[32px] border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <span class="material-symbols-outlined text-slate-400">foundation</span>
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Lingkungan & Ekonomi</h4>
                    </div>
                    <div class="grid grid-cols-2 gap-y-5 gap-x-4">
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase">Kondisi Rumah</p>
                            <p class="text-sm font-bold text-slate-800">{{ $patient->house_condition ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-slate-400 uppercase">Jamban</p>
                            <p class="text-sm font-bold text-slate-800">{{ $patient->has_latrine ? 'Tersedia' : 'Tidak Ada' }}</p>
                        </div>
                        <div class="col-span-2">
                            <p class="text-[10px] font-black text-slate-400 uppercase">Status Ekonomi</p>
                            <p class="text-sm font-bold text-slate-800">{{ $patient->economic_status ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Anthropometric Growth Chart (Child only) --}}
            @if(in_array($patient->category, ['bayi', 'baduta', 'balita']))
                <div class="lg:col-span-12">
                    @livewire('admin.patient-management.growth-chart', ['patient' => $patient, 'isEmbedded' => true])
                </div>
            @endif

            {{-- Riwayat Kunjungan (Premium Table) --}}
            <div class="bg-white rounded-[40px] border border-slate-100 overflow-hidden">
                <div class="p-8 border-b border-slate-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <span class="material-symbols-outlined text-slate-300">history</span>
                        <h4 class="text-xs font-black text-slate-800 uppercase tracking-widest">Riwayat Pemeriksaan</h4>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 bg-slate-50 px-2 py-1 rounded-lg">{{ $medicalRecords->total() }} Kunjungan</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50/50">
                                <th class="px-8 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Tanggal</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">BB/TB</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Status Gizi</th>
                                <th class="px-6 py-4 text-[10px] font-black text-slate-400 uppercase tracking-widest">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50">
                            @forelse($medicalRecords as $record)
                                <tr class="group hover:bg-slate-50/30 transition-all">
                                    <td class="px-8 py-5">
                                        <div class="text-sm font-bold text-slate-700">{{ $record->visit_date->format('d M Y') }}</div>
                                        <div class="text-[9px] font-bold text-slate-400 uppercase tracking-tighter">Oleh: {{ $record->user->name }}</div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <div class="flex items-center gap-2">
                                            <span class="text-sm font-black text-slate-800">{{ $record->weight }}<small class="text-slate-400 ml-0.5">kg</small></span>
                                            <span class="text-slate-200">/</span>
                                            <span class="text-sm font-black text-slate-800">{{ $record->height }}<small class="text-slate-400 ml-0.5">cm</small></span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-5">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-black uppercase {{ $record->nutrition_status == 'Normal' ? 'text-green-600 bg-green-50' : 'text-orange-500 bg-orange-50' }}">
                                            {{ $record->nutrition_status }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-5">
                                        <x-button variant="ghost" size="sm" class="opacity-0 group-hover:opacity-100 transition-opacity">Detail</x-button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-8 py-16 text-center">
                                        <p class="text-xs font-bold text-slate-300 uppercase tracking-widest">Belum ada data pemeriksaan</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="p-6 bg-slate-50/20">
                    {{ $medicalRecords->links() }}
                </div>
            </div>
        </div>
    </div>

</div>
@endsection