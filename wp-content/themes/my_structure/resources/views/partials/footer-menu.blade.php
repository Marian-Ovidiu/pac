@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
    $socialUrls = theme_social_urls();
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
<footer class="ui-footer">
    <div class="ui-footer__panel">
        <div class="ui-container">
            <div class="ui-footer__top">
                <div class="ui-footer__brand">
                    @if ($logoUrl)
                        <div class="ui-footer__logo-wrap">
                            <img class="ui-footer__logo" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                        </div>
                    @endif

                    <div>
                        <p class="ui-footer__eyebrow">Project Africa Conservation</p>
                        <h2 class="ui-footer__title">Conservazione, comunita, continuita</h2>
                    </div>
                </div>

                <div class="ui-footer__copy">
                    <p>
                        La differenza la fai tu: ogni piccolo gesto crea un cambiamento concreto per la fauna e per le comunita locali.
                    </p>
                </div>
            </div>

            <div class="ui-footer__middle">
                <div class="ui-footer__column">
                    <span class="ui-footer__label">Contatti</span>
                    <ul class="ui-footer__list">
                        <li>
                            <a href="mailto:info@project-africa-conservation.org" class="ui-footer__text-link">
                                info@project-africa-conservation.org
                            </a>
                        </li>
                        <li>
                            <span class="ui-footer__text">Via Cavour 7 - 12042, Bra (CN)</span>
                        </li>
                    </ul>
                </div>

                <div class="ui-footer__column">
                    <span class="ui-footer__label">Navigazione</span>
                    <nav aria-label="Navigazione footer">
                        <ul class="ui-footer__nav">
                            @foreach($flatMenu as $item)
                                @php
                                    $itemClasses = is_array($item->classes ?? null) ? $item->classes : [];
                                    $isCurrent = in_array('current-menu-item', $itemClasses, true) || in_array('current_page_item', $itemClasses, true);
                                @endphp
                                <li>
                                    <a class="ui-footer__nav-link" href="{{ $item->url }}" @if($isCurrent) aria-current="page" @endif>
                                        {{ $item->title }}
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </nav>
                </div>

                <div class="ui-footer__column">
                    <span class="ui-footer__label">Social</span>
                    <ul class="ui-footer__social" aria-label="Social media">
                        <li>
                            <a href="{{ esc_url($socialUrls['facebook']) }}" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="PAC su Facebook">
                                <svg class="ui-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M13.5 21v-8h2.7l.4-3.1h-3.1v-2c0-.9.2-1.5 1.5-1.5h1.7V3.6c-.3 0-1.3-.1-2.4-.1-2.4 0-4 1.5-4 4.1v2.3H7.6V13h2.7v8h3.2z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ esc_url($socialUrls['instagram']) }}" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="PAC su Instagram">
                                <svg class="ui-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M7.8 2.5h8.4c2.9 0 5.3 2.4 5.3 5.3v8.4c0 2.9-2.4 5.3-5.3 5.3H7.8c-2.9 0-5.3-2.4-5.3-5.3V7.8c0-2.9 2.4-5.3 5.3-5.3zm0 1.9c-1.9 0-3.4 1.5-3.4 3.4v8.4c0 1.9 1.5 3.4 3.4 3.4h8.4c1.9 0 3.4-1.5 3.4-3.4V7.8c0-1.9-1.5-3.4-3.4-3.4H7.8zm4.2 3.2a4.4 4.4 0 1 1 0 8.8 4.4 4.4 0 0 1 0-8.8zm0 1.9a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zm4.6-2.3a1 1 0 1 1 0 2.1 1 1 0 0 1 0-2.1z" />
                                </svg>
                            </a>
                        </li>
                        <li>
                            <a href="{{ esc_url($socialUrls['linkedin']) }}" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="PAC su LinkedIn">
                                <svg class="ui-footer__social-icon" viewBox="0 0 24 24" aria-hidden="true" focusable="false">
                                    <path d="M6.9 8.9H3.7V21h3.2V8.9zM5.3 3a1.9 1.9 0 1 0 0 3.8 1.9 1.9 0 0 0 0-3.8zm6.8 5.9H9V21h3.2v-6c0-1.6.3-3.1 2.3-3.1 1.9 0 2 1.8 2 3.2V21h3.2v-6.7c0-3.3-.7-5.7-4.5-5.7-1.8 0-3 .9-3.5 1.8h-.1V8.9z" />
                                </svg>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="ui-footer__bottom">
                <p>&copy; 2024. PAC - Project Africa Conservation A.P.S. Tutti i diritti riservati.</p>
                <p>Questo sito e protetto da reCAPTCHA ed e soggetto alla Privacy Policy e ai Termini di Servizio di Google.</p>
            </div>
        </div>
    </div>
</footer>
