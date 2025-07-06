<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center space-x-3">
                        <x-application-logo class="block h-12 w-auto fill-current text-gray-800" />
                        @php $desaProfile = \App\Models\DesaProfile::getActive(); @endphp
                        @if($desaProfile)
                            <div class="hidden sm:block">
                                <div class="text-sm font-semibold text-gray-800">{{ $desaProfile->formatted_nama_desa }}</div>
                                <div class="text-xs text-gray-600">{{ $desaProfile->formatted_kabupaten }}</div>
                            </div>
                        @endif
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>

                    @can('view-kegiatan')
                        <x-nav-link :href="route('kegiatan.index')" :active="request()->routeIs('kegiatan.*')">
                            {{ __('Kegiatan') }}
                        </x-nav-link>
                    @endcan

                    @can('view-realisasi')
                        <x-nav-link :href="route('realisasi.index')" :active="request()->routeIs('realisasi.*')">
                            {{ __('Realisasi') }}
                        </x-nav-link>
                    @endcan

                    @can('view-laporan')
                        <x-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                            {{ __('Laporan') }}
                        </x-nav-link>
                    @endcan

                    @if(auth()->user()->can('manage-users') || auth()->user()->can('view-admin') || auth()->user()->can('view-desa-profile') || auth()->user()->can('view-log'))
                        <!-- Admin Dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = ! open" class="inline-flex items-center px-1 pt-1 border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out" :class="{ 'text-gray-900 border-gray-300': open }">
                                <div>{{ __('Admin') }}</div>
                                <div class="ms-1">
                                    <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>

                            <div x-show="open" x-cloak @click.outside="open = false" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="absolute left-0 z-[9999] mt-2 w-48 rounded-md shadow-lg bg-white border border-gray-200 ring-1 ring-black ring-opacity-5" style="top: 100%;">
                                <div class="py-1">
                                    @can('manage-users')
                                        <a href="{{ route('admin.users.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.users.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                            {{ __('Manajemen User') }}
                                        </a>
                                    @endcan

                                    @can('view-admin')
                                        <a href="{{ route('admin.tahun-anggaran.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.tahun-anggaran.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                            {{ __('Tahun Anggaran') }}
                                        </a>
                                    @endcan

                                    @can('view-desa-profile')
                                        <a href="{{ route('admin.desa-profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('admin.desa-profile.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                            {{ __('Profil Desa') }}
                                        </a>
                                    @endcan

                                    @can('view-log')
                                        <a href="{{ route('log.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 {{ request()->routeIs('log.*') ? 'bg-gray-100 text-gray-900' : '' }}">
                                            {{ __('Log Aktivitas') }}
                                        </a>
                                    @endcan
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Settings Dropdown -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                            <div>
                                <div>{{ Auth::user()->name }}</div>
                                <div class="text-xs text-gray-400">{{ Auth::user()->getRoleName() }}</div>
                            </div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>

                        <!-- Authentication -->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>

            @can('view-kegiatan')
                <x-responsive-nav-link :href="route('kegiatan.index')" :active="request()->routeIs('kegiatan.*')">
                    {{ __('Kegiatan') }}
                </x-responsive-nav-link>
            @endcan

            @can('view-realisasi')
                <x-responsive-nav-link :href="route('realisasi.index')" :active="request()->routeIs('realisasi.*')">
                    {{ __('Realisasi') }}
                </x-responsive-nav-link>
            @endcan

            @can('view-laporan')
                <x-responsive-nav-link :href="route('laporan.index')" :active="request()->routeIs('laporan.*')">
                    {{ __('Laporan') }}
                </x-responsive-nav-link>
            @endcan

            @if(auth()->user()->can('manage-users') || auth()->user()->can('view-admin') || auth()->user()->can('view-desa-profile') || auth()->user()->can('view-log'))
                <!-- Admin Section for Mobile -->
                <div class="border-t border-gray-200 pt-4">
                    <div class="px-4 py-2">
                        <div class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Admin</div>
                    </div>

                    @can('manage-users')
                        <x-responsive-nav-link :href="route('admin.users.index')" :active="request()->routeIs('admin.users.*')">
                            {{ __('Manajemen User') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('view-admin')
                        <x-responsive-nav-link :href="route('admin.tahun-anggaran.index')" :active="request()->routeIs('admin.tahun-anggaran.*')">
                            {{ __('Tahun Anggaran') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('view-desa-profile')
                        <x-responsive-nav-link :href="route('admin.desa-profile.index')" :active="request()->routeIs('admin.desa-profile.*')">
                            {{ __('Profil Desa') }}
                        </x-responsive-nav-link>
                    @endcan

                    @can('view-log')
                        <x-responsive-nav-link :href="route('log.index')" :active="request()->routeIs('log.*')">
                            {{ __('Log Aktivitas') }}
                        </x-responsive-nav-link>
                    @endcan
                </div>
            @endif
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault();
                                        this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
