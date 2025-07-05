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
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
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

                <!-- Current Logos Display -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-6">Logo Saat Ini</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Desa</label>
                                <x-desa-logo type="desa" size="xl" class="mx-auto mb-2" :profile="$desaProfile" />
                                @if($desaProfile->logo_desa)
                                    <p class="text-xs text-green-600">✓ Logo tersedia</p>
                                @else
                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                @endif
                            </div>

                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Kabupaten</label>
                                <x-desa-logo type="kabupaten" size="xl" class="mx-auto mb-2" :profile="$desaProfile" />
                                @if($desaProfile->logo_kabupaten)
                                    <p class="text-xs text-green-600">✓ Logo tersedia</p>
                                @else
                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                @endif
                            </div>

                            <div class="text-center">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Logo Provinsi</label>
                                <x-desa-logo type="provinsi" size="xl" class="mx-auto mb-2" :profile="$desaProfile" />
                                @if($desaProfile->logo_provinsi)
                                    <p class="text-xs text-green-600">✓ Logo tersedia</p>
                                @else
                                    <p class="text-xs text-gray-500">Belum ada logo</p>
                                @endif
                            </div>
                        </div>

                        <h4 class="text-md font-medium text-gray-900 mb-4">Upload Logo Baru (Opsional)</h4>
                        
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label for="logo_desa" class="block text-sm font-medium text-gray-700">Logo Desa Baru</label>
                                <input type="file" name="logo_desa" id="logo_desa" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG up to 2MB</p>
                            </div>

                            <div>
                                <label for="logo_kabupaten" class="block text-sm font-medium text-gray-700">Logo Kabupaten Baru</label>
                                <input type="file" name="logo_kabupaten" id="logo_kabupaten" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG up to 2MB</p>
                            </div>

                            <div>
                                <label for="logo_provinsi" class="block text-sm font-medium text-gray-700">Logo Provinsi Baru</label>
                                <input type="file" name="logo_provinsi" id="logo_provinsi" 
                                       class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100"
                                       accept="image/*">
                                <p class="text-xs text-gray-500 mt-1">PNG, JPG, SVG up to 2MB</p>
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


</x-app-layout>
