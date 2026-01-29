@if ($paginator->hasPages())
    <div class="flex flex-col md:flex-row justify-between items-center gap-3">

        <span class="mb-2 md:mb-0 text-sm text-[#111827]">
            {!! __('pagination.Showing') !!}
            {{ $paginator->firstItem() }}
            {!! __('pagination.to') !!}
            {{ $paginator->lastItem() }}
            {!! __('pagination.of') !!}
            {{ $paginator->total() }}
            {!! __('pagination.results') !!}
        </span>
    
        <div class="flex items-center">
    
            @if ($paginator->onFirstPage())
                <span class="mr-3 text-xs cursor-not-allowed text-gray-300">
                    <i class="las la-angle-left"></i>
                </span>
            @else
                <a href="#" wire:click.prevent="previousPage('{{ $paginator->getPageName() }}')" class="mr-3 text-xs text-gray-600 hover:text-[#2CAA2C] transition cursor-pointer">
                    <i class="las la-angle-left"></i>
                </a>
            @endif

            @foreach ($elements as $element)

                @if (is_string($element))
                    <span class="px-2 py-1 text-sm rounded-sm text-gray-300 cursor-no-drop">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        @if ($page == $paginator->currentPage())
                            <span class="px-2 py-1 text-sm cursor-no-drop rounded-sm border border-[#2CAA2C]/40 bg-[#2CAA2C]/10 text-[#1E7F1E] font-semibold">{{ $page }}</span>
                        @else
                            <a href="#" wire:click.prevent="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')" class="px-2 py-1 text-sm rounded-sm text-[#111827] hover:text-[#2CAA2C] hover:bg-[#2CAA2C]/5 transition cursor-pointer">{{ $page }}</a>
                        @endif
                    @endforeach
                @endif

            @endforeach
    
            @if ($paginator->hasMorePages())
                <a href="#" wire:click.prevent="nextPage('{{ $paginator->getPageName() }}')" class="ml-3 text-xs text-gray-600 hover:text-[#2CAA2C] transition cursor-pointer">
                    <i class="las la-angle-right"></i>
                </a>               
            @else
                <span class="ml-3 text-xs cursor-not-allowed text-gray-300">
                    <i class="las la-angle-right"></i>
                </span>
            @endif
    
        </div>
    
    </div>
@endif
