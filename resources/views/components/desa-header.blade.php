@props([
    'showLogos' => true,
    'showAddress' => true,
    'centered' => true,
    'size' => 'lg' // sm, md, lg, xl
])

@php
    $desaProfile = \App\Models\DesaProfile::getActive();
    
    $headerSizes = [
        'sm' => [
            'container' => 'py-4',
            'title' => 'text-lg font-bold',
            'subtitle' => 'text-sm',
            'address' => 'text-xs',
            'logo' => 'md'
        ],
        'md' => [
            'container' => 'py-6',
            'title' => 'text-xl font-bold',
            'subtitle' => 'text-base',
            'address' => 'text-sm',
            'logo' => 'lg'
        ],
        'lg' => [
            'container' => 'py-8',
            'title' => 'text-2xl font-bold',
            'subtitle' => 'text-lg',
            'address' => 'text-sm',
            'logo' => 'xl'
        ],
        'xl' => [
            'container' => 'py-12',
            'title' => 'text-3xl font-bold',
            'subtitle' => 'text-xl',
            'address' => 'text-base',
            'logo' => 'xl'
        ]
    ];
    
    $currentSize = $headerSizes[$size];
    $alignmentClass = $centered ? 'text-center' : 'text-left';
@endphp

@if($desaProfile)
    <div class="{{ $currentSize['container'] }} {{ $alignmentClass }} border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($showLogos)
                <div class="flex {{ $centered ? 'justify-center' : 'justify-start' }} items-center space-x-6 mb-4">
                    <x-desa-logo type="provinsi" :size="$currentSize['logo']" />
                    <x-desa-logo type="kabupaten" :size="$currentSize['logo']" />
                    <x-desa-logo type="desa" :size="$currentSize['logo']" />
                </div>
            @endif
            
            <div class="space-y-2">
                <h1 class="{{ $currentSize['title'] }} text-gray-900 uppercase tracking-wide">
                    PEMERINTAH {{ strtoupper($desaProfile->provinsi) }}
                </h1>
                <h2 class="{{ $currentSize['subtitle'] }} text-gray-800 uppercase">
                    {{ strtoupper($desaProfile->kabupaten) }}
                </h2>
                <h3 class="{{ $currentSize['subtitle'] }} text-gray-800 uppercase font-semibold">
                    {{ strtoupper($desaProfile->kecamatan) }}
                </h3>
                <h4 class="{{ $currentSize['title'] }} text-gray-900 uppercase font-bold border-b-2 border-gray-300 pb-2 inline-block">
                    {{ strtoupper($desaProfile->nama_desa) }}
                </h4>
                
                @if($showAddress)
                    <div class="{{ $currentSize['address'] }} text-gray-600 mt-4 space-y-1">
                        <p>{{ $desaProfile->alamat_lengkap }}</p>
                        <div class="flex {{ $centered ? 'justify-center' : 'justify-start' }} items-center space-x-4">
                            @if($desaProfile->telepon)
                                <span>📞 {{ $desaProfile->telepon }}</span>
                            @endif
                            @if($desaProfile->email)
                                <span>✉️ {{ $desaProfile->email }}</span>
                            @endif
                            @if($desaProfile->website)
                                <span>🌐 {{ $desaProfile->website }}</span>
                            @endif
                        </div>
                        @if($desaProfile->kode_pos)
                            <p class="font-medium">{{ $desaProfile->kode_pos }}</p>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
@else
    <!-- Fallback header when no profile is set -->
    <div class="{{ $currentSize['container'] }} {{ $alignmentClass }} border-b border-gray-200 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="space-y-2">
                <h1 class="{{ $currentSize['title'] }} text-gray-900 uppercase tracking-wide">
                    SISTEM INFORMASI ANGGARAN DESA
                </h1>
                <p class="{{ $currentSize['address'] }} text-gray-600">
                    Silakan konfigurasi profil desa di menu admin
                </p>
            </div>
        </div>
    </div>
@endif
