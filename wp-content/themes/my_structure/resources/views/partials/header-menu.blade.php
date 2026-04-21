@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
@endphp
<header class="ui-site-header">
    <div class="ui-container">
        <div class="ui-card-soft px-4 py-4 sm:px-6 sm:py-5">
            <nav class="flex items-center justify-between gap-6" aria-label="Navigazione principale">
                <a href="{{ home_url('/') }}" title="{{ __('Home', 'my_structure') }}" class="ui-brand-lockup shrink-0">
                    @if ($logoUrl)
                        <div class="flex h-16 w-auto items-center sm:h-[4.5rem]">
                            <img class="h-full w-auto object-contain" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                        </div>
                    @endif
                </a>

                {{-- Menu mobile senza Alpine: <details> evita conflitti Blade (@click…) e funziona anche se main.js è in ritardo / bloccato dal banner cookie. --}}
                {{-- details senza position:relative così .ui-mobile-menu (absolute) resta ancorato a .ui-card-soft (wide), non al box stretto del summary. --}}
                <details class="ui-mobile-nav-disclosure lg:hidden">
                    <summary
                        class="ui-button-secondary cursor-pointer select-none !px-4 !py-2.5 list-none marker:content-none [&::-webkit-details-marker]:hidden"
                        aria-controls="mobile-primary-menu"
                        aria-label="Apri o chiudi menu principale">
                        Menu
                    </summary>
                    <nav
                        id="mobile-primary-menu"
                        class="ui-mobile-menu"
                        aria-label="Navigazione mobile">
                        <ul class="ui-mobile-menu__list">
                            @foreach ($menu as $item)
                                @php
                                    $itemClasses = is_array($item->classes ?? null) ? $item->classes : [];
                                    $isCurrent = in_array('current-menu-item', $itemClasses, true) || in_array('current_page_item', $itemClasses, true);
                                    $hasChildren = !empty($item->children);
                                @endphp
                                <li class="ui-mobile-menu__item">
                                    <a
                                        href="{{ $item->url }}"
                                        class="ui-mobile-menu__link"
                                        @if($isCurrent) aria-current="page" @endif
                                        @if($hasChildren) aria-haspopup="true" @endif>
                                        <span>{{ $item->title }}</span>
                                        @if($hasChildren)
                                            <span class="ui-mobile-menu__count">{{ count($item->children) }}</span>
                                        @endif
                                    </a>
                                    @if ($hasChildren)
                                        <ul class="ui-mobile-menu__submenu">
                                            @foreach ($item->children as $subitem)
                                                @php
                                                    $subitemClasses = is_array($subitem->classes ?? null) ? $subitem->classes : [];
                                                    $isSubCurrent = in_array('current-menu-item', $subitemClasses, true) || in_array('current_page_item', $subitemClasses, true);
                                                @endphp
                                                <li>
                                                    <a href="{{ $subitem->url }}" class="ui-mobile-menu__sublink" @if($isSubCurrent) aria-current="page" @endif>
                                                        {{ $subitem->title }}
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </details>

                <ul class="ml-auto hidden items-center gap-2 xl:gap-3 lg:flex">
                    @foreach ($menu as $item)
                        @php
                            $itemClasses = is_array($item->classes ?? null) ? $item->classes : [];
                            $isCurrent = in_array('current-menu-item', $itemClasses, true) || in_array('current_page_item', $itemClasses, true);
                        @endphp
                        <li class="group relative">
                            <a
                                href="{{ $item->url }}"
                                class="ui-button-secondary !rounded-full !border border-transparent !bg-transparent !px-4 !py-2.5 !shadow-none hover:!border-custom-dark-green/10 hover:!bg-white/70"
                                @if($isCurrent) aria-current="page" @endif
                                @if(!empty($item->children)) aria-haspopup="true" @endif>
                                {{ $item->title }}
                            </a>
                            @if (!empty($item->children))
                                <ul class="ui-header-submenu">
                                    @foreach ($item->children as $subitem)
                                        @php
                                            $subitemClasses = is_array($subitem->classes ?? null) ? $subitem->classes : [];
                                            $isSubCurrent = in_array('current-menu-item', $subitemClasses, true) || in_array('current_page_item', $subitemClasses, true);
                                        @endphp
                                        <li>
                                            <a href="{{ $subitem->url }}" class="ui-header-submenu__link" @if($isSubCurrent) aria-current="page" @endif>
                                                {{ $subitem->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</header>
