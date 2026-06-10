<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Rapor Perkembangan Warga - {{ $reportData['patient']['full_name'] }}</title>
    <style>
        @page {
            margin: 1cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 8pt;
            line-height: 1.3;
            color: #1e293b;
        }
        .kop {
            text-align: center;
            border-bottom: 2px solid #0f172a;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .kop-title {
            font-size: 14pt;
            font-weight: bold;
            color: #0f172a;
            margin: 0;
            text-transform: uppercase;
        }
        .kop-subtitle {
            font-size: 9pt;
            color: #475569;
            margin: 3px 0 0 0;
        }
        .title {
            text-align: center;
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 15px;
            color: #0f172a;
        }
        .section-title {
            font-size: 9pt;
            font-weight: bold;
            color: #ffffff;
            background-color: #0f766e;
            padding: 4px 8px;
            margin-top: 15px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        
        .biodata-table, .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .biodata-table td {
            padding: 4px;
            border: none;
            vertical-align: top;
        }
        .biodata-label {
            font-weight: bold;
            width: 25%;
            color: #475569;
        }
        .biodata-value {
            width: 75%;
            color: #0f172a;
        }
        
        .data-table th, .data-table td {
            border: 1px solid #cbd5e1;
            padding: 5px;
            text-align: left;
            font-size: 7.5pt;
        }
        .data-table th {
            background-color: #f8fafc;
            color: #334155;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 7pt;
        }
        .text-center {
            text-align: center !important;
        }
        .font-bold {
            font-weight: bold;
        }
        .badge {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 6.5pt;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-normal {
            background-color: #d1fae5;
            color: #065f46;
        }
        .badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }
        .badge-danger {
            background-color: #fee2e2;
            color: #991b1b;
        }
        .badge-info {
            background-color: #dbeafe;
            color: #1e40af;
        }

        .chart-box {
            width: 100%;
            display: block;
            vertical-align: top;
            box-sizing: border-box;
            margin-bottom: 16px;
        }
        .chart-container {
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px;
            background: #ffffff;
            height: 210px;
        }
        
        .row {
            width: 100%;
        }
        .col-6 {
            width: 50%;
            display: inline-block;
            vertical-align: top;
        }
        
        .footer-section {
            margin-top: 30px;
            width: 100%;
        }
        .footer-col {
            width: 50%;
            display: inline-block;
            vertical-align: top;
            text-align: center;
        }
        .signature-space {
            height: 50px;
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    {{-- KOP SURAT --}}
    <div class="kop">
        <h1 class="kop-title">Posyandu {{ $reportData['patient']['posyandu_name'] }}</h1>
        <p class="kop-subtitle">Alamat: Dusun Kenanga, Desa Kenanga, Sleman, D.I. Yogyakarta</p>
    </div>

    {{-- JUDUL LAPORAN --}}
    <div class="title">Rapor Perkembangan & Kesehatan Warga</div>

    {{-- BIODATA --}}
    <div class="section-title">Biodata Warga</div>
    <table class="biodata-table">
        <tr>
            <td class="biodata-label">Nama Lengkap</td>
            <td class="biodata-value">: {{ $reportData['patient']['full_name'] }}</td>
            <td class="biodata-label">NIK / No. Kartu</td>
            <td class="biodata-value">: {{ $reportData['patient']['id_number'] }}</td>
        </tr>
        <tr>
            <td class="biodata-label">Kategori Warga</td>
            <td class="biodata-value">: {{ str_replace('_', ' ', strtoupper($reportData['patient']['category'])) }}</td>
            <td class="biodata-label">Jenis Kelamin</td>
            <td class="biodata-value">: {{ $reportData['patient']['gender'] === 'L' || $reportData['patient']['gender'] === 'M' ? 'Laki-laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="biodata-label">Tanggal Lahir</td>
            <td class="biodata-value">: {{ $reportData['patient']['birth_date'] }}</td>
            <td class="biodata-label">Nama Orang Tua (Ibu/Ayah)</td>
            <td class="biodata-value">: {{ $reportData['patient']['mother_name'] }} / {{ $reportData['patient']['father_name'] }}</td>
        </tr>
        <tr>
            <td class="biodata-label">Domisili / Alamat</td>
            <td class="biodata-value" colspan="3">: {{ $reportData['patient']['address'] }}</td>
        </tr>
        <tr>
            <td class="biodata-label">Periode Laporan</td>
            <td class="biodata-value" colspan="3">: <strong>{{ $reportData['period_label'] }}</strong></td>
        </tr>
    </table>

    {{-- GRAFIK (Hanya untuk Balita) --}}
    @if(in_array($reportData['patient']['category'], ['bayi', 'baduta', 'balita']))
        <div class="section-title">Grafik Pertumbuhan Anak (WHO Standard)</div>
        <div class="row">
            <div class="chart-box">
                <div style="font-weight: bold; font-size: 7.5pt; text-align: center; margin-bottom: 4px;">Kurva Tren Berat Badan (kg)</div>
                <div class="chart-container">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($reportData['svg_charts']['weight']) }}" alt="Grafik Berat Badan" style="width:100%; height:auto; max-height:100%; display:block;" />
                </div>
            </div>
            <div class="chart-box">
                <div style="font-weight: bold; font-size: 7.5pt; text-align: center; margin-bottom: 4px;">Kurva Tren Tinggi Badan (cm)</div>
                <div class="chart-container">
                    <img src="data:image/svg+xml;base64,{{ base64_encode($reportData['svg_charts']['height']) }}" alt="Grafik Tinggi Badan" style="width:100%; height:auto; max-height:100%; display:block;" />
                </div>
            </div>
        </div>
    @endif

    {{-- TABEL RIWAYAT PENGUKURAN --}}
    <div class="section-title" style="margin-top: 20px;">Riwayat Kunjungan & Antropometri Bulanan</div>
    <table class="data-table">
        <thead>
            <tr>
                <th width="12%">Periode</th>
                <th width="15%">Tgl Kunjungan</th>
                <th class="text-center" width="10%">Berat (kg)</th>
                <th class="text-center" width="10%">Tinggi (cm)</th>
                @if(in_array($reportData['patient']['category'], ['bayi', 'baduta', 'balita']))
                    <th class="text-center" width="13%">LILA / LK (cm)</th>
                    <th class="text-center" width="18%">Status Gizi BB/U</th>
                @else
                    <th class="text-center" width="15%">Tekanan Darah</th>
                    <th width="26%">Keluhan / Diagnosa</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($reportData['monthly_records'] as $slot)
                @php $record = $slot['record']; @endphp
                <tr>
                    <td class="font-bold">{{ $slot['period']['label'] }}</td>
                    @if($record)
                        <td>{{ $record['visit_date'] }}</td>
                        <td class="text-center font-bold">{{ $record['weight'] > 0 ? number_format($record['weight'], 1) : '-' }}</td>
                        <td class="text-center font-bold">{{ $record['height'] > 0 ? number_format($record['height'], 1) : '-' }}</td>
                        @if(in_array($reportData['patient']['category'], ['bayi', 'baduta', 'balita']))
                            <td class="text-center">
                                {{ $record['upper_arm_circumference'] > 0 ? number_format($record['upper_arm_circumference'], 1) : '-' }} /
                                {{ $record['head_circumference'] > 0 ? number_format($record['head_circumference'], 1) : '-' }}
                            </td>
                            <td class="text-center">
                                @php
                                    $st = $record['nutrition_status'] ?? null;
                                    $badgeClass = match($st) {
                                        'Normal', 'Gizi Baik' => 'badge-normal',
                                        'Gizi Kurang', 'Kurang' => 'badge-warning',
                                        'Gizi Buruk/Stunting', 'Gizi Buruk' => 'badge-danger',
                                        default => 'badge-info',
                                    };
                                @endphp
                                @if($st)
                                    <span class="badge {{ $badgeClass }}">{{ $st }}</span>
                                @else
                                    -
                                @endif
                            </td>
                        @else
                            <td class="text-center">{{ $record['blood_pressure'] ?? '-' }}</td>
                            <td>{{ $record['complaint'] ?? $record['health_note'] ?? '-' }}</td>
                        @endif
                    @else
                        <td colspan="5" class="text-center" style="color: #64748b; font-style: italic;">Tidak Hadir / Tidak Melakukan Pemeriksaan</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- PAGE BREAK FOR DETAILS (If Balita and has immunization cards, break page so it fits cleanly) --}}
    @if(in_array($reportData['patient']['category'], ['bayi', 'baduta', 'balita']))
        <div class="page-break"></div>
        <div class="kop">
            <h1 class="kop-title">Posyandu {{ $reportData['patient']['posyandu_name'] }}</h1>
            <p class="kop-subtitle">Alamat: Dusun Kenanga, Desa Kenanga, Sleman, D.I. Yogyakarta</p>
        </div>
        <div class="title">Kartu Imunisasi & Riwayat Vitamin</div>
        
        <div class="row">
            {{-- Imunisasi Wajib --}}
            <div class="col-6" style="padding-right: 10px; width: 48%; box-sizing: border-box;">
                <div class="section-title" style="margin-top: 0;">Kartu Imunisasi Wajib</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="35%">Usia</th>
                            <th width="40%">Vaksin</th>
                            <th width="25%" class="text-center">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reportData['immunization_status'] as $group)
                            @foreach($group['vaccines'] as $idx => $vax)
                                <tr>
                                    @if($idx === 0)
                                        <td class="font-bold" rowspan="{{ count($group['vaccines']) }}">{{ $group['label'] }}</td>
                                    @endif
                                    <td>{{ $vax['name'] }}</td>
                                    <td class="text-center">
                                        @if($vax['received'])
                                            <span style="color: #047857; font-weight: bold;">✔ Sudah</span>
                                        @elseif($vax['is_due'])
                                            <span style="color: #b45309; font-weight: bold;">Jatuh Tempo</span>
                                        @else
                                            <span style="color: #64748b; font-style: italic;">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            {{-- Vitamin A & Obat Cacing --}}
            <div class="col-6" style="padding-left: 10px; width: 48%; box-sizing: border-box;">
                <div class="section-title" style="margin-top: 0;">Riwayat Pemberian Vitamin A & Obat Cacing</div>
                <table class="data-table">
                    <thead>
                        <tr>
                            <th width="35%">Tanggal</th>
                            <th width="45%">Jenis / Keterangan</th>
                            <th width="20%" class="text-center">Warna</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData['vitamins_in_period'] as $vit)
                            <tr>
                                <td>{{ $vit['date'] }}</td>
                                <td>{{ $vit['note'] }}</td>
                                <td class="text-center font-bold" style="text-transform: uppercase;">{{ $vit['color'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center" style="color: #64748b; font-style: italic; padding: 20px 0;">Tidak ada pemberian vitamin A pada periode ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- SIGNATURE SECTION --}}
    <div class="footer-section">
        <div class="footer-col" style="float: left;">
            <p>Bidan Desa / Koordinator Layanan</p>
            <div class="signature-space"></div>
            <p><strong>( _______________________ )</strong></p>
            <p style="font-size: 7pt; color: #64748b; margin-top: 3px;">NIP. ____________________</p>
        </div>
        <div class="footer-col" style="float: right;">
            <p>Mengetahui,<br>Kader Pemeriksa Kenanga</p>
            <div class="signature-space"></div>
            <p><strong>( {{ Auth::user()->name }} )</strong></p>
            <p style="font-size: 7pt; color: #64748b; margin-top: 3px;">Kader Posyandu</p>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
