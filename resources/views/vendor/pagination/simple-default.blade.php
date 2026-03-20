@if ($paginator->hasPages())
    <nav class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="page-item disabled">
                <span class="page-link">&laquo;</span>
            </span>
        @else
            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">&laquo;</a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="page-item disabled"><span class="page-link">{{ $element }}</span></span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="page-link active">{{ $page }}</span>
                    @else
                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">&raquo;</a>
        @else
            <span class="page-item disabled">
                <span class="page-link">&raquo;</span>
            </span>
        @endif
    </nav>
@endif

<style>
.pagination {
    display: flex;
    gap: 0.5rem;
    align-items: center;
    justify-content: center;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border: 2px solid #dcfce7;
    border-radius: 6px;
    color: #166534;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    min-width: 40px;
    text-align: center;
    display: inline-block;
}

.page-link:hover:not(.disabled):not(.active) {
    background: #16a34a;
    color: white;
    border-color: #16a34a;
    transform: translateY(-2px);
}

.page-link.active {
    background: #16a34a;
    color: white;
    border-color: #16a34a;
}

.page-link.disabled {
    opacity: 0.5;
    cursor: not-allowed;
}
</style>
