@props([
    'icon' => null,
    'title' => 'No data found',
    'description' => 'There are no items to display.',
    'action' => null,
    'class' => '',
])

<div class="text-center py-12 {{ $class }}" {{ $attributes }}>
    @if ($icon)
        <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-4">
            {{ $icon }}
        </div>
    @endif

    <h3 class="text-lg font-medium text-gray-900 mb-1">{{ $title }}</h3>
    <p class="text-gray-500 mb-6">{{ $description }}</p>

    @if ($action)
        <div class="flex justify-center">
            {{ $action }}
        </div>
    @endif
</div>