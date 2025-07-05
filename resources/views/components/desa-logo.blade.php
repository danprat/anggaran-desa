@props([
    'type' => 'desa', // desa, kabupaten, provinsi
    'size' => 'md', // xs, sm, md, lg, xl
    'showFallback' => true,
    'class' => '',
    'alt' => null,
    'profile' => null // Optional specific profile to use
])

@php
    $desaProfile = $profile ?: \App\Models\DesaProfile::getActive();
    
    $sizeClasses = [
        'xs' => 'w-6 h-6',
        'sm' => 'w-8 h-8', 
        'md' => 'w-12 h-12',
        'lg' => 'w-16 h-16',
        'xl' => 'w-24 h-24'
    ];
    
    $logoUrl = null;
    $altText = $alt;
    
    if ($desaProfile) {
        switch ($type) {
            case 'desa':
                $logoUrl = $desaProfile->logo_desa_url;
                $altText = $altText ?: "Logo {$desaProfile->nama_desa}";
                break;
            case 'kabupaten':
                $logoUrl = $desaProfile->logo_kabupaten_url;
                $altText = $altText ?: "Logo {$desaProfile->kabupaten}";
                break;
            case 'provinsi':
                $logoUrl = $desaProfile->logo_provinsi_url;
                $altText = $altText ?: "Logo {$desaProfile->provinsi}";
                break;
        }
    }
    
    $classes = $sizeClasses[$size] . ' ' . $class;
@endphp

@if($logoUrl)<img src="{{ $logoUrl }}" alt="{{ $altText }}" class="{{ $classes }} object-contain" onerror="@if($showFallback) this.src='{{ asset('images/default-logo-' . $type . '.svg') }}'; @endif" {{ $attributes }}>@elseif($showFallback)<img src="{{ asset('images/default-logo-' . $type . '.svg') }}" alt="{{ $altText }}" class="{{ $classes }} object-contain" {{ $attributes }}>@endif
