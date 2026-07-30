<?php
/**
 * @var \Models\Progetto $progetto
 */
?>
@php
    $thankYouUrl = home_url('/grazie');
    $img         = $progetto->immagine_hero ?? [];
    $imgUrl      = $img['url']  ?? '';
    $imgAlt      = $img['alt']  ?? ($progetto->titolo_hero ?? 'Operazione PAC');
@endphp
@extends('layouts.mainLayout')
@section('content')

{{-- ── HERO ────────────────────────────────────────────────── --}}
<section class="fd-single-hero" aria-labelledby="fd-single-title">
    <div class="ui-container fd-single-hero__inner">

        <div class="fd-single-hero__copy">
            <span class="fd-label">Scheda operazione</span>

            <h1 class="fd-single-hero__title" id="fd-single-title">
                {{ $progetto->titolo_hero }}
            </h1>

            <div class="fd-single-hero__text">
                {!! $progetto->testo_hero !!}
            </div>

            <div class="fd-single-hero__actions">
                <a href="#fd-donation"
                   class="field-dispatch-button field-dispatch-button--primary">
                    Sostieni questa operazione &#8594;
                </a>
                <a href="#fd-details"
                   class="field-dispatch-button field-dispatch-button--secondary">
                    Approfondisci
                </a>
            </div>

            @if(function_exists('pll_get_the_languages'))
                @php $languages = pll_get_the_languages(['raw' => 1]); @endphp
                @if(!empty($languages))
                    <div class="fd-single-hero__langs" aria-label="Versioni linguistiche">
                        @foreach($languages as $lang)
                            <a href="{{ $lang['url'] }}"
                               class="{{ $lang['current_lang'] ? 'fd-single-hero__lang--active' : '' }}"
                               @if($lang['current_lang']) aria-current="true" @endif>
                                {{ strtoupper($lang['slug']) }}
                            </a>
                        @endforeach
                    </div>
                @endif
            @endif
        </div>

        @if($imgUrl)
            <figure class="fd-single-hero__photo">
                <img src="{{ esc_url($imgUrl) }}"
                     alt="{{ $imgAlt }}"
                     loading="eager"
                     decoding="async"
                     width="1920"
                     height="1080">
                <figcaption class="field-dispatch-caption">
                    <span>Missione PAC</span>
                    <strong>{{ $progetto->titolo_card ?: $progetto->titolo_hero }}</strong>
                </figcaption>
            </figure>
        @endif

    </div>
</section>

{{-- ── PROBLEMI / SOLUZIONI (componente con Swiper — solo restyle CSS) ── --}}
<div id="fd-details" class="fd-single-editorial">
    @component('components.section', [
        'titolo' => $progetto->problemi_titolo_1,
        'items'  => $progetto->getProblemi(),
        'eyebrow' => 'Problemi sul campo',
        'theme'  => 'problem',
    ])
    @endcomponent

    @component('components.section', [
        'titolo' => $progetto->soluzioni_titolo_1,
        'items'  => $progetto->getSoluzioni(),
        'eyebrow' => 'Soluzioni concrete',
        'theme'  => 'solution',
    ])
    @endcomponent
</div>

{{-- ── DONATION ─────────────────────────────────────────────── --}}
<section id="fd-donation" class="fd-single-donation" aria-labelledby="fd-donation-title">
    <div class="ui-container fd-single-donation__inner">

        <div class="fd-single-donation__head">
            <span class="fd-label">Sostieni ora</span>
            <h2 class="fd-single-donation__title" id="fd-donation-title">
                Trasforma il tuo supporto in azione concreta.
            </h2>
            <p class="fd-single-donation__lead">
                Ogni donazione porta risorse, formazione e presenza operativa
                dove questo progetto ne ha pi&#249; bisogno.
            </p>
        </div>

        <div class="fd-single-donation__layout">

            <div class="fd-single-donation__story">
                @if($progetto->featured_image)
                    <figure class="fd-single-donation__photo">
                        <img src="{{ esc_url($progetto->featured_image) }}"
                             alt="{{ $progetto->titolo_card }}"
                             loading="lazy"
                             decoding="async">
                    </figure>
                @endif

                <div class="fd-single-donation__copy">
                    <span class="fd-single-donation__copy-label">Donazione progetto</span>
                    <h3 class="fd-single-donation__copy-title">{{ $progetto->titolo_card }}</h3>
                    <div class="fd-single-donation__copy-text">
                        {!! $progetto->content !!}
                    </div>
                    <ul class="fd-single-donation__trust">
                        <li>Importo libero o suggerito</li>
                        <li>Pagamento sicuro con Stripe</li>
                        <li>Conferma e ricevuta via email</li>
                    </ul>
                </div>
            </div>

            <div class="fd-single-donation__form-wrap">
                <div class="fd-single-donation__form-sticky">
                    @include('components.donation-form', [
                        'projectId'   => $progetto->id,
                        'thankYouUrl' => $thankYouUrl,
                    ])
                </div>
            </div>

        </div>
    </div>
</section>

@stop
