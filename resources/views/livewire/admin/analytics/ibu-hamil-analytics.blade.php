<div>
    <div class="space-y-8 animate-fadeIn">
        {{-- Stats Grid Ibu Hamil --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            {{-- AH-02 & 03: 4T Risk --}}
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-350 group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center shadow-xs border border-amber-100/50 transition-transform duration-300 group-hover:scale-105">
                            <span class="material-symbols-outlined text-[26px]">warning</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-105/60 px-2 py-0.5 rounded-md">Risiko Tinggi &amp; 4T</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $riskStats['highRisk'] }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ibu Hamil</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-3 leading-relaxed">Ibu hamil yang tergolong berisiko tinggi (terlalu muda, tua, dekat, atau pendek)</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-xs font-bold text-slate-500">
                    <span>Kondisi Normal:</span>
                    <span class="text-emerald-700 font-extrabold bg-emerald-50 px-2 py-0.5 rounded-md">{{ $riskStats['normal'] }} Ibu</span>
                </div>
            </div>
    
            {{-- AH-06: Anemia --}}
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-350 group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center shadow-xs border border-rose-100/50 transition-transform duration-300 group-hover:scale-105">
                            <span class="material-symbols-outlined text-[26px]">water_drop</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-105/60 px-2 py-0.5 rounded-md">Kasus Anemia</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $anemiaCount }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ibu Hamil</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-3 leading-relaxed">Ibu hamil dengan kadar Hemoglobin (Hb) di bawah 11 g/dL</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 text-xs font-bold text-slate-400 leading-normal">
                    *Terdeteksi otomatis dari rekam medis pemeriksaan terbaru tahun ini
                </div>
            </div>
    
            {{-- AH-04: TTD --}}
            <div class="relative overflow-hidden bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between hover:shadow-md hover:-translate-y-1 transition-all duration-350 group">
                <div>
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-teal-50 text-teal-650 flex items-center justify-center shadow-xs border border-teal-100/50 transition-transform duration-300 group-hover:scale-105">
                            <span class="material-symbols-outlined text-[26px]">pill</span>
                        </div>
                        <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-105/60 px-2 py-0.5 rounded-md">Pemberian Tablet Fe</span>
                    </div>
                    <div class="flex items-baseline gap-2">
                        <span class="text-4xl font-extrabold text-slate-900 tracking-tight">{{ $ttdStats['received'] }}</span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Ibu Hamil</span>
                    </div>
                    <p class="text-xs font-semibold text-slate-500 mt-3 leading-relaxed">Ibu hamil yang sudah menerima Tablet Tambah Darah (TTD) atau suplemen MMS</p>
                </div>
                <div class="mt-6 pt-4 border-t border-slate-100 flex justify-between items-center text-xs font-bold text-slate-500">
                    <span>Belum Menerima:</span>
                    <span class="text-rose-700 font-extrabold bg-rose-50 px-2 py-0.5 rounded-md">{{ $ttdStats['notReceived'] }} Ibu</span>
                </div>
            </div>
        </div>

        {{-- Row 2: Detail Distribusi & Kunjungan --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-6">
            {{-- AH-01: Trimester --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-rose-500 text-[26px]">pregnant_woman</span>
                        <h3 class="text-lg font-bold text-slate-900">Distribusi Tahapan Kehamilan (Trimester)</h3>
                    </div>
                    <p class="text-xs text-slate-500 font-semibold mb-6">Pengelompokan usia kehamilan ibu hamil aktif saat ini</p>
                    
                    <div class="space-y-4">
                        @php
                            $tTotal = array_sum($trimesterStats);
                            $t1Pct = $tTotal > 0 ? round(($trimesterStats['T1'] / $tTotal) * 100) : 0;
                            $t2Pct = $tTotal > 0 ? round(($trimesterStats['T2'] / $tTotal) * 100) : 0;
                            $t3Pct = $tTotal > 0 ? round(($trimesterStats['T3'] / $tTotal) * 100) : 0;
                        @endphp
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1.5">
                                <span>Trimester I (Usia 1 - 13 Minggu)</span>
                                <span class="text-slate-900 font-extrabold">{{ $trimesterStats['T1'] }} Ibu ({{ $t1Pct }}%)</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-300 rounded-full" style="width: {{ $t1Pct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1.5">
                                <span>Trimester II (Usia 14 - 27 Minggu)</span>
                                <span class="text-slate-900 font-extrabold">{{ $trimesterStats['T2'] }} Ibu ({{ $t2Pct }}%)</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-450 rounded-full" style="width: {{ $t2Pct }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="flex justify-between items-center text-xs font-bold text-slate-700 mb-1.5">
                                <span>Trimester III (Usia 28 Minggu ke atas)</span>
                                <span class="text-slate-900 font-extrabold">{{ $trimesterStats['T3'] }} Ibu ({{ $t3Pct }}%)</span>
                            </div>
                            <div class="h-2.5 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-rose-600 rounded-full" style="width: {{ $t3Pct }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- AH-05: ANC K1-K6 --}}
            <div class="bg-white rounded-3xl p-6 border border-slate-200/80 shadow-xs flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="material-symbols-outlined text-indigo-500 text-[26px]">stethoscope</span>
                        <h3 class="text-lg font-bold text-slate-900">Kepatuhan Kunjungan Antenatal Care (ANC)</h3>
                    </div>
                    <p class="text-xs text-slate-500 font-semibold mb-6">Jumlah ibu hamil aktif yang telah melakukan minimal kunjungan pemeriksaan K1 hingga K6</p>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        @foreach([
                            'k1' => 'K1 (Pertama)', 
                            'k2' => 'K2 (Kedua)', 
                            'k3' => 'K3 (Ketiga)', 
                            'k4' => 'K4 (Keempat)', 
                            'k5' => 'K5 (Kelima)', 
                            'k6' => 'K6 (Keenam)'
                        ] as $key => $label)
                        <div class="p-3 bg-slate-50 border border-slate-200/60 rounded-xl flex flex-col justify-center transition-all hover:bg-white hover:shadow-xs hover:border-slate-350">
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">{{ $label }}</span>
                            <span class="text-lg font-extrabold text-slate-900 mt-1">{{ $ancStats[$key] ?? 0 }} <span class="text-xs font-semibold text-slate-500">Ibu</span></span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    
        <div class="bg-white rounded-3xl p-6 md:p-8 border border-slate-200 shadow-xs mt-6">
            <div class="flex items-start justify-between gap-4 mb-6">
                <div>
                    <h3 class="text-lg md:text-xl font-extrabold text-slate-900 tracking-tight">Tren Kepatuhan &amp; Risiko Ibu Hamil</h3>
                    <p class="text-xs md:text-sm text-slate-500 font-semibold mt-1">Tren prevalensi bulanan kepatuhan suplemen zat besi dan risiko tinggi usia kehamilan</p>
                </div>
                <button onclick="downloadChart(pregnancyRiskChart, 'tren_kepatuhan_risiko_ibu_hamil')" class="shrink-0 p-2.5 text-slate-500 hover:text-slate-800 rounded-xl bg-slate-50 border border-slate-300 transition-colors shadow-xs cursor-pointer flex items-center justify-center" title="Unduh Gambar Grafik">
                    <span class="material-symbols-outlined text-[20px]">download</span>
                </button>
            </div>
            <div class="relative h-85">
                <canvas id="pregnancyRiskChart" wire:ignore></canvas>
            </div>
        </div>
    </div>
</div>
