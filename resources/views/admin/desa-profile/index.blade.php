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
                <form method="POST" action="{{ route('admin.desa-profile.update-profile') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Dasar</h3>

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
                        </div>
                    </div>

                    <!-- Kepala Desa Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Kepala Desa</h3>

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

                    <!-- Contact Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Kontak</h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
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

                                <div class="md:col-span-2">
                                    <label for="website" class="village-label">Website</label>
                                    <input type="url" name="website" id="website"
                                           value="{{ old('website', $desaProfile->website) }}"
                                           class="village-input block w-full">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Logo Upload -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Logo Desa</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label for="logo_desa" class="village-label">Logo Desa</label>
                                    @if($desaProfile->logo_desa)
                                        <div class="mb-2">
                                            <img src="{{ $desaProfile->logo_desa_url }}" alt="Logo Desa" class="h-16 w-16 object-contain">
                                        </div>
                                    @endif
                                    <input type="file" name="logo_desa" id="logo_desa"
                                           accept="image/*"
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

                    <!-- Submit Button -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex justify-end space-x-3">
                                <button type="submit" class="village-button-primary">
                                    Simpan Profil Desa
                                </button>
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
</x-app-layout>
