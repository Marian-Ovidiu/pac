@php
    $projectSlides = array_values(array_filter($featuredProjects ?? [], static function ($project) {
        $src = theme_acf_image_url($project['immagine'] ?? null);
        $titolo = trim((string) ($project['titolo'] ?? ''));

        return $src !== '' && $titolo !== '';
    }));

    $primaryCtaLabel = !empty($primaryCta['title']) ? $primaryCta['title'] . ' - Project Africa Conservation' : null;
    $secondaryCtaLabel = !empty($secondaryCta['title']) ? $secondaryCta['title'] . ' - Project Africa Conservation' : null;
@endphp

<section class="ui-section-tight ui-home-hero" aria-label="{{ $title }}">
    <div class="ui-container">
        <div class="hidden items-start gap-8 lg:grid lg:grid-cols-[minmax(0,0.94fr)_minmax(0,1.06fr)] xl:gap-10">
            <div class="ui-home-hero__intro">
                <span class="ui-home-hero__eyebrow">Conservazione sul campo</span>
                <h2 class="ui-home-hero__title">{{ $title }}</h2>
                <p class="ui-home-hero__copy">{{ $description }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                        <a href="{{ $primaryCta['url'] }}" aria-label="{{ $primaryCtaLabel }}" class="ui-home-hero__button ui-home-hero__button--primary">
                            {{ $primaryCta['title'] }}
                        </a>
                    @endif
                    @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                        <a href="{{ $secondaryCta['url'] }}" aria-label="{{ $secondaryCtaLabel }}" class="ui-home-hero__button ui-home-hero__button--secondary">
                            {{ $secondaryCta['title'] }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 self-center xl:gap-5">
                @foreach($projectSlides as $index => $project)
                    @php
                        $projectTitle = trim($project['titolo'] ?? '');
                        $projectImage = is_array($project['immagine'] ?? null) ? $project['immagine'] : [];
                        $projectSrc = theme_acf_image_url($project['immagine'] ?? null);
                        $projectAlt = trim($projectImage['alt'] ?? '') ?: 'Progetto ' . $projectTitle . ' di Project Africa Conservation';
                        $projectCtaTitle = $project['cta']['title'] ?? 'Scopri il progetto';
                        $projectLinkLabel = $projectCtaTitle . ': ' . $projectTitle;
                    @endphp
                    <article class="ui-home-hero__card group">
                        @if(!empty($project['cta']['url']))
                            <a
                                href="{{ $project['cta']['url'] }}"
                                aria-label="{{ $projectLinkLabel }}"
                                class="absolute inset-0 z-10">
                                <span class="sr-only">{{ $projectLinkLabel }}</span>
                            </a>
                        @endif

                        <img
                            src="{{ esc_url($projectSrc) }}"
                            alt="{{ $projectAlt }}"
                            class="ui-home-hero__card-image"
                            loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                            decoding="async"
                            @if($index === 0) fetchpriority="high" @endif>

                        <div class="ui-home-hero__card-overlay" aria-hidden="true"></div>

                        <div class="ui-home-hero__card-copy">
                            <h3 class="ui-home-hero__card-title">{{ $projectTitle }}</h3>
                            @if(!empty($project['cta']['title']))
                                <span class="ui-home-hero__card-link">
                                    {{ $project['cta']['title'] }}
                                    <span aria-hidden="true">&rarr;</span>
                                </span>
                            @endif
                        </div>
                    </article>
                @endforeach
            </div>
        </div>

        <div class="lg:hidden">
            <div class="swiper js-home-hero-slider ui-home-hero__mobile-slider" role="region" aria-roledescription="carousel" aria-label="Progetti in evidenza">
                <div class="swiper-wrapper">
                    @foreach($projectSlides as $index => $project)
                        @php
                            $projectTitle = trim($project['titolo'] ?? '');
                            $projectImage = is_array($project['immagine'] ?? null) ? $project['immagine'] : [];
                            $projectSrc = theme_acf_image_url($project['immagine'] ?? null);
                            $projectAlt = trim($projectImage['alt'] ?? '') ?: 'Progetto ' . $projectTitle . ' di Project Africa Conservation';
                            $projectDescription = trim(($projectImage['caption'] ?? '') ?: ($projectImage['description'] ?? ''));
                            $projectCtaTitle = $project['cta']['title'] ?? 'Scopri il progetto';
                            $projectLinkLabel = $projectCtaTitle . ': ' . $projectTitle;
                        @endphp
                        <div class="swiper-slide" role="group" aria-label="{{ $projectTitle }}">
                            {{-- Nessun link full-card qui: intercettava i touch e Swiper non riceveva lo swipe. Il CTA è il solo link (sotto). --}}
                            <article class="ui-home-hero__mobile-card">
                                <img
                                    src="{{ esc_url($projectSrc) }}"
                                    alt="{{ $projectAlt }}"
                                    class="ui-home-hero__mobile-image"
                                    loading="{{ $index === 0 ? 'eager' : 'lazy' }}"
                                    decoding="async"
                                    @if($index === 0) fetchpriority="high" @endif>

                                <div class="ui-home-hero__mobile-overlay" aria-hidden="true"></div>

                                <div class="ui-home-hero__mobile-copy">
                                    <span class="ui-home-hero__eyebrow ui-home-hero__eyebrow--mobile">Progetto in evidenza</span>
                                    <h2 class="ui-home-hero__mobile-title">{{ $projectTitle }}</h2>
                                    @if($projectDescription)
                                        <p class="ui-home-hero__mobile-text">{{ $projectDescription }}</p>
                                    @elseif($description)
                                        <p class="ui-home-hero__mobile-text">{{ $description }}</p>
                                    @endif

                                    @if(!empty($project['cta']['url']) && !empty($project['cta']['title']))
                                        <a href="{{ esc_url($project['cta']['url']) }}" class="ui-home-hero__mobile-button" aria-label="{{ $projectLinkLabel }}">
                                            {{ $project['cta']['title'] }}
                                            <span aria-hidden="true">&rarr;</span>
                                        </a>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination ui-home-hero__pagination" aria-hidden="true"></div>
            </div>
        </div>
    </div>
</section>
