@if ($paginator->hasPages())
    <nav class="d-flex justify-content-between align-items-center mt-4">
        <!-- Mobile pagination -->
        <div class="d-flex justify-content-between flex-fill d-sm-none">
            @if ($paginator->onFirstPage())
                <span class="btn btn-outline-secondary disabled">
                    <i class="fas fa-chevron-left me-1"></i>Anterior
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-outline-primary" rel="prev">
                    <i class="fas fa-chevron-left me-1"></i>Anterior
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-outline-primary" rel="next">
                    Siguiente<i class="fas fa-chevron-right ms-1"></i>
                </a>
            @else
                <span class="btn btn-outline-secondary disabled">
                    Siguiente<i class="fas fa-chevron-right ms-1"></i>
                </span>
            @endif
        </div>

        <!-- Desktop pagination -->
        <div class="d-none d-sm-flex align-items-center justify-content-between w-100">
            <!-- Results info -->
            <div>
                <p class="small text-muted mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    Mostrando <span class="fw-semibold text-primary">{{ $paginator->firstItem() }}</span>
                    a <span class="fw-semibold text-primary">{{ $paginator->lastItem() }}</span>
                    de <span class="fw-semibold text-primary">{{ $paginator->total() }}</span> resultados
                </p>
            </div>

            <!-- Pagination links -->
            <div>
                <ul class="pagination pagination-sm mb-0 custom-pagination">
                    {{-- Previous Page Link --}}
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-left"></i>
                            </span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">
                                <i class="fas fa-chevron-left"></i>
                            </a>
                        </li>
                    @endif

                    {{-- Pagination Elements --}}
                    @foreach ($elements as $element)
                        {{-- "Three Dots" Separator --}}
                        @if (is_string($element))
                            <li class="page-item disabled">
                                <span class="page-link">{{ $element }}</span>
                            </li>
                        @endif

                        {{-- Array Of Links --}}
                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <li class="page-item active">
                                        <span class="page-link bg-primary border-primary">{{ $page }}</span>
                                    </li>
                                @else
                                    <li class="page-item">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Next Page Link --}}
                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">
                                <i class="fas fa-chevron-right"></i>
                            </a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">
                                <i class="fas fa-chevron-right"></i>
                            </span>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    <style>
        .custom-pagination .page-link {
            color: #64748b;
            border: 1px solid #e2e8f0;
            padding: 0.375rem 0.75rem;
            margin: 0 0.125rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            transition: all 0.2s ease;
        }

        .custom-pagination .page-link:hover {
            color: #2563eb;
            background-color: #f8fafc;
            border-color: #cbd5e1;
        }

        .custom-pagination .page-item.active .page-link {
            background-color: #2563eb;
            border-color: #2563eb;
            color: white;
        }

        .custom-pagination .page-item.disabled .page-link {
            color: #94a3b8;
            background-color: #f8fafc;
            border-color: #e2e8f0;
        }
    </style>
@endif
