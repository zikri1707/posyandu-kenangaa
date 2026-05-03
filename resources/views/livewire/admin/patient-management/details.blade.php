@extends('layouts.admin-layout')

@section('admin-title')
    Detail Warga — {{ $patient->full_name }}
@endsection

@section('admin-content')
<div class="p-6 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
        <div>
            <x-breadcrumb :items="[
                ['label' => 'Data Warga', 'url' => route('admin.patients.index')],
                ['label' => $patient->full_name, 'active' => true]
            ]" />
        </div>
        <div class="flex gap-2">
            @can('update', $patient)
            <a href="{{ route('admin.patients.edit', $patient->id) }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg transition shadow-sm">
                <i class="fas fa-pen mr-2"></i> Edit
            </a>
            @endcan
            <a href="{{ route('admin.patients.index') }}"
               class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-50 transition">
                <i class="fas fa-arrow-left mr-2"></i> Kembali
            </a>
        </div>
    </div>

    {{-- PERINGATAN GIZI BURUK/STUNTING --}}
    @php 
        $latestRecord = $medicalRecords->first();
        $isStunting = $latestRecord && $latestRecord->nutrition_status === 'Gizi Buruk/Stunting';
    @endphp
    @if($isStunting)
    <div class="bg-red-600 border-2 border-red-700 rounded-xl p-5 shadow-lg">
        <div class="flex items-start gap-4">
            <div class="flex-shrink-0">
                <div class="h-12 w-12 rounded-full bg-white flex items-center justify-center">
                    <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
                </div>
            </div>
            <div class="flex-1">
                <h3 class="text-white font-bold text-lg mb-1">
                    ⚠️ PERINGATAN: Status Gizi Buruk/Stunting
                </h3>
                <p class="text-red-50 text-sm leading-relaxed">
                    Pasien <strong>{{ $patient->full_name }}</strong> memiliki status gizi <strong>Gizi Buruk/Stunting</strong> 
                    (Z-score: <strong>{{ $latestRecord->z_score ? number_format($latestRecord->z_score, 2) : '-' }}</strong>).
                    Diperlukan tindakan segera dan pemantauan intensif. Segera koordinasikan dengan tenaga kesehatan untuk penanganan lebih lanjut.
                </p>
                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white text-red-700">
                        <i class="fas fa-calendar-check mr-1.5"></i>
                        Kunjungan Terakhir: {{ $latestRecord->visit_date?->format('d M Y') ?? '-' }}
                    </span>
                    @if($latestRecord->nutrition_trend === 'turun')
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-red-800 text-white">
                        <i class="fas fa-arrow-down mr-1.5"></i>
                        Tren Menurun
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- PROFIL CARD --}}
    @php
        $categoryColors = ['balita'=>'blue','ibu_hamil'=>'pink','remaja'=>'purple','lansia'=>'orange'];
        $categoryLabels = ['balita'=>'Balita','ibu_hamil'=>'Ibu Hamil','remaja'=>'Remaja','lansia'=>'Lansia'];
        $catColor = $categoryColors[$patient->category] ?? 'gray';
        $catLabel = $categoryLabels[$patient->category] ?? ucfirst($patient->category ?? '-');
    @endphp

    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="flex flex-col md:flex-row">

            {{-- Foto & Identitas Singkat --}}
            <div class="md:w-64 lg:w-72 bg-gradient-to-b from-{{ $catColor }}-50 to-white p-6 flex flex-col items-center text-center border-b md:border-b-0 md:border-r border-gray-200">
                <div class="h-24 w-24 rounded-full bg-{{ $catColor }}-100 text-{{ $catColor }}-600 flex items-center justify-center text-3xl font-bold mb-3 shadow-inner">
                    {{ strtoupper(substr($patient->full_name, 0, 2)) }}
                </div>
                <h3 class="text-lg font-bold text-gray-900">{{ $patient->full_name }}</h3>
                <p class="text-sm text-gray-500 mt-0.5">NIK: {{ $patient->id_number }}</p>
                <span class="mt-2 px-3 py-1 rounded-full text-xs font-semibold bg-{{ $catColor }}-100 text-{{ $catColor }}-800">
                    {{ $catLabel }}
                </span>

                <dl class="mt-4 w-full text-left space-y-2 text-sm">
                    <div class="flex justify-between"><dt class="text-gray-500">Jenis Kelamin</dt><dd class="font-medium">{{ $patient->gender === 'M' ? 'Laki-laki' : 'Perempuan' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Tanggal Lahir</dt><dd class="font-medium">{{ $patient->birth_date?->format('d M Y') ?? '-' }}</dd></div>
                    <div class="flex justify-between"><dt class="text-gray-500">Usia</dt><dd class="font-medium">{{ $patient->age }}</dd></div>
                    @if($patient->parent_name)
                    <div class="flex justify-between"><dt class="text-gray-500">Orang Tua</dt><dd class="font-medium">{{ $patient->parent_name }}</dd></div>
                    @endif
                </dl>
            </div>

            {{-- Data Lengkap --}}
            <div class="flex-1 p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Kontak & Alamat</h4>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-500">No. Telepon</dt><dd class="font-medium text-gray-900">{{ $patient->phone_number ?? '-' }}</dd></div>
                        <div><dt class="text-gray-500">Alamat</dt><dd class="font-medium text-gray-900">{{ $patient->address ?? '-' }}</dd></div>
                    </dl>
                </div>
                <div>
                    <h4 class="text-xs font-semibold text-gray-400 uppercase tracking-wide mb-3">Posyandu</h4>
                    <dl class="space-y-2 text-sm">
                        <div><dt class="text-gray-500">Posyandu</dt><dd class="font-medium text-gray-900">{{ $patient->posyandu->name ?? '-' }}</dd></div>
                    </dl>
                </div>

                {{-- Ringkasan Rekam Medis Terbaru --}}
                @php $latest = $medicalRecords->first(); @endphp
                @if($latest)
                <div class="sm:col-span-2 bg-blue-50 rounded-xl p-4 border border-blue-100">
                    <h4 class="text-xs font-semibold text-blue-600 uppercase tracking-wide mb-3">
                        <i class="fas fa-heartbeat mr-1"></i> Kunjungan Terakhir — {{ $latest->visit_date?->format('d F Y') ?? '-' }}
                    </h4>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-sm">
                        <div class="bg-white rounded-lg p-2.5 text-center shadow-sm">
                            <p class="text-xs text-gray-400 mb-0.5">Berat</p>
                            <p class="font-bold text-gray-800">{{ $latest->weight ? $latest->weight.' kg' : '-' }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-2.5 text-center shadow-sm">
                            <p class="text-xs text-gray-400 mb-0.5">Tinggi</p>
                            <p class="font-bold text-gray-800">{{ $latest->height ? $latest->height.' cm' : '-' }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-2.5 text-center shadow-sm">
                            <p class="text-xs text-gray-400 mb-0.5">Ling. Kepala</p>
                            <p class="font-bold text-gray-800">{{ $latest->head_circumference ? $latest->head_circumference.' cm' : '-' }}</p>
                        </div>
                        <div class="bg-white rounded-lg p-2.5 text-center shadow-sm">
                            <p class="text-xs text-gray-400 mb-0.5">Status Gizi</p>
                            @php
                                // Map nutrition status to colors
                                $nutritionColorMap = [
                                    'Normal' => 'green',
                                    'Gizi Kurang' => 'yellow',
                                    'Gizi Lebih' => 'yellow',
                                    'Gizi Buruk/Stunting' => 'red',
                                    'Tidak Dapat Dihitung' => 'gray',
                                ];
                                $nutritionColor = $nutritionColorMap[$latest->nutrition_status] ?? 'gray';
                                
                                // Trend indicator
                                $trendIcon = [
                                    'naik' => '↑',
                                    'turun' => '↓',
                                    'tetap' => '→',
                                ][$latest->nutrition_trend] ?? '';
                                
                                $trendColor = [
                                    'naik' => 'text-green-600',
                                    'turun' => 'text-red-600',
                                    'tetap' => 'text-gray-600',
                                ][$latest->nutrition_trend] ?? 'text-gray-600';
                            @endphp
                            <div class="flex items-center justify-center gap-1">
                                <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $nutritionColor }}-100 text-{{ $nutritionColor }}-800">
                                    {{ $latest->nutrition_status ?? '-' }}
                                </span>
                                @if($latest->nutrition_trend)
                                <span class="{{ $trendColor }} text-sm font-bold" title="Tren: {{ ucfirst($latest->nutrition_trend) }}">
                                    {{ $trendIcon }}
                                </span>
                                @endif
                            </div>
                            @if($latest->z_score !== null)
                            <p class="text-xs text-gray-500 mt-1">Z-score: {{ number_format($latest->z_score, 2) }}</p>
                            @endif
                        </div>
                    </div>
                    @if($latest->immunization)
                    <p class="mt-2 text-xs text-gray-600"><span class="font-semibold">Imunisasi:</span> {{ $latest->immunization }}</p>
                    @endif
                    <div class="mt-2 flex gap-3 text-xs">
                        <span class="{{ $latest->vitamin_a ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                            <i class="{{ $latest->vitamin_a ? 'fas' : 'far' }} fa-check-circle mr-1"></i>Vitamin A
                        </span>
                        <span class="{{ $latest->pill_fe ? 'text-green-600 font-semibold' : 'text-gray-400' }}">
                            <i class="{{ $latest->pill_fe ? 'fas' : 'far' }} fa-check-circle mr-1"></i>Tablet FE
                        </span>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- GRAFIK PERTUMBUHAN (Only for Balita) --}}
    @if($patient->category === 'balita')
        <livewire:admin.patient-management.growth-chart :patient-id="$patient->id" />
    @endif

    {{-- TABEL HISTORI KESEHATAN ── --}}
    <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden" id="medical-history-section">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center px-6 py-4 border-b border-gray-200 gap-4">
            <div class="flex items-center gap-3">
                <h3 class="font-semibold text-gray-800">
                    <i class="fas fa-history mr-2 text-blue-500"></i>Riwayat Rekam Medis
                </h3>
            </div>
            
            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto">
                {{-- Local Search for History --}}
                <form action="{{ url()->current() }}#medical-history-section" method="GET" class="relative flex-1 md:flex-none md:w-64">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-xs"></i>
                    <input type="text" name="history_search" value="{{ request('history_search') }}"
                           placeholder="Cari diagnosa atau catatan..."
                           class="w-full h-9 pl-9 pr-4 bg-gray-50 border border-gray-200 rounded-lg text-xs font-medium focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 focus:bg-white transition-all">
                    @if(request('history_search'))
                        <a href="{{ url()->current() }}#medical-history-section" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-red-500 transition-colors">
                            <i class="fas fa-times-circle"></i>
                        </a>
                    @endif
                </form>

                @can('update', $patient)
                <a href="{{ route('admin.medical-records.create', ['patient_id' => $patient->id]) }}"
                   class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold rounded-lg transition shadow-sm">
                    <i class="fas fa-plus mr-1.5"></i> Tambah Rekam Medis
                </a>
                @endcan
            </div>
        </div>

        @if($medicalRecords->isEmpty())
        <div class="py-12 text-center text-gray-400">
            <i class="fas fa-notes-medical text-4xl mb-3 block"></i>
            <p class="text-sm font-medium">Belum ada rekam medis untuk warga ini.</p>
            <p class="text-xs mt-1">Klik tombol "Tambah Rekam Medis" di atas untuk memulai.</p>
        </div>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50 text-xs font-medium text-gray-500 uppercase tracking-wide">
                    <tr>
                        <th class="px-4 py-3 text-left">Tanggal</th>
                        <th class="px-4 py-3 text-left">BB / TB / LK</th>
                        <th class="px-4 py-3 text-left">Status Gizi</th>
                        <th class="px-4 py-3 text-center">Z-Score</th>
                        <th class="px-4 py-3 text-center">Tren</th>
                        <th class="px-4 py-3 text-center">Imunisasi</th>
                        <th class="px-4 py-3 text-center">Vit A</th>
                        <th class="px-4 py-3 text-center">Pil FE</th>
                        <th class="px-4 py-3 text-left">Diagnosa</th>
                        <th class="px-4 py-3 text-left">Oleh Kader</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-100">
                    @foreach($medicalRecords as $record)
                    @php
                        // Map nutrition status to colors
                        $nutritionColorMap = [
                            'Normal' => 'green',
                            'Gizi Kurang' => 'yellow',
                            'Gizi Lebih' => 'yellow',
                            'Gizi Buruk/Stunting' => 'red',
                            'Tidak Dapat Dihitung' => 'gray',
                        ];
                        $sColor = $nutritionColorMap[$record->nutrition_status] ?? 'gray';
                        
                        // Trend indicator
                        $trendIcon = [
                            'naik' => '↑ Naik',
                            'turun' => '↓ Turun',
                            'tetap' => '→ Tetap',
                        ][$record->nutrition_trend] ?? '-';
                        
                        $trendColor = [
                            'naik' => 'text-green-600',
                            'turun' => 'text-red-600',
                            'tetap' => 'text-gray-600',
                        ][$record->nutrition_trend] ?? 'text-gray-600';
                    @endphp
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap">
                            {{ $record->visit_date?->format('d M Y') ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-600 whitespace-nowrap">
                            {{ $record->weight ? $record->weight.'kg' : '-' }}
                            / {{ $record->height ? $record->height.'cm' : '-' }}
                            / {{ $record->head_circumference ? $record->head_circumference.'cm' : '-' }}
                        </td>
                        <td class="px-4 py-3">
                            <span class="inline-block px-2 py-0.5 rounded-full text-xs font-semibold bg-{{ $sColor }}-100 text-{{ $sColor }}-800">
                                {{ $record->nutrition_status ?? '-' }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-sm font-medium text-gray-700">
                            {{ $record->z_score !== null ? number_format($record->z_score, 2) : '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="{{ $trendColor }} text-xs font-semibold">
                                {{ $trendIcon }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center text-xs text-gray-600">
                            {{ $record->immunization ?: '-' }}
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($record->vitamin_a)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                            @else
                                <span class="text-gray-300"><i class="fas fa-times-circle"></i></span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            @if($record->pill_fe)
                                <span class="text-green-600"><i class="fas fa-check-circle"></i></span>
                            @else
                                <span class="text-gray-300"><i class="fas fa-times-circle"></i></span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 max-w-xs truncate" title="{{ $record->diagnosis }}">
                            {{ Str::limit($record->diagnosis, 40) ?? '-' }}
                        </td>
                        <td class="px-4 py-3 text-gray-500">
                            {{ $record->user->name ?? 'n/a' }}
                        </td>
                        <td class="px-4 py-3 text-right whitespace-nowrap space-x-1">
                            <a href="{{ route('admin.medical-records.show', $record->id) }}"
                               class="px-2.5 py-1 text-xs bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition">Detail</a>
                            @can('update', $patient)
                            <a href="{{ route('admin.medical-records.edit', $record->id) }}"
                               class="px-2.5 py-1 text-xs bg-indigo-50 text-indigo-700 rounded hover:bg-indigo-100 transition">Edit</a>
                            @endcan
                            @can('delete', $patient)
                            <form action="{{ route('admin.medical-records.destroy', $record->id) }}" method="POST" class="inline"
                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini? Seluruh riwayat kesehatan terkait juga akan dihapus.')">
                                @csrf @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 text-xs bg-red-50 text-red-700 rounded hover:bg-red-100 transition">Hapus</button>
                            </form>
                            @endcan
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4">{{ $medicalRecords->links() }}</div>
        @endif
    </div>

</div>

@endsection