<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                Dashboard - Anggaran Desa
            </h2>
            @if($tahunAktif)
                <span class="bg-green-100 text-green-800 text-base font-medium px-4 py-2 rounded-full">
                    Tahun Aktif: {{ $tahunAktif->tahun }}
                </span>
            @else
                <span class="bg-red-100 text-red-800 text-base font-medium px-4 py-2 rounded-full">
                    Belum ada tahun anggaran aktif
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-4">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">



            <!-- Welcome & Quick Stats Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                <!-- Welcome Card -->
                <div class="lg:col-span-2 village-card p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-xl font-semibold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h3>
                            <p class="text-base text-gray-600 mt-1">Role: <span class="font-medium">{{ auth()->user()->getRoleName() }}</span></p>
                            @if($tahunAktif)
                                <p class="text-sm text-green-600 mt-2">📅 Tahun Anggaran Aktif: <span class="font-semibold">{{ $tahunAktif->tahun }}</span></p>
                            @endif
                        </div>
                        @php $desaProfile = \App\Models\DesaProfile::getActive(); @endphp
                        @if($desaProfile)
                            <div class="text-right">
                                <x-application-logo class="h-16 w-16 mx-auto mb-2" />
                                <p class="text-sm font-medium text-gray-900">{{ $desaProfile->formatted_nama_desa }}</p>
                                <p class="text-xs text-gray-600">{{ $desaProfile->formatted_kabupaten }}</p>
                            </div>
                        @endif
                    </div>
                    @if(!$tahunAktif)
                        <div class="bg-yellow-50 border border-yellow-200 rounded-md px-4 py-3 mt-4">
                            <p class="text-base text-yellow-800">⚠️ Belum ada tahun anggaran aktif</p>
                        </div>
                    @endif
                </div>

                <!-- Quick Action Card -->
                <div class="village-card p-6">
                    <h4 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h4>
                    <div class="space-y-3">
                        @can('create-kegiatan')
                            <a href="{{ route('kegiatan.create') }}" class="village-button-primary w-full text-center block">
                                ➕ Tambah Kegiatan
                            </a>
                        @endcan
                        @can('create-realisasi')
                            <a href="{{ route('realisasi.create') }}" class="village-button-secondary w-full text-center block">
                                📊 Tambah Realisasi
                            </a>
                        @endcan
                        <a href="{{ route('laporan.index') }}" class="village-button-secondary w-full text-center block">
                            📋 Lihat Laporan
                        </a>
                    </div>
                </div>
            </div>

            <!-- Main Statistics Grid -->
            @if($tahunAktif)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Total Kegiatan -->
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-blue-600">{{ $stats['total_kegiatan'] }}</div>
                        <div class="dashboard-stat-label">Total Kegiatan</div>
                        <div class="mt-2 text-xs text-gray-500">Tahun {{ $tahunAktif->tahun }}</div>
                    </div>

                    <!-- Kegiatan Disetujui -->
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-green-600">{{ $stats['kegiatan_disetujui'] }}</div>
                        <div class="dashboard-stat-label">Disetujui</div>
                        <div class="mt-2 text-xs text-gray-500">
                            {{ $stats['total_kegiatan'] > 0 ? round(($stats['kegiatan_disetujui'] / $stats['total_kegiatan']) * 100, 1) : 0 }}% dari total
                        </div>
                    </div>

                    <!-- Total Anggaran -->
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-purple-600 text-xl">Rp {{ number_format($stats['total_pagu'] / 1000000, 1) }}M</div>
                        <div class="dashboard-stat-label">Total Anggaran</div>
                        <div class="mt-2 text-xs text-gray-500">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</div>
                    </div>

                    <!-- Realisasi -->
                    <div class="dashboard-stat-card text-center">
                        <div class="dashboard-stat-value text-orange-600 text-xl">Rp {{ number_format($stats['total_realisasi'] / 1000000, 1) }}M</div>
                        <div class="dashboard-stat-label">Realisasi</div>
                        <div class="mt-2 text-xs text-gray-500">
                            {{ $stats['total_pagu'] > 0 ? round(($stats['total_realisasi'] / $stats['total_pagu']) * 100, 1) : 0 }}% terealisasi
                        </div>
                    </div>
                </div>

                <!-- Status Kegiatan dan Recent Activity -->
                <div class="grid grid-cols-1 lg:grid-cols-4 gap-6 mb-6">
                    <!-- Status Kegiatan -->
                    <div class="village-card p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Status Kegiatan</h4>
                        <div class="space-y-3">
                            <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Draft</span>
                                </div>
                                <span class="text-lg font-bold text-gray-900">{{ $stats['kegiatan_draft'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-yellow-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-yellow-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Verifikasi</span>
                                </div>
                                <span class="text-lg font-bold text-yellow-600">{{ $stats['kegiatan_verifikasi'] ?? 0 }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-green-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-green-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Disetujui</span>
                                </div>
                                <span class="text-lg font-bold text-green-600">{{ $stats['kegiatan_disetujui'] }}</span>
                            </div>
                            <div class="flex items-center justify-between p-3 bg-red-50 rounded-lg">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-red-500 rounded-full mr-3"></div>
                                    <span class="text-sm font-medium text-gray-700">Ditolak</span>
                                </div>
                                <span class="text-lg font-bold text-red-600">{{ $stats['kegiatan_ditolak'] ?? 0 }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Kegiatan -->
                    <div class="village-card p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Kegiatan Terbaru</h4>
                        @if(isset($kegiatanTerbaru) && $kegiatanTerbaru->count() > 0)
                            <div class="space-y-3">
                                @foreach($kegiatanTerbaru->take(3) as $kegiatan)
                                    <div class="border-l-4 border-blue-500 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $kegiatan->nama_kegiatan }}</p>
                                        <p class="text-xs text-gray-500">{{ $kegiatan->created_at->diffForHumans() }}</p>
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full
                                            @if($kegiatan->status === 'disetujui') bg-green-100 text-green-800
                                            @elseif($kegiatan->status === 'verifikasi') bg-yellow-100 text-yellow-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ ucfirst($kegiatan->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('kegiatan.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat semua kegiatan →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Belum ada kegiatan</p>
                                @can('create-kegiatan')
                                    <a href="{{ route('kegiatan.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Tambah kegiatan pertama →
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>

                    <!-- Progress Chart -->
                    <div class="village-card p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Progress Realisasi</h4>
                        <div class="text-center">
                            @php
                                $progressPercentage = $stats['total_pagu'] > 0 ? round(($stats['total_realisasi'] / $stats['total_pagu']) * 100, 1) : 0;
                            @endphp
                            <div class="relative w-24 h-24 mx-auto mb-4">
                                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                                    <path class="text-gray-300" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                    <path class="text-blue-600" stroke="currentColor" stroke-width="3" fill="none" stroke-dasharray="{{ $progressPercentage }}, 100" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"></path>
                                </svg>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-lg font-bold text-gray-900">{{ $progressPercentage }}%</span>
                                </div>
                            </div>
                            <p class="text-sm text-gray-600">Anggaran Terealisasi</p>
                            <p class="text-xs text-gray-500 mt-1">
                                Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }} dari
                                Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <!-- Realisasi Terbaru -->
                    <div class="village-card p-6">
                        <h4 class="text-lg font-semibold text-gray-900 mb-4">Realisasi Terbaru</h4>
                        @if(isset($realisasiTerbaru) && $realisasiTerbaru->count() > 0)
                            <div class="space-y-3">
                                @foreach($realisasiTerbaru->take(3) as $realisasi)
                                    <div class="border-l-4 border-green-500 pl-3 py-2">
                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $realisasi->kegiatan->nama_kegiatan }}</p>
                                        <p class="text-xs text-gray-500">Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}</p>
                                        <p class="text-xs text-gray-400">{{ $realisasi->tanggal->format('d/m/Y') }}</p>
                                    </div>
                                @endforeach
                            </div>
                            <div class="mt-4">
                                <a href="{{ route('realisasi.index') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                    Lihat semua realisasi →
                                </a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <p class="text-sm text-gray-500">Belum ada realisasi</p>
                                @can('create-realisasi')
                                    <a href="{{ route('realisasi.create') }}" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                                        Tambah realisasi pertama →
                                    </a>
                                @endcan
                            </div>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
