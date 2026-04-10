@php
    $projectSlides = array_values(array_filter($featuredProjects ?? [], static function ($project) {
        return !empty($project['immagine']['url']) && !empty($project['titolo']);
    }));
@endphp

<section class="ui-section-tight ui-home-hero">
    <div class="ui-container">
        <div class="hidden items-start gap-8 lg:grid lg:grid-cols-[minmax(0,0.94fr)_minmax(0,1.06fr)] xl:gap-10">
            <div class="ui-home-hero__intro">
                <span class="ui-home-hero__eyebrow">Conservazione sul campo</span>
                <h2 class="ui-home-hero__title">{{ $title }}</h2>
                <p class="ui-home-hero__copy">{{ $description }}</p>

                <div class="mt-8 flex flex-wrap gap-3">
                    @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                        <a href="{{ $primaryCta['url'] }}" class="ui-home-hero__button ui-home-hero__button--primary">
                            {{ $primaryCta['title'] }}
                        </a>
                    @endif
                    @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                        <a href="{{ $secondaryCta['url'] }}" class="ui-home-hero__button ui-home-hero__button--secondary">
                            {{ $secondaryCta['title'] }}
                        </a>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 xl:gap-5">
                @foreach($projectSlides as $project)
                    <article class="ui-home-hero__card group">
                        @if(!empty($project['cta']['url']))
                            <a
                                href="{{ $project['cta']['url'] }}"
                                aria-label="{{ $project['cta']['title'] ?? $project['titolo'] }}"
                                class="absolute inset-0 z-10">
                                <span class="sr-only">{{ $project['cta']['title'] ?? $project['titolo'] }}</span>
                            </a>
                        @endif

                        <img
                            src="{{ $project['immagine']['url'] }}"
                            alt="{{ $project['immagine']['alt'] ?? $project['titolo'] }}"
                            title="{{ $project['immagine']['title'] ?? '' }}"
                            class="ui-home-hero__card-image"
                            loading="lazy"
                            decoding="async">

                        <div class="ui-home-hero__card-overlay"></div>

                        <div class="ui-home-hero__card-copy">
                            <h3 class="ui-home-hero__card-title">{{ $project['titolo'] }}</h3>
                            @if(!empty($project['cta']['title']))
                                <span class="ui-home-hero__card-link">
                                    {{ $project['cta']['title'] }}
                                    <span aria-hidden="true">→</span>
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
                    @foreach($projectSlides as $project)
                        @php
                            $projectDescription = trim(($project['immagine']['caption'] ?? '') ?: ($project['immagine']['description'] ?? ''));
                        @endphp
                        <div class="swiper-slide">
                            <article class="ui-home-hero__mobile-card">
                                @if(!empty($project['cta']['url']))
                                    <a
                                        href="{{ $project['cta']['url'] }}"
                                        aria-label="{{ $project['cta']['title'] ?? $project['titolo'] }}"
                                        class="absolute inset-0 z-10">
                                        <span class="sr-only">{{ $project['cta']['title'] ?? $project['titolo'] }}</span>
                                    </a>
                                @endif

                                <img
                                    src="{{ $project['immagine']['url'] }}"
                                    alt="{{ $project['immagine']['alt'] ?? $project['titolo'] }}"
                                    title="{{ $project['immagine']['title'] ?? '' }}"
                                    class="ui-home-hero__mobile-image"
                                    loading="eager"
                                    decoding="async">

                                <div class="ui-home-hero__mobile-overlay"></div>

                                <div class="ui-home-hero__mobile-copy">
                                    <span class="ui-home-hero__eyebrow ui-home-hero__eyebrow--mobile">Progetto in evidenza</span>
                                    <h2 class="ui-home-hero__mobile-title">{{ $project['titolo'] }}</h2>
                                    @if($projectDescription)
                                        <p class="ui-home-hero__mobile-text">{{ $projectDescription }}</p>
                                    @elseif($description)
                                        <p class="ui-home-hero__mobile-text">{{ $description }}</p>
                                    @endif

                                    @if(!empty($project['cta']['title']))
                                        <span class="ui-home-hero__mobile-button">
                                            {{ $project['cta']['title'] }}
                                            <span aria-hidden="true">→</span>
                                        </span>
                                    @endif
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>

                <div class="swiper-pagination ui-home-hero__pagination"></div>
            </div>
        </div>
    </div>
</section>
