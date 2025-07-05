<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard - Anggaran Desa
            </h2>
            @if($tahunAktif)
                <span class="bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
                    Tahun Aktif: {{ $tahunAktif->tahun }}
                </span>
            @else
                <span class="bg-red-100 text-red-800 text-sm font-medium px-3 py-1 rounded-full">
                    Belum ada tahun anggaran aktif
                </span>
            @endif
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Welcome Message -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <div class="flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-semibold mb-2">Selamat datang, {{ auth()->user()->name }}!</h3>
                            <p class="text-gray-600">Role: <span class="font-medium">{{ auth()->user()->getRoleName() }}</span></p>
                            @if($tahunAktif)
                                <p class="text-sm text-gray-500 mt-2">
                                    Anda sedang melihat data untuk tahun anggaran {{ $tahunAktif->tahun }}.
                                </p>
                            @endif
                        </div>
                        @if(!empty($roleSpecificData))
                            <div class="text-right">
                                <h4 class="text-sm font-medium text-gray-500">{{ $roleSpecificData['title'] ?? '' }}</h4>
                                <div class="mt-1 text-xs text-gray-400">
                                    {{ now()->format('d F Y, H:i') }}
                                </div>
                            </div>
                        @endif
                    </div>

                    @if(!$tahunAktif)
                        <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <p>Belum ada tahun anggaran yang aktif. Silakan hubungi administrator untuk mengaktifkan tahun anggaran.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            @if(!empty($quickActions) && $tahunAktif)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($quickActions as $action)
                                <a href="{{ $action['url'] }}" class="block p-4 border border-gray-200 rounded-lg hover:shadow-md transition-all duration-200
                                    @if($action['color'] === 'blue') hover:border-blue-300 @endif
                                    @if($action['color'] === 'green') hover:border-green-300 @endif
                                    @if($action['color'] === 'yellow') hover:border-yellow-300 @endif
                                    @if($action['color'] === 'purple') hover:border-purple-300 @endif
                                    @if($action['color'] === 'gray') hover:border-gray-300 @endif">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center
                                                @if($action['color'] === 'blue') bg-blue-500 @endif
                                                @if($action['color'] === 'green') bg-green-500 @endif
                                                @if($action['color'] === 'yellow') bg-yellow-500 @endif
                                                @if($action['color'] === 'purple') bg-purple-500 @endif
                                                @if($action['color'] === 'gray') bg-gray-500 @endif">
                                                @if($action['icon'] === 'users')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'plus')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'check')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'document')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'chart')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M2 11a1 1 0 011-1h2a1 1 0 011 1v5a1 1 0 01-1 1H3a1 1 0 01-1-1v-5zM8 7a1 1 0 011-1h2a1 1 0 011 1v9a1 1 0 01-1 1H9a1 1 0 01-1-1V7zM14 4a1 1 0 011-1h2a1 1 0 011 1v12a1 1 0 01-1 1h-2a1 1 0 01-1-1V4z"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'money')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($action['icon'] === 'calendar')
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <h4 class="text-sm font-medium text-gray-900">{{ $action['title'] }}</h4>
                                            <p class="text-xs text-gray-500">{{ $action['description'] }}</p>
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Total Kegiatan -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Kegiatan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_kegiatan'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kegiatan Disetujui -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Kegiatan Disetujui</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['kegiatan_disetujui'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Anggaran -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Anggaran</p>
                                <p class="text-2xl font-semibold text-gray-900">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Persentase Realisasi -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 4.707a1 1 0 00-1.414-1.414L10 9.586 8.707 8.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Realisasi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ number_format($stats['persentase_realisasi'], 1) }}%</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Role-Specific Content -->
            @if(!empty($roleSpecificData) && $tahunAktif)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">{{ $roleSpecificData['title'] ?? 'Informasi Khusus' }}</h3>

                        @if(auth()->user()->hasRole('admin'))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900">Total User</h4>
                                    <p class="text-2xl font-bold text-blue-600">{{ $roleSpecificData['total_users'] ?? 0 }}</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900">Tahun Anggaran</h4>
                                    <p class="text-2xl font-bold text-green-600">{{ $roleSpecificData['total_tahun_anggaran'] ?? 0 }}</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-purple-900">Total Log</h4>
                                    <p class="text-2xl font-bold text-purple-600">{{ $roleSpecificData['system_stats']['total_logs'] ?? 0 }}</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasRole('kepala-desa'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-yellow-900">Pending Persetujuan</h4>
                                    <p class="text-3xl font-bold text-yellow-600">{{ $roleSpecificData['pending_approvals'] ?? 0 }}</p>
                                    <p class="text-sm text-yellow-700">Kegiatan menunggu persetujuan</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900">Disetujui Bulan Ini</h4>
                                    <p class="text-3xl font-bold text-green-600">{{ $roleSpecificData['approved_this_month'] ?? 0 }}</p>
                                    <p class="text-sm text-green-700">Total anggaran: Rp {{ number_format($roleSpecificData['budget_summary']['total_approved'] ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasRole('sekretaris'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900">Pending Verifikasi</h4>
                                    <p class="text-3xl font-bold text-blue-600">{{ $roleSpecificData['pending_verifications'] ?? 0 }}</p>
                                    <p class="text-sm text-blue-700">Kegiatan menunggu verifikasi</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900">Diverifikasi Bulan Ini</h4>
                                    <p class="text-3xl font-bold text-green-600">{{ $roleSpecificData['verified_this_month'] ?? 0 }}</p>
                                    <p class="text-sm text-green-700">Kegiatan yang telah diverifikasi</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasRole('bendahara'))
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900">Total Realisasi</h4>
                                    <p class="text-2xl font-bold text-green-600">Rp {{ number_format($roleSpecificData['total_realisasi'] ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-sm text-green-700">Semua tahun anggaran</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900">Realisasi Bulan Ini</h4>
                                    <p class="text-2xl font-bold text-blue-600">Rp {{ number_format($roleSpecificData['realisasi_this_month'] ?? 0, 0, ',', '.') }}</p>
                                    <p class="text-sm text-blue-700">Sisa anggaran: Rp {{ number_format($roleSpecificData['budget_utilization']['remaining_budget'] ?? 0, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasRole('operator'))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900">Kegiatan Saya</h4>
                                    <p class="text-3xl font-bold text-blue-600">{{ $roleSpecificData['my_kegiatan_total'] ?? 0 }}</p>
                                    <p class="text-sm text-blue-700">Total kegiatan dibuat</p>
                                </div>
                                <div class="bg-green-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-green-900">Disetujui</h4>
                                    <p class="text-3xl font-bold text-green-600">{{ $roleSpecificData['my_kegiatan_approved'] ?? 0 }}</p>
                                    <p class="text-sm text-green-700">Kegiatan disetujui</p>
                                </div>
                                <div class="bg-yellow-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-yellow-900">Draft</h4>
                                    <p class="text-3xl font-bold text-yellow-600">{{ $roleSpecificData['my_kegiatan_draft'] ?? 0 }}</p>
                                    <p class="text-sm text-yellow-700">Belum disubmit</p>
                                </div>
                            </div>
                        @elseif(auth()->user()->hasRole('auditor'))
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div class="bg-gray-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-gray-900">Total Aktivitas</h4>
                                    <p class="text-2xl font-bold text-gray-600">{{ $roleSpecificData['total_activities'] ?? 0 }}</p>
                                    <p class="text-sm text-gray-700">Log sistem</p>
                                </div>
                                <div class="bg-blue-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-blue-900">Aktivitas Bulan Ini</h4>
                                    <p class="text-2xl font-bold text-blue-600">{{ $roleSpecificData['activities_this_month'] ?? 0 }}</p>
                                    <p class="text-sm text-blue-700">Log bulan ini</p>
                                </div>
                                <div class="bg-purple-50 p-4 rounded-lg">
                                    <h4 class="font-medium text-purple-900">Total File</h4>
                                    <p class="text-2xl font-bold text-purple-600">{{ $roleSpecificData['audit_summary']['total_files'] ?? 0 }}</p>
                                    <p class="text-sm text-purple-700">Bukti realisasi</p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Kegiatan Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Kegiatan Terbaru</h3>
                        @if($kegiatanTerbaru->count() > 0)
                            <div class="space-y-3">
                                @foreach($kegiatanTerbaru as $kegiatan)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $kegiatan->nama_kegiatan }}</p>
                                            <p class="text-sm text-gray-500">{{ $kegiatan->bidang }} - Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
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
                        @else
                            <p class="text-gray-500">Belum ada kegiatan.</p>
                        @endif
                    </div>
                </div>

                <!-- Realisasi Terbaru -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Realisasi Terbaru</h3>
                        @if($realisasiTerbaru->count() > 0)
                            <div class="space-y-3">
                                @foreach($realisasiTerbaru as $realisasi)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $realisasi->kegiatan->nama_kegiatan }}</p>
                                            <p class="text-sm text-gray-500">Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }} - {{ $realisasi->tanggal->format('d/m/Y') }}</p>
                                        </div>
                                        <span class="px-2 py-1 text-xs font-medium rounded-full
                                            @if($realisasi->status === 'belum') bg-gray-100 text-gray-800
                                            @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                            @else bg-green-100 text-green-800
                                            @endif">
                                            {{ ucfirst($realisasi->status) }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="text-gray-500">Belum ada realisasi.</p>
                        @endif
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
