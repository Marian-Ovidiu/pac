@php
    $projectSlides = array_values(array_filter($featuredProjects ?? [], static function ($project) {
        $src = theme_acf_image_url($project['immagine'] ?? null);
        $titolo = trim((string) ($project['titolo'] ?? ''));

        return $src !== '' && $titolo !== '';
    }));

    $resolveLocalMedia = static function ($src) {
        $src = trim((string) $src);

        if ($src === '') {
            return '';
        }

        $path = parse_url($src, PHP_URL_PATH);
        $localPath = is_string($path) ? ABSPATH . ltrim($path, '/') : '';

        if ($localPath !== '' && strpos($path, '/wp-content/uploads/') === 0 && !file_exists($localPath)) {
            $fallback = ABSPATH . 'wp-content/uploads/2025/03/mt-sample-background.jpg';

            if (file_exists($fallback)) {
                return home_url('/wp-content/uploads/2025/03/mt-sample-background.jpg');
            }
        }

        return $src;
    };
    $resolveLocalLink = static function ($url) {
        $url = trim((string) $url);

        if ($url === '') {
            return '';
        }

        return str_replace(
            ['https://project-africa-conservation.org', 'http://project-africa-conservation.org'],
            home_url(),
            $url
        );
    };

    $leadProject = $projectSlides[0] ?? null;
    $leadImage = is_array($leadProject['immagine'] ?? null) ? $leadProject['immagine'] : [];
    $leadSrc = $resolveLocalMedia(theme_acf_image_url($leadProject['immagine'] ?? null));
    $leadTitle = trim((string) ($leadProject['titolo'] ?? 'Operazione sul campo'));
    $leadAlt = trim($leadImage['alt'] ?? '') ?: 'Project Africa Conservation: ' . $leadTitle;
    $primaryCtaLabel = !empty($primaryCta['title']) ? $primaryCta['title'] . ' - Project Africa Conservation' : null;
    $secondaryCtaLabel = !empty($secondaryCta['title']) ? $secondaryCta['title'] . ' - Project Africa Conservation' : null;
    $dispatchDate = date_i18n('M Y');
@endphp

<section class="field-dispatch-hero" aria-label="{{ $title }}">
    <div class="ui-container field-dispatch-hero__inner">
        <div class="field-dispatch-hero__copy">
            <span class="field-dispatch-label">Dispatch #47 · {{ $dispatchDate }}</span>
            <h2 class="field-dispatch-hero__title">{{ $title }}</h2>
            <p class="field-dispatch-hero__text">{{ $description }}</p>

        </div>

        <figure class="field-dispatch-hero__visual">
            @if($leadSrc)
                <div class="field-dispatch-photo">
                    <img
                        src="{{ esc_url($leadSrc) }}"
                        alt="{{ $leadAlt }}"
                        loading="eager"
                        decoding="async"
                        fetchpriority="high">
                </div>
            @else
                <div class="field-dispatch-photo field-dispatch-photo--empty" aria-hidden="true"></div>
            @endif
            <figcaption class="field-dispatch-caption">
                <span>S 22deg 41' · E 31deg 02'</span>
                <span>{{ $leadTitle }} · Evidenza dal campo</span>
            </figcaption>
        </figure>

        <div class="field-dispatch-hero__actions">
            @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                <a href="{{ esc_url($resolveLocalLink($primaryCta['url'])) }}" aria-label="{{ $primaryCtaLabel }}" class="field-dispatch-button field-dispatch-button--primary">
                    Finanzia un'operazione
                    <span aria-hidden="true">-&gt;</span>
                </a>
            @endif
            @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                <a href="{{ esc_url($resolveLocalLink($secondaryCta['url'])) }}" aria-label="{{ $secondaryCtaLabel }}" class="field-dispatch-button field-dispatch-button--secondary">
                    Vedi missioni attive
                </a>
            @endif
        </div>
    </div>

    <div class="ui-container">
        <dl class="field-dispatch-status" aria-label="Stato operativo PAC">
            <div>
                <dt>Unit&#224; attive</dt>
                <dd>6</dd>
            </div>
            <div>
                <dt>km&#178; in pattuglia</dt>
                <dd>847</dd>
            </div>
            <div>
                <dt>Unit&#224; K-9 operative</dt>
                <dd>3</dd>
            </div>
            <div>
                <dt>Stato</dt>
                <dd>[OPERATIVO]</dd>
            </div>
        </dl>
    </div>

    @if(count($projectSlides) > 0)
        <div class="field-dispatch-field">
            <div class="ui-container">
                <div class="field-dispatch-section-head">
                    <span class="field-dispatch-label">Dal campo</span>
                    @if(!empty($secondaryCta['url']))
                        <a href="{{ esc_url($resolveLocalLink($secondaryCta['url'])) }}">Vedi tutti i dispacci -&gt;</a>
                    @endif
                </div>

                <div class="field-dispatch-grid">
                    @foreach($projectSlides as $index => $project)
                        @php
                            $projectTitle = trim((string) ($project['titolo'] ?? 'Field dispatch'));
                            $projectImage = is_array($project['immagine'] ?? null) ? $project['immagine'] : [];
                            $projectSrc = $resolveLocalMedia(theme_acf_image_url($project['immagine'] ?? null));
                            $projectAlt = trim($projectImage['alt'] ?? '') ?: 'Project Africa Conservation: ' . $projectTitle;
                            $projectDescription = trim(strip_tags((string) (($projectImage['caption'] ?? '') ?: ($projectImage['description'] ?? ''))));
                            $projectLink = $resolveLocalLink($project['cta']['url'] ?? '');
                        @endphp
                        <article class="field-dispatch-card {{ $index === 0 ? 'field-dispatch-card--lead' : '' }} {{ $index === 2 ? 'field-dispatch-card--text' : '' }}">
                            @if($projectLink)
                                <a href="{{ esc_url($projectLink) }}" class="field-dispatch-card__link" aria-label="Scopri il progetto: {{ $projectTitle }}"></a>
                            @endif

                            @if($projectSrc && $index !== 2)
                                <img src="{{ esc_url($projectSrc) }}" alt="{{ $projectAlt }}" loading="{{ $index === 0 ? 'eager' : 'lazy' }}" decoding="async">
                            @endif

                            <div class="field-dispatch-card__body">
                                <span>Dispatch #{{ str_pad((string) (47 - $index), 2, '0', STR_PAD_LEFT) }} · Active</span>
                                <h3>{{ $projectTitle }}</h3>
                                @if($projectDescription)
                                    <p>{{ wp_trim_words($projectDescription, $index === 0 ? 18 : 12) }}</p>
                                @endif
                                <small>{{ $dispatchDate }} · Project Africa Conservation</small>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</section>
