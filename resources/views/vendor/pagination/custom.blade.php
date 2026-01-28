@if ($paginator->hasPages())
    <nav aria-label="Page navigation example">
        <ul class="pagination pagination-primary">

            {{-- Previous Page Link --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled"><span class="page-link">Previous</span></li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">Previous</a>
                </li>
            @endif

            {{-- Page 1 --}}
            <li class="page-item {{ $paginator->currentPage() == 1 ? 'active' : '' }}">
                <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
            </li>

            {{-- Page 2 (if exists) --}}
            @if ($paginator->lastPage() >= 2)
                <li class="page-item {{ $paginator->currentPage() == 2 ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url(2) }}">2</a>
                </li>
            @endif

            {{-- Last Page (if more than 2 pages) --}}
            @if ($paginator->lastPage() > 2)
                {{-- Show "..." if gap is large --}}
                @if ($paginator->lastPage() > 3 && $paginator->currentPage() < $paginator->lastPage())
                    <li class="page-item disabled"><span class="page-link">...</span></li>
                @endif

                <li class="page-item {{ $paginator->currentPage() == $paginator->lastPage() ? 'active' : '' }}">
                    <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">
                        {{ $paginator->lastPage() }}
                    </a>
                </li>
            @endif

            {{-- Next Page Link --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">Next</a>
                </li>
            @else
                <li class="page-item disabled"><span class="page-link">Next</span></li>
            @endif

        </ul>
    </nav>
@endif
