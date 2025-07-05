@props([
    'href' => '#',
    'icon' => 'plus',
    'variant' => 'primary',
    'size' => 'sm',
    'tooltip' => '',
    'onclick' => '',
    'type' => 'link'
])

@php
$baseClasses = 'inline-flex items-center justify-center rounded-md font-medium transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:pointer-events-none';

$sizeClasses = [
    'xs' => 'h-6 px-2 text-xs min-w-6',
    'sm' => 'h-8 px-3 text-sm min-w-8',
    'md' => 'h-10 px-4 text-base min-w-10',
    'lg' => 'h-12 px-6 text-lg min-w-12'
];

$variantClasses = [
    'primary' => 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-500',
    'success' => 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-500',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
    'warning' => 'bg-amber-500 text-white hover:bg-amber-600 focus:ring-amber-500',
    'info' => 'bg-cyan-600 text-white hover:bg-cyan-700 focus:ring-cyan-500',
    'verify' => 'bg-orange-500 text-white hover:bg-orange-600 focus:ring-orange-500',
    'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:ring-gray-500',
    'ghost' => 'text-gray-600 hover:bg-gray-100 focus:ring-gray-500'
];

// Tentukan apakah tombol hanya icon atau dengan text
$hasText = $slot->isNotEmpty();
$finalSizeClasses = $hasText ? $sizeClasses[$size] : str_replace(['px-2', 'px-3', 'px-4', 'px-6'], ['w-6', 'w-8', 'w-10', 'w-12'], $sizeClasses[$size]);

$classes = $baseClasses . ' ' . $finalSizeClasses . ' ' . $variantClasses[$variant];

$iconSize = match($size) {
    'xs' => 'w-3 h-3',
    'sm' => 'w-4 h-4',
    'md' => 'w-5 h-5',
    'lg' => 'w-6 h-6',
    default => 'w-4 h-4'
};
@endphp

@if($type === 'button')
    <button
        type="button"
        class="{{ $classes }}"
        @if($onclick) onclick="{{ $onclick }}" @endif
        @if($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes }}
    >
        <x-icon :name="$icon" :class="$iconSize" :title="$tooltip" />
        @if($hasText)
            <span class="ml-2 whitespace-nowrap">{{ $slot }}</span>
        @endif
    </button>
@else
    <a
        href="{{ $href }}"
        class="{{ $classes }}"
        @if($tooltip) title="{{ $tooltip }}" @endif
        {{ $attributes }}
    >
        <x-icon :name="$icon" :class="$iconSize" :title="$tooltip" />
        @if($hasText)
            <span class="ml-2 whitespace-nowrap">{{ $slot }}</span>
        @endif
    </a>
@endif
