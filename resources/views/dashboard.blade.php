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

            <!-- Compact Desa Header -->
            @php $desaProfile = \App\Models\DesaProfile::getActive(); @endphp
            @if($desaProfile)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-4 p-4">
                    <div class="flex items-center justify-between">
                        <!-- Logo Section -->
                        <div class="flex items-center space-x-3">
                            <x-desa-logo type="provinsi" size="sm" />
                            <x-desa-logo type="kabupaten" size="sm" />
                            <x-desa-logo type="desa" size="sm" />
                        </div>

                        <!-- Desa Info -->
                        <div class="text-center flex-1 mx-4">
                            <h3 class="text-sm font-bold text-gray-900 uppercase">{{ $desaProfile->provinsi }}</h3>
                            <h4 class="text-sm font-bold text-gray-800 uppercase">{{ $desaProfile->kabupaten }}</h4>
                            <h4 class="text-sm font-bold text-gray-800 uppercase">{{ $desaProfile->kecamatan }}</h4>
                            <h2 class="text-lg font-bold text-gray-900 uppercase border-b border-gray-300 pb-1">{{ $desaProfile->nama_desa }}</h2>
                            <p class="text-xs text-gray-600 mt-1">{{ $desaProfile->alamat_lengkap }}</p>
                        </div>

                        <!-- Contact Info -->
                        <div class="text-right text-xs text-gray-600 space-y-1">
                            @if($desaProfile->telepon)
                                <div class="flex items-center justify-end">
                                    <span>📞</span>
                                    <span class="ml-1">{{ $desaProfile->telepon }}</span>
                                </div>
                            @endif
                            @if($desaProfile->email)
                                <div class="flex items-center justify-end">
                                    <span>✉️</span>
                                    <span class="ml-1">{{ $desaProfile->email }}</span>
                                </div>
                            @endif
                            @if($desaProfile->website)
                                <div class="flex items-center justify-end">
                                    <span>🌐</span>
                                    <span class="ml-1">{{ $desaProfile->website }}</span>
                                </div>
                            @endif
                            @if($desaProfile->kode_pos)
                                <div class="text-center mt-2 font-medium">{{ $desaProfile->kode_pos }}</div>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Welcome & Alert Section -->
            <div class="flex items-center justify-between village-card p-6 mb-6">
                <div>
                    <h3 class="text-xl font-semibold text-gray-900">Selamat datang, {{ auth()->user()->name }}!</h3>
                    <p class="text-base text-gray-600 mt-1">Role: <span class="font-medium">{{ auth()->user()->getRoleName() }}</span></p>
                </div>
                @if(!$tahunAktif)
                    <div class="bg-yellow-50 border border-yellow-200 rounded-md px-4 py-3">
                        <p class="text-base text-yellow-800">⚠️ Belum ada tahun anggaran aktif</p>
                    </div>
                @endif
            </div>

            <!-- Quick Actions & Statistics Combined -->
            @if($tahunAktif)
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">
                    <!-- Quick Actions -->
                    @if(!empty($quickActions))
                        <div class="lg:col-span-2">
                            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                                <h3 class="text-sm font-semibold text-gray-900 mb-3">Aksi Cepat</h3>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2">
                                    @foreach($quickActions as $action)
                                        <a href="{{ $action['url'] }}" class="flex items-center p-2 border border-gray-200 rounded-md hover:bg-gray-50 transition-colors">
                                            <div class="w-6 h-6 rounded-full flex items-center justify-center mr-2
                                                @if($action['color'] === 'blue') bg-blue-500 @endif
                                                @if($action['color'] === 'green') bg-green-500 @endif
                                                @if($action['color'] === 'yellow') bg-yellow-500 @endif
                                                @if($action['color'] === 'purple') bg-purple-500 @endif
                                                @if($action['color'] === 'gray') bg-gray-500 @endif">
                                                <x-icon :name="$action['icon']" class="w-3 h-3 text-white" />
                                            </div>
                                            <span class="text-xs font-medium text-gray-900">{{ $action['title'] }}</span>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Key Statistics -->
                    <div class="dashboard-stat-card">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Ringkasan</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-600">Total Kegiatan</span>
                                <span class="text-lg font-semibold text-gray-900">{{ $stats['total_kegiatan'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-600">Disetujui</span>
                                <span class="text-lg font-semibold text-green-600">{{ $stats['kegiatan_disetujui'] }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-600">Total Anggaran</span>
                                <span class="text-base font-semibold text-gray-900">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span class="text-base text-gray-600">Realisasi</span>
                                <span class="text-base font-semibold text-blue-600">Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detailed Statistics Cards -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                    <!-- Status Kegiatan -->
                    <div class="dashboard-stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="dashboard-stat-label">Draft</p>
                                <p class="dashboard-stat-value text-gray-900">{{ $stats['kegiatan_draft'] ?? 0 }}</p>
                            </div>
                            <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                <span class="text-sm text-white">📝</span>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="dashboard-stat-label">Verifikasi</p>
                                <p class="dashboard-stat-value text-yellow-600">{{ $stats['kegiatan_verifikasi'] ?? 0 }}</p>
                            </div>
                            <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                <span class="text-sm text-white">⏳</span>
                            </div>
                        </div>
                    </div>

                    <div class="dashboard-stat-card">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="dashboard-stat-label">Disetujui</p>
                                <p class="dashboard-stat-value text-green-600">{{ $stats['kegiatan_disetujui'] }}</p>
                            </div>
                            <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                <span class="text-sm text-white">✅</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-3">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-xs text-gray-600">Ditolak</p>
                                <p class="text-lg font-semibold text-red-600">{{ $stats['kegiatan_ditolak'] ?? 0 }}</p>
                            </div>
                            <div class="w-6 h-6 bg-red-500 rounded-full flex items-center justify-center">
                                <span class="text-xs text-white">❌</span>
                            </div>
                        </div>
                    </div>
                </div>

            @endif

            <!-- Recent Activities -->
            @if($tahunAktif)
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                    <!-- Kegiatan Terbaru -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Kegiatan Terbaru</h3>
                        @if($kegiatanTerbaru->count() > 0)
                            <div class="space-y-2">
                                @foreach($kegiatanTerbaru->take(3) as $kegiatan)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $kegiatan->nama_kegiatan }}</p>
                                            <p class="text-xs text-gray-500">{{ $kegiatan->bidang }} - Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                            @if($kegiatan->status === 'draft') bg-gray-100 text-gray-800
                                            @elseif($kegiatan->status === 'verifikasi') bg-yellow-100 text-yellow-800
                                            @elseif($kegiatan->status === 'disetujui') bg-green-100 text-green-800
                                            @else bg-red-100 text-red-800
                                            @endif">
                                            {{ ucfirst($kegiatan->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @if($kegiatanTerbaru->count() > 3)
                                <div class="mt-3 text-center">
                                    <a href="{{ route('kegiatan.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Lihat semua kegiatan</a>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">Belum ada kegiatan.</p>
                        @endif
                    </div>

                    <!-- Realisasi Terbaru -->
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4">
                        <h3 class="text-sm font-semibold text-gray-900 mb-3">Realisasi Terbaru</h3>
                        @if($realisasiTerbaru->count() > 0)
                            <div class="space-y-2">
                                @foreach($realisasiTerbaru->take(3) as $realisasi)
                                    <div class="flex items-center justify-between p-2 bg-gray-50 rounded-md">
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-medium text-gray-900 truncate">{{ $realisasi->kegiatan->nama_kegiatan }}</p>
                                            <p class="text-xs text-gray-500">Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }} - {{ $realisasi->tanggal->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="ml-2 px-2 py-1 text-xs font-medium rounded-full
                                            @if($realisasi->status === 'belum') bg-gray-100 text-gray-800
                                            @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($realisasi->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                            @if($realisasiTerbaru->count() > 3)
                                <div class="mt-3 text-center">
                                    <a href="{{ route('realisasi.index') }}" class="text-xs text-blue-600 hover:text-blue-800">Lihat semua realisasi</a>
                                </div>
                            @endif
                        @else
                            <p class="text-sm text-gray-500">Belum ada realisasi.</p>
                        @endif
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
