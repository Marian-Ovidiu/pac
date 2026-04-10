<section class="ui-mono-logo">
    <div class="ui-container">
        <div class="hidden lg:flex ui-mono-logo__desktop">
            <div class="ui-mono-logo__mark" aria-hidden="true">
                <span></span>
                <span></span>
            </div>

            <div class="ui-mono-logo__quote-wrap">
                @if($titolo_monologo)
                    <h2 class="ui-mono-logo__quote">"{{ $titolo_monologo }}"</h2>
                @endif
            </div>

            @if($immagine_monologo)
                <div class="ui-mono-logo__brand">
                    <img
                        src="{{ $immagine_monologo['url'] }}"
                        alt="{{ $immagine_monologo['alt'] ?? ($titolo_monologo ?? 'Partner') }}"
                        class="ui-mono-logo__brand-image">
                </div>
            @endif

            @if($sottotitolo_monologo)
                <div class="ui-mono-logo__meta">{{ $sottotitolo_monologo }}</div>
            @endif
        </div>

        <div class="lg:hidden ui-mono-logo__mobile-shell">
            <article class="ui-mono-logo__mobile-card">
                <div class="ui-mono-logo__mobile-copy">
                    <span class="ui-mono-logo__mobile-kicker">Featured partner</span>
                    @if($titolo_monologo)
                        <h2 class="ui-mono-logo__mobile-title">{{ $titolo_monologo }}</h2>
                    @endif
                </div>

                @if($immagine_monologo)
                    <div class="ui-mono-logo__mobile-brand">
                        <img
                            src="{{ $immagine_monologo['url'] }}"
                            alt="{{ $immagine_monologo['alt'] ?? ($titolo_monologo ?? 'Partner') }}"
                            class="ui-mono-logo__mobile-brand-image">
                    </div>
                @endif

                <div class="ui-mono-logo__mobile-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" class="h-5 w-5">
                        <path d="M14 4H20V10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M10 14L20 4" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 14V18C20 19.1046 19.1046 20 18 20H6C4.89543 20 4 19.1046 4 18V6C4 4.89543 4.89543 4 6 4H10" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
            </article>
        </div>
    </div>
</section>
