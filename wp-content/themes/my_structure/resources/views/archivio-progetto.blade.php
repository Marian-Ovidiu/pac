@extends('layouts.mainLayout')
@section('content')
@php
    $img          = $opzioniArchivio->immagine_hero ?? [];
    $heroTitle    = $opzioniArchivio->titolo_hero   ?? 'Operazioni sul campo.';
    $heroText     = $opzioniArchivio->testo_sotto_hero ?? '';
    $count        = count($progetti);

    $typingTitoli = array_values(array_filter([
        $opzioniArchivio->highlights_frase_1 ?? null,
        $opzioniArchivio->highlights_frase_2 ?? null,
        $opzioniArchivio->highlights_frase_3 ?? null,
    ]));
@endphp

{{-- ── HERO ─────────────────────────────────────────────── --}}
<section class="fd-archive-hero" aria-labelledby="fd-archive-title">
    <div class="ui-container fd-archive-hero__inner">

        <div class="fd-archive-hero__copy">
            <span class="fd-label">Archivio operazioni</span>

            <h1 class="fd-archive-hero__title" id="fd-archive-title">
                {!! $heroTitle !!}
            </h1>

            @if(!empty($typingTitoli))
                <p class="fd-archive-hero__ticker"
                   x-data="typingEffect({{ json_encode($typingTitoli) }})"
                   aria-live="polite"
                   aria-atomic="true">
                    <span x-text="displayText"></span>
                </p>
            @endif

            @if($heroText)
                <div class="fd-archive-hero__text">
                    {!! wp_strip_all_tags($heroText) !!}
                </div>
            @endif

            <dl class="fd-archive-hero__stats" aria-label="Numeri operazioni PAC">
                <div>
                    <dd>{{ $count }}+</dd>
                    <dt>Operazioni documentate</dt>
                </div>
                <div>
                    <dd>50k</dd>
                    <dt>Ettari protetti</dt>
                </div>
                <div>
                    <dd>6</dd>
                    <dt>Paesi attivi</dt>
                </div>
            </dl>

            <a href="#fd-archive-list"
               class="field-dispatch-button field-dispatch-button--primary fd-archive-hero__cta"
               aria-label="Scorri all'elenco dei progetti">
                Scopri le operazioni &#8594;
            </a>
        </div>

        @if(!empty($img['url']))
            <figure class="fd-archive-hero__photo">
                <img src="{{ esc_url($img['url']) }}"
                     alt="{{ $img['alt'] ?? $heroTitle }}"
                     loading="eager"
                     decoding="async"
                     width="{{ $img['width'] ?? '' }}"
                     height="{{ $img['height'] ?? '' }}">
                <figcaption class="field-dispatch-caption">
                    <span>Operazioni PAC</span>
                    <span>{{ $heroTitle }}</span>
                </figcaption>
            </figure>
        @endif

    </div>
</section>

{{-- ── LIST ─────────────────────────────────────────────── --}}
<section class="fd-archive-list" id="fd-archive-list" aria-label="Elenco operazioni PAC">
    <div class="ui-container">

        <div class="fd-archive-list__head">
            <span class="fd-label">{{ $count }} operazioni</span>
            <p class="fd-archive-list__desc">
                Ogni scheda rappresenta un impegno concreto sul campo.
                Anti-bracconaggio, K-9, supporto comunit&#224;, sorveglianza attiva.
            </p>
        </div>

        <ol class="fd-archive-cards" role="list">
            @foreach($progetti as $index => $progetto)
                @php
                    $permalink   = get_permalink($progetto->id);
                    $num         = str_pad((string)($index + 1), 3, '0', STR_PAD_LEFT);
                    $thumb       = $progetto->featured_image ?? '';
                    $thumbAlt    = $progetto->titolo_card ?? 'Operazione PAC';
                    $excerpt     = wp_trim_words(wp_strip_all_tags($progetto->content ?? ''), 22, '&#8230;');
                @endphp

                <li class="fd-archive-card {{ $index % 2 === 1 ? 'fd-archive-card--reverse' : '' }}">

                    @if($thumb)
                        <figure class="fd-archive-card__media">
                            <a href="{{ esc_url($permalink) }}"
                               tabindex="-1"
                               aria-hidden="true">
                                <img src="{{ esc_url($thumb) }}"
                                     alt="{{ esc_attr($thumbAlt) }}"
                                     loading="{{ $index < 2 ? 'eager' : 'lazy' }}"
                                     decoding="async">
                            </a>
                        </figure>
                    @endif

                    <div class="fd-archive-card__body">
                        <span class="fd-archive-card__num" aria-hidden="true">#{{ $num }}</span>
                        <h2 class="fd-archive-card__title">
                            <a href="{{ esc_url($permalink) }}"
                               class="fd-archive-card__link">
                                {{ $progetto->titolo_card }}
                            </a>
                        </h2>
                        @if($excerpt)
                            <p class="fd-archive-card__excerpt">{{ $excerpt }}</p>
                        @endif
                        <a href="{{ esc_url($permalink) }}"
                           class="fd-archive-card__cta"
                           aria-label="Scopri operazione: {{ $progetto->titolo_card }}">
                            Leggi operazione &#8594;
                        </a>
                    </div>

                    <span class="fd-archive-card__badge">[ACTIVE]</span>

                </li>
            @endforeach
        </ol>

    </div>
</section>

@stop
