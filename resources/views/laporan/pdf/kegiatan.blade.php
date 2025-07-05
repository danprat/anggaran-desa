<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Kegiatan - {{ $selectedTahun->tahun }}</title>
    <style>
        body {
            font-family: 'Times New Roman', serif;
            font-size: 12px;
            line-height: 1.5;
            margin: 0;
            padding: 30px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
        }

        .logo-container {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }

        .logo {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            text-align: left;
        }

        .logo img {
            width: 70px;
            height: 70px;
            object-fit: contain;
        }

        .header-text {
            display: table-cell;
            vertical-align: middle;
            text-align: center;
            padding-left: 20px;
        }

        .header h1 {
            margin: 2px 0;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            line-height: 1.2;
        }

        .header h2 {
            margin: 2px 0;
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .header h3 {
            margin: 2px 0;
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
        }

        .header p {
            margin: 5px 0 0 0;
            font-size: 10px;
            font-style: italic;
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
    @php
        $desaProfile = \App\Models\DesaProfile::getActive();
    @endphp

    <div class="header">
        @if($desaProfile)
            <div class="logo-container">
                <div class="logo">
                    @if($desaProfile->logo_desa && file_exists(storage_path('app/public/' . $desaProfile->logo_desa)))
                        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $desaProfile->logo_desa))) }}" alt="Logo Desa">
                    @else
                        <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/default-logo-desa.svg'))) }}" alt="Logo Desa">
                    @endif
                </div>
                <div class="header-text">
                    <h1>PEMERINTAH KABUPATEN {{ strtoupper($desaProfile->kabupaten) }}</h1>
                    <h2>KECAMATAN {{ strtoupper($desaProfile->kecamatan) }}</h2>
                    <h3>DESA {{ strtoupper($desaProfile->nama_desa) }}</h3>
                    <p>{{ $desaProfile->alamat_lengkap }}</p>
                </div>
            </div>
        @endif

        <div style="margin-top: 30px; text-align: center;">
            <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">LAPORAN KEGIATAN</h2>
            <p style="margin: 5px 0 0 0; font-size: 12px;">TAHUN ANGGARAN {{ $selectedTahun->tahun }}</p>
        </div>
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
        @if($desaProfile)
            <p>{{ $desaProfile->full_name }}</p>
        @else
            <p>Sistem Informasi Anggaran Desa</p>
        @endif
    </div>
</body>
</html>
