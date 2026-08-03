@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $missionsUrl = home_url('/4-progetti-antibracconaggio-sociale/');
    $primaryItems = [];

    foreach ($menu as $item) {
        $haystack = strtolower(($item->title ?? '') . ' ' . ($item->url ?? ''));
        if (str_contains($haystack, 'home')) {
            continue;
        }
        if (str_contains($haystack, 'progett')) {
            $item->display_title = 'Missioni';
            $missionsUrl = $item->url ?: $missionsUrl;
        } else {
            $item->display_title = $item->title;
        }
        $primaryItems[] = $item;
    }

    $languages = [];
    if (function_exists('pll_get_the_languages')) {
        $rawLanguages = pll_get_the_languages(['raw' => 1, 'hide_if_empty' => 1]);
        if (is_array($rawLanguages) && count($rawLanguages) > 1) {
            $languages = array_values(array_filter($rawLanguages, static fn($language) => !empty($language['url'])));
        }
    }
@endphp

<header class="site-header" x-data="mobileNavigation">
    <div class="site-container site-header__inner">
        <a href="{{ esc_url(home_url('/')) }}" class="site-header__brand" aria-label="PAC Project Africa Conservation — torna alla Home">
            @include('components.brand-lockup', ['options' => $options])
        </a>

        <nav class="desktop-navigation" aria-label="Navigazione principale">
            <ul>
                @foreach($primaryItems as $item)
                    @php
                        $classes = is_array($item->classes ?? null) ? $item->classes : [];
                        $isCurrent = in_array('current-menu-item', $classes, true) || in_array('current_page_item', $classes, true);
                    @endphp
                    <li>
                        <a href="{{ esc_url($item->url) }}" @if($isCurrent) aria-current="page" @endif>
                            {{ $item->display_title }}
                        </a>
                        @if(!empty($item->children))
                            <ul class="desktop-navigation__submenu">
                                @foreach($item->children as $child)
                                    <li><a href="{{ esc_url($child->url) }}">{{ $child->title }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>
        </nav>

        <div class="site-header__actions">
            @if(!empty($languages))
                <div class="language-switcher">
                    <button type="button" class="language-switcher__trigger" aria-haspopup="true">
                        {{ strtoupper(pll_current_language('slug')) }}
                    </button>
                    <ul>
                        @foreach($languages as $language)
                            <li>
                                <a href="{{ esc_url($language['url']) }}" lang="{{ esc_attr($language['slug']) }}" @if(!empty($language['current_lang'])) aria-current="page" @endif>
                                    {{ strtoupper($language['slug']) }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <a class="button site-header__donate" href="{{ esc_url($missionsUrl) }}">Dona ora</a>
            <button
                type="button"
                class="menu-trigger"
                x-ref="trigger"
                @click="show()"
                :aria-expanded="open.toString()"
                aria-controls="mobile-navigation">
                <span>Menu</span>
                <span class="menu-trigger__lines" aria-hidden="true"></span>
            </button>
        </div>
    </div>

    <div
        id="mobile-navigation"
        class="mobile-navigation"
        x-cloak
        x-show="open"
        x-transition.opacity.duration.300ms
        @keydown="handleKeydown($event)"
        @click.self="close()"
        role="dialog"
        aria-modal="true"
        aria-label="Menu principale">
        <div class="mobile-navigation__panel" x-ref="panel">
            <div class="mobile-navigation__top">
                @include('components.brand-lockup', ['options' => $options])
                <button type="button" class="mobile-navigation__close" @click="close()">Chiudi</button>
            </div>
            <nav aria-label="Navigazione mobile">
                <ul class="mobile-navigation__list">
                    @foreach($primaryItems as $item)
                        @if(!empty($item->children))
                            <li>
                                <details>
                                    <summary>{{ $item->display_title }}</summary>
                                    <ul>
                                        <li><a href="{{ esc_url($item->url) }}">Tutte le missioni</a></li>
                                        @foreach($item->children as $child)
                                            <li><a href="{{ esc_url($child->url) }}">{{ $child->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </details>
                            </li>
                        @else
                            <li><a href="{{ esc_url($item->url) }}">{{ $item->display_title }}</a></li>
                        @endif
                    @endforeach
                </ul>
            </nav>
            <div class="mobile-navigation__bottom">
                <a class="button button--light" href="{{ esc_url($missionsUrl) }}">Sostieni una missione</a>
                @if(!empty($languages))
                    <div class="mobile-navigation__languages" aria-label="Lingue disponibili">
                        @foreach($languages as $language)
                            <a href="{{ esc_url($language['url']) }}" lang="{{ esc_attr($language['slug']) }}" @if(!empty($language['current_lang'])) aria-current="page" @endif>
                                {{ strtoupper($language['slug']) }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</header>
