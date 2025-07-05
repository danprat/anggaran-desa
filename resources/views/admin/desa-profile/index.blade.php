<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Profil Desa
                </h2>
                <p class="text-gray-600 text-sm mt-1">Kelola informasi profil desa, logo, dan data administratif</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="desa-profile-container overflow-x-hidden">

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

            @if($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @can('manage-desa-profile')
                <!-- Tab Navigation -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6 w-full">
                    <div class="border-b border-gray-200">
                        <nav class="desa-profile-tab-nav -mb-px flex space-x-2 sm:space-x-8 px-4 sm:px-6 overflow-x-auto" aria-label="Tabs">
                            <button type="button" onclick="showTab('basic')"
                                    class="tab-button active border-blue-500 text-blue-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-basic">
                                📋 Informasi Dasar
                            </button>
                            <button type="button" onclick="showTab('leadership')"
                                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-leadership">
                                👤 Kepemimpinan
                            </button>
                            <button type="button" onclick="showTab('content')"
                                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-content">
                                📝 Visi & Misi
                            </button>
                            <button type="button" onclick="showTab('demographics')"
                                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-demographics">
                                📊 Data Demografis
                            </button>
                            <button type="button" onclick="showTab('geography')"
                                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-geography">
                                🗺️ Geografis
                            </button>
                            <button type="button" onclick="showTab('media')"
                                    class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm"
                                    id="tab-media">
                                🏛️ Logo Desa
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.desa-profile.update-profile') }}" enctype="multipart/form-data" class="desa-profile-form-section space-y-6 w-full max-w-4xl mx-auto">
                    @csrf

                    <!-- Tab Content: Basic Information -->
                    <div id="content-basic" class="tab-content">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-blue-600 text-xl">📋</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Informasi Dasar</h3>
                                        <p class="text-sm text-gray-600">Data dasar identitas desa dan kontak</p>
                                    </div>
                                </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="nama_desa" class="village-label">Nama Desa *</label>
                                    <input type="text" name="nama_desa" id="nama_desa"
                                           value="{{ old('nama_desa', $desaProfile->nama_desa) }}"
                                           class="village-input block w-full"
                                           required>
                                </div>

                                <div>
                                    <label for="kecamatan" class="village-label">Kecamatan *</label>
                                    <input type="text" name="kecamatan" id="kecamatan"
                                           value="{{ old('kecamatan', $desaProfile->kecamatan) }}"
                                           class="village-input block w-full"
                                           required>
                                </div>

                                <div>
                                    <label for="kabupaten" class="village-label">Kabupaten *</label>
                                    <input type="text" name="kabupaten" id="kabupaten"
                                           value="{{ old('kabupaten', $desaProfile->kabupaten) }}"
                                           class="village-input block w-full"
                                           required>
                                </div>

                                <div>
                                    <label for="provinsi" class="village-label">Provinsi *</label>
                                    <input type="text" name="provinsi" id="provinsi"
                                           value="{{ old('provinsi', $desaProfile->provinsi) }}"
                                           class="village-input block w-full"
                                           required>
                                </div>

                                <div>
                                    <label for="kode_pos" class="village-label">Kode Pos</label>
                                    <input type="text" name="kode_pos" id="kode_pos"
                                           value="{{ old('kode_pos', $desaProfile->kode_pos) }}"
                                           class="village-input block w-full">
                                </div>
                            </div>

                            <div class="mt-6">
                                <label for="alamat_lengkap" class="village-label">Alamat Lengkap *</label>
                                <textarea name="alamat_lengkap" id="alamat_lengkap" rows="3"
                                          class="village-input block w-full"
                                          required>{{ old('alamat_lengkap', $desaProfile->alamat_lengkap) }}</textarea>
                            </div>

                            <div class="mt-6">
                                <h4 class="text-md font-medium text-gray-900 mb-4">Informasi Kontak</h4>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="telepon" class="village-label">Telepon</label>
                                        <input type="text" name="telepon" id="telepon"
                                               value="{{ old('telepon', $desaProfile->telepon) }}"
                                               class="village-input block w-full">
                                    </div>

                                    <div>
                                        <label for="email" class="village-label">Email</label>
                                        <input type="email" name="email" id="email"
                                               value="{{ old('email', $desaProfile->email) }}"
                                               class="village-input block w-full">
                                    </div>

                                    <div>
                                        <label for="website" class="village-label">Website</label>
                                        <input type="url" name="website" id="website"
                                               value="{{ old('website', $desaProfile->website) }}"
                                               class="village-input block w-full">
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button for Basic Information -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex justify-end desa-profile-buttons">
                                    <button type="button" onclick="resetSection('basic')" class="village-button-secondary">
                                        Reset Section
                                    </button>
                                    <button type="submit" name="section" value="basic" class="village-button-primary">
                                        💾 Simpan Informasi Dasar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </div>

                    <!-- Tab Content: Leadership -->
                    <div id="content-leadership" class="tab-content hidden">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-green-600 text-xl">👤</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Informasi Kepemimpinan</h3>
                                        <p class="text-sm text-gray-600">Data kepala desa dan periode jabatan</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="kepala_desa" class="village-label">Nama Kepala Desa *</label>
                                        <input type="text" name="kepala_desa" id="kepala_desa"
                                               value="{{ old('kepala_desa', $desaProfile->kepala_desa) }}"
                                               class="village-input block w-full"
                                               required>
                                    </div>

                                    <div>
                                        <label for="nip_kepala_desa" class="village-label">NIP Kepala Desa</label>
                                        <input type="text" name="nip_kepala_desa" id="nip_kepala_desa"
                                               value="{{ old('nip_kepala_desa', $desaProfile->nip_kepala_desa) }}"
                                               class="village-input block w-full">
                                    </div>

                                    <div>
                                        <label for="periode_jabatan_mulai" class="village-label">Periode Jabatan Mulai</label>
                                        <input type="date" name="periode_jabatan_mulai" id="periode_jabatan_mulai"
                                               value="{{ old('periode_jabatan_mulai', $desaProfile->periode_jabatan_mulai?->format('Y-m-d')) }}"
                                               class="village-input block w-full">
                                    </div>

                                    <div>
                                        <label for="periode_jabatan_selesai" class="village-label">Periode Jabatan Selesai</label>
                                        <input type="date" name="periode_jabatan_selesai" id="periode_jabatan_selesai"
                                               value="{{ old('periode_jabatan_selesai', $desaProfile->periode_jabatan_selesai?->format('Y-m-d')) }}"
                                               class="village-input block w-full">
                                    </div>
                                </div>

                                <!-- Save Button for Leadership -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end desa-profile-buttons">
                                        <button type="button" onclick="resetSection('leadership')" class="village-button-secondary">
                                            Reset Section
                                        </button>
                                        <button type="submit" name="section" value="leadership" class="village-button-primary">
                                            💾 Simpan Informasi Kepemimpinan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Content (Visi, Misi, Sejarah) -->
                    <div id="content-content" class="tab-content hidden">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-purple-600 text-xl">📝</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Visi, Misi, dan Sejarah</h3>
                                        <p class="text-sm text-gray-600">Konten deskriptif tentang desa</p>
                                    </div>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label for="visi" class="village-label">Visi Desa</label>
                                        <textarea name="visi" id="visi" rows="3"
                                                  class="village-input block w-full"
                                                  placeholder="Masukkan visi desa...">{{ old('visi', $desaProfile->visi) }}</textarea>
                                    </div>

                                    <div>
                                        <label for="misi" class="village-label">Misi Desa</label>
                                        <textarea name="misi" id="misi" rows="5"
                                                  class="village-input block w-full"
                                                  placeholder="Masukkan misi desa...">{{ old('misi', $desaProfile->misi) }}</textarea>
                                    </div>

                                    <div>
                                        <label for="sejarah_singkat" class="village-label">Sejarah Singkat</label>
                                        <textarea name="sejarah_singkat" id="sejarah_singkat" rows="4"
                                                  class="village-input block w-full"
                                              placeholder="Masukkan sejarah singkat desa...">{{ old('sejarah_singkat', $desaProfile->sejarah_singkat) }}</textarea>
                                    </div>
                                </div>

                                <!-- Save Button for Content -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end desa-profile-buttons">
                                        <button type="button" onclick="resetSection('content')" class="village-button-secondary">
                                            Reset Section
                                        </button>
                                        <button type="submit" name="section" value="content" class="village-button-primary">
                                            💾 Simpan Visi, Misi & Sejarah
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Demographics -->
                    <div id="content-demographics" class="tab-content hidden">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-orange-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-orange-600 text-xl">📊</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Data Demografis</h3>
                                        <p class="text-sm text-gray-600">Statistik penduduk dan wilayah desa</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="luas_wilayah" class="village-label">Luas Wilayah (Ha)</label>
                                        <input type="number" name="luas_wilayah" id="luas_wilayah"
                                               value="{{ old('luas_wilayah', $desaProfile->luas_wilayah) }}"
                                               class="village-input block w-full"
                                               step="0.01" min="0"
                                               placeholder="0.00">
                                    </div>

                                    <div>
                                        <label for="jumlah_penduduk" class="village-label">Jumlah Penduduk</label>
                                        <input type="number" name="jumlah_penduduk" id="jumlah_penduduk"
                                               value="{{ old('jumlah_penduduk', $desaProfile->jumlah_penduduk) }}"
                                               class="village-input block w-full"
                                               min="0"
                                               placeholder="0">
                                    </div>

                                    <div>
                                        <label for="jumlah_kk" class="village-label">Jumlah Kepala Keluarga</label>
                                        <input type="number" name="jumlah_kk" id="jumlah_kk"
                                               value="{{ old('jumlah_kk', $desaProfile->jumlah_kk) }}"
                                               class="village-input block w-full"
                                               min="0"
                                               placeholder="0">
                                    </div>
                                </div>

                                <!-- Save Button for Demographics -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end desa-profile-buttons">
                                        <button type="button" onclick="resetSection('demographics')" class="village-button-secondary">
                                            Reset Section
                                        </button>
                                        <button type="submit" name="section" value="demographics" class="village-button-primary">
                                            💾 Simpan Data Demografis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Geography -->
                    <div id="content-geography" class="tab-content hidden">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-teal-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-teal-600 text-xl">🗺️</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Informasi Geografis</h3>
                                        <p class="text-sm text-gray-600">Batas wilayah dan informasi geografis</p>
                                    </div>
                                </div>

                                    <div>
                                        <label for="batas_utara" class="village-label">Batas Utara</label>
                                        <input type="text" name="batas_utara" id="batas_utara"
                                               value="{{ old('batas_utara', $desaProfile->batas_utara) }}"
                                               class="village-input block w-full"
                                               placeholder="Sebelah utara berbatasan dengan...">
                                    </div>

                                    <div>
                                        <label for="batas_selatan" class="village-label">Batas Selatan</label>
                                        <input type="text" name="batas_selatan" id="batas_selatan"
                                               value="{{ old('batas_selatan', $desaProfile->batas_selatan) }}"
                                               class="village-input block w-full"
                                               placeholder="Sebelah selatan berbatasan dengan...">
                                    </div>

                                    <div>
                                        <label for="batas_timur" class="village-label">Batas Timur</label>
                                        <input type="text" name="batas_timur" id="batas_timur"
                                               value="{{ old('batas_timur', $desaProfile->batas_timur) }}"
                                               class="village-input block w-full"
                                               placeholder="Sebelah timur berbatasan dengan...">
                                    </div>

                                    <div>
                                        <label for="batas_barat" class="village-label">Batas Barat</label>
                                        <input type="text" name="batas_barat" id="batas_barat"
                                               value="{{ old('batas_barat', $desaProfile->batas_barat) }}"
                                               class="village-input block w-full"
                                               placeholder="Sebelah barat berbatasan dengan...">
                                    </div>
                                </div>

                                <!-- Save Button for Geography -->
                                <div class="mt-6 pt-6 border-t border-gray-200">
                                    <div class="flex justify-end desa-profile-buttons">
                                        <button type="button" onclick="resetSection('geography')" class="village-button-secondary">
                                            Reset Section
                                        </button>
                                        <button type="submit" name="section" value="geography" class="village-button-primary">
                                            💾 Simpan Informasi Geografis
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Media -->
                    <div id="content-media" class="tab-content hidden">
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

                                <!-- Logo Desa Display -->
                                <div class="text-center mb-6">
                                    <x-desa-logo type="desa" size="xl" class="mx-auto mb-3" :profile="$desaProfile" />
                                    @if($desaProfile->logo_desa)
                                        <p class="text-sm text-green-600 font-medium">✓ Logo desa tersedia</p>
                                    @else
                                        <p class="text-sm text-gray-500">Belum ada logo desa</p>
                                    @endif
                                </div>

                                <div>
                                    <label for="logo_desa" class="village-label">Logo Desa Baru</label>
                                    <input type="file" name="logo_desa" id="logo_desa"
                                           accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                           class="village-input block w-full"
                                           onchange="previewImage(this, 'preview-logo-desa')">
                                    <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    <div id="preview-logo-desa" class="mt-3 text-center hidden">
                                        <img class="h-20 w-20 object-contain border border-gray-200 rounded mx-auto max-w-full" alt="Preview Logo Desa">
                                    </div>
                                </div>

                                <!-- Logo Tambahan (Collapsed) -->
                                <div class="mt-6">
                                    <button type="button" onclick="toggleAdditionalLogos()"
                                            class="flex items-center justify-between w-full text-left text-sm font-medium text-gray-700 hover:text-gray-900 hover:bg-gray-50 rounded-md p-2 -m-2 transition-colors">
                                        <span>Logo Tambahan (Kabupaten & Provinsi)</span>
                                        <svg id="additional-logos-icon" class="w-5 h-5 transform transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                        </svg>
                                    </button>

                                    <div id="additional-logos-content" class="hidden mt-4 space-y-6">
                                        <!-- Logo Kabupaten -->
                                        <div>
                                            <div class="text-center mb-3">
                                                <x-desa-logo type="kabupaten" size="lg" class="mx-auto mb-2" :profile="$desaProfile" />
                                                @if($desaProfile->logo_kabupaten)
                                                    <p class="text-xs text-green-600">✓ Logo tersedia</p>
                                                @else
                                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                                @endif
                                            </div>
                                            <label for="logo_kabupaten" class="village-label">Logo Kabupaten</label>
                                            <input type="file" name="logo_kabupaten" id="logo_kabupaten"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                                   class="village-input block w-full"
                                                   onchange="previewImage(this, 'preview-logo-kabupaten')">
                                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                            <div id="preview-logo-kabupaten" class="mt-3 text-center hidden">
                                                <img class="h-20 w-20 object-contain border border-gray-200 rounded mx-auto max-w-full" alt="Preview Logo Kabupaten">
                                            </div>
                                        </div>

                                        <!-- Logo Provinsi -->
                                        <div>
                                            <div class="text-center mb-3">
                                                <x-desa-logo type="provinsi" size="lg" class="mx-auto mb-2" :profile="$desaProfile" />
                                                @if($desaProfile->logo_provinsi)
                                                    <p class="text-xs text-green-600">✓ Logo tersedia</p>
                                                @else
                                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                                @endif
                                            </div>
                                            <label for="logo_provinsi" class="village-label">Logo Provinsi</label>
                                            <input type="file" name="logo_provinsi" id="logo_provinsi"
                                                   accept="image/jpeg,image/png,image/jpg,image/gif,image/svg+xml"
                                                   class="village-input block w-full"
                                                   onchange="previewImage(this, 'preview-logo-provinsi')">
                                            <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                            <div id="preview-logo-provinsi" class="mt-3 text-center hidden">
                                                <img class="h-20 w-20 object-contain border border-gray-200 rounded mx-auto max-w-full" alt="Preview Logo Provinsi">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Save Button for Media -->
                            <div class="mt-6 pt-6 border-t border-gray-200">
                                <div class="flex justify-end desa-profile-buttons">
                                    <button type="button" onclick="resetSection('media')" class="village-button-secondary">
                                        Reset Section
                                    </button>
                                    <button type="submit" name="section" value="media" class="village-button-primary">
                                        💾 Simpan Logo Desa
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </form>
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-center">
                        <p class="text-gray-500">Anda tidak memiliki izin untuk mengelola profil desa.</p>
                    </div>
                </div>
            @endcan

        </div>
    </div>

    <!-- Tab JavaScript -->
    <script>
        function showTab(tabName) {
            // Hide all tab contents
            const tabContents = document.querySelectorAll('.tab-content');
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });

            // Remove active class from all tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.classList.remove('active', 'border-blue-500', 'text-blue-600');
                button.classList.add('border-transparent', 'text-gray-500');
            });

            // Show selected tab content
            const selectedContent = document.getElementById('content-' + tabName);
            if (selectedContent) {
                selectedContent.classList.remove('hidden');
            }

            // Add active class to selected tab button
            const selectedButton = document.getElementById('tab-' + tabName);
            if (selectedButton) {
                selectedButton.classList.add('active', 'border-blue-500', 'text-blue-600');
                selectedButton.classList.remove('border-transparent', 'text-gray-500');
            }

            // Save current tab to localStorage
            localStorage.setItem('activeDesaProfileTab', tabName);
        }

        function resetForm() {
            if (confirm('Apakah Anda yakin ingin mereset form? Semua perubahan yang belum disimpan akan hilang.')) {
                document.querySelector('form').reset();
                // Show first tab after reset
                showTab('basic');
            }
        }

        // Initialize tabs on page load
        document.addEventListener('DOMContentLoaded', function() {
            // Get saved tab from localStorage or default to 'basic'
            const savedTab = localStorage.getItem('activeDesaProfileTab') || 'basic';
            showTab(savedTab);

            // Add click event listeners to tab buttons
            const tabButtons = document.querySelectorAll('.tab-button');
            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.id.replace('tab-', '');
                    showTab(tabName);
                });
            });

            // Add form validation before submit
            const form = document.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const requiredFields = form.querySelectorAll('[required]');
                    let hasErrors = false;

                    requiredFields.forEach(field => {
                        if (!field.value.trim()) {
                            hasErrors = true;
                            field.classList.add('border-red-500');

                            // Find which tab this field belongs to and show it
                            const tabContent = field.closest('.tab-content');
                            if (tabContent) {
                                const tabName = tabContent.id.replace('content-', '');
                                showTab(tabName);
                            }
                        } else {
                            field.classList.remove('border-red-500');
                        }
                    });

                    if (hasErrors) {
                        e.preventDefault();
                        alert('Mohon lengkapi semua field yang wajib diisi (ditandai dengan *)');
                    }
                });
            }
        });

        // Add keyboard navigation (Ctrl+Arrow keys)
        document.addEventListener('keydown', function(e) {
            if (e.ctrlKey || e.metaKey) {
                const tabs = ['basic', 'leadership', 'content', 'demographics', 'geography', 'media'];
                const currentTab = localStorage.getItem('activeDesaProfileTab') || 'basic';
                const currentIndex = tabs.indexOf(currentTab);

                if (e.key === 'ArrowLeft' && currentIndex > 0) {
                    e.preventDefault();
                    showTab(tabs[currentIndex - 1]);
                } else if (e.key === 'ArrowRight' && currentIndex < tabs.length - 1) {
                    e.preventDefault();
                    showTab(tabs[currentIndex + 1]);
                }
            }
        });

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

        // Reset specific section function
        function resetSection(sectionName) {
            if (confirm('Apakah Anda yakin ingin mereset section ini? Semua perubahan yang belum disimpan akan hilang.')) {
                const sectionSelectors = {
                    'basic': '#content-basic input, #content-basic textarea',
                    'leadership': '#content-leadership input, #content-leadership textarea',
                    'content': '#content-content input, #content-content textarea',
                    'demographics': '#content-demographics input, #content-demographics textarea',
                    'geography': '#content-geography input, #content-geography textarea',
                    'media': '#content-media input[type="file"]'
                };

                const selector = sectionSelectors[sectionName];
                if (selector) {
                    const elements = document.querySelectorAll(selector);
                    elements.forEach(element => {
                        if (element.type === 'file') {
                            element.value = '';
                        } else if (element.tagName === 'TEXTAREA') {
                            element.value = element.defaultValue || '';
                        } else {
                            element.value = element.defaultValue || '';
                        }
                    });

                    // Show success message
                    const sectionNames = {
                        'basic': 'Informasi Dasar',
                        'leadership': 'Kepemimpinan',
                        'content': 'Visi, Misi & Sejarah',
                        'demographics': 'Data Demografis',
                        'geography': 'Geografis',
                        'media': 'Logo & Media'
                    };

                    alert(`Section ${sectionNames[sectionName]} telah direset.`);
                }
            }
        }

        // Image preview function
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
    </script>
</x-app-layout>
