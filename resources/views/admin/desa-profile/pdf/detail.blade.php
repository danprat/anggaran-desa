<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Desa {{ $profile->nama_desa }}</title>
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
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin: 20px 0 10px 0;
            padding-bottom: 5px;
            border-bottom: 1px solid #ddd;
            color: #333;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
        }
        .logo-placeholder {
            width: 80px;
            height: 80px;
            border: 2px dashed #ccc;
            display: inline-block;
            text-align: center;
            line-height: 76px;
            font-size: 10px;
            color: #999;
            margin: 0 10px;
        }
        .status-active {
            background-color: #d4edda;
            color: #155724;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .status-inactive {
            background-color: #f8f9fa;
            color: #6c757d;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="logo-container">
            <div class="logo">
                @if($profile->logo_desa && file_exists(storage_path('app/public/' . $profile->logo_desa)))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(storage_path('app/public/' . $profile->logo_desa))) }}" alt="Logo Desa">
                @else
                    <img src="data:image/svg+xml;base64,{{ base64_encode(file_get_contents(public_path('images/default-logo-desa.svg'))) }}" alt="Logo Desa">
                @endif
            </div>
            <div class="header-text">
                <h1>PEMERINTAH KABUPATEN {{ strtoupper($profile->kabupaten) }}</h1>
                <h2>KECAMATAN {{ strtoupper($profile->kecamatan) }}</h2>
                <h3>DESA {{ strtoupper($profile->nama_desa) }}</h3>
                <p>{{ $profile->alamat_lengkap }}</p>
            </div>
        </div>

        <div style="margin-top: 30px; text-align: center;">
            <h2 style="margin: 0; font-size: 16px; font-weight: bold; text-decoration: underline; text-transform: uppercase;">PROFIL DESA</h2>
        </div>
    </div>

    <!-- Informasi Dasar -->
    <div class="section-title">INFORMASI DASAR</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Desa</td>
            <td>{{ $profile->nama_desa }}</td>
        </tr>
        <tr>
            <td class="label">Kecamatan</td>
            <td>{{ $profile->kecamatan }}</td>
        </tr>
        <tr>
            <td class="label">Kabupaten</td>
            <td>{{ $profile->kabupaten }}</td>
        </tr>
        <tr>
            <td class="label">Provinsi</td>
            <td>{{ $profile->provinsi }}</td>
        </tr>
        @if($profile->kode_pos)
        <tr>
            <td class="label">Kode Pos</td>
            <td>{{ $profile->kode_pos }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Alamat Lengkap</td>
            <td>{{ $profile->alamat_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">Status Profil</td>
            <td>
                @if($profile->is_active)
                    <span class="status-active">Aktif</span>
                @else
                    <span class="status-inactive">Nonaktif</span>
                @endif
            </td>
        </tr>
    </table>

    <!-- Informasi Kepala Desa -->
    <div class="section-title">INFORMASI KEPALA DESA</div>
    <table class="info-table">
        <tr>
            <td class="label">Nama Kepala Desa</td>
            <td>{{ $profile->kepala_desa }}</td>
        </tr>
        @if($profile->nip_kepala_desa)
        <tr>
            <td class="label">NIP</td>
            <td>{{ $profile->nip_kepala_desa }}</td>
        </tr>
        @endif
        @if($profile->periode_jabatan_mulai && $profile->periode_jabatan_selesai)
        <tr>
            <td class="label">Periode Jabatan</td>
            <td>
                {{ $profile->periode_jabatan_mulai->format('d M Y') }} - 
                {{ $profile->periode_jabatan_selesai->format('d M Y') }}
                @if($profile->is_period_active)
                    <span class="status-active">Aktif</span>
                @else
                    <span class="status-inactive">Berakhir</span>
                @endif
            </td>
        </tr>
        @endif
    </table>

    <!-- Informasi Kontak -->
    @if($profile->website || $profile->email || $profile->telepon || $profile->fax)
        <div class="section-title">INFORMASI KONTAK</div>
        <table class="info-table">
            @if($profile->website)
            <tr>
                <td class="label">Website</td>
                <td>{{ $profile->website }}</td>
            </tr>
            @endif
            @if($profile->email)
            <tr>
                <td class="label">Email</td>
                <td>{{ $profile->email }}</td>
            </tr>
            @endif
            @if($profile->telepon)
            <tr>
                <td class="label">Telepon</td>
                <td>{{ $profile->telepon }}</td>
            </tr>
            @endif
            @if($profile->fax)
            <tr>
                <td class="label">Fax</td>
                <td>{{ $profile->fax }}</td>
            </tr>
            @endif
        </table>
    @endif

    <!-- Informasi Geografis -->
    @if($profile->luas_wilayah || $profile->jumlah_penduduk || $profile->jumlah_kk)
        <div class="section-title">INFORMASI GEOGRAFIS DAN DEMOGRAFIS</div>
        <table class="info-table">
            @if($profile->luas_wilayah)
            <tr>
                <td class="label">Luas Wilayah</td>
                <td>{{ number_format($profile->luas_wilayah, 2) }} Ha</td>
            </tr>
            @endif
            @if($profile->jumlah_penduduk)
            <tr>
                <td class="label">Jumlah Penduduk</td>
                <td>{{ number_format($profile->jumlah_penduduk) }} jiwa</td>
            </tr>
            @endif
            @if($profile->jumlah_kk)
            <tr>
                <td class="label">Jumlah Kepala Keluarga</td>
                <td>{{ number_format($profile->jumlah_kk) }} KK</td>
            </tr>
            @endif
        </table>
    @endif

    <!-- Batas Wilayah -->
    @if($profile->batas_utara || $profile->batas_selatan || $profile->batas_timur || $profile->batas_barat)
        <div class="section-title">BATAS WILAYAH</div>
        <table class="info-table">
            @if($profile->batas_utara)
            <tr>
                <td class="label">Batas Utara</td>
                <td>{{ $profile->batas_utara }}</td>
            </tr>
            @endif
            @if($profile->batas_selatan)
            <tr>
                <td class="label">Batas Selatan</td>
                <td>{{ $profile->batas_selatan }}</td>
            </tr>
            @endif
            @if($profile->batas_timur)
            <tr>
                <td class="label">Batas Timur</td>
                <td>{{ $profile->batas_timur }}</td>
            </tr>
            @endif
            @if($profile->batas_barat)
            <tr>
                <td class="label">Batas Barat</td>
                <td>{{ $profile->batas_barat }}</td>
            </tr>
            @endif
        </table>
    @endif

    <!-- Visi Misi -->
    @if($profile->visi || $profile->misi)
        <div class="section-title">VISI DAN MISI</div>
        <table class="info-table">
            @if($profile->visi)
            <tr>
                <td class="label">Visi</td>
                <td>{{ $profile->visi }}</td>
            </tr>
            @endif
            @if($profile->misi)
            <tr>
                <td class="label">Misi</td>
                <td style="white-space: pre-line;">{{ $profile->misi }}</td>
            </tr>
            @endif
        </table>
    @endif

    <!-- Sejarah -->
    @if($profile->sejarah_singkat)
        <div class="section-title">SEJARAH SINGKAT</div>
        <table class="info-table">
            <tr>
                <td style="padding: 15px; text-align: justify;">{{ $profile->sejarah_singkat }}</td>
            </tr>
        </table>
    @endif

    <!-- Informasi Sistem -->
    <div class="section-title">INFORMASI SISTEM</div>
    <table class="info-table">
        <tr>
            <td class="label">Dibuat Pada</td>
            <td>{{ $profile->created_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Terakhir Diupdate</td>
            <td>{{ $profile->updated_at->format('d M Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Status Logo</td>
            <td>
                @if($profile->logo_desa && $profile->logo_kabupaten && $profile->logo_provinsi)
                    <span class="status-active">Lengkap</span>
                @else
                    <span class="status-inactive">Belum Lengkap</span>
                @endif
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak pada: {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>Sistem Informasi Anggaran Desa - {{ $profile->full_name }}</p>
    </div>
</body>
</html>
