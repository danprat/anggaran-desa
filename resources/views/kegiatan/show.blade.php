<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Kegiatan
            </h2>
            <div class="flex space-x-2">
                @can('update', $kegiatan)
                    <a href="{{ route('kegiatan.edit', $kegiatan) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Edit
                    </a>
                @endcan
                <a href="{{ route('kegiatan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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
                                    <h3 class="text-2xl font-bold text-gray-900">{{ $kegiatan->nama_kegiatan }}</h3>
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

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Realisasi Summary -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Ringkasan Realisasi</h3>
                            
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

                    <!-- Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aksi</h3>
                            
                            <div class="space-y-3">
                                @can('verify', $kegiatan)
                                    @if($kegiatan->status === 'draft')
                                        <form method="POST" action="{{ route('kegiatan.verify', $kegiatan) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded"
                                                    onclick="return confirm('Yakin ingin memverifikasi kegiatan ini?')">
                                                Verifikasi Kegiatan
                                            </button>
                                        </form>
                                    @endif
                                @endcan

                                @can('approve', $kegiatan)
                                    @if($kegiatan->status === 'verifikasi')
                                        <form method="POST" action="{{ route('kegiatan.approve', $kegiatan) }}">
                                            @csrf
                                            <button type="submit" 
                                                    class="w-full bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded"
                                                    onclick="return confirm('Yakin ingin menyetujui kegiatan ini?')">
                                                Setujui Kegiatan
                                            </button>
                                        </form>

                                        <button type="button" 
                                                class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
                                                onclick="showRejectModal()">
                                            Tolak Kegiatan
                                        </button>
                                    @endif
                                @endcan

                                @if($kegiatan->canBeRealized())
                                    <a href="#" 
                                       class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded text-center block"
                                       onclick="alert('Fitur Realisasi sedang dalam pengembangan')">
                                        Kelola Realisasi
                                    </a>
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
    </script>
</x-app-layout>
