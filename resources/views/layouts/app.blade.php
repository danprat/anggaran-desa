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
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 flex flex-col">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main class="flex-1 pb-16">
                {{ $slot }}
            </main>

            <!-- Sticky Footer -->
            <footer class="fixed bottom-0 left-0 right-0 bg-gray-50 border-t border-gray-200 shadow-lg z-50">
                <div class="max-w-7xl mx-auto px-4 py-2">
                    <div class="text-center">
                        <p class="text-xs text-gray-600">
                            Dibuat oleh
                            <a href="https://wa.me/6208974041777"
                               target="_blank"
                               class="font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200">
                                Dany Pratmanto
                            </a>
                            • WA:
                            <a href="https://wa.me/6208974041777"
                               target="_blank"
                               class="font-medium text-green-600 hover:text-green-800 transition-colors duration-200">
                                08974041777
                            </a>
                            • © {{ date('Y') }} Sistem Anggaran Desa
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
