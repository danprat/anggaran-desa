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

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                <div class="text-center mb-8">
                    <!-- Main Title -->
                    <div class="mb-4">
                        <h2 class="text-4xl md:text-5xl font-bold text-gray-900 mb-3 bg-gradient-to-r from-blue-600 to-green-600 bg-clip-text text-transparent">
                            Dashboard Anggaran Desa
                        </h2>
                        <h3 class="text-2xl md:text-3xl font-semibold text-gray-800 mb-3">
                            Laporan Realisasi Anggaran
                        </h3>
                        <div class="max-w-4xl mx-auto">
                            <p class="text-lg md:text-xl text-gray-600 leading-relaxed">
                                🏛️ <span class="font-semibold text-blue-600">Transparansi Total</span> •
                                📊 <span class="font-semibold text-green-600">Data Real-time</span> •
                                🤝 <span class="font-semibold text-purple-600">Akuntabilitas Publik</span>
                            </p>
                            <p class="text-base text-gray-500 mt-2">
                                Pantau penggunaan anggaran desa secara transparan untuk kemajuan bersama
                            </p>
                        </div>
                    </div>

                    <!-- Year Filter -->
                    @if($availableYears->count() > 1)
                        <div class="flex justify-center mb-6">
                            <div class="bg-white rounded-xl shadow-lg border border-gray-200 p-4">
                                <form method="GET" action="{{ route('landing') }}" class="flex items-center space-x-4" id="yearFilterForm">
                                    <div class="flex items-center space-x-3">
                                        <span class="text-sm font-medium text-gray-700 flex items-center">
                                            <svg class="w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            Tahun Anggaran:
                                        </span>
                                        <select name="tahun" id="tahun" onchange="handleYearChange()"
                                                class="village-input py-2 px-4 text-sm min-w-[140px] font-medium border-2 border-blue-200 focus:border-blue-500 rounded-lg">
                                            @foreach($availableYears as $tahun)
                                                <option value="{{ $tahun->tahun }}"
                                                        {{ $selectedTahun && $selectedTahun->tahun == $tahun->tahun ? 'selected' : '' }}>
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
                                    </div>
                                </form>
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <div class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-medium">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                Tahun Anggaran {{ $selectedTahun->tahun }}
                            </div>
                        </div>
                    @endif
                </div>




            </div>
        </section>
    @endif

    <!-- Laporan Realisasi Section -->
    @if($selectedTahun)
        <section class="py-8 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">


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



                <!-- Chart Visualizations -->
                @if(!empty($realisasiStats) && !empty($realisasiStats['chart_data']))
                    <!-- Primary Charts - Anggaran vs Realisasi & Trend Bulanan -->
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <!-- Anggaran vs Realisasi per Bidang -->
                        <div class="village-card p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Anggaran vs Realisasi per Bidang</h3>
                            <div class="relative h-96">
                                <canvas id="bidangHorizontalChart"></canvas>
                            </div>
                        </div>

                        <!-- Trend Realisasi Bulanan -->
                        <div class="village-card p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Trend Realisasi Bulanan</h3>
                            <div class="relative h-96">
                                <canvas id="monthlyHorizontalChart"></canvas>
                            </div>
                        </div>
                    </div>
                @endif

                @if($realisasiStats['stats_bidang']->count() > 0)
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
                        <!-- Donut Chart - Realisasi per Bidang -->
                        <div class="village-card p-6">
                            <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Realisasi per Bidang</h3>
                            <div class="relative h-96">
                                <canvas id="bidangDonutChart"></canvas>
                            </div>
                        </div>

                        <!-- Horizontal Bar Chart - Top 5 Kegiatan -->
                        @if($realisasiStats['top_kegiatan']->count() > 0)
                            <div class="village-card p-6">
                                <h3 class="text-xl font-semibold text-gray-900 mb-6 text-center">Top 5 Kegiatan Realisasi</h3>
                                <div class="relative h-96">
                                    <canvas id="topKegiatanChart"></canvas>
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

    <!-- JavaScript for enhanced UX and Charts -->
    <script>
        function handleYearChange() {
            const select = document.getElementById('tahun');
            const loadingIndicator = document.getElementById('loadingIndicator');

            // Pastikan value tidak kosong
            if (!select.value) {
                return;
            }

            // Show loading indicator
            if (loadingIndicator) {
                loadingIndicator.classList.remove('hidden');
            }
            select.disabled = true;

            // Redirect langsung dengan parameter tahun
            const baseUrl = window.location.origin + window.location.pathname;
            const newUrl = baseUrl + '?tahun=' + encodeURIComponent(select.value);
            window.location.href = newUrl;
        }

        // Chart initialization
        @if(!empty($realisasiStats) && !empty($realisasiStats['chart_data']))
        document.addEventListener('DOMContentLoaded', function() {
            // Data dari PHP
            const chartData = @json($realisasiStats['chart_data']);

            // Horizontal Bar Chart - Anggaran vs Realisasi per Bidang
            if (document.getElementById('bidangHorizontalChart') && chartData.bidang) {
                const bidangCtx = document.getElementById('bidangHorizontalChart').getContext('2d');
                const bidangLabels = chartData.bidang.map(item => item.bidang);
                const anggaranValues = chartData.bidang.map(item => parseFloat(item.total_anggaran));
                const realisasiValues = chartData.bidang.map(item => parseFloat(item.total_realisasi || 0));

                new Chart(bidangCtx, {
                    type: 'bar',
                    data: {
                        labels: bidangLabels,
                        datasets: [
                            {
                                label: 'Anggaran',
                                data: anggaranValues,
                                backgroundColor: 'rgba(59, 130, 246, 0.7)',
                                borderColor: 'rgb(59, 130, 246)',
                                borderWidth: 1
                            },
                            {
                                label: 'Realisasi',
                                data: realisasiValues,
                                backgroundColor: 'rgba(16, 185, 129, 0.7)',
                                borderColor: 'rgb(16, 185, 129)',
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        aspectRatio: 1,
                        indexAxis: 'y',
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed.x;
                                        const label = context.dataset.label;
                                        return label + ': Rp ' + value.toLocaleString('id-ID');
                                    },
                                    afterBody: function(tooltipItems) {
                                        if (tooltipItems.length === 2) {
                                            const anggaran = tooltipItems[0].parsed.x;
                                            const realisasi = tooltipItems[1].parsed.x;
                                            const percentage = anggaran > 0 ? ((realisasi / anggaran) * 100).toFixed(1) : 0;
                                            return 'Persentase Realisasi: ' + percentage + '%';
                                        }
                                        return '';
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                    },
                                    font: { size: 10 }
                                }
                            },
                            y: {
                                ticks: {
                                    font: { size: 10 }
                                }
                            }
                        }
                    }
                });
            }

            // Horizontal Bar Chart - Realisasi Bulanan
            if (document.getElementById('monthlyHorizontalChart') && chartData.monthly) {
                const monthlyCtx = document.getElementById('monthlyHorizontalChart').getContext('2d');
                const monthlyLabels = chartData.monthly.map(item => item.month_name);
                const monthlyValues = chartData.monthly.map(item => parseFloat(item.total));

                new Chart(monthlyCtx, {
                    type: 'bar',
                    data: {
                        labels: monthlyLabels,
                        datasets: [{
                            label: 'Realisasi',
                            data: monthlyValues,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.7)', 'rgba(16, 185, 129, 0.7)', 'rgba(245, 158, 11, 0.7)',
                                'rgba(239, 68, 68, 0.7)', 'rgba(139, 92, 246, 0.7)', 'rgba(6, 182, 212, 0.7)',
                                'rgba(132, 204, 22, 0.7)', 'rgba(249, 115, 22, 0.7)', 'rgba(236, 72, 153, 0.7)',
                                'rgba(107, 114, 128, 0.7)', 'rgba(75, 85, 99, 0.7)', 'rgba(55, 65, 81, 0.7)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)', 'rgb(16, 185, 129)', 'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)', 'rgb(139, 92, 246)', 'rgb(6, 182, 212)',
                                'rgb(132, 204, 22)', 'rgb(249, 115, 22)', 'rgb(236, 72, 153)',
                                'rgb(107, 114, 128)', 'rgb(75, 85, 99)', 'rgb(55, 65, 81)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        aspectRatio: 1,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Realisasi: Rp ' + context.parsed.x.toLocaleString('id-ID');
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                    },
                                    font: { size: 10 }
                                }
                            },
                            y: {
                                ticks: {
                                    font: { size: 10 }
                                }
                            }
                        }
                    }
                });
            }

            // Donut Chart - Realisasi per Bidang
            if (document.getElementById('bidangDonutChart') && chartData.bidang) {
                const donutCtx = document.getElementById('bidangDonutChart').getContext('2d');
                const bidangLabels = chartData.bidang.map(item => item.bidang);
                const bidangRealisasi = chartData.bidang.map(item => parseFloat(item.total_realisasi || 0));
                const totalRealisasi = bidangRealisasi.reduce((a, b) => a + b, 0);

                new Chart(donutCtx, {
                    type: 'doughnut',
                    data: {
                        labels: bidangLabels,
                        datasets: [{
                            data: bidangRealisasi,
                            backgroundColor: [
                                '#3B82F6', '#10B981', '#F59E0B', '#EF4444',
                                '#8B5CF6', '#06B6D4', '#84CC16', '#F97316',
                                '#EC4899', '#6B7280'
                            ],
                            borderWidth: 2,
                            borderColor: '#ffffff',
                            hoverBorderWidth: 3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        aspectRatio: 1,
                        cutout: '60%',
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: 15,
                                    usePointStyle: true,
                                    font: {
                                        size: 11
                                    }
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const value = context.parsed;
                                        const percentage = ((value / totalRealisasi) * 100).toFixed(1);
                                        return context.label + ': Rp ' + value.toLocaleString('id-ID') + ' (' + percentage + '%)';
                                    }
                                }
                            }
                        },
                        // Plugin untuk menampilkan total di tengah
                        plugins: [{
                            beforeDraw: function(chart) {
                                const width = chart.width;
                                const height = chart.height;
                                const ctx = chart.ctx;

                                ctx.restore();
                                const fontSize = (height / 200).toFixed(2);
                                ctx.font = fontSize + "em sans-serif";
                                ctx.textBaseline = "middle";
                                ctx.fillStyle = "#374151";

                                const text = "Total";
                                const textX = Math.round((width - ctx.measureText(text).width) / 2);
                                const textY = height / 2 - 10;

                                ctx.fillText(text, textX, textY);

                                // Total value
                                ctx.font = (fontSize * 1.2) + "em sans-serif";
                                ctx.fillStyle = "#1F2937";
                                const valueText = "Rp " + (totalRealisasi / 1000000).toFixed(0) + "M";
                                const valueX = Math.round((width - ctx.measureText(valueText).width) / 2);
                                const valueY = height / 2 + 15;

                                ctx.fillText(valueText, valueX, valueY);
                                ctx.save();
                            }
                        }]
                    }
                });
            }

            // Horizontal Bar Chart - Top 5 Kegiatan
            if (document.getElementById('topKegiatanChart') && chartData.top_kegiatan) {
                const topCtx = document.getElementById('topKegiatanChart').getContext('2d');
                const topLabels = chartData.top_kegiatan.map(item => {
                    // Truncate long names
                    return item.nama_kegiatan.length > 25 ?
                           item.nama_kegiatan.substring(0, 25) + '...' :
                           item.nama_kegiatan;
                });
                const topValues = chartData.top_kegiatan.map(item => parseFloat(item.total_realisasi || 0));

                new Chart(topCtx, {
                    type: 'bar',
                    data: {
                        labels: topLabels,
                        datasets: [{
                            label: 'Realisasi',
                            data: topValues,
                            backgroundColor: [
                                'rgba(59, 130, 246, 0.8)',
                                'rgba(16, 185, 129, 0.8)',
                                'rgba(245, 158, 11, 0.8)',
                                'rgba(239, 68, 68, 0.8)',
                                'rgba(139, 92, 246, 0.8)'
                            ],
                            borderColor: [
                                'rgb(59, 130, 246)',
                                'rgb(16, 185, 129)',
                                'rgb(245, 158, 11)',
                                'rgb(239, 68, 68)',
                                'rgb(139, 92, 246)'
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        aspectRatio: 1,
                        indexAxis: 'y',
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        // Show full name in tooltip
                                        return chartData.top_kegiatan[context[0].dataIndex].nama_kegiatan;
                                    },
                                    label: function(context) {
                                        const kegiatan = chartData.top_kegiatan[context.dataIndex];
                                        return [
                                            'Realisasi: Rp ' + context.parsed.x.toLocaleString('id-ID'),
                                            'Bidang: ' + kegiatan.bidang,
                                            'Progress: ' + kegiatan.persentase_realisasi + '%'
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                ticks: {
                                    callback: function(value) {
                                        return 'Rp ' + (value / 1000000).toFixed(0) + 'M';
                                    }
                                }
                            },
                            y: {
                                ticks: {
                                    font: {
                                        size: 10
                                    }
                                }
                            }
                        }
                    }
                });
            }
        });
        @endif

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
