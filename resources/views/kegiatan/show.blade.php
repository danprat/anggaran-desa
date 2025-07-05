<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Kegiatan
                </h2>
                <!-- Breadcrumbs -->
                <nav class="flex mt-2" aria-label="Breadcrumb">
                    <ol class="inline-flex items-center space-x-1 md:space-x-3">
                        <li class="inline-flex items-center">
                            <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600">
                                <x-icon name="plus" class="w-3 h-3 mr-2" />
                                Dashboard
                            </a>
                        </li>
                        <li>
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <a href="{{ route('kegiatan.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-blue-600 md:ml-2">Kegiatan</a>
                            </div>
                        </li>
                        <li aria-current="page">
                            <div class="flex items-center">
                                <svg class="w-3 h-3 text-gray-400 mx-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2">{{ Str::limit($kegiatan->nama_kegiatan, 30) }}</span>
                            </div>
                        </li>
                    </ol>
                </nav>
            </div>
            <div class="flex space-x-2">
                @can('update', $kegiatan)
                    <x-action-button
                        href="{{ route('kegiatan.edit', $kegiatan) }}"
                        icon="edit"
                        variant="primary"
                        size="md"
                        tooltip="Edit Kegiatan"
                    >
                        Edit
                    </x-action-button>
                @endcan

                @if($kegiatan->status === 'disetujui')
                    @can('create-realisasi')
                        <x-action-button
                            href="{{ route('realisasi.create', ['kegiatan_id' => $kegiatan->id]) }}"
                            icon="plus"
                            variant="success"
                            size="md"
                            tooltip="Tambah Realisasi"
                        >
                            Tambah Realisasi
                        </x-action-button>
                    @endcan
                @endif

                <x-action-button
                    href="{{ route('kegiatan.index') }}"
                    icon="x"
                    variant="secondary"
                    size="md"
                    tooltip="Kembali ke Daftar"
                >
                    Kembali
                </x-action-button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Success/Error Messages -->
            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif



            <!-- Budget Progress Overview -->
            @php
                $totalRealisasi = $kegiatan->realisasi->sum('jumlah_realisasi');
                $persentaseRealisasi = $kegiatan->pagu_anggaran > 0 ? ($totalRealisasi / $kegiatan->pagu_anggaran) * 100 : 0;
                $sisaAnggaran = $kegiatan->pagu_anggaran - $totalRealisasi;
            @endphp

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-3">Progress Anggaran</h3>
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="text-center">
                            <div class="text-xl font-bold text-blue-600">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">Pagu Anggaran</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-green-600">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">Total Realisasi</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-orange-600">Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}</div>
                            <div class="text-xs text-gray-500">Sisa Anggaran</div>
                        </div>
                        <div class="text-center">
                            <div class="text-xl font-bold text-purple-600">{{ number_format($persentaseRealisasi, 1) }}%</div>
                            <div class="text-xs text-gray-500">Persentase Realisasi</div>
                        </div>
                    </div>

                    <!-- Progress Bar -->
                    <div class="mt-4">
                        <div class="flex justify-between text-xs text-gray-600 mb-1">
                            <span>Progress Realisasi</span>
                            <span>{{ number_format($persentaseRealisasi, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-gradient-to-r from-blue-500 to-green-500 h-2 rounded-full transition-all duration-500"
                                 style="width: {{ min($persentaseRealisasi, 100) }}%"></div>
                        </div>
                        @if($persentaseRealisasi > 100)
                            <div class="text-red-600 text-xs mt-1">⚠️ Realisasi melebihi pagu anggaran!</div>
                        @endif
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

                <!-- Main Info -->
                <div class="lg:col-span-2 space-y-4">
                    <!-- Kegiatan Details -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-900">{{ $kegiatan->nama_kegiatan }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $kegiatan->bidang }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($kegiatan->status === 'draft') bg-gray-100 text-gray-800
                                    @elseif($kegiatan->status === 'verifikasi') bg-yellow-100 text-yellow-800
                                    @elseif($kegiatan->status === 'disetujui') bg-green-100 text-green-800
                                    @else bg-red-100 text-red-800
                                    @endif">
                                    {{ ucfirst($kegiatan->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pagu Anggaran</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sumber Dana</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $kegiatan->sumber_dana }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Waktu Pelaksanaan</h4>
                                    <p class="mt-1 text-lg text-gray-900">
                                        {{ $kegiatan->waktu_mulai->format('d M Y') }} - {{ $kegiatan->waktu_selesai->format('d M Y') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tahun Anggaran</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $kegiatan->tahunAnggaran->tahun }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $kegiatan->pembuatKegiatan->name }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $kegiatan->created_at->format('d M Y H:i') }}</p>
                                </div>
                            </div>

                            @if($kegiatan->keterangan)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Keterangan</h4>
                                    <p class="mt-2 text-gray-900 whitespace-pre-line">{{ $kegiatan->keterangan }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Realisasi Management Section -->
                    @if($kegiatan->status === 'disetujui')
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-4">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-medium text-gray-900">Manajemen Realisasi</h3>
                                    @can('create-realisasi')
                                        <x-action-button
                                            href="{{ route('realisasi.create', ['kegiatan_id' => $kegiatan->id]) }}"
                                            icon="plus"
                                            variant="success"
                                            size="sm"
                                            tooltip="Tambah Realisasi Baru"
                                        />
                                    @endcan
                                </div>

                                @if($kegiatan->realisasi->count() > 0)
                                    @php
                                        $realisasiSorted = $kegiatan->realisasi->sortByDesc('tanggal');
                                        $showLimit = 3;
                                        $totalRealisasi = $realisasiSorted->count();
                                    @endphp

                                    <div class="space-y-3" id="realisasi-list">
                                        @foreach($realisasiSorted->take($showLimit) as $realisasi)
                                            <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                                 onclick="window.location.href='{{ route('realisasi.show', $realisasi) }}'">
                                                <div class="flex justify-between items-start">
                                                    <div class="flex-1">
                                                        <div class="flex items-center justify-between mb-2">
                                                            <h4 class="font-medium text-gray-900">{{ $realisasi->tanggal->format('d M Y') }}</h4>
                                                            <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                @if($realisasi->status === 'selesai') bg-green-100 text-green-800
                                                                @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                                                @else bg-gray-100 text-gray-800
                                                                @endif">
                                                                {{ ucfirst($realisasi->status) }}
                                                            </span>
                                                        </div>
                                                        <p class="text-sm text-gray-600 mb-2">{{ Str::limit($realisasi->deskripsi, 80) }}</p>
                                                        <div class="flex items-center justify-between">
                                                            <span class="text-base font-semibold text-green-600">
                                                                Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}
                                                            </span>
                                                            <div class="flex space-x-1" onclick="event.stopPropagation();">
                                                                @can('view', $realisasi)
                                                                    <x-action-button
                                                                        href="{{ route('realisasi.show', $realisasi) }}"
                                                                        icon="eye"
                                                                        variant="info"
                                                                        size="xs"
                                                                        tooltip="Lihat Detail"
                                                                    />
                                                                @endcan
                                                                @can('update', $realisasi)
                                                                    <x-action-button
                                                                        href="{{ route('realisasi.edit', $realisasi) }}"
                                                                        icon="edit"
                                                                        variant="primary"
                                                                        size="xs"
                                                                        tooltip="Edit Realisasi"
                                                                    />
                                                                @endcan
                                                            </div>
                                                        </div>
                                                        @if($realisasi->buktiFiles->count() > 0)
                                                            <div class="mt-1 text-xs text-gray-500">
                                                                📎 {{ $realisasi->buktiFiles->count() }} file bukti
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach

                                        @if($totalRealisasi > $showLimit)
                                            <div class="hidden space-y-3" id="realisasi-additional">
                                                @foreach($realisasiSorted->skip($showLimit) as $realisasi)
                                                    <div class="border border-gray-200 rounded-lg p-3 hover:bg-gray-50 transition-colors cursor-pointer"
                                                         onclick="window.location.href='{{ route('realisasi.show', $realisasi) }}'">
                                                        <div class="flex justify-between items-start">
                                                            <div class="flex-1">
                                                                <div class="flex items-center justify-between mb-2">
                                                                    <h4 class="font-medium text-gray-900">{{ $realisasi->tanggal->format('d M Y') }}</h4>
                                                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                                                        @if($realisasi->status === 'selesai') bg-green-100 text-green-800
                                                                        @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                                                        @else bg-gray-100 text-gray-800
                                                                        @endif">
                                                                        {{ ucfirst($realisasi->status) }}
                                                                    </span>
                                                                </div>
                                                                <p class="text-sm text-gray-600 mb-2">{{ Str::limit($realisasi->deskripsi, 80) }}</p>
                                                                <div class="flex items-center justify-between">
                                                                    <span class="text-base font-semibold text-green-600">
                                                                        Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}
                                                                    </span>
                                                                    <div class="flex space-x-1" onclick="event.stopPropagation();">
                                                                        @can('view', $realisasi)
                                                                            <x-action-button
                                                                                href="{{ route('realisasi.show', $realisasi) }}"
                                                                                icon="eye"
                                                                                variant="info"
                                                                                size="xs"
                                                                                tooltip="Lihat Detail"
                                                                            />
                                                                        @endcan
                                                                        @can('update', $realisasi)
                                                                            <x-action-button
                                                                                href="{{ route('realisasi.edit', $realisasi) }}"
                                                                                icon="edit"
                                                                                variant="primary"
                                                                                size="xs"
                                                                                tooltip="Edit Realisasi"
                                                                            />
                                                                        @endcan
                                                                    </div>
                                                                </div>
                                                                @if($realisasi->buktiFiles->count() > 0)
                                                                    <div class="mt-1 text-xs text-gray-500">
                                                                        📎 {{ $realisasi->buktiFiles->count() }} file bukti
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    @if($totalRealisasi > $showLimit)
                                        <div class="mt-3 text-center">
                                            <button type="button"
                                                    id="toggle-realisasi"
                                                    class="inline-flex items-center px-3 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-md hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors">
                                                <x-icon name="eye" class="w-4 h-4 mr-2" />
                                                <span id="toggle-text">Lihat Semua ({{ $totalRealisasi }})</span>
                                            </button>
                                        </div>
                                    @endif
                                @else
                                    <div class="text-center py-8">
                                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <p class="text-gray-500 mt-2 mb-4">Belum ada realisasi untuk kegiatan ini.</p>
                                        @can('create-realisasi')
                                            <x-action-button
                                                href="{{ route('realisasi.create', ['kegiatan_id' => $kegiatan->id]) }}"
                                                icon="plus"
                                                variant="primary"
                                                size="md"
                                                tooltip="Tambah Realisasi Pertama"
                                            >
                                                Tambah Realisasi Pertama
                                            </x-action-button>
                                        @endcan
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-4">

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Aksi Cepat</h3>

                            <div class="space-y-3">
                                @can('verify', $kegiatan)
                                    @if($kegiatan->status === 'draft')
                                        <form method="POST" action="{{ route('kegiatan.verify', $kegiatan) }}">
                                            @csrf
                                            <x-action-button
                                                type="button"
                                                icon="verify"
                                                variant="verify"
                                                size="md"
                                                tooltip="Verifikasi Kegiatan"
                                                class="w-full justify-center"
                                                onclick="if(confirm('Yakin ingin memverifikasi kegiatan ini?')) this.closest('form').submit();"
                                            >
                                                Verifikasi Kegiatan
                                            </x-action-button>
                                        </form>
                                    @endif
                                @endcan

                                @can('approve', $kegiatan)
                                    @if($kegiatan->status === 'verifikasi')
                                        <form method="POST" action="{{ route('kegiatan.approve', $kegiatan) }}">
                                            @csrf
                                            <x-action-button
                                                type="button"
                                                icon="approve"
                                                variant="success"
                                                size="md"
                                                tooltip="Setujui Kegiatan"
                                                class="w-full justify-center"
                                                onclick="if(confirm('Yakin ingin menyetujui kegiatan ini?')) this.closest('form').submit();"
                                            >
                                                Setujui Kegiatan
                                            </x-action-button>
                                        </form>

                                        <x-action-button
                                            type="button"
                                            icon="x"
                                            variant="danger"
                                            size="md"
                                            tooltip="Tolak Kegiatan"
                                            class="w-full justify-center"
                                            onclick="showRejectModal()"
                                        >
                                            Tolak Kegiatan
                                        </x-action-button>
                                    @endif
                                @endcan
                            </div>
                        </div>
                    </div>

                    <!-- Quick Stats -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-4">
                            <h3 class="text-lg font-medium text-gray-900 mb-3">Statistik Cepat</h3>

                            <div class="space-y-3">
                                <div class="flex items-center justify-between p-2 bg-blue-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                                            <x-icon name="plus" class="w-3 h-3 text-white" />
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-blue-900">Total Realisasi</span>
                                    </div>
                                    <span class="text-sm font-bold text-blue-900">{{ $kegiatan->realisasi->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between p-2 bg-green-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                                            <x-icon name="check" class="w-3 h-3 text-white" />
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-green-900">Selesai</span>
                                    </div>
                                    <span class="text-sm font-bold text-green-900">{{ $kegiatan->realisasi->where('status', 'selesai')->count() }}</span>
                                </div>

                                <div class="flex items-center justify-between p-2 bg-yellow-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                                            <x-icon name="refresh" class="w-3 h-3 text-white" />
                                        </div>
                                        <span class="ml-2 text-sm font-medium text-yellow-900">Dalam Proses</span>
                                    </div>
                                    <span class="text-sm font-bold text-yellow-900">{{ $kegiatan->realisasi->whereIn('status', ['belum', 'sebagian'])->count() }}</span>
                                </div>

                                @php
                                    $totalFiles = $kegiatan->realisasi->sum(function($r) { return $r->buktiFiles->count(); });
                                @endphp
                                <div class="flex items-center justify-between p-3 bg-purple-50 rounded-lg">
                                    <div class="flex items-center">
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                            <x-icon name="upload" class="w-4 h-4 text-white" />
                                        </div>
                                        <span class="ml-3 text-sm font-medium text-purple-900">File Bukti</span>
                                    </div>
                                    <span class="text-sm font-bold text-purple-900">{{ $totalFiles }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Budget Summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Anggaran</h3>

                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm mb-2">
                                        <span class="text-gray-500">Progress Realisasi</span>
                                        <span class="font-medium">{{ number_format($persentaseRealisasi, 1) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-3">
                                        <div class="bg-gradient-to-r from-blue-500 to-green-500 h-3 rounded-full transition-all duration-500"
                                             style="width: {{ min($persentaseRealisasi, 100) }}%"></div>
                                    </div>
                                </div>

                                <div class="space-y-2 pt-4 border-t">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Pagu Anggaran</span>
                                        <span class="text-sm font-medium">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Total Realisasi</span>
                                        <span class="text-sm font-medium text-green-600">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Sisa Anggaran</span>
                                        <span class="text-sm font-medium {{ $sisaAnggaran >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>

                                @if($persentaseRealisasi > 100)
                                    <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                        <div class="flex">
                                            <div class="flex-shrink-0">
                                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-red-800">Peringatan</h3>
                                                <div class="mt-2 text-sm text-red-700">
                                                    <p>Realisasi melebihi pagu anggaran yang ditetapkan.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>



                </div>
            </div>

            <!-- Realisasi List -->
            @if($kegiatan->realisasi->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Realisasi</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bukti</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kegiatan->realisasi as $realisasi)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $realisasi->tanggal->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($realisasi->status === 'belum') bg-gray-100 text-gray-800
                                                    @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($realisasi->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $realisasi->buktiFiles->count() }} file
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $realisasi->pembuat->name }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>

    <!-- Reject Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Tolak Kegiatan</h3>
                <form method="POST" action="{{ route('kegiatan.reject', $kegiatan) }}">
                    @csrf
                    <div class="mb-4">
                        <label for="alasan_penolakan" class="block text-sm font-medium text-gray-700 mb-2">
                            Alasan Penolakan <span class="text-red-500">*</span>
                        </label>
                        <textarea id="alasan_penolakan" 
                                  name="alasan_penolakan" 
                                  rows="4"
                                  required
                                  class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                  placeholder="Masukkan alasan penolakan..."></textarea>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" 
                                onclick="hideRejectModal()"
                                class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                            Batal
                        </button>
                        <button type="submit" 
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Tolak Kegiatan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function showRejectModal() {
            document.getElementById('rejectModal').classList.remove('hidden');
        }

        function hideRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('rejectModal').addEventListener('click', function(e) {
            if (e.target === this) {
                hideRejectModal();
            }
        });

        // Toggle realisasi view more functionality
        document.addEventListener('DOMContentLoaded', function() {
            const toggleButton = document.getElementById('toggle-realisasi');
            const additionalRealisasi = document.getElementById('realisasi-additional');
            const toggleText = document.getElementById('toggle-text');

            if (toggleButton && additionalRealisasi && toggleText) {
                let isExpanded = false;
                const totalCount = {{ $kegiatan->realisasi->count() ?? 0 }};

                toggleButton.addEventListener('click', function() {
                    if (isExpanded) {
                        // Collapse
                        additionalRealisasi.classList.add('hidden');
                        toggleText.textContent = `Lihat Semua (${totalCount})`;
                        isExpanded = false;
                    } else {
                        // Expand
                        additionalRealisasi.classList.remove('hidden');
                        toggleText.textContent = 'Lihat Lebih Sedikit';
                        isExpanded = true;
                    }
                });
            }
        });
    </script>
</x-app-layout>
