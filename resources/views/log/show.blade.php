<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detail Log Aktivitas
            </h2>
            <x-action-button 
                href="{{ route('log.index') }}" 
                icon="x" 
                variant="secondary" 
                size="md"
                tooltip="Kembali ke Log Aktivitas"
            >
                Kembali
            </x-action-button>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Log Detail Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <!-- Basic Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Dasar
                            </h3>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">ID Log</label>
                                <p class="mt-1 text-sm text-gray-900">#{{ $log->id }}</p>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">User</label>
                                <div class="mt-1 flex items-center">
                                    <div class="flex-shrink-0">
                                        <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                                            <span class="text-white text-xs font-medium">
                                                {{ strtoupper(substr($log->user->name, 0, 1)) }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $log->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $log->user->email }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Waktu</label>
                                <p class="mt-1 text-sm text-gray-900">{{ $log->created_at->format('d/m/Y H:i:s') }}</p>
                                <p class="text-xs text-gray-500">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                            
                            @if($log->tabel_terkait)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">Tabel Terkait</label>
                                    <p class="mt-1 text-sm text-gray-900">{{ $log->tabel_terkait }}</p>
                                    @if($log->row_id)
                                        <p class="text-xs text-gray-500">ID: {{ $log->row_id }}</p>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <!-- Technical Information -->
                        <div class="space-y-4">
                            <h3 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">
                                Informasi Teknis
                            </h3>
                            
                            @if($log->ip_address)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">IP Address</label>
                                    <p class="mt-1 text-sm text-gray-900 font-mono">{{ $log->ip_address }}</p>
                                </div>
                            @endif
                            
                            @if($log->user_agent)
                                <div>
                                    <label class="block text-sm font-medium text-gray-500">User Agent</label>
                                    <p class="mt-1 text-sm text-gray-900 break-all">{{ $log->user_agent }}</p>
                                </div>
                            @endif
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-500">Browser Info</label>
                                @php
                                    $userAgent = $log->user_agent;
                                    $browser = 'Unknown';
                                    $os = 'Unknown';
                                    
                                    if (strpos($userAgent, 'Chrome') !== false) {
                                        $browser = 'Chrome';
                                    } elseif (strpos($userAgent, 'Firefox') !== false) {
                                        $browser = 'Firefox';
                                    } elseif (strpos($userAgent, 'Safari') !== false) {
                                        $browser = 'Safari';
                                    } elseif (strpos($userAgent, 'Edge') !== false) {
                                        $browser = 'Edge';
                                    }
                                    
                                    if (strpos($userAgent, 'Windows') !== false) {
                                        $os = 'Windows';
                                    } elseif (strpos($userAgent, 'Mac') !== false) {
                                        $os = 'macOS';
                                    } elseif (strpos($userAgent, 'Linux') !== false) {
                                        $os = 'Linux';
                                    } elseif (strpos($userAgent, 'Android') !== false) {
                                        $os = 'Android';
                                    } elseif (strpos($userAgent, 'iOS') !== false) {
                                        $os = 'iOS';
                                    }
                                @endphp
                                <div class="mt-1 space-y-1">
                                    <p class="text-sm text-gray-900">Browser: <span class="font-medium">{{ $browser }}</span></p>
                                    <p class="text-sm text-gray-900">OS: <span class="font-medium">{{ $os }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Activity Description -->
                    <div class="mt-6 pt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Deskripsi Aktivitas</h3>
                        <div class="bg-gray-50 rounded-lg p-4">
                            <p class="text-sm text-gray-900">{{ $log->aktivitas }}</p>
                        </div>
                    </div>
                    
                    <!-- Data Changes (if available) -->
                    @if($log->data_lama || $log->data_baru)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Perubahan Data</h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                @if($log->data_lama)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Data Sebelum</h4>
                                        <div class="bg-red-50 border border-red-200 rounded-lg p-3">
                                            <pre class="text-xs text-red-800 whitespace-pre-wrap">{{ json_encode($log->data_lama, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif

                                @if($log->data_baru)
                                    <div>
                                        <h4 class="text-sm font-medium text-gray-700 mb-2">Data Sesudah</h4>
                                        <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                            <pre class="text-xs text-green-800 whitespace-pre-wrap">{{ json_encode($log->data_baru, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif
                    
                    <!-- Related Logs -->
                    @php
                        $relatedLogs = \App\Models\LogAktivitas::where('user_id', $log->user_id)
                            ->where('id', '!=', $log->id)
                            ->where('created_at', '>=', $log->created_at->subHours(1))
                            ->where('created_at', '<=', $log->created_at->addHours(1))
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
                    @endphp
                    
                    @if($relatedLogs->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-200">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Aktivitas Terkait (±1 jam)</h3>
                            <div class="space-y-2">
                                @foreach($relatedLogs as $relatedLog)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="text-sm text-gray-900">{{ $relatedLog->aktivitas }}</p>
                                            <p class="text-xs text-gray-500">{{ $relatedLog->created_at->format('H:i:s') }}</p>
                                        </div>
                                        <x-action-button 
                                            href="{{ route('log.show', $relatedLog) }}" 
                                            icon="eye" 
                                            variant="info" 
                                            size="xs"
                                            tooltip="Lihat Detail"
                                        />
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
