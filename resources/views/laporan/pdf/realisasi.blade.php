<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Realisasi - {{ $selectedTahun->tahun }}</title>
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
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-selesai { background-color: #d4edda; color: #155724; }
        .status-sebagian { background-color: #fff3cd; color: #856404; }
        .status-belum { background-color: #f8f9fa; color: #6c757d; }
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
    </style>
</head>
<body>
    @php
        $desaProfile = \App\Models\DesaProfile::getActive();
    @endphp

    <div class="header">
        @if($desaProfile)
            <h1>PEMERINTAH {{ strtoupper($desaProfile->provinsi) }}</h1>
            <h2>{{ strtoupper($desaProfile->kabupaten) }}</h2>
            <h3>{{ strtoupper($desaProfile->kecamatan) }}</h3>
            <h1 style="margin-top: 10px; border-bottom: 2px solid #333; padding-bottom: 10px;">{{ strtoupper($desaProfile->nama_desa) }}</h1>
            <p style="margin-top: 10px;">{{ $desaProfile->alamat_lengkap }}</p>
        @endif

        <h2 style="margin-top: 20px; color: #333;">LAPORAN REALISASI ANGGARAN</h2>
        <h3>TAHUN ANGGARAN {{ $selectedTahun->tahun }}</h3>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <h3>RINGKASAN LAPORAN</h3>
        <table class="info-table">
            <tr>
                <td style="width: 25%;"><strong>Total Realisasi:</strong></td>
                <td style="width: 25%;">{{ $realisasi->count() }} realisasi</td>
                <td style="width: 25%;"><strong>Realisasi Selesai:</strong></td>
                <td style="width: 25%;">{{ $realisasi->where('status', 'selesai')->count() }} realisasi</td>
            </tr>
            <tr>
                <td><strong>Total Nilai Realisasi:</strong></td>
                <td>Rp {{ number_format($realisasi->sum('jumlah_realisasi'), 0, ',', '.') }}</td>
                <td><strong>Dalam Proses:</strong></td>
                <td>{{ $realisasi->whereIn('status', ['belum', 'sebagian'])->count() }} realisasi</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td><strong>Rata-rata per Realisasi:</strong></td>
                <td>Rp {{ $realisasi->count() > 0 ? number_format($realisasi->sum('jumlah_realisasi') / $realisasi->count(), 0, ',', '.') : '0' }}</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Kegiatan</th>
                <th style="width: 12%;">Tanggal</th>
                <th style="width: 15%;">Jumlah Realisasi</th>
                <th style="width: 25%;">Deskripsi</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 10%;">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($realisasi as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ Str::limit($item->kegiatan->nama_kegiatan, 50) }}</strong>
                        <br><small style="color: #666;">{{ $item->kegiatan->bidang }}</small>
                    </td>
                    <td style="text-align: center;">{{ $item->tanggal->format('d/m/Y') }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}</td>
                    <td>{{ Str::limit($item->deskripsi, 100) }}</td>
                    <td style="text-align: center;">
                        @if($item->status === 'selesai')
                            <span class="status-badge status-selesai">Selesai</span>
                        @elseif($item->status === 'sebagian')
                            <span class="status-badge status-sebagian">Sebagian</span>
                        @else
                            <span class="status-badge status-belum">Belum</span>
                        @endif
                    </td>
                    <td>{{ $item->dibuatOleh->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary by Kegiatan -->
    @php
        $kegiatanSummary = $realisasi->groupBy('kegiatan_id')->map(function($items) {
            $kegiatan = $items->first()->kegiatan;
            return [
                'nama' => $kegiatan->nama_kegiatan,
                'bidang' => $kegiatan->bidang,
                'pagu' => $kegiatan->pagu_anggaran,
                'total_realisasi' => $items->sum('jumlah_realisasi'),
                'jumlah_realisasi' => $items->count(),
                'persentase' => $kegiatan->pagu_anggaran > 0 ? ($items->sum('jumlah_realisasi') / $kegiatan->pagu_anggaran) * 100 : 0
            ];
        });
    @endphp

    @if($kegiatanSummary->count() > 0)
        <div class="page-break">
            <h3>RINGKASAN PER KEGIATAN</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Nama Kegiatan</th>
                        <th>Bidang</th>
                        <th>Pagu Anggaran</th>
                        <th>Total Realisasi</th>
                        <th>Jumlah Transaksi</th>
                        <th>% Realisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($kegiatanSummary as $summary)
                        <tr>
                            <td>{{ Str::limit($summary['nama'], 40) }}</td>
                            <td>{{ $summary['bidang'] }}</td>
                            <td style="text-align: right;">Rp {{ number_format($summary['pagu'], 0, ',', '.') }}</td>
                            <td style="text-align: right;">Rp {{ number_format($summary['total_realisasi'], 0, ',', '.') }}</td>
                            <td style="text-align: center;">{{ $summary['jumlah_realisasi'] }}</td>
                            <td style="text-align: center;">{{ number_format($summary['persentase'], 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        @if($desaProfile)
            <p>{{ $desaProfile->full_name }}</p>
        @else
            <p>Sistem Informasi Anggaran Desa</p>
        @endif
    </div>
</body>
</html>
