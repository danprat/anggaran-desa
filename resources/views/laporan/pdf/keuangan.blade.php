<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ $selectedTahun->tahun }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 5px;
            vertical-align: top;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .data-table th,
        .data-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        .data-table th {
            background-color: #f5f5f5;
            font-weight: bold;
            text-align: center;
        }
        .summary {
            margin-bottom: 20px;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .summary h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .page-break {
            page-break-before: always;
        }
        .progress-bar {
            width: 100px;
            height: 10px;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            display: inline-block;
            position: relative;
        }
        .progress-fill {
            height: 100%;
            background-color: #007bff;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <h2>TAHUN ANGGARAN {{ $selectedTahun->tahun }}</h2>
        <p>Desa [Nama Desa] - Kecamatan [Nama Kecamatan] - Kabupaten [Nama Kabupaten]</p>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <h3>RINGKASAN KEUANGAN</h3>
        <table class="info-table">
            <tr>
                <td style="width: 25%;"><strong>Total Pagu Anggaran:</strong></td>
                <td style="width: 25%;">Rp {{ number_format($summaryBidang->sum('total_pagu'), 0, ',', '.') }}</td>
                <td style="width: 25%;"><strong>Total Realisasi:</strong></td>
                <td style="width: 25%;">Rp {{ number_format($summaryBidang->sum('total_realisasi'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><strong>Sisa Anggaran:</strong></td>
                <td>Rp {{ number_format($summaryBidang->sum('total_pagu') - $summaryBidang->sum('total_realisasi'), 0, ',', '.') }}</td>
                <td><strong>Persentase Realisasi:</strong></td>
                <td>
                    @php
                        $totalPagu = $summaryBidang->sum('total_pagu');
                        $totalRealisasi = $summaryBidang->sum('total_realisasi');
                        $persentase = $totalPagu > 0 ? ($totalRealisasi / $totalPagu) * 100 : 0;
                    @endphp
                    {{ number_format($persentase, 1) }}%
                </td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td><strong>Jumlah Bidang:</strong></td>
                <td>{{ $summaryBidang->count() }} bidang</td>
            </tr>
        </table>
    </div>

    <!-- Summary by Bidang -->
    <h3>RINGKASAN PER BIDANG</h3>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 20%;">Bidang</th>
                <th style="width: 10%;">Jumlah Kegiatan</th>
                <th style="width: 20%;">Total Pagu</th>
                <th style="width: 20%;">Total Realisasi</th>
                <th style="width: 20%;">Sisa Anggaran</th>
                <th style="width: 10%;">% Realisasi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($summaryBidang as $item)
                <tr>
                    <td><strong>{{ $item->bidang }}</strong></td>
                    <td style="text-align: center;">{{ $item->jumlah_kegiatan }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->total_pagu, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->total_realisasi, 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->total_pagu - $item->total_realisasi, 0, ',', '.') }}</td>
                    <td style="text-align: center;">{{ number_format($item->persentase_realisasi, 1) }}%</td>
                </tr>
            @endforeach
            <tr style="background-color: #f8f9fa; font-weight: bold;">
                <td>TOTAL</td>
                <td style="text-align: center;">{{ $summaryBidang->sum('jumlah_kegiatan') }}</td>
                <td style="text-align: right;">Rp {{ number_format($summaryBidang->sum('total_pagu'), 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($summaryBidang->sum('total_realisasi'), 0, ',', '.') }}</td>
                <td style="text-align: right;">Rp {{ number_format($summaryBidang->sum('total_pagu') - $summaryBidang->sum('total_realisasi'), 0, ',', '.') }}</td>
                <td style="text-align: center;">{{ number_format($persentase, 1) }}%</td>
            </tr>
        </tbody>
    </table>

    <!-- Summary by Sumber Dana -->
    <div class="page-break">
        <h3>RINGKASAN PER SUMBER DANA</h3>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 25%;">Sumber Dana</th>
                    <th style="width: 15%;">Jumlah Kegiatan</th>
                    <th style="width: 20%;">Total Pagu</th>
                    <th style="width: 20%;">Total Realisasi</th>
                    <th style="width: 20%;">% Realisasi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($summarySumberDana as $item)
                    <tr>
                        <td><strong>{{ $item->sumber_dana }}</strong></td>
                        <td style="text-align: center;">{{ $item->jumlah_kegiatan }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_pagu, 0, ',', '.') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($item->total_realisasi, 0, ',', '.') }}</td>
                        <td style="text-align: center;">{{ number_format($item->persentase_realisasi, 1) }}%</td>
                    </tr>
                @endforeach
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td>TOTAL</td>
                    <td style="text-align: center;">{{ $summarySumberDana->sum('jumlah_kegiatan') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($summarySumberDana->sum('total_pagu'), 0, ',', '.') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($summarySumberDana->sum('total_realisasi'), 0, ',', '.') }}</td>
                    <td style="text-align: center;">
                        @php
                            $totalPaguSumber = $summarySumberDana->sum('total_pagu');
                            $totalRealisasiSumber = $summarySumberDana->sum('total_realisasi');
                            $persentaseSumber = $totalPaguSumber > 0 ? ($totalRealisasiSumber / $totalPaguSumber) * 100 : 0;
                        @endphp
                        {{ number_format($persentaseSumber, 1) }}%
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Analysis Section -->
    <div class="summary">
        <h3>ANALISIS KEUANGAN</h3>
        <table class="info-table">
            <tr>
                <td style="width: 50%;"><strong>Bidang dengan Realisasi Tertinggi:</strong></td>
                <td style="width: 50%;">
                    @php
                        $bidangTertinggi = $summaryBidang->sortByDesc('persentase_realisasi')->first();
                    @endphp
                    {{ $bidangTertinggi->bidang ?? 'N/A' }} ({{ number_format($bidangTertinggi->persentase_realisasi ?? 0, 1) }}%)
                </td>
            </tr>
            <tr>
                <td><strong>Bidang dengan Realisasi Terendah:</strong></td>
                <td>
                    @php
                        $bidangTerendah = $summaryBidang->sortBy('persentase_realisasi')->first();
                    @endphp
                    {{ $bidangTerendah->bidang ?? 'N/A' }} ({{ number_format($bidangTerendah->persentase_realisasi ?? 0, 1) }}%)
                </td>
            </tr>
            <tr>
                <td><strong>Rata-rata Realisasi per Bidang:</strong></td>
                <td>{{ number_format($summaryBidang->avg('persentase_realisasi'), 1) }}%</td>
            </tr>
            <tr>
                <td><strong>Status Realisasi:</strong></td>
                <td>
                    @if($persentase >= 80)
                        Sangat Baik (≥80%)
                    @elseif($persentase >= 60)
                        Baik (60-79%)
                    @elseif($persentase >= 40)
                        Cukup (40-59%)
                    @else
                        Perlu Peningkatan (<40%)
                    @endif
                </td>
            </tr>
        </table>
    </div>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Anggaran Desa</p>
    </div>
</body>
</html>
