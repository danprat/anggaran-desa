<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Manajemen Profil Desa
                </h2>
                <p class="text-gray-600 text-sm mt-1">Kelola informasi profil desa, logo, dan data administratif</p>
            </div>
            <div class="flex space-x-2">
                @can('manage-desa-profile')
                    <x-action-button 
                        href="{{ route('admin.desa-profile.create') }}" 
                        icon="plus" 
                        variant="success" 
                        size="md"
                        tooltip="Tambah Profil Desa Baru"
                    >
                        Tambah Profil Desa
                    </x-action-button>
                @endcan
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

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <x-icon name="plus" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Total Profil</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $profiles->total() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                                    <x-icon name="check" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Profil Aktif</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $profiles->where('is_active', true)->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center">
                                    <x-icon name="upload" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Dengan Logo</div>
                                <div class="text-2xl font-bold text-gray-900">{{ $profiles->whereNotNull('logo_desa')->count() }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                                    <x-icon name="refresh" class="w-4 h-4 text-white" />
                                </div>
                            </div>
                            <div class="ml-4">
                                <div class="text-sm font-medium text-gray-500">Update Terakhir</div>
                                <div class="text-sm font-bold text-gray-900">
                                    {{ $profiles->first()?->updated_at?->diffForHumans() ?? 'Belum ada' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Profiles Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Daftar Profil Desa</h3>
                    </div>

                    @if($profiles->count() > 0)
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Desa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Wilayah
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Kepala Desa
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Logo
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Aksi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($profiles as $profile)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <x-desa-logo type="desa" size="sm" class="mr-3" />
                                                    <div>
                                                        <div class="text-sm font-medium text-gray-900">
                                                            {{ $profile->nama_desa }}
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            {{ $profile->alamat_lengkap }}
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div>{{ $profile->kecamatan }}</div>
                                                <div class="text-gray-500">{{ $profile->kabupaten }}</div>
                                                <div class="text-gray-500">{{ $profile->provinsi }}</div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="font-medium">{{ $profile->kepala_desa }}</div>
                                                @if($profile->nip_kepala_desa)
                                                    <div class="text-gray-500">NIP: {{ $profile->nip_kepala_desa }}</div>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <div class="flex space-x-1">
                                                    @if($profile->logo_desa)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Desa</span>
                                                    @endif
                                                    @if($profile->logo_kabupaten)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">Kab</span>
                                                    @endif
                                                    @if($profile->logo_provinsi)
                                                        <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-purple-100 text-purple-800">Prov</span>
                                                    @endif
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                @if($profile->is_active)
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">Aktif</span>
                                                @else
                                                    <span class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800">Nonaktif</span>
                                                @endif
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex space-x-1">
                                                    @can('view-admin')
                                                        <x-action-button
                                                            href="{{ route('admin.desa-profile.show', $profile) }}"
                                                            icon="eye"
                                                            variant="info"
                                                            size="sm"
                                                            tooltip="Lihat Detail"
                                                        />
                                                    @endcan

                                                    @can('manage-desa-profile')
                                                        <x-action-button
                                                            href="{{ route('admin.desa-profile.edit', $profile) }}"
                                                            icon="edit"
                                                            variant="primary"
                                                            size="sm"
                                                            tooltip="Edit Profil"
                                                        />

                                                        @if(!$profile->is_active)
                                                            <form method="POST" action="{{ route('admin.desa-profile.set-active', $profile) }}" class="inline">
                                                                @csrf
                                                                <x-action-button
                                                                    type="button"
                                                                    icon="check"
                                                                    variant="success"
                                                                    size="sm"
                                                                    tooltip="Aktifkan Profil"
                                                                    onclick="if(confirm('Yakin ingin mengaktifkan profil ini?')) this.closest('form').submit();"
                                                                />
                                                            </form>
                                                        @endif

                                                        <x-action-button
                                                            href="{{ route('admin.desa-profile.export-pdf', $profile) }}"
                                                            icon="download"
                                                            variant="secondary"
                                                            size="sm"
                                                            tooltip="Export PDF"
                                                        />
                                                    @endcan
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-6">
                            {{ $profiles->links() }}
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-4m-5 0H9m0 0H5m0 0h2M7 7h10M7 11h4m6 0h2M7 15h10" />
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada profil desa</h3>
                            <p class="mt-1 text-sm text-gray-500">Mulai dengan membuat profil desa pertama.</p>
                            @can('manage-desa-profile')
                                <div class="mt-6">
                                    <x-action-button 
                                        href="{{ route('admin.desa-profile.create') }}" 
                                        icon="plus" 
                                        variant="primary" 
                                        size="md"
                                    >
                                        Tambah Profil Desa
                                    </x-action-button>
                                </div>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
