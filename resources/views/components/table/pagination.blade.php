@props([
    'paginator' => null,
    'class' => '',
])

@if ($paginator && $paginator->hasPages())
    <nav class="flex items-center justify-between {{ $class }}" aria-label="Pagination" {{ $attributes }}>
        <div class="text-sm text-gray-700">
            Showing {{ $paginator->firstItem() }} to {{ $paginator->lastItem() }} of {{ $paginator->total() }} results
        </div>

        <div class="flex gap-1">
            @if ($paginator->onFirstPage())
                <button type="button" disabled class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                    Previous
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    Previous
                </a>
            @endif

            @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                @if ($page == $paginator->currentPage())
                    <span class="px-3 py-1.5 bg-teal-600 text-white rounded-lg text-sm">{{ $page }}</span>
                @else
                    <a href="{{ $url }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">{{ $page }}</a>
                @endif
            @endforeach

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                    Next
                </a>
            @else
                <button type="button" disabled class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm text-gray-400 cursor-not-allowed">
                    Next
                </button>
            @endif
        </div>
    </nav>
@endif