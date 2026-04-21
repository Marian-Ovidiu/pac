@extends('layouts.mainLayout')
@section('content')
    @php
        $img = $opzioniArchivio->immagine_hero ?? [];
        $typingTitoli = array_values(array_filter([
            $opzioniArchivio->highlights_frase_1 ?? null,
            $opzioniArchivio->highlights_frase_2 ?? null,
            $opzioniArchivio->highlights_frase_3 ?? null,
        ]));
    @endphp

    <section class="archive-project-hero">
        @if (!empty($img['url']))
            <div class="archive-project-hero__mobile-bg" aria-hidden="true">
                <img
                    src="{{ $img['url'] }}"
                    alt=""
                    class="archive-project-hero__mobile-bg-image"
                    loading="eager">
            </div>
        @endif

        <div class="ui-container">
            <div class="archive-project-hero__layout">
                <div class="archive-project-hero__copy">
                    <span class="archive-project-hero__eyebrow">Archivio progetti</span>
                    <h1 class="archive-project-hero__title">
                        {{ $opzioniArchivio->titolo_hero }}
                    </h1>

                    @if(!empty($typingTitoli))
                        <div x-data="typingEffect({{ json_encode($typingTitoli) }})" class="archive-project-hero__highlight">
                            <span x-text="displayText"></span>
                        </div>
                    @endif

                    @if(!empty($opzioniArchivio->testo_sotto_hero))
                        <div class="archive-project-hero__text">
                            {!! $opzioniArchivio->testo_sotto_hero !!}
                        </div>
                    @endif

                    <div class="archive-project-hero__actions">
                        <a href="#archive-project-list" class="archive-project-hero__button">
                            Scopri i progetti
                        </a>
                    </div>

                    <div class="archive-project-hero__stats">
                        <div>
                            <strong>{{ count($progetti) }}+</strong>
                            <span>progetti</span>
                        </div>
                        <div>
                            <strong>3</strong>
                            <span>ambiti di impatto</span>
                        </div>
                    </div>
                </div>

                @if (!empty($img['url']))
                    <div class="archive-project-hero__visual">
                        <figure class="archive-project-hero__frame">
                            <img
                                src="{{ $img['url'] }}"
                                alt="{{ $img['alt'] ?? ($opzioniArchivio->titolo_hero ?? 'Progetti') }}"
                                title="{{ $img['title'] ?? '' }}"
                                width="{{ $img['width'] ?? '' }}"
                                height="{{ $img['height'] ?? '' }}"
                                class="archive-project-hero__image"
                                loading="eager">
                            <div class="archive-project-hero__frame-overlay"></div>
                            <figcaption class="archive-project-hero__caption">
                                <span>Focus progetti</span>
                                <strong>{{ $opzioniArchivio->titolo_hero }}</strong>
                            </figcaption>
                        </figure>
                        <div class="archive-project-hero__halo" aria-hidden="true"></div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <section class="archive-project-list" id="archive-project-list">
        <div class="ui-container">
            <div class="archive-project-list__layout">
                <aside class="archive-project-list__intro">
                    <div class="archive-project-list__intro-lead">
                        <span class="archive-project-list__eyebrow">Archivio progetti</span>
                        <h2 class="archive-project-list__title archive-project-list__title--desktop">
                            Dare un futuro alla <span>natura.</span>
                        </h2>
                        <h2 class="archive-project-list__title archive-project-list__title--mobile">
                            Azioni concrete per il <span>pianeta.</span>
                        </h2>
                        <p class="archive-project-list__kicker">Protezione fauna selvatica</p>
                    </div>
                    <div class="archive-project-list__intro-aside">
                        <p class="archive-project-list__text archive-project-list__text--desktop">
                            Esplora le nostre missioni sul campo. Ogni scheda rappresenta una sfida viva e un impegno costante per la biodiversita globale.
                        </p>
                        <p class="archive-project-list__text archive-project-list__text--mobile">
                            Esplora i nostri interventi sul campo. Ogni progetto e un passo verso un futuro piu sostenibile e giusto.
                        </p>
                        <div class="archive-project-list__stats">
                            <div>
                                <strong>{{ count($progetti) }}+</strong>
                                <span>progetti attivi</span>
                            </div>
                            <div>
                                <strong>50k</strong>
                                <span>ettari protetti</span>
                            </div>
                        </div>
                    </div>
                </aside>

                <div class="archive-project-list__showcase" id="archive-project-list-cards">
                    <div class="archive-project-list__rings" aria-hidden="true"></div>

                    <div class="archive-project-list__items">
                        @foreach ($progetti as $index => $progetto)
                            @php
                                $progettoPermalink = get_permalink($progetto->id);
                            @endphp
                            <article class="archive-project-card {{ $index % 2 === 1 ? 'archive-project-card--reverse' : '' }}">
                                <a href="{{ esc_url($progettoPermalink) }}" class="archive-project-card__stretch">
                                    <span class="sr-only">Scopri {{ $progetto->titolo_card }}</span>
                                </a>
                                <div class="archive-project-card__media">
                                    <img
                                        src="{{ $progetto->featured_image }}"
                                        alt=""
                                        class="archive-project-card__image"
                                        loading="lazy"
                                        decoding="async" />
                                </div>

                                <div class="archive-project-card__content">
                                    <h3 class="archive-project-card__title">{{ $progetto->titolo_card }}</h3>
                                    <p class="archive-project-card__text">
                                        {{ wp_trim_words(wp_strip_all_tags($progetto->content), 26, '...') }}
                                    </p>
                                    <span class="archive-project-card__cta" aria-hidden="true">
                                        Scopri il progetto
                                        <span aria-hidden="true">-></span>
                                    </span>
                                </div>
                            </article>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
