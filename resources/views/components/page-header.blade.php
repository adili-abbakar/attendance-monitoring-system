@props([
    'title' => '',
    'subtitle' => null,
    'breadcrumb' => [],
    'actions' => null,
    'class' => '',
])

<div class="mb-6 {{ $class }}" {{ $attributes }}>
    @if ($breadcrumb && count($breadcrumb) > 0)
        <nav class="mb-4" aria-label="Breadcrumb">
            <ol class="flex items-center gap-2 text-sm">
                @foreach ($breadcrumb as $index => $item)
                    <li class="flex items-center gap-2">
                        @if ($index > 0)
                            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        @endif
                        @if ($item['url'] && $index < count($breadcrumb) - 1)
                            <a href="{{ $item['url'] }}" class="text-gray-500 hover:text-gray-700 transition-colors">{{ $item['label'] }}</a>
                        @else
                            <span class="text-gray-900 font-medium" aria-current="page">{{ $item['label'] }}</span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    @endif

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $title }}</h1>
            @if ($subtitle)
                <p class="text-gray-600 mt-1">{{ $subtitle }}</p>
            @endif
        </div>

        @if ($actions)
            <div class="flex items-center gap-3">
                {{ $actions }}
            </div>
        @endif
    </div>
</div>