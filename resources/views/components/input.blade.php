@props([
    'type' => 'text',
    'label' => null,
    'name' => null,
    'value' => null,
    'placeholder' => '',
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'error' => null,
    'help' => null,
    'id' => null,
    'class' => '',
    'autocomplete' => 'off',
])

<?php
$inputId = $id ?? $name ?? 'input-' . uniqid();
$errorId = $inputId . '-error';
$helpId = $inputId . '-help';
$describedBy = [];
if ($error) $describedBy[] = $errorId;
if ($help) $describedBy[] = $helpId;
$ariaDescribedBy = $describedBy ? 'aria-describedby="' . implode(' ', $describedBy) . '"' : '';
?>

<div class="{{ $class }}">
    @if ($label)
        <label for="{{ $inputId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <input
        type="{{ $type }}"
        id="{{ $inputId }}"
        name="{{ $name }}"
        value="{{ old($name, $value) }}"
        placeholder="{{ $placeholder }}"
        class="w-full px-3 py-2 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent
            {{ $error ? 'border-red-500 bg-red-50' : 'border-gray-300 bg-white' }}
            {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}
            {{ $readonly ? 'bg-gray-50 cursor-default' : '' }}"
        @if ($disabled) disabled @endif
        @if ($readonly) readonly @endif
        {{ $ariaDescribedBy }}
        {{ $attributes->except(['class', 'id', 'name', 'value', 'placeholder', 'required', 'disabled', 'readonly']) }} />

    @if ($error)
        <p id="{{ $errorId }}" class="mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif

    @if ($help)
        <p id="{{ $helpId }}" class="mt-1.5 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>