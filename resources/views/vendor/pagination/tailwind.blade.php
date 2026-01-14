@if ($paginator->hasPages())
    <nav class="flex items-center justify-between">
        {{-- LEFT: Showing results --}}
        <div>
            <p class="text-sm text-gray-500">
                Showing
                <span class="font-medium text-gray-700">{{ $paginator->firstItem() }}</span>
                –
                <span class="font-medium text-gray-700">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium text-gray-700">{{ $paginator->total() }}</span>
            </p>
        </div>

        {{-- RIGHT: Pagination --}}
        <div class="flex items-center gap-2">
            {{-- Prev --}}
            @if ($paginator->onFirstPage())
                <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Prev
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}"
                   class="px-3 py-1.5 text-xs border rounded-lg hover:bg-gray-100">
                    Prev
                </a>
            @endif

            {{-- Pages --}}
            @foreach ($elements as $element)
                {{-- Dots --}}
                @if (is_string($element))
                    <span class="px-3 py-2 text-gray-400">{{ $element }}</span>
                @endif

                {{-- Page Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-3 py-1.5 text-xs primary_bgcolor text-white rounded-lg font-semibold">
                                {{ $page }}
                            </span>
                        @else
                            <a href="{{ $url }}"
                               class="px-3 py-1.5 text-xs border rounded-lg hover:bg-gray-100">
                                {{ $page }}
                            </a>
                        @endif
                    @endforeach
                @endif
            @endforeach

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}"
                   class="px-3 py-1.5 text-xs border rounded-lg hover:bg-gray-100">
                    Next
                </a>
            @else
                <span class="px-3 py-1.5 text-xs text-gray-400 bg-gray-100 rounded-lg cursor-not-allowed">
                    Next
                </span>
            @endif
        </div>
    </nav>
@endif
