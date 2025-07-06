@php
    $desaProfile = \App\Models\DesaProfile::getActive();
    $faviconUrl = $desaProfile && $desaProfile->logo_desa 
        ? $desaProfile->logo_desa_url 
        : asset('images/default-logo-desa.svg');
@endphp

<!-- Dynamic Favicon using Village Logo -->
<link rel="icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="shortcut icon" type="image/x-icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="57x57" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="72x72" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="76x76" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="114x114" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="120x120" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="144x144" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="152x152" href="{{ $faviconUrl }}">
<link rel="apple-touch-icon" sizes="180x180" href="{{ $faviconUrl }}">

<!-- Meta tags for better favicon support -->
<meta name="msapplication-TileImage" content="{{ $faviconUrl }}">
<meta name="msapplication-TileColor" content="#ffffff">
<meta name="theme-color" content="#ffffff">
