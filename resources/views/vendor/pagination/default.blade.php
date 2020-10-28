@if ($paginator->hasPages())
    <ul class="pag">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li><a href="#"><span class="icon-arrow-left"></span></a></li>
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}" rel="prev"><span class="icon-arrow-left"></span></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li><a href="#"><span>{{ $element }}</span></a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a href="#"><span>{{ $page }}</span></a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}"><span class="icon-arrow-right"></span></a></li>
            {{--<li><a href="" rel="next">&raquo;</a></li>--}}
        @else
            <li><a href="#"><span class="icon-arrow-right"></span></a></li>
        @endif
    </ul>
@endif
