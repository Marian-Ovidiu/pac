@php
    $eyebrow = $eyebrow ?? 'Approfondimento';
    $theme = $theme ?? 'default';
@endphp

<section class="project-editorial-section project-editorial-section--{{ $theme }}">
    <div class="ui-container">
        @if ($titolo)
            <div class="project-editorial-section__head">
                <span class="project-single-kicker">{{ $eyebrow }}</span>
                <h2>{{ $titolo }}</h2>
            </div>
        @endif

        <div class="project-editorial-section__items">
            @foreach ($items as $index => $item)
                @php
                    $hasImage = isset($item['immagini']) && count($item['immagini']) > 0;
                @endphp

                <article class="project-editorial-card {{ $index % 2 === 1 ? 'project-editorial-card--reverse' : '' }}">
                    <div class="project-editorial-card__layout {{ $hasImage ? 'has-image' : '' }}">
                        @if($hasImage)
                            @php
                                $carouselHintId = 'swiper-progetto-hint-' . uniqid();
                            @endphp
                            <div class="project-editorial-card__media">
                                <div
                                    class="swiper swiper-progetto"
                                    role="group"
                                    aria-roledescription="carousel"
                                    aria-label="Galleria immagini del progetto"
                                    aria-describedby="{{ $carouselHintId }}"
                                    tabindex="0">
                                    <p class="sr-only" id="{{ $carouselHintId }}">
                                        Carosello: con il carosello a fuoco, usa le frecce Sinistra e Destra (o Pagina su e Pagina giù) per cambiare immagine. Puoi anche usare i pulsanti in basso per aprire una slide.
                                    </p>
                                    <div class="swiper-wrapper">
                                        @foreach ($item['immagini'] as $img)
                                            <figure class="swiper-slide">
                                                <img
                                                    src="{{ $img['url'] }}"
                                                    alt="{{ $img['alt'] ?? ($item['sottoTitolo'] ?? 'Immagine progetto') }}"
                                                    title="{{ $img['title'] ?? ($item['sottoTitolo'] ?? '') }}"
                                                    loading="lazy">
                                                @if(!empty($img['caption']))
                                                    <figcaption class="sr-only">{{ $img['caption'] }}</figcaption>
                                                @endif
                                            </figure>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination !bottom-4"></div>
                                </div>
                            </div>
                        @endif

                        <div class="project-editorial-card__copy">
                            @if (!empty($item['sottoTitolo']))
                                <h3>{{ $item['sottoTitolo'] }}</h3>
                            @endif

                            @if (!empty($item['testo']))
                                <div class="project-editorial-card__text">
                                    {!! $item['testo'] !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
