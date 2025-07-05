<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Tahun Anggaran - {{ $tahunAnggaran->tahun }}
            </h2>
            <div class="flex space-x-2">
                <x-action-button 
                    href="{{ route('admin.tahun-anggaran.edit', $tahunAnggaran) }}" 
                    icon="edit" 
                    variant="primary" 
                    size="md"
                    tooltip="Edit Tahun Anggaran"
                >
                    Edit
                </x-action-button>
                <x-action-button 
                    href="{{ route('admin.tahun-anggaran.index') }}" 
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
            
            <!-- Basic Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Informasi Dasar</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Tahun</label>
                            <p class="mt-1 text-2xl font-semibold text-gray-900">{{ $tahunAnggaran->tahun }}</p>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Status</label>
                            <div class="mt-1">
                                @if($tahunAnggaran->status === 'aktif')
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                @else
                                    <span class="inline-flex px-3 py-1 text-sm font-semibold rounded-full bg-gray-100 text-gray-800">Non-aktif</span>
                                @endif
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-500">Dibuat</label>
                            <p class="mt-1 text-sm text-gray-900">{{ $tahunAnggaran->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Statistics -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <x-icon name="plus" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Kegiatan</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['total_kegiatan'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <x-icon name="check" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Kegiatan Disetujui</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $stats['kegiatan_disetujui'] }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <x-icon name="plus" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Pagu</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <x-icon name="check" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Realisasi</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Progress Realisasi</h3>
                    
                    <div class="flex items-center">
                        <div class="flex-1">
                            <div class="bg-gray-200 rounded-full h-4">
                                <div class="bg-blue-600 h-4 rounded-full" style="width: {{ $stats['persentase_realisasi'] }}%"></div>
                            </div>
                        </div>
                        <span class="ml-4 text-lg font-semibold text-gray-900">{{ number_format($stats['persentase_realisasi'], 1) }}%</span>
                    </div>
                    
                    <div class="mt-2 text-sm text-gray-500">
                        Realisasi: Rp {{ number_format($stats['total_realisasi'], 0, ',', '.') }} dari Rp {{ number_format($stats['total_pagu'], 0, ',', '.') }}
                    </div>
                </div>
            </div>

            <!-- Kegiatan List -->
            @if($tahunAnggaran->kegiatan->count() > 0)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Kegiatan</h3>
                        
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Kegiatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pagu Anggaran</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tahunAnggaran->kegiatan->take(10) as $kegiatan)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="font-medium">{{ Str::limit($kegiatan->nama_kegiatan, 50) }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $kegiatan->bidang }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($kegiatan->pagu_anggaran, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($kegiatan->status === 'disetujui')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Disetujui</span>
                                                @elseif($kegiatan->status === 'verifikasi')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Verifikasi</span>
                                                @elseif($kegiatan->status === 'draft')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Draft</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Ditolak</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <x-action-button 
                                                    href="{{ route('kegiatan.show', $kegiatan) }}" 
                                                    icon="eye" 
                                                    variant="info" 
                                                    size="sm"
                                                    tooltip="Lihat Detail"
                                                />
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        @if($tahunAnggaran->kegiatan->count() > 10)
                            <div class="mt-4 text-center">
                                <x-action-button 
                                    href="{{ route('kegiatan.index', ['tahun_id' => $tahunAnggaran->id]) }}" 
                                    icon="eye" 
                                    variant="primary" 
                                    size="md"
                                    tooltip="Lihat Semua Kegiatan"
                                >
                                    Lihat Semua {{ $tahunAnggaran->kegiatan->count() }} Kegiatan
                                </x-action-button>
                            </div>
                        @endif
                    </div>
                </div>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <p class="text-gray-500 mt-2">Belum ada kegiatan untuk tahun anggaran ini.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
