@props([
    'variant' => 'default',
    'size' => 'md',
    'dot' => false,
    'class' => '',
])

<?php
$variants = [
    'default' => 'bg-gray-100 text-gray-700',
    'primary' => 'bg-teal-100 text-teal-700',
    'success' => 'bg-green-100 text-green-700',
    'warning' => 'bg-yellow-100 text-yellow-700',
    'danger' => 'bg-red-100 text-red-700',
    'info' => 'bg-blue-100 text-blue-700',
];

$sizes = [
    'sm' => 'px-2 py-0.5 text-xs',
    'md' => 'px-2.5 py-1 text-xs',
    'lg' => 'px-3 py-1.5 text-sm',
];

$dotColors = [
    'default' => 'bg-gray-400',
    'primary' => 'bg-teal-500',
    'success' => 'bg-green-500',
    'warning' => 'bg-yellow-500',
    'danger' => 'bg-red-500',
    'info' => 'bg-blue-500',
];
?>

@php
$badgeClass = "inline-flex items-center font-medium rounded-full " . ($variants[$variant] ?? $variants['default']) . " " . ($sizes[$size] ?? $sizes['md']) . " " . $class;
@endphp

<span class="{{ $badgeClass }}" {{ $attributes }}>
    @if ($dot)
        <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotColors[$variant] ?? $dotColors['default'] }}" aria-hidden="true"></span>
    @endif
    {{ $slot }}
</span>