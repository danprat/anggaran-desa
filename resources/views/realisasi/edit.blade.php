<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Realisasi: {{ $realisasi->kegiatan->nama_kegiatan }}
            </h2>
            <a href="{{ route('realisasi.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                Kembali
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Current Info -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">Status Realisasi</h3>
                            <p class="text-sm text-gray-600">Tahun Anggaran: {{ $realisasi->kegiatan->tahunAnggaran->tahun }}</p>
                        </div>
                        <span class="px-3 py-1 text-sm font-medium rounded-full
                            @if($realisasi->status === 'belum') bg-gray-100 text-gray-800
                            @elseif($realisasi->status === 'sebagian') bg-yellow-100 text-yellow-800
                            @else bg-green-100 text-green-800
                            @endif">
                            {{ ucfirst($realisasi->status) }}
                        </span>
                    </div>
                </div>
            </div>

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('realisasi.update', $realisasi) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Kegiatan -->
                            <div class="md:col-span-2">
                                <label for="kegiatan_id" class="block text-sm font-medium text-gray-700 mb-1">
                                    Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <select id="kegiatan_id" 
                                        name="kegiatan_id" 
                                        required
                                        onchange="updateKegiatanInfo()"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Kegiatan</option>
                                    @foreach($kegiatanList as $kegiatan)
                                        <option value="{{ $kegiatan->id }}" 
                                                data-pagu="{{ $kegiatan->pagu_anggaran }}"
                                                data-realisasi="{{ $kegiatan->realisasi()->where('id', '!=', $realisasi->id)->sum('jumlah_realisasi') }}"
                                                data-sisa="{{ $kegiatan->pagu_anggaran - $kegiatan->realisasi()->where('id', '!=', $realisasi->id)->sum('jumlah_realisasi') }}"
                                                {{ old('kegiatan_id', $realisasi->kegiatan_id) == $kegiatan->id ? 'selected' : '' }}>
                                            {{ $kegiatan->nama_kegiatan }} - {{ $kegiatan->bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Info Kegiatan -->
                            <div id="kegiatan-info" class="md:col-span-2">
                                <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                                    <h4 class="text-sm font-medium text-blue-800 mb-2">Informasi Kegiatan</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                                        <div>
                                            <span class="text-blue-600">Pagu Anggaran:</span>
                                            <div id="pagu-anggaran" class="font-medium text-blue-900"></div>
                                        </div>
                                        <div>
                                            <span class="text-blue-600">Realisasi Lain:</span>
                                            <div id="total-realisasi" class="font-medium text-blue-900"></div>
                                        </div>
                                        <div>
                                            <span class="text-blue-600">Sisa Anggaran:</span>
                                            <div id="sisa-anggaran" class="font-medium text-blue-900"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Jumlah Realisasi -->
                            <div>
                                <label for="jumlah_realisasi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Jumlah Realisasi (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="jumlah_realisasi" 
                                       name="jumlah_realisasi" 
                                       value="{{ old('jumlah_realisasi', $realisasi->jumlah_realisasi) }}"
                                       min="0"
                                       step="1000"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0">
                            </div>

                            <!-- Tanggal -->
                            <div>
                                <label for="tanggal" class="block text-sm font-medium text-gray-700 mb-1">
                                    Tanggal Realisasi <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="tanggal" 
                                       name="tanggal" 
                                       value="{{ old('tanggal', $realisasi->tanggal->format('Y-m-d')) }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Deskripsi -->
                            <div class="md:col-span-2">
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-1">
                                    Deskripsi Realisasi
                                </label>
                                <textarea id="deskripsi" 
                                          name="deskripsi" 
                                          rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Deskripsi detail realisasi anggaran...">{{ old('deskripsi', $realisasi->deskripsi) }}</textarea>
                            </div>

                            <!-- Upload Bukti Tambahan -->
                            <div class="md:col-span-2">
                                <label for="bukti_files" class="block text-sm font-medium text-gray-700 mb-1">
                                    Upload Bukti Tambahan (PDF, JPG, PNG - Max 5MB per file)
                                </label>
                                <input type="file" 
                                       id="bukti_files" 
                                       name="bukti_files[]" 
                                       multiple
                                       accept=".pdf,.jpg,.jpeg,.png"
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="mt-1 text-sm text-gray-500">
                                    File yang sudah ada akan tetap tersimpan. File baru akan ditambahkan.
                                </p>
                            </div>
                        </div>

                        <!-- Current Files -->
                        @if($realisasi->buktiFiles->count() > 0)
                            <div class="mt-6">
                                <h4 class="text-sm font-medium text-gray-700 mb-3">Bukti yang Sudah Ada ({{ $realisasi->buktiFiles->count() }} file)</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($realisasi->buktiFiles as $bukti)
                                        <div class="border border-gray-200 rounded-lg p-3">
                                            <div class="flex items-center justify-between">
                                                <div class="flex items-center flex-1">
                                                    @if($bukti->isImage())
                                                        <svg class="w-6 h-6 text-blue-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @elseif($bukti->isPdf())
                                                        <svg class="w-6 h-6 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @else
                                                        <svg class="w-6 h-6 text-gray-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                                            <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"></path>
                                                        </svg>
                                                    @endif
                                                    <div class="flex-1">
                                                        <p class="text-sm font-medium text-gray-900 truncate">{{ $bukti->file_name }}</p>
                                                        <p class="text-xs text-gray-500">{{ $bukti->getFileSizeFormatted() }}</p>
                                                    </div>
                                                </div>
                                                <div class="flex space-x-1 ml-2">
                                                    <a href="{{ $bukti->getFileUrl() }}" 
                                                       target="_blank"
                                                       class="text-blue-600 hover:text-blue-900 text-xs">
                                                        Lihat
                                                    </a>
                                                    <form method="POST" action="{{ route('bukti-file.delete', $bukti) }}" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" 
                                                                class="text-red-600 hover:text-red-900 text-xs"
                                                                onclick="return confirm('Yakin ingin menghapus file ini?')">
                                                            Hapus
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Info Box -->
                        <div class="mt-6 bg-yellow-50 border border-yellow-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-yellow-800">Perhatian</h3>
                                    <div class="mt-2 text-sm text-yellow-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Perubahan akan dicatat dalam log aktivitas</li>
                                            <li>Pastikan jumlah realisasi tidak melebihi sisa anggaran</li>
                                            <li>File bukti yang sudah ada akan tetap tersimpan kecuali dihapus manual</li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('realisasi.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update Realisasi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function updateKegiatanInfo() {
            const select = document.getElementById('kegiatan_id');
            const selectedOption = select.options[select.selectedIndex];

            if (selectedOption.value) {
                const pagu = parseInt(selectedOption.dataset.pagu);
                const realisasi = parseInt(selectedOption.dataset.realisasi);
                const sisa = parseInt(selectedOption.dataset.sisa);

                document.getElementById('pagu-anggaran').textContent = 'Rp ' + pagu.toLocaleString('id-ID');
                document.getElementById('total-realisasi').textContent = 'Rp ' + realisasi.toLocaleString('id-ID');
                document.getElementById('sisa-anggaran').textContent = 'Rp ' + sisa.toLocaleString('id-ID');

                // Update max value for jumlah_realisasi
                document.getElementById('jumlah_realisasi').max = sisa;
            }
        }

        // Auto-format number input
        document.getElementById('jumlah_realisasi').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });

        // Initialize
        updateKegiatanInfo();
    </script>
</x-app-layout>
