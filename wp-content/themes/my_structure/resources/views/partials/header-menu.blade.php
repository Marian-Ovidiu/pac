@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
    $dispatchNumber = '47';
    $dispatchDate = date_i18n('M Y');
@endphp

<header class="ui-site-header ui-dispatch-header">
    <div class="ui-dispatch-header__bar">
        <div class="ui-container ui-dispatch-header__inner">
            <a href="{{ home_url('/') }}" title="{{ __('Home', 'my_structure') }}" class="ui-dispatch-brand">
                @if ($logoUrl)
                    <span class="ui-dispatch-brand__mark">
                        <img src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                    </span>
                @endif
                <span class="ui-dispatch-brand__copy">
                    <span class="ui-dispatch-brand__name">PAC</span>
                    <span class="ui-dispatch-brand__tag">Project Africa Conservation</span>
                </span>
            </a>

            <nav class="ui-dispatch-nav" aria-label="Navigazione principale">
                <ul class="ui-dispatch-nav__list">
                    @foreach ($menu as $item)
                        @php
                            $itemClasses = is_array($item->classes ?? null) ? $item->classes : [];
                            $isCurrent = in_array('current-menu-item', $itemClasses, true) || in_array('current_page_item', $itemClasses, true);
                            $haystack = strtolower(($item->title ?? '') . ' ' . ($item->url ?? ''));
                            $isDonate = strpos($haystack, 'dona') !== false || strpos($haystack, 'donat') !== false;
                        @endphp
                        <li class="ui-dispatch-nav__item group">
                            <a
                                href="{{ $item->url }}"
                                class="ui-dispatch-nav__link {{ $isDonate ? 'ui-dispatch-nav__link--cta' : '' }}"
                                @if($isCurrent) aria-current="page" @endif
                                @if(!empty($item->children)) aria-haspopup="true" @endif>
                                {{ $isDonate ? __('Fund an operation', 'my_structure') : $item->title }}
                                @if($isDonate)
                                    <span aria-hidden="true">-&gt;</span>
                                @endif
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

            <details class="ui-mobile-nav-disclosure ui-dispatch-mobile lg:hidden">
                <summary
                    class="ui-dispatch-mobile__summary"
                    aria-controls="mobile-primary-menu"
                    aria-label="Apri o chiudi menu principale">
                    Menu
                </summary>
                <nav id="mobile-primary-menu" class="ui-mobile-menu" aria-label="Navigazione mobile">
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
        </div>
    </div>

    <div class="ui-dispatch-ticker" aria-label="Metriche operative">
        <div class="ui-container ui-dispatch-ticker__inner">
            <span>Field Dispatch #{{ $dispatchNumber }}</span>
            <span>847 km&#178; sotto pattuglia attiva</span>
            <span>3 unit&#224; K-9 operative</span>
            <span>{{ $dispatchDate }}</span>
        </div>
    </div>
</header>
