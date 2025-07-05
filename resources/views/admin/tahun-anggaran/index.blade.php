<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Manajemen Tahun Anggaran
            </h2>
            <x-action-button 
                href="{{ route('admin.tahun-anggaran.create') }}" 
                icon="plus" 
                variant="primary" 
                size="md"
                tooltip="Tambah Tahun Anggaran Baru"
            >
                Tambah Tahun Anggaran
            </x-action-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Daftar Tahun Anggaran</h3>
                    
                    @if($tahunAnggaran->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tahun</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jumlah Kegiatan</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Dibuat</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tahunAnggaran as $item)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $item->tahun }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($item->status === 'aktif')
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Non-aktif</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->kegiatan_count }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $item->created_at->format('d/m/Y') }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-1">
                                                    <x-action-button 
                                                        href="{{ route('admin.tahun-anggaran.show', $item) }}" 
                                                        icon="eye" 
                                                        variant="info" 
                                                        size="sm"
                                                        tooltip="Lihat Detail"
                                                    />
                                                    <x-action-button 
                                                        href="{{ route('admin.tahun-anggaran.edit', $item) }}" 
                                                        icon="edit" 
                                                        variant="primary" 
                                                        size="sm"
                                                        tooltip="Edit Tahun Anggaran"
                                                    />
                                                    @if($item->status === 'nonaktif')
                                                        <form method="POST" action="{{ route('admin.tahun-anggaran.set-aktif', $item) }}" class="inline">
                                                            @csrf
                                                            <x-action-button 
                                                                type="button" 
                                                                icon="check" 
                                                                variant="success" 
                                                                size="sm"
                                                                tooltip="Set Aktif"
                                                                onclick="if(confirm('Yakin ingin mengaktifkan tahun anggaran ini?')) this.closest('form').submit();"
                                                            />
                                                        </form>
                                                    @endif
                                                    @if($item->kegiatan_count == 0)
                                                        <form method="POST" action="{{ route('admin.tahun-anggaran.destroy', $item) }}" class="inline">
                                                            @csrf
                                                            @method('DELETE')
                                                            <x-action-button 
                                                                type="button" 
                                                                icon="delete" 
                                                                variant="danger" 
                                                                size="sm"
                                                                tooltip="Hapus Tahun Anggaran"
                                                                onclick="if(confirm('Yakin ingin menghapus tahun anggaran ini?')) this.closest('form').submit();"
                                                            />
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4m-6 4v10m6-10v10m-6-4h6" />
                            </svg>
                            <p class="text-gray-500 mb-4">Belum ada tahun anggaran yang tersedia.</p>
                            <x-action-button 
                                href="{{ route('admin.tahun-anggaran.create') }}" 
                                icon="plus" 
                                variant="primary" 
                                size="md"
                                tooltip="Tambah Tahun Anggaran Pertama"
                            >
                                Tambah Tahun Anggaran Pertama
                            </x-action-button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
