@props([
    'title' => null,
    'subtitle' => null,
    'header' => null,
    'footer' => null,
    'class' => '',
    'padding' => 'p-6',
])

<div class="bg-white rounded-xl border border-gray-200 shadow-sm {{ $class }}">
    @if ($title || $header)
        <div class="px-6 py-4 border-b border-gray-200 flex items-center justify-between">
            <div>
                @if ($title)
                    <h3 class="text-lg font-semibold text-gray-900">{{ $title }}</h3>
                @endif
                @if ($subtitle)
                    <p class="text-sm text-gray-500 mt-0.5">{{ $subtitle }}</p>
                @endif
            </div>
            @if ($header)
                <div>{{ $header }}</div>
            @endif
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if ($footer)
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-xl">
            {{ $footer }}
        </div>
    @endif
</div>