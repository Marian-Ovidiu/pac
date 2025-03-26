@php
$options = \Models\Options\OpzioniGlobaliFields::get();
@endphp
<header x-data="{ open: false }" class="p-6 bg-white lg:pb-0">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- lg+ -->
        <nav class="flex items-center justify-between h-16 lg:h-20">
            <a href="{{ get_home_url(null, '/', 'https') }}" title="{{ __('Home', 'text_domain') }}" class="flex">
                <div class="text-center flex flex-col items-center justify-between">
                    <img class="w-auto h-12 lg:h-12" src="{{$options->logo['url']}}" alt="" />
                    <div class="text-custom-dark-green font-bold text-xs">Project Africa Conservation</div>
                </div>
            </a>


            <button @click="open = !open" type="button" class="inline-flex p-2 text-black transition-all duration-200 rounded-md lg:hidden focus:bg-gray-100 hover:bg-gray-100">
                <!-- Menu open: "hidden", Menu closed: "block" -->
                <svg x-show="!open" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16" />
                </svg>

                <!-- Menu open: "block", Menu closed: "hidden" -->
                <svg x-show="open" class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <div class="hidden lg:flex lg:items-center lg:ml-auto lg:space-x-10">
                @foreach($menu as $key => $item)
                    <a href="{{ $item->url }}" title="" class="text-base font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans"> {{ $item->title }} </a>
                    @if(!empty($item->children))
                        @foreach($item->children as $subkey => $subitem)
                            <a href="{{ $subitem->url }}" title="" class="text-base font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans"> {{ $subitem->title }} </a>
                        @endforeach
                    @endif
                @endforeach
            </div>
        </nav>

        <!-- xs to lg -->
        <nav x-show="open" @click.away="open = false"
             x-transition:enter="transition ease-out duration-400"
             x-transition:enter-start="opacity-0 transform scale-95"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-95"
             class="absolute inset-x-0 pt-4 pb-6 bg-white border border-gray-200 rounded-md shadow-md lg:hidden z-50">
            <div class="flow-root">
                <div class="flex flex-col items-center px-6 -my-2 space-y-4 pt-2">
                    @foreach($menu as $item)
                        <div>
                            <a href="{{ $item->url }}"
                               title=""
                               class="text-base text-center font-medium text-black transition-all duration-200 hover:text-custom-green focus:text-custom-green font-nunitoSans flex items-center">
                                {{ $item->title }}
                            </a>
                        </div>
                        @if(!empty($item->children))
                            <div class="mt-1 space-y-1">
                                @foreach($item->children as $subitem)
                                    <a href="{{ $subitem->url }}"
                                       title=""
                                       class="block text-center text-sm font-medium text-gray-700 transition-all duration-200 hover:text-custom-green">
                                        {{ $subitem->title }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </nav>
    </div>
</header>
