<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan - {{ $selectedTahun->tahun }}</title>
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
        .status-disetujui { background-color: #d4edda; color: #155724; }
        .status-verifikasi { background-color: #fff3cd; color: #856404; }
        .status-draft { background-color: #f8f9fa; color: #6c757d; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
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
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }
        .summary-item {
            padding: 5px 0;
        }
        .summary-label {
            font-weight: bold;
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
    <div class="header">
        <h1>LAPORAN KEGIATAN</h1>
        <h2>TAHUN ANGGARAN {{ $selectedTahun->tahun }}</h2>
        <p>Desa [Nama Desa] - Kecamatan [Nama Kecamatan] - Kabupaten [Nama Kabupaten]</p>
    </div>

    <!-- Summary Section -->
    <div class="summary">
        <h3>RINGKASAN LAPORAN</h3>
        <table class="info-table">
            <tr>
                <td style="width: 25%;"><strong>Total Kegiatan:</strong></td>
                <td style="width: 25%;">{{ $kegiatan->count() }} kegiatan</td>
                <td style="width: 25%;"><strong>Kegiatan Disetujui:</strong></td>
                <td style="width: 25%;">{{ $kegiatan->where('status', 'disetujui')->count() }} kegiatan</td>
            </tr>
            <tr>
                <td><strong>Total Pagu Anggaran:</strong></td>
                <td>Rp {{ number_format($kegiatan->sum('pagu_anggaran'), 0, ',', '.') }}</td>
                <td><strong>Dalam Proses:</strong></td>
                <td>{{ $kegiatan->whereIn('status', ['draft', 'verifikasi'])->count() }} kegiatan</td>
            </tr>
            <tr>
                <td><strong>Tanggal Cetak:</strong></td>
                <td>{{ now()->format('d/m/Y H:i') }}</td>
                <td><strong>Ditolak:</strong></td>
                <td>{{ $kegiatan->where('status', 'ditolak')->count() }} kegiatan</td>
            </tr>
        </table>
    </div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 25%;">Nama Kegiatan</th>
                <th style="width: 12%;">Bidang</th>
                <th style="width: 15%;">Pagu Anggaran</th>
                <th style="width: 12%;">Sumber Dana</th>
                <th style="width: 15%;">Waktu Pelaksanaan</th>
                <th style="width: 8%;">Status</th>
                <th style="width: 8%;">Dibuat Oleh</th>
            </tr>
        </thead>
        <tbody>
            @foreach($kegiatan as $index => $item)
                <tr>
                    <td style="text-align: center;">{{ $index + 1 }}</td>
                    <td>
                        <strong>{{ $item->nama_kegiatan }}</strong>
                        @if($item->keterangan)
                            <br><small style="color: #666;">{{ Str::limit($item->keterangan, 100) }}</small>
                        @endif
                    </td>
                    <td>{{ $item->bidang }}</td>
                    <td style="text-align: right;">Rp {{ number_format($item->pagu_anggaran, 0, ',', '.') }}</td>
                    <td>{{ $item->sumber_dana }}</td>
                    <td style="text-align: center;">
                        {{ $item->waktu_mulai->format('d/m/Y') }}<br>
                        {{ $item->waktu_selesai->format('d/m/Y') }}
                    </td>
                    <td style="text-align: center;">
                        @if($item->status === 'disetujui')
                            <span class="status-badge status-disetujui">Disetujui</span>
                        @elseif($item->status === 'verifikasi')
                            <span class="status-badge status-verifikasi">Verifikasi</span>
                        @elseif($item->status === 'draft')
                            <span class="status-badge status-draft">Draft</span>
                        @else
                            <span class="status-badge status-ditolak">Ditolak</span>
                        @endif
                    </td>
                    <td>{{ $item->pembuatKegiatan->name ?? 'N/A' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Summary by Bidang -->
    @if($bidangList->count() > 0)
        <div class="page-break">
            <h3>RINGKASAN PER BIDANG</h3>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Bidang</th>
                        <th>Jumlah Kegiatan</th>
                        <th>Total Pagu Anggaran</th>
                        <th>Kegiatan Disetujui</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bidangList as $bidang)
                        @php
                            $kegiatanBidang = $kegiatan->where('bidang', $bidang);
                            $totalPagu = $kegiatanBidang->sum('pagu_anggaran');
                            $disetujui = $kegiatanBidang->where('status', 'disetujui')->count();
                        @endphp
                        <tr>
                            <td>{{ $bidang }}</td>
                            <td style="text-align: center;">{{ $kegiatanBidang->count() }}</td>
                            <td style="text-align: right;">Rp {{ number_format($totalPagu, 0, ',', '.') }}</td>
                            <td style="text-align: center;">{{ $disetujui }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Anggaran Desa</p>
    </div>
</body>
</html>
