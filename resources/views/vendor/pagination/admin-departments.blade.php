@if ($paginator->hasPages())
    @php
        $current = $paginator->currentPage();
        $last = $paginator->lastPage();
        $start = max(1, $current - 2);
        $end = min($last, $current + 2);
    @endphp

    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center mb-0">
            {{-- Previous --}}
            @if ($paginator->onFirstPage())
                <li class="page-item disabled" aria-disabled="true">
                    <a class="page-link" href="#" tabindex="-1">
                        <i class="fa-solid fa-chevron-right me-1"></i> پێشتر
                    </a>
                </li>
            @else
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->previousPageUrl() }}">
                        <i class="fa-solid fa-chevron-right me-1"></i> پێشتر
                    </a>
                </li>
            @endif

            {{-- First page --}}
            @if ($start > 1)
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url(1) }}">1</a>
                </li>
                @if ($start > 2)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif
            @endif

            {{-- Page window --}}
            @for ($i = $start; $i <= $end; $i++)
                @if ($i === $current)
                    <li class="page-item active" aria-current="page">
                        <span class="page-link">{{ $i }}</span>
                    </li>
                @else
                    <li class="page-item">
                        <a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a>
                    </li>
                @endif
            @endfor

            {{-- Last page --}}
            @if ($end < $last)
                @if ($end < $last - 1)
                    <li class="page-item disabled" aria-disabled="true">
                        <span class="page-link">...</span>
                    </li>
                @endif
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->url($last) }}">{{ $last }}</a>
                </li>
            @endif

            {{-- Next --}}
            @if ($paginator->hasMorePages())
                <li class="page-item">
                    <a class="page-link" href="{{ $paginator->nextPageUrl() }}">
                        داهاتوو <i class="fa-solid fa-chevron-left ms-1"></i>
                    </a>
                </li>
            @else
                <li class="page-item disabled" aria-disabled="true">
                    <a class="page-link" href="#" tabindex="-1">
                        داهاتوو <i class="fa-solid fa-chevron-left ms-1"></i>
                    </a>
                </li>
            @endif
        </ul>
    </nav>
@endif
