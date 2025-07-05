<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kegiatan - {{ $kegiatan->nama_kegiatan }}</title>
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
            border-collapse: collapse;
        }
        .info-table td {
            padding: 8px;
            vertical-align: top;
            border: 1px solid #ddd;
        }
        .info-table .label {
            background-color: #f5f5f5;
            font-weight: bold;
            width: 30%;
        }
        .progress-section {
            margin: 20px 0;
            padding: 15px;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .progress-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr 1fr;
            gap: 15px;
            margin-bottom: 15px;
        }
        .progress-item {
            text-align: center;
        }
        .progress-value {
            font-size: 16px;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .progress-label {
            font-size: 10px;
            color: #666;
        }
        .progress-bar {
            width: 100%;
            height: 10px;
            background-color: #e9ecef;
            border: 1px solid #dee2e6;
            margin-top: 10px;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(to right, #007bff, #28a745);
        }
        .realisasi-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .realisasi-table th,
        .realisasi-table td {
            border: 1px solid #333;
            padding: 8px;
            text-align: left;
            vertical-align: top;
        }
        .realisasi-table th {
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
        .status-disetujui { background-color: #d4edda; color: #155724; }
        .status-verifikasi { background-color: #fff3cd; color: #856404; }
        .status-draft { background-color: #f8f9fa; color: #6c757d; }
        .status-ditolak { background-color: #f8d7da; color: #721c24; }
        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 10px;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>DETAIL KEGIATAN</h1>
        <h2>{{ strtoupper($kegiatan->nama_kegiatan) }}</h2>
        <p>Tahun Anggaran {{ $kegiatan->tahunAnggaran->tahun }}</p>
    </div>

    <!-- Informasi Kegiatan -->
    <div class="section-title">INFORMASI KEGIATAN</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Kegiatan</td>
            <td>{{ $kegiatan->nama_kegiatan }}</td>
        </tr>
        <tr>
            <td class="label">Bidang</td>
            <td>{{ $kegiatan->bidang }}</td>
        </tr>
        <tr>
            <td class="label">Pagu Anggaran</td>
            <td>Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Sumber Dana</td>
            <td>{{ $kegiatan->sumber_dana }}</td>
        </tr>
        <tr>
            <td class="label">Waktu Pelaksanaan</td>
            <td>{{ $kegiatan->waktu_mulai->format('d M Y') }} - {{ $kegiatan->waktu_selesai->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>
                <span class="status-badge status-{{ $kegiatan->status }}">
                    {{ ucfirst($kegiatan->status) }}
                </span>
            </td>
        </tr>
        <tr>
            <td class="label">Dibuat Oleh</td>
            <td>{{ $kegiatan->pembuatKegiatan->name }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Dibuat</td>
            <td>{{ $kegiatan->created_at->format('d M Y H:i') }}</td>
        </tr>
        @if($kegiatan->keterangan)
        <tr>
            <td class="label">Keterangan</td>
            <td>{{ $kegiatan->keterangan }}</td>
        </tr>
        @endif
    </table>

    <!-- Progress Anggaran -->
    <div class="progress-section">
        <div class="section-title" style="margin-top: 0;">PROGRESS ANGGARAN</div>
        <div class="progress-grid">
            <div class="progress-item">
                <div class="progress-value">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</div>
                <div class="progress-label">Pagu Anggaran</div>
            </div>
            <div class="progress-item">
                <div class="progress-value">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</div>
                <div class="progress-label">Total Realisasi</div>
            </div>
            <div class="progress-item">
                <div class="progress-value">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</div>
                <div class="progress-label">Sisa Anggaran</div>
            </div>
            <div class="progress-item">
                <div class="progress-value">{{ number_format($persentaseRealisasi, 1) }}%</div>
                <div class="progress-label">Persentase Realisasi</div>
            </div>
        </div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ min($persentaseRealisasi, 100) }}%"></div>
        </div>
    </div>

    <!-- Daftar Realisasi -->
    @if($kegiatan->realisasi->count() > 0)
        <div class="section-title">DAFTAR REALISASI</div>
        <table class="realisasi-table">
            <thead>
                <tr>
                    <th style="width: 5%;">No</th>
                    <th style="width: 15%;">Tanggal</th>
                    <th style="width: 20%;">Jumlah Realisasi</th>
                    <th style="width: 35%;">Deskripsi</th>
                    <th style="width: 10%;">Status</th>
                    <th style="width: 10%;">File Bukti</th>
                    <th style="width: 5%;">Dibuat Oleh</th>
                </tr>
            </thead>
            <tbody>
                @foreach($kegiatan->realisasi->sortBy('tanggal') as $index => $realisasi)
                    <tr>
                        <td style="text-align: center;">{{ $index + 1 }}</td>
                        <td style="text-align: center;">{{ $realisasi->tanggal->format('d/m/Y') }}</td>
                        <td style="text-align: right;">Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}</td>
                        <td>{{ $realisasi->deskripsi }}</td>
                        <td style="text-align: center;">
                            <span class="status-badge status-{{ $realisasi->status }}">
                                {{ ucfirst($realisasi->status) }}
                            </span>
                        </td>
                        <td style="text-align: center;">{{ $realisasi->buktiFiles->count() }} file</td>
                        <td>{{ $realisasi->dibuatOleh->name ?? 'N/A' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background-color: #f8f9fa; font-weight: bold;">
                    <td colspan="2" style="text-align: center;">TOTAL</td>
                    <td style="text-align: right;">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    @else
        <div class="section-title">REALISASI</div>
        <p style="text-align: center; color: #666; font-style: italic;">Belum ada realisasi untuk kegiatan ini.</p>
    @endif

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Anggaran Desa</p>
    </div>
</body>
</html>
