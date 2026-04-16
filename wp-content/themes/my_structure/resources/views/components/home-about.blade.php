@php
    $stats = [
        [
            'value' => (string) count($projects),
            'label' => 'progetti attivi',
        ],
        [
            'value' => '3',
            'label' => 'aree di impatto',
        ],
        [
            'value' => '365',
            'label' => 'giorni sul campo',
        ],
    ];

    $highlights = [
        'Tutela della fauna e anti-bracconaggio.',
        'Progetti sociali radicati nelle comunita locali.',
        'Presenza continua, relazioni stabili, risultati misurabili.',
    ];

    $aboutImage = is_array($image ?? null) ? $image : null;
    $aboutImageAlt = trim($aboutImage['alt'] ?? '') ?: 'Project Africa Conservation: ' . $title;
    $primaryCtaLabel = !empty($primaryCta['title']) ? $primaryCta['title'] . ' - ' . $title : null;
    $secondaryCtaLabel = !empty($secondaryCta['title']) ? $secondaryCta['title'] . ' - ' . $title : null;
@endphp

<section class="ui-home-about" aria-label="{{ $title }}">
    <div class="ui-container">
        <div class="hidden lg:grid ui-home-about__desktop">
            <div class="ui-home-about__panel">
                <span class="ui-home-about__eyebrow">Chi siamo</span>
                <h2 class="ui-home-about__title">{{ $title }}</h2>
                <p class="ui-home-about__copy">{{ $description }}</p>

                <div class="ui-home-about__actions">
                    @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                        <a href="{{ $primaryCta['url'] }}" aria-label="{{ $primaryCtaLabel }}" class="ui-home-about__button ui-home-about__button--primary">
                            {{ $primaryCta['title'] }}
                        </a>
                    @endif
                    @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                        <a href="{{ $secondaryCta['url'] }}" aria-label="{{ $secondaryCtaLabel }}" class="ui-home-about__button ui-home-about__button--secondary">
                            {{ $secondaryCta['title'] }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="ui-home-about__visual">
                @if(!empty($aboutImage['url']))
                    <figure class="ui-home-about__image-wrap">
                        <img
                            src="{{ $aboutImage['url'] }}"
                            alt="{{ $aboutImageAlt }}"
                            class="ui-home-about__image"
                            loading="lazy"
                            decoding="async">
                    </figure>
                @else
                    <div class="ui-home-about__image-wrap ui-home-about__image-wrap--mock" aria-hidden="true"></div>
                @endif

                <div class="ui-home-about__floating ui-home-about__floating--stats" role="group" aria-label="Numeri di Project Africa Conservation">
                    @foreach($stats as $stat)
                        <div class="ui-home-about__stat">
                            <div class="ui-home-about__stat-value">{{ $stat['value'] }}</div>
                            <div class="ui-home-about__stat-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="ui-home-about__floating ui-home-about__floating--note">
                    <div class="ui-home-about__note-kicker">Approccio</div>
                    <ul class="ui-home-about__list" aria-label="Punti chiave dell'approccio PAC">
                        @foreach($highlights as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>

        <div class="lg:hidden ui-home-about__mobile">
            <div class="ui-home-about__mobile-card">
                <span class="ui-home-about__eyebrow">Chi siamo</span>
                <h2 class="ui-home-about__mobile-title">{{ $title }}</h2>
                <p class="ui-home-about__mobile-copy">{{ $description }}</p>

                @if(!empty($aboutImage['url']))
                    <figure class="ui-home-about__mobile-image-wrap">
                        <img
                            src="{{ $aboutImage['url'] }}"
                            alt="{{ $aboutImageAlt }}"
                            class="ui-home-about__mobile-image"
                            loading="lazy"
                            decoding="async">
                    </figure>
                @endif

                <div class="ui-home-about__mobile-stats" role="group" aria-label="Numeri di Project Africa Conservation">
                    @foreach($stats as $stat)
                        <div class="ui-home-about__mobile-stat">
                            <div class="ui-home-about__stat-value">{{ $stat['value'] }}</div>
                            <div class="ui-home-about__stat-label">{{ $stat['label'] }}</div>
                        </div>
                    @endforeach
                </div>

                <div class="ui-home-about__mobile-note">
                    <div class="ui-home-about__note-kicker">Approccio</div>
                    <ul class="ui-home-about__list" aria-label="Punti chiave dell'approccio PAC">
                        @foreach($highlights as $highlight)
                            <li>{{ $highlight }}</li>
                        @endforeach
                    </ul>
                </div>

                <div class="ui-home-about__actions ui-home-about__actions--mobile">
                    @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                        <a href="{{ $primaryCta['url'] }}" aria-label="{{ $primaryCtaLabel }}" class="ui-home-about__button ui-home-about__button--primary">
                            {{ $primaryCta['title'] }}
                        </a>
                    @endif
                    @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                        <a href="{{ $secondaryCta['url'] }}" aria-label="{{ $secondaryCtaLabel }}" class="ui-home-about__button ui-home-about__button--secondary">
                            {{ $secondaryCta['title'] }}
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
