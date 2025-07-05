<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Kegiatan
            </h2>
            @can('create-kegiatan')
                <x-action-button
                    href="{{ route('kegiatan.create') }}"
                    icon="plus"
                    variant="primary"
                    size="md"
                    tooltip="Tambah Kegiatan Baru"
                >
                    Tambah Kegiatan
                </x-action-button>
            @endcan
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Filter Section -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('kegiatan.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cari Kegiatan</label>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Nama kegiatan..." 
                                   class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                            <select name="status" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Status</option>
                                <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="verifikasi" {{ request('status') === 'verifikasi' ? 'selected' : '' }}>Verifikasi</option>
                                <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                                <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                            <select name="bidang" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Semua Bidang</option>
                                @foreach($bidangList as $bidang)
                                    <option value="{{ $bidang }}" {{ request('bidang') === $bidang ? 'selected' : '' }}>
                                        {{ $bidang }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded mr-2">
                                Filter
                            </button>
                            <a href="{{ route('kegiatan.index') }}" class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded text-center">
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

            <!-- Kegiatan Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    @if($kegiatan->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kegiatan
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Bidang
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Pagu Anggaran
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Waktu Pelaksanaan
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($kegiatan as $item)
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div>
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ $item->nama_kegiatan }}
                                                    </div>
                                                    <div class="text-sm text-gray-500">
                                                        {{ $item->sumber_dana }}
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->bidang }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                Rp {{ number_format($item->pagu_anggaran, 0, ',', '.') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                    @if($item->status === 'draft') bg-gray-100 text-gray-800
                                                    @elseif($item->status === 'verifikasi') bg-yellow-100 text-yellow-800
                                                    @elseif($item->status === 'disetujui') bg-green-100 text-green-800
                                                    @else bg-red-100 text-red-800
                                                    @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $item->waktu_mulai->format('d/m/Y') }} - {{ $item->waktu_selesai->format('d/m/Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-1">
                                                    @can('view', $item)
                                                        <x-action-button
                                                            href="{{ route('kegiatan.show', $item) }}"
                                                            icon="eye"
                                                            variant="info"
                                                            size="sm"
                                                            tooltip="Lihat Detail"
                                                        />
                                                    @endcan

                                                    @can('update', $item)
                                                        <x-action-button
                                                            href="{{ route('kegiatan.edit', $item) }}"
                                                            icon="edit"
                                                            variant="primary"
                                                            size="sm"
                                                            tooltip="Edit Kegiatan"
                                                        />
                                                    @endcan
                                                    
                                                    @can('verify', $item)
                                                        @if($item->status === 'draft')
                                                            <form method="POST" action="{{ route('kegiatan.verify', $item) }}" class="inline">
                                                                @csrf
                                                                <x-action-button
                                                                    type="button"
                                                                    icon="check"
                                                                    variant="warning"
                                                                    size="sm"
                                                                    tooltip="Verifikasi Kegiatan"
                                                                    onclick="if(confirm('Yakin ingin memverifikasi kegiatan ini?')) this.closest('form').submit();"
                                                                />
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @can('approve', $item)
                                                        @if($item->status === 'verifikasi')
                                                            <form method="POST" action="{{ route('kegiatan.approve', $item) }}" class="inline">
                                                                @csrf
                                                                <x-action-button
                                                                    type="button"
                                                                    icon="check"
                                                                    variant="success"
                                                                    size="sm"
                                                                    tooltip="Setujui Kegiatan"
                                                                    onclick="if(confirm('Yakin ingin menyetujui kegiatan ini?')) this.closest('form').submit();"
                                                                />
                                                            </form>
                                                        @endif
                                                    @endcan

                                                    @can('delete', $item)
                                                        <form method="POST" action="{{ route('kegiatan.destroy', $item) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-action-button
                                                                type="button"
                                                                icon="delete"
                                                                variant="danger"
                                                                size="sm"
                                                                tooltip="Hapus Kegiatan"
                                                                onclick="if(confirm('Yakin ingin menghapus kegiatan ini?')) this.closest('form').submit();"
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
                            {{ $kegiatan->appends(request()->query())->links() }}
                        </div>
                    @else
                        <div class="text-center py-8">
                            <p class="text-gray-500">Belum ada kegiatan yang tersedia.</p>
                            @can('create-kegiatan')
                                <x-action-button
                                    href="{{ route('kegiatan.create') }}"
                                    icon="plus"
                                    variant="primary"
                                    size="md"
                                    tooltip="Tambah Kegiatan Pertama"
                                    class="mt-4"
                                >
                                    Tambah Kegiatan Pertama
                                </x-action-button>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
