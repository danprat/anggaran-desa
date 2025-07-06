<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $desaProfile ? $desaProfile->nama_desa : 'Sistem Anggaran Desa' }}</title>

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
        <section class="village-header py-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                <div class="flex justify-center mb-8">
                    <x-desa-logo type="desa" size="xl" />
                </div>
                
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-4">
                    {{ $desaProfile->formatted_nama_desa }}
                </h1>
                <p class="text-xl md:text-2xl text-blue-100 mb-2">
                    {{ $desaProfile->formatted_kecamatan }}, {{ $desaProfile->formatted_kabupaten }}
                </p>
                @if($desaProfile->kepala_desa)
                    <p class="text-lg text-blue-200 mb-8">
                        Kepala Desa: {{ $desaProfile->kepala_desa }}
                    </p>
                @endif
                
                @if($desaProfile->visi)
                    <div class="max-w-4xl mx-auto">
                        <h2 class="text-2xl font-semibold text-white mb-4">Visi</h2>
                        <p class="text-lg text-blue-100 italic leading-relaxed">
                            "{{ $desaProfile->visi }}"
                        </p>
                    </div>
                @endif
            </div>
        </section>
    @endif

    <!-- Statistics Section -->
    @if($tahunAktif && !empty($stats))
        <section class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-12">
                    <h2 class="text-3xl font-bold text-gray-900 mb-4">Statistik Anggaran</h2>
                    <p class="text-lg text-gray-600">Tahun Anggaran {{ $tahunAktif->tahun }}</p>
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

    <!-- Village Information -->
    @if($desaProfile)
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
</body>
</html>
