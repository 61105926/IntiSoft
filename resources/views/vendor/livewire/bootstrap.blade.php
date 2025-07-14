<div class="paginating-container pagination-solid">
    <ul class="pagination">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <li class="prev disabled"><a class="pointer-cursor" class="pointer-cursor"
                    wire:click="previousPage('{{ $paginator->getPageName() }}')" wire:loading.attr="disabled">Prev</a>
            </li>
        @else
            <li><a class="pointer-cursor" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled">Prev</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a class="pointer-cursor"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</a>
                        </li>
                    @else
                        <li><a class="pointer-cursor"
                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">{{ $page }}</a>
                        </li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a class="pointer-cursor" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    wire:loading.attr="disabled">Next</a></li>
        @else
            <li aria-disabled="true"><a wire:click="nextPage('{{ $paginator->getPageName() }}')"
                    class="pointer-cursor">Next</a></li>
        @endif
    </ul>
</div>
<style>
    .pointer-cursor {
        cursor: pointer;
    }
</style>
