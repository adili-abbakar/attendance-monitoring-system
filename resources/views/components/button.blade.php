@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'disabled' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left',
    'fullWidth' => false,
    'class' => '',
])

<?php
$variants = [
    'primary' => 'bg-teal-600 hover:bg-teal-700 text-white',
    'secondary' => 'bg-gray-100 hover:bg-gray-200 text-gray-900 border border-gray-300',
    'success' => 'bg-green-600 hover:bg-green-700 text-white',
    'danger' => 'bg-red-600 hover:bg-red-700 text-white',
    'outline' => 'border-2 border-teal-600 text-teal-600 hover:bg-teal-50',
    'ghost' => 'text-gray-700 hover:bg-gray-100',
];

$sizes = [
    'sm' => 'px-3 py-1.5 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-6 py-3 text-base',
];

$widthClass = $fullWidth ? 'w-full' : '';
$disabledClass = $disabled || $loading ? 'opacity-50 cursor-not-allowed' : '';
$loadingSpinner = $loading ? '<svg class="animate-spin -ml-1 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>' : '';
?>

<button
    type="{{ $type }}"
    class="inline-flex items-center justify-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed {{ $variants[$variant] ?? $variants['primary'] }} {{ $sizes[$size] ?? $sizes['md'] }} {{ $widthClass }} {{ $disabledClass }} {{ $class }}"
    {{ $disabled || $loading ? 'disabled' : '' }}
    {{ $attributes->except(['class']) }}>
    {{ $loadingSpinner }}
    @if ($icon && $iconPosition === 'left')
        <span class="mr-2">{{ $icon }}</span>
    @endif
    {{ $slot }}
    @if ($icon && $iconPosition === 'right')
        <span class="ml-2">{{ $icon }}</span>
    @endif
</button>