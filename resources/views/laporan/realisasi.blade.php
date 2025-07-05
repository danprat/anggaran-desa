<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Laporan Realisasi Anggaran - {{ $selectedTahun->tahun }}
            </h2>
            <div class="flex space-x-2">
                <form method="POST" action="{{ route('laporan.pdf', 'realisasi') }}" class="inline">
                    @csrf
                    <input type="hidden" name="tahun_id" value="{{ $selectedTahun->id }}">
                    <x-action-button 
                        type="button" 
                        icon="download" 
                        variant="danger" 
                        size="md"
                        tooltip="Export PDF"
                        onclick="this.closest('form').submit();"
                    >
                        Export PDF
                    </x-action-button>
                </form>
                <form method="POST" action="{{ route('laporan.excel', 'realisasi') }}" class="inline">
                    @csrf
                    <input type="hidden" name="tahun_id" value="{{ $selectedTahun->id }}">
                    <x-action-button 
                        type="button" 
                        icon="download" 
                        variant="success" 
                        size="md"
                        tooltip="Export Excel"
                        onclick="this.closest('form').submit();"
                    >
                        Export Excel
                    </x-action-button>
                </form>
                <x-action-button 
                    href="{{ route('laporan.index') }}" 
                    icon="x" 
                    variant="secondary" 
                    size="md"
                    tooltip="Kembali ke Laporan"
                >
                    Kembali
                </x-action-button>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Laporan</h3>
                    <form method="GET" action="{{ route('laporan.show', 'realisasi') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="tahun_id" class="block text-sm font-medium text-gray-700">Tahun Anggaran</label>
                            <select name="tahun_id" id="tahun_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach($tahunList as $tahun)
                                    <option value="{{ $tahun->id }}" {{ $selectedTahun->id == $tahun->id ? 'selected' : '' }}>
                                        {{ $tahun->tahun }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="kegiatan_id" class="block text-sm font-medium text-gray-700">Kegiatan</label>
                            <select name="kegiatan_id" id="kegiatan_id" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kegiatan</option>
                                @foreach($kegiatanList as $kegiatan)
                                    <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                        {{ Str::limit($kegiatan->nama_kegiatan, 50) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="belum" {{ request('status') == 'belum' ? 'selected' : '' }}>Belum</option>
                                <option value="sebagian" {{ request('status') == 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="selesai" {{ request('status') == 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <x-action-button 
                                type="button" 
                                icon="filter" 
                                variant="primary" 
                                size="md"
                                tooltip="Filter Data"
                                onclick="this.closest('form').submit();"
                            >
                                Filter
                            </x-action-button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <x-icon name="plus" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Realisasi</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $realisasi->count() }}</p>
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
                                <p class="text-sm font-medium text-gray-500">Selesai</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $realisasi->where('status', 'selesai')->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-yellow-500 rounded-full flex items-center justify-center">
                                    <x-icon name="refresh" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Dalam Proses</p>
                                <p class="text-2xl font-semibold text-gray-900">{{ $realisasi->whereIn('status', ['belum', 'sebagian'])->count() }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gray-500 rounded-full flex items-center justify-center">
                                    <x-icon name="plus" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-500">Total Nilai</p>
                                <p class="text-lg font-semibold text-gray-900">Rp {{ number_format($realisasi->sum('jumlah_realisasi'), 0, ',', '.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Data Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Realisasi</h3>
                    
                    @if($realisasi->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kegiatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Realisasi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Deskripsi</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat Oleh</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($realisasi as $index => $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">
                                                <div class="font-medium">{{ Str::limit($item->kegiatan->nama_kegiatan, 50) }}</div>
                                                <div class="text-gray-500 text-xs">{{ $item->kegiatan->bidang }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->tanggal->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}</td>
                                            <td class="px-6 py-4 text-sm text-gray-900">{{ Str::limit($item->deskripsi, 100) }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($item->status === 'selesai')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Selesai</span>
                                                @elseif($item->status === 'sebagian')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">Sebagian</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Belum</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->dibuatOleh->name }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            <p class="text-gray-500 mt-2">Tidak ada data realisasi untuk ditampilkan.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
