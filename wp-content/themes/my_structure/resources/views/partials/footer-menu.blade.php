@php
    $options      = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl      = $options->logo['url'] ?? null;
    $socialUrls   = theme_social_urls();
    $dispatchNum  = '47';
    $dispatchDate = date_i18n('M Y');

    $flatMenu = [];
    foreach ($menu as $item) {
        $flatMenu[] = $item;
        if (!empty($item->children)) {
            foreach ($item->children as $subitem) {
                $flatMenu[] = $subitem;
            }
        }
    }
@endphp

<footer class="fd-footer" aria-label="Footer Project Africa Conservation">

    <div class="ui-container fd-footer__top">

        {{-- ── Brand ── --}}
        <div class="fd-footer__brand">
            @if($logoUrl)
                <img class="fd-footer__logo"
                     src="{{ $logoUrl }}"
                     alt="Project Africa Conservation logo"
                     loading="lazy"
                     decoding="async">
            @endif
            <div class="fd-footer__brand-copy">
                <span class="fd-footer__brand-name">PAC</span>
                <span class="fd-footer__brand-tag">Project Africa Conservation</span>
            </div>
        </div>

        {{-- ── Claim ── --}}
        <p class="fd-footer__claim">
            La differenza la fai tu: ogni contributo si traduce in ore di pattuglia,
            equipaggiamento K-9 e protezione concreta per la fauna africana.
        </p>

    </div>

    {{-- ── Divider ── --}}
    <div class="fd-footer__rule" aria-hidden="true"></div>

    {{-- ── Middle grid ── --}}
    <div class="ui-container fd-footer__middle">

        <div class="fd-footer__col">
            <span class="fd-footer__col-label">Contatti</span>
            <ul class="fd-footer__list">
                <li>
                    <a href="mailto:info@project-africa-conservation.org"
                       class="fd-footer__link">
                        info@project-africa-conservation.org
                    </a>
                </li>
                <li>
                    <span class="fd-footer__text">Via Cavour 7 &#8212; 12042, Bra (CN)</span>
                </li>
                <li>
                    <span class="fd-footer__text">P.IVA / CF: registrata in Italia</span>
                </li>
            </ul>
        </div>

        <div class="fd-footer__col">
            <span class="fd-footer__col-label">Navigazione</span>
            <nav aria-label="Navigazione footer">
                <ul class="fd-footer__list">
                    @foreach($flatMenu as $item)
                        @php
                            $itemClasses = is_array($item->classes ?? null) ? $item->classes : [];
                            $isCurrent   = in_array('current-menu-item', $itemClasses, true)
                                        || in_array('current_page_item', $itemClasses, true);
                        @endphp
                        <li>
                            <a class="fd-footer__link"
                               href="{{ $item->url }}"
                               @if($isCurrent) aria-current="page" @endif>
                                {{ $item->title }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>

        <div class="fd-footer__col">
            <span class="fd-footer__col-label">Social</span>
            <ul class="fd-footer__social" aria-label="Social media PAC">
                @if(!empty($socialUrls['facebook']))
                    <li>
                        <a href="{{ esc_url($socialUrls['facebook']) }}"
                           class="fd-footer__social-link"
                           rel="noreferrer noopener"
                           target="_blank"
                           aria-label="PAC su Facebook">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor">
                                <path d="M13.5 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.2-1.5 1.5-1.5h1.7V3.6c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.1v2.3H7.6V13h2.7v8h3.2z"/>
                            </svg>
                            <span>Facebook</span>
                        </a>
                    </li>
                @endif
                @if(!empty($socialUrls['instagram']))
                    <li>
                        <a href="{{ esc_url($socialUrls['instagram']) }}"
                           class="fd-footer__social-link"
                           rel="noreferrer noopener"
                           target="_blank"
                           aria-label="PAC su Instagram">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor">
                                <path d="M7.8 2.5h8.4c2.9 0 5.3 2.4 5.3 5.3v8.4c0 2.9-2.4 5.3-5.3 5.3H7.8c-2.9 0-5.3-2.4-5.3-5.3V7.8c0-2.9 2.4-5.3 5.3-5.3zm0 1.9c-1.9 0-3.4 1.5-3.4 3.4v8.4c0 1.9 1.5 3.4 3.4 3.4h8.4c1.9 0 3.4-1.5 3.4-3.4V7.8c0-1.9-1.5-3.4-3.4-3.4H7.8zm4.2 3.2a4.4 4.4 0 1 1 0 8.8 4.4 4.4 0 0 1 0-8.8zm0 1.9a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm4.6-2.3a1 1 0 1 1 0 2.1 1 1 0 0 1 0-2.1z"/>
                            </svg>
                            <span>Instagram</span>
                        </a>
                    </li>
                @endif
                @if(!empty($socialUrls['linkedin']))
                    <li>
                        <a href="{{ esc_url($socialUrls['linkedin']) }}"
                           class="fd-footer__social-link"
                           rel="noreferrer noopener"
                           target="_blank"
                           aria-label="PAC su LinkedIn">
                            <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false" fill="currentColor">
                                <path d="M6.9 8.9H3.7V21h3.2V8.9zM5.3 3a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zm6.8 5.9H9V21h3.2v-6c0-1.6.3-3.1 2.3-3.1 1.9 0 2 1.8 2 3.2V21h3.2v-6.7c0-3.3-.7-5.7-4.5-5.7-1.8 0-3 .9-3.5 1.8h-.1V8.9z"/>
                            </svg>
                            <span>LinkedIn</span>
                        </a>
                    </li>
                @endif
            </ul>
        </div>

    </div>

    {{-- ── Bottom / colophon ── --}}
    <div class="fd-footer__rule" aria-hidden="true"></div>

    <div class="ui-container fd-footer__bottom">

        <div class="fd-footer__legal">
            <span>&copy; {{ date('Y') }} PAC &#8212; Project Africa Conservation A.P.S.</span>
            <span aria-hidden="true">&#183;</span>
            <a href="{{ home_url('/privacy-policy') }}" class="fd-footer__legal-link">Privacy</a>
            <span aria-hidden="true">&#183;</span>
            <a href="{{ home_url('/termini-di-servizio') }}" class="fd-footer__legal-link">Termini</a>
        </div>

        <p class="fd-footer__colophon" aria-label="Numero dispatch e data aggiornamento">
            Field Dispatch #{{ $dispatchNum }}
            <span aria-hidden="true">&#183;</span>
            Aggiornato {{ $dispatchDate }}
            <span aria-hidden="true">&#183;</span>
            Operazioni attive
        </p>

    </div>

    <div class="ui-container fd-footer__credit-bar">
        <p class="fd-footer__credit" lang="it">
            Progettato e costruito con cura da
            <span lang="en">Marian</span>
            <span aria-hidden="true">&#8212;</span>
            <span lang="en">made with love &amp; coffee</span>
        </p>
    </div>

</footer>
