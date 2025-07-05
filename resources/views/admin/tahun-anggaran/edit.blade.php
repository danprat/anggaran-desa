<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Edit Tahun Anggaran - {{ $tahunAnggaran->tahun }}
            </h2>
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
    </x-slot>

    <div class="py-6">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-6">Form Edit Tahun Anggaran</h3>
                    
                    <form method="POST" action="{{ route('admin.tahun-anggaran.update', $tahunAnggaran) }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="space-y-6">
                            <!-- Tahun -->
                            <div>
                                <x-input-label for="tahun" :value="__('Tahun')" />
                                <x-text-input 
                                    id="tahun" 
                                    name="tahun" 
                                    type="number" 
                                    class="mt-1 block w-full" 
                                    :value="old('tahun', $tahunAnggaran->tahun)" 
                                    required 
                                    min="2020" 
                                    max="2050"
                                    placeholder="Contoh: 2025"
                                />
                                <x-input-error class="mt-2" :messages="$errors->get('tahun')" />
                                <p class="mt-1 text-sm text-gray-500">Masukkan tahun anggaran (2020-2050)</p>
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Status')" />
                                <select 
                                    id="status" 
                                    name="status" 
                                    class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                    required
                                >
                                    <option value="">Pilih Status</option>
                                    <option value="aktif" {{ old('status', $tahunAnggaran->status) == 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="nonaktif" {{ old('status', $tahunAnggaran->status) == 'nonaktif' ? 'selected' : '' }}>Non-aktif</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('status')" />
                                <p class="mt-1 text-sm text-gray-500">
                                    <strong>Catatan:</strong> Jika memilih "Aktif", tahun anggaran lain akan otomatis menjadi non-aktif.
                                </p>
                            </div>

                            <!-- Info Kegiatan -->
                            @if($tahunAnggaran->kegiatan_count > 0)
                                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                                    <div class="flex">
                                        <div class="flex-shrink-0">
                                            <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <div class="ml-3">
                                            <h3 class="text-sm font-medium text-blue-800">Informasi</h3>
                                            <div class="mt-2 text-sm text-blue-700">
                                                <p>Tahun anggaran ini memiliki {{ $tahunAnggaran->kegiatan_count }} kegiatan terkait.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center justify-end mt-6 space-x-3">
                            <x-action-button 
                                href="{{ route('admin.tahun-anggaran.index') }}" 
                                icon="x" 
                                variant="secondary" 
                                size="md"
                                tooltip="Batal"
                            >
                                Batal
                            </x-action-button>
                            
                            <x-action-button 
                                type="button" 
                                icon="check" 
                                variant="primary" 
                                size="md"
                                tooltip="Update Tahun Anggaran"
                                onclick="this.closest('form').submit();"
                            >
                                Update
                            </x-action-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
