@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $socialUrls = theme_social_urls();
    $missionsUrl = home_url('/4-progetti-antibracconaggio-sociale/');
    $navigationItems = [];
    $legalItems = [];

    foreach ($menu as $item) {
        $haystack = strtolower(($item->title ?? '') . ' ' . ($item->url ?? ''));
        if (str_contains($haystack, 'privacy') || str_contains($haystack, 'cookie')) {
            $legalItems[] = $item;
            continue;
        }
        if (str_contains($haystack, 'progett')) {
            $missionsUrl = $item->url ?: $missionsUrl;
        }
        $navigationItems[] = $item;
    }
@endphp

<footer class="site-footer">
    <div class="site-container site-footer__lead">
        <a href="{{ esc_url(home_url('/')) }}" aria-label="PAC Project Africa Conservation — torna alla Home">
            @include('components.brand-lockup', ['options' => $options, 'class' => 'brand-lockup--footer'])
        </a>
        <div>
            <p class="site-footer__mission">Proteggiamo chi protegge l'Africa.</p>
            <p>Con ranger, unità K-9 e comunità locali trasformiamo il sostegno in presenza, strumenti e futuro.</p>
        </div>
        <a class="button button--light" href="{{ esc_url($missionsUrl) }}">Dona ora</a>
    </div>

    <div class="site-container site-footer__grid">
        <section aria-labelledby="footer-navigation-title">
            <h2 id="footer-navigation-title">Esplora</h2>
            <nav aria-label="Navigazione footer">
                <ul>
                    @foreach($navigationItems as $item)
                        <li><a href="{{ esc_url($item->url) }}">{{ $item->title === 'Progetti' ? 'Missioni' : $item->title }}</a></li>
                    @endforeach
                </ul>
            </nav>
        </section>

        <section aria-labelledby="footer-contact-title">
            <h2 id="footer-contact-title">Contatti</h2>
            <address>
                <a href="mailto:info@project-africa-conservation.org">info@project-africa-conservation.org</a>
                <span>Via Cavour 7, 12042 Bra (CN)</span>
            </address>
        </section>

        <section aria-labelledby="footer-social-title">
            <h2 id="footer-social-title">Seguici</h2>
            <ul>
                @foreach(['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'] as $network => $label)
                    @if(!empty($socialUrls[$network]))
                        <li><a href="{{ esc_url($socialUrls[$network]) }}" target="_blank" rel="noreferrer noopener">{{ $label }}<span class="sr-only">, si apre in una nuova finestra</span></a></li>
                    @endif
                @endforeach
            </ul>
        </section>

        <section aria-labelledby="footer-transparency-title">
            <h2 id="footer-transparency-title">Trasparenza</h2>
            <ul>
                @foreach($legalItems as $item)
                    <li><a href="{{ esc_url($item->url) }}">{{ $item->title }}</a></li>
                @endforeach
            </ul>
        </section>
    </div>

    <div class="site-container site-footer__legal">
        <span>&copy; {{ date('Y') }} PAC — Project Africa Conservation A.P.S.</span>
        @foreach($legalItems as $item)
            <a href="{{ esc_url($item->url) }}">{{ $item->title }}</a>
        @endforeach
    </div>
</footer>
