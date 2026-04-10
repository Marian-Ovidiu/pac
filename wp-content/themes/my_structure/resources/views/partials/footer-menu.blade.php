@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
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
    <div class="ui-container">
        <div class="ui-footer__panel">
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
                                <li>
                                    <a class="ui-footer__nav-link" href="{{ $item->url }}">
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
                            <a href="https://www.facebook.com/share/15kZKmU4gr/" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="Facebook">
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="Instagram">
                                Instagram
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/" rel="noreferrer noopener" target="_blank" class="ui-footer__social-link" aria-label="LinkedIn">
                                LinkedIn
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
