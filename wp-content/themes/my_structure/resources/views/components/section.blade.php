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
                            <div class="project-editorial-card__media">
                                <div class="swiper swiper-progetto" role="group" aria-roledescription="carousel" aria-label="Galleria immagini del progetto">
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
                                    <div class="swiper-pagination !bottom-4" aria-hidden="true"></div>
                                </div>
                            </div>
                        @endif

                        <div class="project-editorial-card__copy">
                            <span class="project-editorial-card__index">{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
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
