<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @php
        $titleSuffix = $desaProfile ? " {$desaProfile->nama_desa}" : '';
    @endphp
    <title>Anggaran Desa{{ $titleSuffix }}</title>

    <!-- Dynamic Favicon -->
    <x-favicon />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased">
    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center space-x-4">
                    @if($desaProfile)
                        <x-desa-logo type="desa" size="sm" />
                        <div>
                            <div class="text-lg font-semibold text-gray-900">{{ $desaProfile->formatted_nama_desa }}</div>
                            <div class="text-sm text-gray-600">{{ $desaProfile->formatted_kabupaten }}</div>
                        </div>
                    @else
                        <div class="text-lg font-semibold text-gray-900">Sistem Anggaran Desa</div>
                    @endif
                </div>
                
                <div class="flex items-center space-x-4">
                    @auth
                        <a href="{{ route('dashboard') }}" class="village-button-primary">
                            Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="village-button-primary">
                            Masuk
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    @if($desaProfile)
        <section class="village-header py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <!-- Logo -->
                    <div class="flex justify-center mb-6">
                        <x-desa-logo type="desa" size="lg" />
                    </div>

                    <!-- Village Name & Location -->
                    <h1 class="text-3xl md:text-4xl font-bold text-white mb-3">
                        {{ $desaProfile->formatted_nama_desa }}
                    </h1>
                    <p class="text-xl text-blue-100 mb-2">
                        {{ $desaProfile->formatted_kecamatan }}, {{ $desaProfile->formatted_kabupaten }}
                    </p>
                    @if($desaProfile->kepala_desa)
                        <p class="text-blue-200 mb-6">
                            Kepala Desa: {{ $desaProfile->kepala_desa }}
                        </p>
                    @endif

                    <!-- Visi -->
                    @if($desaProfile->visi)
                        <div class="max-w-3xl mx-auto">
                            <h2 class="text-lg font-semibold text-white mb-3">Visi</h2>
                            <p class="text-blue-100 italic leading-relaxed">
                                "{{ $desaProfile->visi }}"
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- Statistics Section -->
    @if($selectedTahun && !empty($stats))
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Statistik Anggaran</h2>
                    <p class="text-lg text-gray-600">Tahun Anggaran {{ $selectedTahun->tahun }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-blue-600">{{ number_format($stats['total_kegiatan']) }}</div>
                        <div class="dashboard-stat-label">Total Kegiatan</div>
                    </div>
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-green-600">{{ number_format($stats['kegiatan_approved']) }}</div>
                        <div class="dashboard-stat-label">Kegiatan Disetujui</div>
                    </div>
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-purple-600">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</div>
                        <div class="dashboard-stat-label">Total Pagu Anggaran</div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Laporan Realisasi Section -->
    @if($selectedTahun)
        <section class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header with Year Filter -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-12">
                    <div class="text-center lg:text-left mb-6 lg:mb-0">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Laporan Realisasi Anggaran</h2>
                        <p class="text-lg text-gray-600">Transparansi penggunaan anggaran desa untuk masyarakat</p>
                    </div>

                    @if($availableYears->count() > 1)
                        <div class="flex justify-center lg:justify-end">
                            <form method="GET" action="{{ route('landing') }}" class="flex items-center space-x-3" id="yearFilterForm">
                                <label for="tahun" class="text-sm font-medium text-gray-700">Filter Tahun:</label>
                                <select name="tahun" id="tahun" onchange="handleYearChange()"
                                        class="village-input py-2 px-3 text-sm min-w-[120px]">
                                    @foreach($availableYears as $tahun)
                                        <option value="{{ $tahun->id }}"
                                                {{ $selectedTahun->id == $tahun->id ? 'selected' : '' }}>
                                            {{ $tahun->tahun }}
                                        </option>
                                    @endforeach
                                </select>
                                <div id="loadingIndicator" class="hidden">
                                    <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </div>
                            </form>
                        </div>
                    @endif
                </div>

                @if(!empty($realisasiStats) && $realisasiStats['total_anggaran'] > 0)
                    <!-- Ringkasan Realisasi -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
                    <div class="village-card p-6 text-center border-l-4 border-blue-500">
                        <div class="text-2xl font-bold text-blue-600 mb-2">
                            Rp {{ number_format($realisasiStats['total_anggaran'], 0, ',', '.') }}
                        </div>
                        <div class="text-sm font-medium text-gray-600">Total Anggaran</div>
                    </div>

                    <div class="village-card p-6 text-center border-l-4 border-green-500">
                        <div class="text-2xl font-bold text-green-600 mb-2">
                            Rp {{ number_format($realisasiStats['total_realisasi'], 0, ',', '.') }}
                        </div>
                        <div class="text-sm font-medium text-gray-600">Total Realisasi</div>
                    </div>

                    <div class="village-card p-6 text-center border-l-4 border-orange-500">
                        <div class="text-2xl font-bold text-orange-600 mb-2">
                            Rp {{ number_format($realisasiStats['sisa_anggaran'], 0, ',', '.') }}
                        </div>
                        <div class="text-sm font-medium text-gray-600">Sisa Anggaran</div>
                    </div>

                    <div class="village-card p-6 text-center border-l-4 border-purple-500">
                        <div class="text-2xl font-bold text-purple-600 mb-2">
                            {{ $realisasiStats['persentase_realisasi'] }}%
                        </div>
                        <div class="text-sm font-medium text-gray-600">Persentase Realisasi</div>
                    </div>
                </div>

                <!-- Progress Bar Realisasi -->
                <div class="village-card p-6 mb-12">
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">Progress Realisasi Anggaran</h3>
                    <div class="relative" role="progressbar"
                         aria-valuenow="{{ $realisasiStats['persentase_realisasi'] }}"
                         aria-valuemin="0"
                         aria-valuemax="100"
                         aria-label="Progress realisasi anggaran {{ $realisasiStats['persentase_realisasi'] }} persen">
                        <div class="flex justify-between text-sm text-gray-600 mb-2">
                            <span>0%</span>
                            <span class="font-medium">{{ $realisasiStats['persentase_realisasi'] }}%</span>
                            <span>100%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-6 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2" tabindex="0">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-6 rounded-full transition-all duration-500 ease-out flex items-center justify-end pr-2"
                                 style="width: {{ min($realisasiStats['persentase_realisasi'], 100) }}%">
                                @if($realisasiStats['persentase_realisasi'] > 10)
                                    <span class="text-white text-xs font-medium">{{ $realisasiStats['persentase_realisasi'] }}%</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex justify-between text-xs text-gray-500 mt-2">
                            <span>Rp {{ number_format($realisasiStats['total_realisasi'], 0, ',', '.') }}</span>
                            <span>Rp {{ number_format($realisasiStats['total_anggaran'], 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <!-- Status Indicator -->
                    <div class="mt-4 p-3 rounded-lg {{ $realisasiStats['persentase_realisasi'] >= 80 ? 'bg-green-50 border border-green-200' : ($realisasiStats['persentase_realisasi'] >= 50 ? 'bg-yellow-50 border border-yellow-200' : 'bg-red-50 border border-red-200') }}">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                @if($realisasiStats['persentase_realisasi'] >= 80)
                                    <svg class="w-5 h-5 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @elseif($realisasiStats['persentase_realisasi'] >= 50)
                                    <svg class="w-5 h-5 text-yellow-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-red-600" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <div class="ml-3">
                                <p class="text-sm font-medium {{ $realisasiStats['persentase_realisasi'] >= 80 ? 'text-green-800' : ($realisasiStats['persentase_realisasi'] >= 50 ? 'text-yellow-800' : 'text-red-800') }}">
                                    @if($realisasiStats['persentase_realisasi'] >= 80)
                                        Realisasi anggaran berjalan dengan baik
                                    @elseif($realisasiStats['persentase_realisasi'] >= 50)
                                        Realisasi anggaran dalam tahap normal
                                    @else
                                        Realisasi anggaran masih dalam tahap awal
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Realisasi per Bidang -->
                @if($realisasiStats['stats_bidang']->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <div class="village-card p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6">Realisasi per Bidang</h3>
                            <div class="space-y-4">
                                @foreach($realisasiStats['stats_bidang'] as $bidang)
                                    <div class="border-b border-gray-100 pb-4 last:border-b-0">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-medium text-gray-900">{{ $bidang->bidang }}</span>
                                            <span class="text-sm text-gray-600">{{ $bidang->persentase_realisasi }}%</span>
                                        </div>
                                        <div class="w-full bg-gray-200 rounded-full h-3 mb-2">
                                            <div class="bg-blue-500 h-3 rounded-full transition-all duration-300"
                                                 style="width: {{ min($bidang->persentase_realisasi, 100) }}%"></div>
                                        </div>
                                        <div class="flex justify-between text-xs text-gray-500">
                                            <span>{{ $bidang->jumlah_kegiatan }} kegiatan</span>
                                            <span>Rp {{ number_format($bidang->total_realisasi, 0, ',', '.') }} / Rp {{ number_format($bidang->total_anggaran, 0, ',', '.') }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Top Kegiatan -->
                        @if($realisasiStats['top_kegiatan']->count() > 0)
                            <div class="village-card p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6">Top 5 Kegiatan Realisasi</h3>
                                <div class="space-y-4">
                                    @foreach($realisasiStats['top_kegiatan'] as $index => $kegiatan)
                                        <div class="flex items-start space-x-3 p-3 bg-gray-50 rounded-lg">
                                            <div class="flex-shrink-0 w-8 h-8 bg-blue-500 text-white rounded-full flex items-center justify-center text-sm font-bold">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <h4 class="text-sm font-medium text-gray-900 truncate">
                                                    {{ $kegiatan->nama_kegiatan }}
                                                </h4>
                                                <p class="text-xs text-gray-500 mb-1">{{ $kegiatan->bidang }}</p>
                                                <div class="flex justify-between items-center">
                                                    <span class="text-sm font-medium text-green-600">
                                                        Rp {{ number_format($kegiatan->total_realisasi, 0, ',', '.') }}
                                                    </span>
                                                    <span class="text-xs text-gray-500">
                                                        {{ $kegiatan->persentase_realisasi }}%
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Call to Action -->
                <div class="text-center">
                    <div class="village-card p-8 bg-gradient-to-r from-blue-50 to-green-50 border border-blue-200">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Transparansi Anggaran Desa</h3>
                        <p class="text-gray-600 mb-6 max-w-2xl mx-auto">
                            Laporan ini menunjukkan komitmen kami dalam transparansi penggunaan anggaran desa.
                            Setiap rupiah dikelola dengan penuh tanggung jawab untuk kesejahteraan masyarakat.
                        </p>
                        @auth
                            <a href="{{ route('dashboard') }}" class="village-button-primary">
                                Lihat Detail Dashboard
                            </a>
                        @else
                            <p class="text-sm text-gray-500">
                                Data diperbarui secara berkala sesuai dengan realisasi kegiatan yang telah dilaksanakan.
                            </p>
                        @endauth
                    </div>
                </div>
                @else
                    <!-- No Data State -->
                    <div class="text-center py-12">
                        <div class="village-card p-8 max-w-md mx-auto">
                            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Belum Ada Data Realisasi</h3>
                            <p class="text-gray-600 text-sm">
                                Data realisasi anggaran untuk tahun {{ $selectedTahun->tahun }} belum tersedia.
                                Silakan coba tahun lain atau tunggu update data terbaru.
                            </p>
                        </div>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Village Information -->
    @if($desaProfile && ($desaProfile->misi || $desaProfile->sejarah_singkat || $desaProfile->alamat_lengkap))
        <section class="py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                    <!-- Profile Information -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">Profil Desa</h2>

                        @if($desaProfile->misi)
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Misi</h3>
                                <div class="prose prose-lg text-gray-700">
                                    {!! nl2br(e($desaProfile->misi)) !!}
                                </div>
                            </div>
                        @endif

                        @if($desaProfile->sejarah_singkat)
                            <div class="mb-8">
                                <h3 class="text-xl font-semibold text-gray-900 mb-4">Sejarah Singkat</h3>
                                <div class="prose prose-lg text-gray-700">
                                    {!! nl2br(e($desaProfile->sejarah_singkat)) !!}
                                </div>
                            </div>
                        @endif
                    </div>

                    <!-- Contact & Details -->
                    <div>
                        <h2 class="text-3xl font-bold text-gray-900 mb-8">Informasi Kontak</h2>

                        <div class="space-y-6">
                            <div class="village-card p-6">
                                <h3 class="text-lg font-semibold text-gray-900 mb-4">Alamat</h3>
                                <p class="text-gray-700">{{ $desaProfile->formatted_address }}</p>
                            </div>

                            @if($desaProfile->telepon || $desaProfile->email || $desaProfile->website)
                                <div class="village-card p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Kontak</h3>
                                    <div class="space-y-2">
                                        @if($desaProfile->telepon)
                                            <p class="text-gray-700">
                                                <span class="font-medium">Telepon:</span> {{ $desaProfile->telepon }}
                                            </p>
                                        @endif
                                        @if($desaProfile->email)
                                            <p class="text-gray-700">
                                                <span class="font-medium">Email:</span>
                                                <a href="mailto:{{ $desaProfile->email }}" class="text-blue-600 hover:text-blue-800">
                                                    {{ $desaProfile->email }}
                                                </a>
                                            </p>
                                        @endif
                                        @if($desaProfile->website)
                                            <p class="text-gray-700">
                                                <span class="font-medium">Website:</span>
                                                <a href="{{ $desaProfile->website }}" target="_blank" class="text-blue-600 hover:text-blue-800">
                                                    {{ $desaProfile->website }}
                                                </a>
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @php $demographic = $desaProfile->demographic_info; @endphp
                            @if(array_filter($demographic))
                                <div class="village-card p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Data Demografis</h3>
                                    <div class="space-y-2">
                                        @if($demographic['luas_wilayah'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Luas Wilayah:</span> {{ $demographic['luas_wilayah'] }}
                                            </p>
                                        @endif
                                        @if($demographic['jumlah_penduduk'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Jumlah Penduduk:</span> {{ $demographic['jumlah_penduduk'] }}
                                            </p>
                                        @endif
                                        @if($demographic['jumlah_kk'])
                                            <p class="text-gray-700">
                                                <span class="font-medium">Jumlah KK:</span> {{ $demographic['jumlah_kk'] }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <div class="flex justify-center items-center space-x-4 mb-4">
                    @if($desaProfile)
                        <x-desa-logo type="desa" size="sm" class="text-white" />
                        <div>
                            <div class="text-lg font-semibold">{{ $desaProfile->formatted_nama_desa }}</div>
                            <div class="text-sm text-gray-300">{{ $desaProfile->formatted_kabupaten }}</div>
                        </div>
                    @endif
                </div>
                <p class="text-gray-300">
                    © {{ date('Y') }} {{ $desaProfile ? $desaProfile->nama_desa : 'Sistem Anggaran Desa' }}.
                    Semua hak dilindungi.
                </p>
                <p class="text-sm text-gray-400 mt-2">
                    Sistem Informasi Anggaran Desa - Transparansi dan Akuntabilitas
                </p>
                <div class="mt-4 pt-4 border-t border-gray-700">
                    <p class="text-sm text-gray-400">
                        Dibuat oleh
                        <a href="https://wa.me/6208974041777"
                           target="_blank"
                           class="font-medium text-gray-300 hover:text-white transition-colors duration-200">
                            Dany Pratmanto
                        </a>
                        • WA:
                        <a href="https://wa.me/6208974041777"
                           target="_blank"
                           class="font-medium text-green-400 hover:text-green-300 transition-colors duration-200">
                            08974041777
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript for enhanced UX -->
    <script>
        function handleYearChange() {
            const form = document.getElementById('yearFilterForm');
            const loadingIndicator = document.getElementById('loadingIndicator');
            const select = document.getElementById('tahun');

            // Show loading indicator
            loadingIndicator.classList.remove('hidden');
            select.disabled = true;

            // Submit form
            form.submit();
        }

        // Smooth scroll for anchor links
        document.addEventListener('DOMContentLoaded', function() {
            // Add smooth scrolling to progress bars
            const progressBars = document.querySelectorAll('[role="progressbar"]');
            progressBars.forEach(bar => {
                bar.addEventListener('focus', function() {
                    this.scrollIntoView({ behavior: 'smooth', block: 'center' });
                });
            });

            // Animate progress bars on scroll
            const observerOptions = {
                threshold: 0.1,
                rootMargin: '0px 0px -50px 0px'
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const progressFill = entry.target.querySelector('.bg-gradient-to-r');
                        if (progressFill) {
                            progressFill.style.width = '0%';
                            setTimeout(() => {
                                progressFill.style.width = progressFill.getAttribute('data-width') || progressFill.style.width;
                            }, 100);
                        }
                    }
                });
            }, observerOptions);

            // Observe progress bars
            document.querySelectorAll('.realisasi-progress-bar, .realisasi-bidang-progress').forEach(bar => {
                observer.observe(bar);
            });
        });

        // Keyboard navigation for cards
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                const focusedElement = document.activeElement;
                if (focusedElement.classList.contains('realisasi-top-item')) {
                    e.preventDefault();
                    // Could add click behavior here if needed
                }
            }
        });
    </script>
</body>
</html>
