@if ($paginator->hasPages())
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="previous_page disabled"><span><i class="icon-chevron-left"> </i></span></li>
        @else
            <li class="previous_page"><a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev"><i class="icon-chevron-left"> </i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="page-item active"><span class="page-link">{{ $page }}</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li class="next_page"><a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next"><i class="icon-chevron-right"> </i></a></li>
        @else
            <li class="next_page disabled"><a class="page-link" rel="next"><i class="icon-chevron-right"> </i></a></li>
        @endif
    </ul>
@endif
