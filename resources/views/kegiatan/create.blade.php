<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Tambah Kegiatan Baru
            </h2>
            <a href="{{ route('kegiatan.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
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

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('kegiatan.store') }}">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kegiatan -->
                            <div class="md:col-span-2">
                                <label for="nama_kegiatan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Nama Kegiatan <span class="text-red-500">*</span>
                                </label>
                                <input type="text" 
                                       id="nama_kegiatan" 
                                       name="nama_kegiatan" 
                                       value="{{ old('nama_kegiatan') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="Masukkan nama kegiatan">
                            </div>

                            <!-- Bidang -->
                            <div>
                                <label for="bidang" class="block text-sm font-medium text-gray-700 mb-1">
                                    Bidang <span class="text-red-500">*</span>
                                </label>
                                <select id="bidang" 
                                        name="bidang" 
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Bidang</option>
                                    @foreach($bidangOptions as $bidang)
                                        <option value="{{ $bidang }}" {{ old('bidang') === $bidang ? 'selected' : '' }}>
                                            {{ $bidang }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Sumber Dana -->
                            <div>
                                <label for="sumber_dana" class="block text-sm font-medium text-gray-700 mb-1">
                                    Sumber Dana <span class="text-red-500">*</span>
                                </label>
                                <select id="sumber_dana" 
                                        name="sumber_dana" 
                                        required
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    <option value="">Pilih Sumber Dana</option>
                                    @foreach($sumberDanaOptions as $sumber)
                                        <option value="{{ $sumber }}" {{ old('sumber_dana') === $sumber ? 'selected' : '' }}>
                                            {{ $sumber }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Pagu Anggaran -->
                            <div>
                                <label for="pagu_anggaran" class="block text-sm font-medium text-gray-700 mb-1">
                                    Pagu Anggaran (Rp) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" 
                                       id="pagu_anggaran" 
                                       name="pagu_anggaran" 
                                       value="{{ old('pagu_anggaran') }}"
                                       min="0"
                                       step="1000"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="0">
                            </div>

                            <!-- Waktu Mulai -->
                            <div>
                                <label for="waktu_mulai" class="block text-sm font-medium text-gray-700 mb-1">
                                    Waktu Mulai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="waktu_mulai" 
                                       name="waktu_mulai" 
                                       value="{{ old('waktu_mulai') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Waktu Selesai -->
                            <div>
                                <label for="waktu_selesai" class="block text-sm font-medium text-gray-700 mb-1">
                                    Waktu Selesai <span class="text-red-500">*</span>
                                </label>
                                <input type="date" 
                                       id="waktu_selesai" 
                                       name="waktu_selesai" 
                                       value="{{ old('waktu_selesai') }}"
                                       required
                                       class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <!-- Keterangan -->
                            <div class="md:col-span-2">
                                <label for="keterangan" class="block text-sm font-medium text-gray-700 mb-1">
                                    Keterangan
                                </label>
                                <textarea id="keterangan" 
                                          name="keterangan" 
                                          rows="4"
                                          class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Keterangan tambahan (opsional)">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>

                        <!-- Info Box -->
                        <div class="mt-6 bg-blue-50 border border-blue-200 rounded-md p-4">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                                    <div class="mt-2 text-sm text-blue-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Kegiatan akan disimpan dengan status "Draft"</li>
                                            <li>Kegiatan perlu diverifikasi oleh Sekretaris sebelum dapat disetujui</li>
                                            <li>Setelah disetujui, kegiatan dapat direalisasikan oleh Bendahara</li>
                                            <li>Tahun anggaran aktif: <strong>{{ $tahunAktif->tahun }}</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Submit Buttons -->
                        <div class="mt-6 flex justify-end space-x-3">
                            <a href="{{ route('kegiatan.index') }}" 
                               class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded">
                                Batal
                            </a>
                            <button type="submit" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Simpan Kegiatan
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        // Auto-format number input
        document.getElementById('pagu_anggaran').addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            e.target.value = value;
        });

        // Validate date range
        document.getElementById('waktu_mulai').addEventListener('change', function() {
            const waktuMulai = this.value;
            const waktuSelesaiInput = document.getElementById('waktu_selesai');
            waktuSelesaiInput.min = waktuMulai;
            
            if (waktuSelesaiInput.value && waktuSelesaiInput.value < waktuMulai) {
                waktuSelesaiInput.value = waktuMulai;
            }
        });
    </script>
</x-app-layout>
