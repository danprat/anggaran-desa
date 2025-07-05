<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Realisasi Anggaran
            </h2>
            @can('create-realisasi')
                <x-action-button
                    href="{{ route('realisasi.create') }}"
                    icon="plus"
                    variant="primary"
                    size="md"
                    tooltip="Tambah Realisasi Baru"
                >
                    Tambah Realisasi
                </x-action-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('realisasi.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Kegiatan</label>
                            <select name="kegiatan_id" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Kegiatan</option>
                                @foreach($kegiatanList as $kegiatan)
                                    <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>
                                        {{ $kegiatan->nama_kegiatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="belum" {{ request('status') === 'belum' ? 'selected' : '' }}>Belum</option>
                                <option value="sebagian" {{ request('status') === 'sebagian' ? 'selected' : '' }}>Sebagian</option>
                                <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Bulan</option>
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ request('bulan') == $i ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $i)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filter
                            </button>
                            <a href="{{ route('realisasi.index') }}" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-center">
                                Reset
                            </a>
                        </div>
                    </form>
                </div>
            </div>

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

            <!-- Realisasi Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($realisasi->count() > 0)
                        <!-- Info untuk user -->
                        <div class="mb-4 p-3 bg-blue-50 border border-blue-200 rounded-lg">
                            <p class="text-sm text-blue-700">
                                💡 <strong>Tips:</strong> Klik pada baris tabel untuk melihat detail realisasi
                            </p>
                        </div>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kegiatan
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Tanggal
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Jumlah Realisasi
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bukti
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Dibuat Oleh
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($realisasi as $item)
                                        <tr class="hover:bg-gray-50 cursor-pointer transition-colors duration-200"
                                            onclick="window.location.href='{{ route('realisasi.show', $item) }}'"
                                            title="Klik untuk melihat detail realisasi">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->kegiatan->nama_kegiatan }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $item->kegiatan->bidang }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->tanggal->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($item->jumlah_realisasi, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($item->status === 'belum') bg-gray-100 text-gray-800
                                                    @elseif($item->status === 'sebagian') bg-yellow-100 text-yellow-800
                                                    @else bg-green-100 text-green-800
                                                    @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex items-center">
                                                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v8a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2H4zm12 2H4v8h12V6z" clip-rule="evenodd"></path>
                                                    </svg>
                                                    {{ $item->buktiFiles->count() }} file
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->pembuat->name }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-1">
                                                    @can('view', $item)
                                                        <x-action-button
                                                            href="{{ route('realisasi.show', $item) }}"
                                                            icon="eye"
                                                            variant="info"
                                                            size="sm"
                                                            tooltip="Lihat Detail"
                                                        />
                                                    @endcan

                                                    @can('update', $item)
                                                        <x-action-button
                                                            href="{{ route('realisasi.edit', $item) }}"
                                                            icon="edit"
                                                            variant="primary"
                                                            size="sm"
                                                            tooltip="Edit Realisasi"
                                                        />
                                                    @endcan

                                                    @can('delete', $item)
                                                        <form method="POST" action="{{ route('realisasi.destroy', $item) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-action-button
                                                                type="button"
                                                                icon="delete"
                                                                variant="danger"
                                                                size="sm"
                                                                tooltip="Hapus Realisasi"
                                                                onclick="if(confirm('Yakin ingin menghapus realisasi ini?')) this.closest('form').submit();"
                                                            />
                                                        </form>
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        
                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $realisasi->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="mb-4">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <p class="text-gray-500 mb-4">Belum ada realisasi yang tersedia.</p>
                            @can('create-realisasi')
                                <a href="{{ route('realisasi.create') }}" class="inline-block bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                    Tambah Realisasi Pertama
                                </a>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <!-- JavaScript untuk clickable rows -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mencegah navigasi saat mengklik tombol aksi
            const actionButtons = document.querySelectorAll('button, a[href], form');
            actionButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                });
            });
        });
    </script>
</x-app-layout>
