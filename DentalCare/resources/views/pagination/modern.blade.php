@if ($paginator->hasPages())
    <nav>
        <ul class="pagination modern justify-content-center">
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
                    <li class="page-item disabled"><span class="page-link">{{ $element }}</span></li>
                @endif

                {{-- Array Of Links --}}
                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <li class="page-item active">
                                <span class="page-link">{{ $page }}</span>
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
    </nav>

    <style>
        /* Modern Pagination Styles */
        .pagination.modern {
            margin-bottom: 0;
        }
        
        .pagination.modern .page-item {
            margin: 0 2px;
        }
        
        .pagination.modern .page-link {
            border: none;
            padding: 8px 16px;
            color: #6c757d;
            background-color: #f8f9fa;
            border-radius: 6px;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .pagination.modern .page-link:hover {
            background-color: #e9ecef;
            color: #2c3e50;
            transform: translateY(-1px);
        }
        
        .pagination.modern .page-item.active .page-link {
            background-color: #4e73df;
            color: white;
            box-shadow: 0 2px 4px rgba(78, 115, 223, 0.2);
        }
        
        .pagination.modern .page-item.disabled .page-link {
            background-color: #f8f9fa;
            color: #adb5bd;
            cursor: not-allowed;
        }
        
        .pagination.modern .page-link:focus {
            box-shadow: none;
            outline: none;
        }
        
        /* Icon Styling */
        .pagination.modern .page-link i {
            font-size: 0.875rem;
        }
    </style>
@endif
