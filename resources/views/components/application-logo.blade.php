@php
    $desaProfile = \App\Models\DesaProfile::getActive();
    $altText = $desaProfile ? "Logo {$desaProfile->nama_desa}" : 'Logo Desa';
@endphp

@if($desaProfile && $desaProfile->logo_desa && \Illuminate\Support\Facades\Storage::disk('public')->exists($desaProfile->logo_desa))
    <img src="{{ \Illuminate\Support\Facades\Storage::disk('public')->url($desaProfile->logo_desa) }}"
         alt="{{ $altText }}"
         {{ $attributes->merge(['class' => 'object-contain']) }}>
@else
    <!-- Fallback to default village logo SVG -->
    <svg viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" {{ $attributes }}>
        <!-- Simple village/government building icon -->
        <rect x="20" y="60" width="60" height="30" fill="currentColor" opacity="0.8"/>
        <polygon points="15,60 50,30 85,60" fill="currentColor"/>
        <rect x="35" y="45" width="8" height="15" fill="white"/>
        <rect x="57" y="45" width="8" height="15" fill="white"/>
        <rect x="45" y="70" width="10" height="20" fill="white"/>
        <circle cx="50" cy="25" r="5" fill="currentColor"/>
        <rect x="48" y="20" width="4" height="10" fill="currentColor"/>
    </svg>
@endif
