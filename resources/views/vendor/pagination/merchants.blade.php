@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="previous_page disabled"><a href="#">←</a></li>
        @else
            <li class="previous_page"><a href="{{ $paginator->previousPageUrl() }}" rel="prev">←</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="disabled"><span>{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a href="#">{{ $page }}</a></li>
                    @else
                        <li class=""><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="next_page"><a href="{{ $paginator->nextPageUrl() }}" rel="next">→</a></li>
        @else
            <li class="next_page disabled"><a href="#">→</a></li>
        @endif
    </ul>
@endif
