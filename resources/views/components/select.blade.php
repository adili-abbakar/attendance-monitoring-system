@props([
    'label' => null,
    'name' => null,
    'value' => null,
    'options' => [],
    'required' => false,
    'disabled' => false,
    'error' => null,
    'help' => null,
    'id' => null,
    'class' => '',
    'placeholder' => 'Select an option',
])

<?php
$selectId = $id ?? $name ?? 'select-' . uniqid();
$errorId = $selectId . '-error';
$helpId = $selectId . '-help';
$describedBy = [];
if ($error) $describedBy[] = $errorId;
if ($help) $describedBy[] = $helpId;
$ariaDescribedBy = $describedBy ? 'aria-describedby="' . implode(' ', $describedBy) . '"' : '';
?>

<div class="{{ $class }}">
    @if ($label)
        <label for="{{ $selectId }}" class="block text-sm font-medium text-gray-700 mb-1.5">
            {{ $label }}
            @if ($required)
                <span class="text-red-500 ml-1" aria-hidden="true">*</span>
            @endif
        </label>
    @endif

    <select
        id="{{ $selectId }}"
        name="{{ $name }}"
        class="w-full px-3 py-2 border rounded-lg text-sm transition-colors focus:outline-none focus:ring-2 focus:ring-teal-500 focus:border-transparent appearance-none bg-white
            {{ $error ? 'border-red-500 bg-red-50' : 'border-gray-300' }}
            {{ $disabled ? 'bg-gray-100 cursor-not-allowed' : '' }}"
        @if ($disabled) disabled @endif
        {{ $ariaDescribedBy }}
        {{ $attributes->except(['class', 'id', 'name', 'value', 'required', 'disabled']) }}>
        <option value="">{{ $placeholder }}</option>
        @foreach ($options as $key => $label)
            <option value="{{ $key }}" {{ old($name, $value) == $key ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
    </select>

    @if ($error)
        <p id="{{ $errorId }}" class="mt-1.5 text-sm text-red-600" role="alert">{{ $error }}</p>
    @endif

    @if ($help)
        <p id="{{ $helpId }}" class="mt-1.5 text-sm text-gray-500">{{ $help }}</p>
    @endif
</div>