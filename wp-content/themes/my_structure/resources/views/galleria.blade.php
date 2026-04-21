@extends('layouts.mainLayout')
@section('content')
@php
    $heroImage = $galleria->immagine_12 ?: $galleria->immagine_1;
    $typingWords = array_values(array_filter([
        $galleria->parola_1 ?? null,
        $galleria->parola_2 ?? null,
        $galleria->parola_3 ?? null,
    ]));
    $gallerySections = [
        [
            'title' => 'Sguardi dal campo',
            'intro' => 'Ranger, habitat e momenti operativi raccontano la presenza quotidiana sul territorio.',
        ],
        [
            'title' => $galleria->testo_8 ?: 'K-9 in azione',
            'intro' => 'Il lavoro con le unita cinofile rende piu forte ogni missione contro il bracconaggio.',
        ],
        [
            'title' => $galleria->testo_11 ?: 'Unita e cooperazione',
            'intro' => 'Persone, squadre e paesaggi mostrano il valore di una conservazione condivisa.',
        ],
    ];
@endphp

<section class="gallery-archive-hero">
    <div class="ui-container">
        <div class="gallery-archive-hero__layout">
            <div class="gallery-archive-hero__copy">
                <span class="gallery-archive-hero__eyebrow">Galleria fotografica</span>

                @if($galleria->titolo)
                    <h1 class="gallery-archive-hero__title">{{ $galleria->titolo }}</h1>
                @endif

                <div
                    class="gallery-archive-hero__highlight"
                    @if (!empty($typingWords)) x-data="typingEffect({{ json_encode($typingWords) }})" @endif>
                    <span>{{ $galleria->frase_base ?: 'Ogni foto, una storia di' }}</span>
                    @if (!empty($typingWords))
                        <strong class="gallery-archive-hero__typing">
                            <span x-text="displayText"></span>
                        </strong>
                    @endif
                </div>

                @if($galleria->descrizione)
                    <div class="gallery-archive-hero__text">
                        {!! $galleria->descrizione !!}
                    </div>
                @endif

                <div class="gallery-archive-hero__actions">
                    <a href="#gallery-grid" class="gallery-archive-hero__button">Esplora la galleria</a>
                </div>

                <div class="gallery-archive-hero__stats">
                    <div>
                        <strong>12</strong>
                        <span>scatti</span>
                    </div>
                    <div>
                        <strong>3</strong>
                        <span>storie dal campo</span>
                    </div>
                </div>
            </div>

            @if(!empty($heroImage['url']))
                <div class="gallery-archive-hero__visual">
                    <figure class="gallery-archive-hero__frame">
                        <img
                            src="{{ $heroImage['url'] }}"
                            alt="{{ $heroImage['alt'] ?? ($galleria->titolo ?? 'Galleria fotografica') }}"
                            title="{{ $heroImage['title'] ?? '' }}"
                            class="gallery-archive-hero__image"
                            loading="eager"
                            decoding="async" />
                        <div class="gallery-archive-hero__frame-overlay" aria-hidden="true"></div>
                        <figcaption class="gallery-archive-hero__caption">
                            <span>Focus galleria</span>
                            <strong>Conservazione, ranger e comunita</strong>
                        </figcaption>
                    </figure>
                    <div class="gallery-archive-hero__halo" aria-hidden="true"></div>
                </div>
            @endif
        </div>
    </div>
</section>

<section class="gallery-hero gallery-hero--mobile">
    @if(!empty($heroImage['url']))
        <img
            src="{{ $heroImage['url'] }}"
            alt="{{ $heroImage['alt'] ?? ($galleria->titolo ?? 'Galleria fotografica') }}"
            title="{{ $heroImage['title'] ?? '' }}"
            class="gallery-hero__image"
            loading="eager"
            decoding="async" />
    @endif

    <div class="gallery-hero__overlay" aria-hidden="true"></div>

    <div class="gallery-hero__content">
        <span class="gallery-hero__eyebrow">Galleria fotografica</span>

        @if($galleria->titolo)
            <h1 class="gallery-hero__title">{{ $galleria->titolo }}</h1>
        @endif

        <div
            class="gallery-hero__line"
            @if (!empty($typingWords)) x-data="typingEffect({{ json_encode($typingWords) }})" @endif>
            <span>{{ $galleria->frase_base ?: 'Ogni foto, una storia di' }}</span>
            @if (!empty($typingWords))
                <span class="gallery-hero__typing">
                    <span x-text="displayText"></span><span class="gallery-hero__cursor" aria-hidden="true"></span>
                </span>
            @endif
        </div>

        <a href="#gallery-grid" class="gallery-hero__button">
            <span>Esplora la galleria</span>
            <span aria-hidden="true">&rarr;</span>
        </a>

        <a href="#gallery-grid" class="gallery-hero__scroll">
            <span>Scorri per esplorare</span>
            <span aria-hidden="true">&#8964;</span>
        </a>
    </div>
</section>

<section id="gallery-grid" class="gallery-showcase">
    <div class="gallery-showcase__inner">
        <div class="gallery-showcase__mobile-head">
            <h2>La Galleria</h2>
        </div>

        <div class="gallery-showcase__desktop">
            @foreach($galleria->data as $sectionIndex => $group)
                @php
                    $sectionMeta = $gallerySections[$sectionIndex] ?? $gallerySections[0];
                    $sectionDescription = null;
                    foreach ($group as $sectionItem) {
                        if (!empty($sectionItem['descrizione'])) {
                            $sectionDescription = $sectionItem['descrizione'];
                            break;
                        }
                    }
                @endphp

                <section class="gallery-section gallery-section--{{ $sectionIndex + 1 }}">
                    <div class="gallery-section__head">
                        <div>
                            <h2>{{ $sectionMeta['title'] }}</h2>
                            <p>{{ $sectionMeta['intro'] }}</p>
                        </div>
                    </div>

                    <div class="gallery-section__grid gallery-section__grid--{{ $sectionIndex + 1 }}">
                        @foreach($group as $itemIndex => $gr)
                            @if(!empty($gr['immagine']['url']))
                                <figure class="gallery-tile gallery-tile--{{ $itemIndex + 1 }}">
                                    <img
                                        src="{{ $gr['immagine']['url'] }}"
                                        loading="lazy"
                                        decoding="async"
                                        alt="{{ $gr['immagine']['alt'] ?? ($gr['testo'] ?? $galleria->titolo ?? 'Immagine galleria') }}"
                                        title="{{ $gr['immagine']['title'] ?? ($gr['testo'] ?? '') }}" />
                                    @if($gr['testo'])
                                        <figcaption>{{ $gr['testo'] }}</figcaption>
                                    @endif
                                </figure>
                            @endif
                        @endforeach
                    </div>

                    @if($sectionDescription)
                        <div class="gallery-story-card">
                            <h3>{{ $sectionMeta['title'] }}</h3>
                            <div>{!! $sectionDescription !!}</div>
                        </div>
                    @endif
                </section>
            @endforeach
        </div>

        <div class="gallery-showcase__mobile">
            @foreach($galleria->data as $sectionIndex => $group)
                @php
                    $sectionMeta = $gallerySections[$sectionIndex] ?? $gallerySections[0];
                    $sectionDescription = null;
                    foreach ($group as $sectionItem) {
                        if (!empty($sectionItem['descrizione'])) {
                            $sectionDescription = $sectionItem['descrizione'];
                            break;
                        }
                    }
                @endphp

                @foreach($group as $itemIndex => $gr)
                    @if(!empty($gr['immagine']['url']))
                        <figure class="gallery-mobile-tile {{ ($itemIndex + $sectionIndex) % 2 === 0 ? 'gallery-mobile-tile--tall' : '' }}">
                            <img
                                src="{{ $gr['immagine']['url'] }}"
                                loading="lazy"
                                decoding="async"
                                alt="{{ $gr['immagine']['alt'] ?? ($gr['testo'] ?? $galleria->titolo ?? 'Immagine galleria') }}"
                                title="{{ $gr['immagine']['title'] ?? ($gr['testo'] ?? '') }}" />
                            @if($gr['testo'])
                                <figcaption>{{ $gr['testo'] }}</figcaption>
                            @endif
                        </figure>
                    @endif
                @endforeach

                @if($sectionDescription)
                    <article class="gallery-mobile-story">
                        <h3>{{ $sectionMeta['title'] }}</h3>
                        <div>{!! $sectionDescription !!}</div>
                    </article>
                @endif
            @endforeach
        </div>

        <section class="gallery-donation-strip">
            <span aria-hidden="true">♡</span>
            <h2>Sostieni la Natura</h2>
            <p>Non siamo solo osservatori. Siamo i protettori del futuro africano. Unisciti a noi oggi.</p>
            <a href="{{ home_url('/progetti') }}">Fai una donazione ora</a>
        </section>
    </div>
</section>
@endsection
