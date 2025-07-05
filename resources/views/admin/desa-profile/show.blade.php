<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Detail Profil Desa: {{ $desaProfile->nama_desa }}
                </h2>
                <p class="text-gray-600 text-sm mt-1">
                    @if($desaProfile->is_active)
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Profil Aktif</span>
                    @else
                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Profil Nonaktif</span>
                    @endif
                </p>
            </div>
            <div class="flex space-x-2">
                @can('manage-desa-profile')
                    <x-action-button 
                        href="{{ route('admin.desa-profile.edit', $desaProfile) }}" 
                        icon="edit" 
                        variant="primary" 
                        size="md"
                        tooltip="Edit Profil"
                    >
                        Edit
                    </x-action-button>
                    
                    @if(!$desaProfile->is_active)
                        <form method="POST" action="{{ route('admin.desa-profile.set-active', $desaProfile) }}" class="inline">
                            @csrf
                            <x-action-button
                                type="button"
                                icon="check"
                                variant="success"
                                size="md"
                                tooltip="Aktifkan Profil"
                                onclick="if(confirm('Yakin ingin mengaktifkan profil ini?')) this.closest('form').submit();"
                            >
                                Aktifkan
                            </x-action-button>
                        </form>
                    @endif

                    <x-action-button 
                        href="{{ route('admin.desa-profile.export-pdf', $desaProfile) }}" 
                        icon="download" 
                        variant="danger" 
                        size="md"
                        tooltip="Export PDF"
                    >
                        Export PDF
                    </x-action-button>
                @endcan
                
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
            
            <!-- Header Preview -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Preview Header Resmi</h3>
                    <x-desa-header size="md" />
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                
                <!-- Main Information -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Basic Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Dasar</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Nama Desa</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $desaProfile->nama_desa }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kecamatan</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $desaProfile->kecamatan }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Kabupaten</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $desaProfile->kabupaten }}</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Provinsi</label>
                                    <p class="mt-1 text-lg text-gray-900">{{ $desaProfile->provinsi }}</p>
                                </div>

                                @if($desaProfile->kode_pos)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">Kode Pos</label>
                                        <p class="mt-1 text-lg text-gray-900">{{ $desaProfile->kode_pos }}</p>
                                    </div>
                                @endif

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500">Alamat Lengkap</label>
                                    <p class="mt-1 text-gray-900">{{ $desaProfile->alamat_lengkap }}</p>
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-500">Alamat Terformat</label>
                                    <p class="mt-1 text-gray-900 italic">{{ $desaProfile->formatted_address }}</p>
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
                                    <label class="block text-sm font-medium text-gray-500">Nama Kepala Desa</label>
                                    <p class="mt-1 text-lg font-semibold text-gray-900">{{ $desaProfile->kepala_desa }}</p>
                                </div>

                                @if($desaProfile->nip_kepala_desa)
                                    <div>
                                        <label class="block text-sm font-medium text-gray-500">NIP</label>
                                        <p class="mt-1 text-lg text-gray-900">{{ $desaProfile->nip_kepala_desa }}</p>
                                    </div>
                                @endif

                                @if($desaProfile->periode_jabatan_mulai && $desaProfile->periode_jabatan_selesai)
                                    <div class="md:col-span-2">
                                        <label class="block text-sm font-medium text-gray-500">Periode Jabatan</label>
                                        <p class="mt-1 text-lg text-gray-900">
                                            {{ $desaProfile->periode_jabatan_mulai->format('d M Y') }} - 
                                            {{ $desaProfile->periode_jabatan_selesai->format('d M Y') }}
                                            @if($desaProfile->is_period_active)
                                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                            @else
                                                <span class="ml-2 inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">Berakhir</span>
                                            @endif
                                        </p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    @if($desaProfile->website || $desaProfile->email || $desaProfile->telepon || $desaProfile->fax)
                        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-6">Informasi Kontak</h3>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    @if($desaProfile->website)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Website</label>
                                            <p class="mt-1">
                                                <a href="{{ $desaProfile->website }}" target="_blank" 
                                                   class="text-blue-600 hover:text-blue-800 underline">
                                                    {{ $desaProfile->website }}
                                                </a>
                                            </p>
                                        </div>
                                    @endif

                                    @if($desaProfile->email)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Email</label>
                                            <p class="mt-1">
                                                <a href="mailto:{{ $desaProfile->email }}" 
                                                   class="text-blue-600 hover:text-blue-800 underline">
                                                    {{ $desaProfile->email }}
                                                </a>
                                            </p>
                                        </div>
                                    @endif

                                    @if($desaProfile->telepon)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Telepon</label>
                                            <p class="mt-1 text-gray-900">{{ $desaProfile->telepon }}</p>
                                        </div>
                                    @endif

                                    @if($desaProfile->fax)
                                        <div>
                                            <label class="block text-sm font-medium text-gray-500">Fax</label>
                                            <p class="mt-1 text-gray-900">{{ $desaProfile->fax }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Sidebar -->
                <div class="space-y-6">
                    
                    <!-- Logo Display -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Logo</h3>
                            
                            <div class="space-y-6">
                                <div class="text-center">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Logo Desa</label>
                                    <x-desa-logo type="desa" size="xl" class="mx-auto" />
                                    @if($desaProfile->logo_desa)
                                        <p class="text-xs text-green-600 mt-2">✓ Logo tersedia</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-2">Belum ada logo</p>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Logo Kabupaten</label>
                                    <x-desa-logo type="kabupaten" size="xl" class="mx-auto" />
                                    @if($desaProfile->logo_kabupaten)
                                        <p class="text-xs text-green-600 mt-2">✓ Logo tersedia</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-2">Belum ada logo</p>
                                    @endif
                                </div>

                                <div class="text-center">
                                    <label class="block text-sm font-medium text-gray-500 mb-2">Logo Provinsi</label>
                                    <x-desa-logo type="provinsi" size="xl" class="mx-auto" />
                                    @if($desaProfile->logo_provinsi)
                                        <p class="text-xs text-green-600 mt-2">✓ Logo tersedia</p>
                                    @else
                                        <p class="text-xs text-gray-500 mt-2">Belum ada logo</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Status Information -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Status & Informasi</h3>
                            
                            <div class="space-y-4">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Status Profil</span>
                                    @if($desaProfile->is_active)
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                    @endif
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Dibuat</span>
                                    <span class="text-sm text-gray-900">{{ $desaProfile->created_at->format('d M Y') }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Terakhir Update</span>
                                    <span class="text-sm text-gray-900">{{ $desaProfile->updated_at->diffForHumans() }}</span>
                                </div>

                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-500">Logo Lengkap</span>
                                    @if($desaProfile->logo_desa && $desaProfile->logo_kabupaten && $desaProfile->logo_provinsi)
                                        <span class="text-sm text-green-600">✓ Lengkap</span>
                                    @else
                                        <span class="text-sm text-orange-600">⚠ Belum lengkap</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Actions -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-6">Aksi Cepat</h3>
                            
                            <div class="space-y-3">
                                @can('manage-desa-profile')
                                    <x-action-button 
                                        href="{{ route('admin.desa-profile.edit', $desaProfile) }}" 
                                        icon="edit" 
                                        variant="primary" 
                                        size="md"
                                        class="w-full justify-center"
                                    >
                                        Edit Profil
                                    </x-action-button>
                                @endcan

                                <x-action-button 
                                    href="{{ route('admin.desa-profile.export-pdf', $desaProfile) }}" 
                                    icon="download" 
                                    variant="danger" 
                                    size="md"
                                    class="w-full justify-center"
                                >
                                    Export PDF
                                </x-action-button>

                                <x-action-button 
                                    href="{{ route('admin.desa-profile.index') }}" 
                                    icon="eye" 
                                    variant="secondary" 
                                    size="md"
                                    class="w-full justify-center"
                                >
                                    Lihat Semua Profil
                                </x-action-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
