<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Realisasi Anggaran
            </h2>
            <div class="flex space-x-2">
                @can('update', $realisasi)
                    <a href="{{ route('realisasi.edit', $realisasi) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endcan
                <a href="{{ route('realisasi.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                    Kembali
                </a>
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

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Info -->
                <div class="lg:col-span-2">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-between items-start mb-6">
                                <div>
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $realisasi->kegiatan->nama_kegiatan }}</h3>
                                    <p class="text-gray-600 mt-1">{{ $realisasi->kegiatan->bidang }}</p>
                                </div>
                                <span class="px-3 py-1 text-sm font-medium rounded-full
                                    @if($realisasi->status === 'belum') bg-gray-100 text-gray-800
                                    @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                                    @else bg-green-100 text-green-800
                                    @endif">
                                    {{ ucfirst($realisasi->status) }}
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Jumlah Realisasi</h4>
                                    <p class="mt-1 text-2xl font-semibold text-gray-900">
                                        Rp {{ number_format($realisasi->jumlah_realisasi, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal Realisasi</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $realisasi->tanggal->format('d F Y') }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Pagu Anggaran Kegiatan</h4>
                                    <p class="mt-1 text-lg text-gray-900">
                                        Rp {{ number_format($realisasi->kegiatan->pagu_anggaran, 0, ',', '.') }}
                                    </p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Sumber Dana</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $realisasi->kegiatan->sumber_dana }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $realisasi->pembuat->name }}</p>
                                </div>

                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</h4>
                                    <p class="mt-1 text-lg text-gray-900">{{ $realisasi->created_at->format('d F Y H:i') }}</p>
                                </div>
                            </div>

                            @if($realisasi->deskripsi)
                                <div class="mt-6">
                                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider">Deskripsi</h4>
                                    <p class="mt-2 text-gray-900 whitespace-pre-line">{{ $realisasi->deskripsi }}</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Progress Kegiatan -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Kegiatan</h3>
                            
                            @php
                                $totalRealisasi = $realisasi->kegiatan->getTotalRealisasi();
                                $persentaseRealisasi = $realisasi->kegiatan->getPersentaseRealisasi();
                                $sisaAnggaran = $realisasi->kegiatan->getSisaAnggaran();
                            @endphp
                            
                            <div class="space-y-4">
                                <div>
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-500">Total Realisasi</span>
                                        <span class="font-medium">Rp {{ number_format($totalRealisasi, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="mt-1 w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($persentaseRealisasi, 100) }}%"></div>
                                    </div>
                                    <div class="mt-1 text-xs text-gray-500">{{ number_format($persentaseRealisasi, 1) }}% dari pagu</div>
                                </div>

                                <div class="border-t pt-4">
                                    <div class="flex justify-between">
                                        <span class="text-sm text-gray-500">Sisa Anggaran</span>
                                        <span class="text-sm font-medium {{ $sisaAnggaran >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                            Rp {{ number_format($sisaAnggaran, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                            
                            <div class="space-y-3">
                                <a href="{{ route('kegiatan.show', $realisasi->kegiatan) }}" 
                                   class="w-full bg-indigo-500 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-center block">
                                    Lihat Detail Kegiatan
                                </a>

                                @can('create-realisasi')
                                    <a href="{{ route('realisasi.create', ['kegiatan_id' => $realisasi->kegiatan_id]) }}" 
                                       class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded text-center block">
                                        Tambah Realisasi Lain
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Bukti Files -->
            @if($realisasi->buktiFiles->count() > 0)
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Bukti Pendukung ({{ $realisasi->buktiFiles->count() }} file)</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($realisasi->buktiFiles as $bukti)
                                <div class="border border-gray-200 rounded-lg p-4">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center">
                                                @if($bukti->isImage())
                                                    <svg class="w-8 h-8 text-blue-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @elseif($bukti->isPdf())
                                                    <svg class="w-8 h-8 text-red-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @else
                                                    <svg class="w-8 h-8 text-gray-500 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                @endif
                                                <div>
                                                    <p class="text-sm font-medium text-gray-900 truncate">{{ $bukti->file_name }}</p>
                                                    <p class="text-xs text-gray-500">{{ $bukti->getFileSizeFormatted() }}</p>
                                                </div>
                                            </div>
                                            <p class="text-xs text-gray-400 mt-2">
                                                Diupload oleh {{ $bukti->uploader->name }} pada {{ $bukti->created_at->format('d/m/Y H:i') }}
                                            </p>
                                        </div>
                                        
                                        <div class="flex space-x-2 ml-4">
                                            <a href="{{ $bukti->getFileUrl() }}" 
                                               target="_blank"
                                               class="text-blue-600 hover:text-blue-900 text-sm">
                                                Lihat
                                            </a>
                                            @can('update', $realisasi)
                                                <form method="POST" action="{{ route('bukti-file.delete', $bukti) }}" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 text-sm"
                                                            onclick="return confirm('Yakin ingin menghapus file ini?')">
                                                        Hapus
                                                    </button>
                                                </form>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="mt-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="mt-2 text-gray-500">Belum ada bukti pendukung yang diupload.</p>
                        @can('update', $realisasi)
                            <p class="mt-1 text-sm text-gray-400">Edit realisasi untuk menambahkan bukti pendukung.</p>
                        @endcan
                    </div>
                </div>
            @endif

        </div>
    </div>
</x-app-layout>
