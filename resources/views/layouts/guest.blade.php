<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Dynamic Favicon -->
        <x-favicon />

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans text-gray-900 antialiased">
        @php $desaProfile = \App\Models\DesaProfile::getActive(); @endphp
        <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 village-header">
            <!-- Village Branding Header -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block">
                    <x-application-logo class="w-24 h-24 fill-current text-white mx-auto mb-4" />
                </a>
                @if($desaProfile)
                    <h1 class="text-2xl font-bold text-white mb-2">{{ $desaProfile->formatted_nama_desa }}</h1>
                    <p class="text-blue-100 text-lg">{{ $desaProfile->formatted_kecamatan }}, {{ $desaProfile->formatted_kabupaten }}</p>
                    @if($desaProfile->visi)
                        <p class="text-blue-100 text-sm mt-2 max-w-md mx-auto italic">"{{ $desaProfile->visi }}"</p>
                    @endif
                @else
                    <h1 class="text-2xl font-bold text-white mb-2">Sistem Anggaran Desa</h1>
                    <p class="text-blue-100 text-lg">Portal Administrasi Desa</p>
                @endif
            </div>

            <!-- Login Form Card -->
            <div class="w-full sm:max-w-md px-8 py-8 bg-white shadow-xl overflow-hidden sm:rounded-xl border border-gray-200">
                <div class="text-center mb-6">
                    <h2 class="text-xl font-semibold text-gray-800">Masuk ke Sistem</h2>
                    <p class="text-gray-600 text-base mt-2">Silakan masukkan kredensial Anda</p>
                </div>
                {{ $slot }}
            </div>

            <!-- Footer -->
            <div class="mt-8 text-center">
                <p class="text-blue-100 text-sm">
                    © {{ date('Y') }} {{ $desaProfile ? $desaProfile->formatted_nama_desa : 'Sistem Anggaran Desa' }}.
                    Semua hak dilindungi.
                </p>
                @if($desaProfile && ($desaProfile->website || $desaProfile->email))
                    <div class="mt-2 space-x-4">
                        @if($desaProfile->website)
                            <a href="{{ $desaProfile->website }}" target="_blank" class="text-blue-100 hover:text-white text-sm underline">
                                Website Desa
                            </a>
                        @endif
                        @if($desaProfile->email)
                            <a href="mailto:{{ $desaProfile->email }}" class="text-blue-100 hover:text-white text-sm underline">
                                Kontak
                            </a>
                        @endif
                    </div>
                @endif
                <div class="mt-3 pt-3 border-t border-blue-400">
                    <p class="text-blue-200 text-xs">
                        Dibuat oleh
                        <a href="https://wa.me/6208974041777"
                           target="_blank"
                           class="font-medium text-white hover:text-blue-100 transition-colors duration-200">
                            Dany Pratmanto
                        </a>
                        • WA:
                        <a href="https://wa.me/6208974041777"
                           target="_blank"
                           class="font-medium text-white hover:text-blue-100 transition-colors duration-200">
                            08974041777
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
