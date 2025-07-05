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
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

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
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="border-b border-gray-200">
                        <nav class="-mb-px flex space-x-8 px-6" aria-label="Tabs">
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
                                🖼️ Logo & Media
                            </button>
                        </nav>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.desa-profile.update-profile') }}" enctype="multipart/form-data" class="space-y-6">
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
                            </div>
                        </div>
                    </div>

                    <!-- Tab Content: Media -->
                    <div id="content-media" class="tab-content hidden">
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex items-center mb-6">
                                    <div class="w-10 h-10 bg-indigo-100 rounded-lg flex items-center justify-center mr-4">
                                        <span class="text-indigo-600 text-xl">🖼️</span>
                                    </div>
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900">Logo & Media</h3>
                                        <p class="text-sm text-gray-600">Upload logo desa, kabupaten, dan provinsi</p>
                                    </div>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                    <div>
                                        <label for="logo_desa" class="village-label">Logo Desa</label>
                                        @if($desaProfile->logo_desa)
                                            <div class="mb-2">
                                                <img src="{{ $desaProfile->logo_desa_url }}" alt="Logo Desa" class="h-16 w-16 object-contain">
                                            </div>
                                        @endif
                                               class="village-input block w-full">
                                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    </div>

                                    <div>
                                        <label for="logo_kabupaten" class="village-label">Logo Kabupaten</label>
                                        @if($desaProfile->logo_kabupaten)
                                            <div class="mb-2">
                                                <img src="{{ $desaProfile->logo_kabupaten_url }}" alt="Logo Kabupaten" class="h-16 w-16 object-contain">
                                            </div>
                                        @endif
                                        <input type="file" name="logo_kabupaten" id="logo_kabupaten"
                                               accept="image/*"
                                               class="village-input block w-full">
                                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    </div>

                                    <div>
                                        <label for="logo_provinsi" class="village-label">Logo Provinsi</label>
                                        @if($desaProfile->logo_provinsi)
                                            <div class="mb-2">
                                                <img src="{{ $desaProfile->logo_provinsi_url }}" alt="Logo Provinsi" class="h-16 w-16 object-contain">
                                            </div>
                                        @endif
                                        <input type="file" name="logo_provinsi" id="logo_provinsi"
                                               accept="image/*"
                                               class="village-input block w-full">
                                        <p class="text-sm text-gray-500 mt-1">Format: JPG, PNG, SVG. Max: 2MB</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button (Fixed at bottom) -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg sticky bottom-0 z-10 border-t-2 border-blue-200">
                        <div class="p-6">
                            <div class="flex justify-between items-center">
                                <div class="text-sm text-gray-600">
                                    <span class="font-medium">💡 Tips:</span> Gunakan tab di atas untuk navigasi yang mudah
                                </div>
                                <div class="flex space-x-3">
                                    <button type="button" onclick="resetForm()" class="village-button-secondary">
                                        Reset Form
                                    </button>
                                    <button type="submit" class="village-button-primary">
                                        💾 Simpan Profil Desa
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
    </script>
</x-app-layout>
