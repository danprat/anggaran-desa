<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Edit Profil Desa: {{ $desaProfile->nama_desa }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">Update informasi profil desa</p>
            </div>
            <div class="flex space-x-2">
                <x-action-button
                    href="{{ route('admin.desa-profile.show', $desaProfile->id) }}"
                    icon="eye"
                    variant="info"
                    size="md"
                    tooltip="Lihat Detail"
                >
                    Lihat Detail
                </x-action-button>
                <x-action-button 
                    href="{{ route('admin.desa-profile.index') }}" 
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

            <form method="POST" action="{{ route('admin.desa-profile.update', $desaProfile->id) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Dasar</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nama_desa" class="block text-sm font-medium text-gray-700">Nama Desa *</label>
                                <input type="text" name="nama_desa" id="nama_desa" 
                                       value="{{ old('nama_desa', $desaProfile->nama_desa) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                            </div>

                            <div>
                                <label for="kecamatan" class="block text-sm font-medium text-gray-700">Kecamatan *</label>
                                <input type="text" name="kecamatan" id="kecamatan" 
                                       value="{{ old('kecamatan', $desaProfile->kecamatan) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                            </div>

                            <div>
                                <label for="kabupaten" class="block text-sm font-medium text-gray-700">Kabupaten *</label>
                                <input type="text" name="kabupaten" id="kabupaten" 
                                       value="{{ old('kabupaten', $desaProfile->kabupaten) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                            </div>

                            <div>
                                <label for="provinsi" class="block text-sm font-medium text-gray-700">Provinsi *</label>
                                <input type="text" name="provinsi" id="provinsi" 
                                       value="{{ old('provinsi', $desaProfile->provinsi) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                            </div>

                            <div>
                                <label for="kode_pos" class="block text-sm font-medium text-gray-700">Kode Pos</label>
                                <input type="text" name="kode_pos" id="kode_pos" 
                                       value="{{ old('kode_pos', $desaProfile->kode_pos) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div class="md:col-span-2">
                                <label for="alamat_lengkap" class="block text-sm font-medium text-gray-700">Alamat Lengkap *</label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3" 
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                          required>{{ old('alamat_lengkap', $desaProfile->alamat_lengkap) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Kepala Desa Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Kepala Desa</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="kepala_desa" class="block text-sm font-medium text-gray-700">Nama Kepala Desa *</label>
                                <input type="text" name="kepala_desa" id="kepala_desa" 
                                       value="{{ old('kepala_desa', $desaProfile->kepala_desa) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                       required>
                            </div>

                            <div>
                                <label for="nip_kepala_desa" class="block text-sm font-medium text-gray-700">NIP Kepala Desa</label>
                                <input type="text" name="nip_kepala_desa" id="nip_kepala_desa" 
                                       value="{{ old('nip_kepala_desa', $desaProfile->nip_kepala_desa) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="periode_jabatan_mulai" class="block text-sm font-medium text-gray-700">Periode Jabatan Mulai</label>
                                <input type="date" name="periode_jabatan_mulai" id="periode_jabatan_mulai" 
                                       value="{{ old('periode_jabatan_mulai', $desaProfile->periode_jabatan_mulai?->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>

                            <div>
                                <label for="periode_jabatan_selesai" class="block text-sm font-medium text-gray-700">Periode Jabatan Selesai</label>
                                <input type="date" name="periode_jabatan_selesai" id="periode_jabatan_selesai" 
                                       value="{{ old('periode_jabatan_selesai', $desaProfile->periode_jabatan_selesai?->format('Y-m-d')) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Current Logo Display - Fokus pada Logo Desa -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center mb-6">
                            <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                <span class="text-indigo-600 text-xl">🏛️</span>
                            </div>
                            <div>
                                <h3 class="text-lg font-medium text-gray-900">Logo Desa</h3>
                                <p class="text-sm text-gray-600">Logo utama yang mewakili identitas desa</p>
                            </div>
                        </div>

                        <!-- Logo Desa Utama -->
                        <div class="text-center mb-8">
                            <label class="village-label">Logo Desa Saat Ini</label>
                            <div class="flex justify-center mb-4">
                                <x-desa-logo type="desa" size="xl" class="mx-auto" :profile="$desaProfile" />
                            </div>
                            @if($desaProfile->logo_desa)
                                <p class="text-sm text-green-600 font-medium">✓ Logo desa tersedia</p>
                            @else
                                <p class="text-sm text-gray-500">Belum ada logo desa</p>
                            @endif
                        </div>

                        <!-- Upload Logo Desa Baru -->
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Upload Logo Desa Baru (Opsional)</h4>

                            <div class="max-w-md mx-auto">
                                <label for="logo_desa" class="village-label">Logo Desa Baru</label>
                                <input type="file" name="logo_desa" id="logo_desa"
                                       class="village-input block w-full"
                                       accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                       onchange="previewImage(this, 'preview-logo-desa')">
                                <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                <div id="preview-logo-desa" class="mt-4 text-center hidden">
                                    <img class="h-20 w-20 object-contain border border-gray-200 rounded mx-auto" alt="Preview Logo Desa">
                                </div>
                            </div>
                        </div>

                        <!-- Logo Tambahan (Collapsed) -->
                        <div class="border-t border-gray-200 pt-6 mt-6">
                            <button type="button" onclick="toggleAdditionalLogos()"
                                    class="flex items-center justify-between w-full text-left text-sm font-medium text-gray-700 hover:text-gray-900">
                                <span>Logo Tambahan (Kabupaten & Provinsi)</span>
                                <svg id="additional-logos-icon" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>

                            <div id="additional-logos-content" class="hidden mt-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Logo Kabupaten -->
                                    <div class="text-center">
                                        <label class="village-label">Logo Kabupaten</label>
                                        <div class="flex justify-center mb-2">
                                            <x-desa-logo type="kabupaten" size="lg" class="mx-auto" :profile="$desaProfile" />
                                        </div>
                                        @if($desaProfile->logo_kabupaten)
                                            <p class="text-xs text-green-600 mb-3">✓ Logo tersedia</p>
                                        @else
                                            <p class="text-xs text-gray-500 mb-3">Belum ada logo</p>
                                        @endif
                                        <input type="file" name="logo_kabupaten" id="logo_kabupaten"
                                               class="village-input block w-full text-sm"
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    </div>

                                    <!-- Logo Provinsi -->
                                    <div class="text-center">
                                        <label class="village-label">Logo Provinsi</label>
                                        <div class="flex justify-center mb-2">
                                            <x-desa-logo type="provinsi" size="lg" class="mx-auto" :profile="$desaProfile" />
                                        </div>
                                        @if($desaProfile->logo_provinsi)
                                            <p class="text-xs text-green-600 mb-3">✓ Logo tersedia</p>
                                        @else
                                            <p class="text-xs text-gray-500 mb-3">Belum ada logo</p>
                                        @endif
                                        <input type="file" name="logo_provinsi" id="logo_provinsi"
                                               class="village-input block w-full text-sm"
                                               accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml">
                                        <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <!-- Contact Information -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Kontak</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="website" class="block text-sm font-medium text-gray-700">Website</label>
                                <input type="url" name="website" id="website" 
                                       value="{{ old('website', $desaProfile->website) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="https://example.com">
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                                <input type="email" name="email" id="email" 
                                       value="{{ old('email', $desaProfile->email) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="admin@desa.go.id">
                            </div>

                            <div>
                                <label for="telepon" class="block text-sm font-medium text-gray-700">Telepon</label>
                                <input type="text" name="telepon" id="telepon" 
                                       value="{{ old('telepon', $desaProfile->telepon) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="(021) 1234567">
                            </div>

                            <div>
                                <label for="fax" class="block text-sm font-medium text-gray-700">Fax</label>
                                <input type="text" name="fax" id="fax" 
                                       value="{{ old('fax', $desaProfile->fax) }}" 
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                       placeholder="(021) 1234567">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Visi, Misi, dan Sejarah -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Visi, Misi, dan Sejarah</h3>

                        <div class="space-y-6">
                            <div>
                                <label for="visi" class="block text-sm font-medium text-gray-700">Visi</label>
                                <textarea name="visi" id="visi" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Masukkan visi desa...">{{ old('visi', $desaProfile->visi) }}</textarea>
                            </div>

                            <div>
                                <label for="misi" class="block text-sm font-medium text-gray-700">Misi</label>
                                <textarea name="misi" id="misi" rows="4"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Masukkan misi desa...">{{ old('misi', $desaProfile->misi) }}</textarea>
                            </div>

                            <div>
                                <label for="sejarah_singkat" class="block text-sm font-medium text-gray-700">Sejarah Singkat</label>
                                <textarea name="sejarah_singkat" id="sejarah_singkat" rows="5"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                          placeholder="Masukkan sejarah singkat desa...">{{ old('sejarah_singkat', $desaProfile->sejarah_singkat) }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Status -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Status</h3>
                        
                        <div class="flex items-center">
                            <input type="checkbox" name="is_active" id="is_active" value="1" 
                                   {{ old('is_active', $desaProfile->is_active) ? 'checked' : '' }}
                                   class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded">
                            <label for="is_active" class="ml-2 block text-sm text-gray-900">
                                Aktifkan profil ini sebagai profil utama
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">
                            Jika diaktifkan, profil ini akan menjadi profil utama yang ditampilkan di seluruh sistem.
                        </p>
                    </div>
                </div>

                <!-- Submit Buttons -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex justify-end space-x-3">
                            <x-action-button 
                                href="{{ route('admin.desa-profile.index') }}" 
                                icon="x" 
                                variant="secondary" 
                                size="md"
                            >
                                Batal
                            </x-action-button>
                            
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 h-10 px-4 text-base min-w-10 bg-green-600 text-white hover:bg-green-700 focus:ring-green-500"
                            >
                                <x-icon name="check" class="w-5 h-5" />
                                <span class="ml-2 whitespace-nowrap">Update Profil</span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- JavaScript untuk Toggle dan Preview -->
    <script>
        // Toggle Additional Logos
        function toggleAdditionalLogos() {
            const content = document.getElementById('additional-logos-content');
            const icon = document.getElementById('additional-logos-icon');

            if (content.classList.contains('hidden')) {
                content.classList.remove('hidden');
                icon.style.transform = 'rotate(180deg)';
            } else {
                content.classList.add('hidden');
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Preview Image Function
        function previewImage(input, previewId) {
            const preview = document.getElementById(previewId);
            const img = preview.querySelector('img');

            if (input.files && input.files[0]) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    img.src = e.target.result;
                    preview.classList.remove('hidden');
                };

                reader.readAsDataURL(input.files[0]);
            } else {
                preview.classList.add('hidden');
            }
        }

        // Auto-hide success messages
        document.addEventListener('DOMContentLoaded', function() {
            const successMessages = document.querySelectorAll('.alert-success');
            successMessages.forEach(function(message) {
                setTimeout(function() {
                    message.style.transition = 'opacity 0.5s ease-out';
                    message.style.opacity = '0';
                    setTimeout(function() {
                        message.remove();
                    }, 500);
                }, 5000);
            });
        });
    </script>

</x-app-layout>
