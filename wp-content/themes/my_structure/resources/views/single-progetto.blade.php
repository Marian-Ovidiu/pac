<?php
/**
 * @var \Models\Progetto $progetto
 */
?>
@php
    $thankYouUrl = home_url('/grazie');
    $img = $progetto->immagine_hero ?? [];
@endphp
@extends('layouts.mainLayout')
@section('content')
    <section class="project-single-hero">
        <div class="ui-container">
            <div class="project-single-hero__layout">
                <div class="project-single-hero__copy">
                    <a href="{{ home_url('/4-progetti-antibracconaggio-sociale') }}" class="project-single-hero__back">
                        <span aria-hidden="true">&larr;</span>
                        <span>Torna ai progetti</span>
                    </a>

                    <span class="project-single-kicker">Scheda progetto</span>
                    <h1>{{ $progetto->titolo_hero }}</h1>
                    <div class="project-single-hero__text">
                        {!! $progetto->testo_hero !!}
                    </div>

                    <div class="project-single-hero__actions">
                        <a href="#project-donation" class="project-single-button project-single-button--primary">Sostieni questo progetto</a>
                        <a href="#project-details" class="project-single-button project-single-button--secondary">Approfondisci</a>
                    </div>

                    @if (function_exists('pll_get_the_languages'))
                        @php $languages = pll_get_the_languages(['raw' => 1]); @endphp
                        @if(!empty($languages))
                            <div class="project-single-hero__langs">
                                @foreach ($languages as $lang)
                                    <a href="{{ $lang['url'] }}" class="{{ $lang['current_lang'] ? 'is-active' : '' }}">
                                        {{ strtoupper($lang['slug']) }}
                                    </a>
                                @endforeach
                            </div>
                        @endif
                    @endif
                </div>

                @if(!empty($img['url']))
                    <figure class="project-single-hero__media">
                        <img
                            src="{{ $img['url'] }}"
                            alt="{{ $img['alt'] ?? $progetto->titolo_hero }}"
                            loading="eager"
                            decoding="async"
                            width="1920"
                            height="1080">
                        <figcaption>
                            <span>Missione PAC</span>
                            <strong>{{ $progetto->titolo_card ?: $progetto->titolo_hero }}</strong>
                        </figcaption>
                    </figure>
                @endif
            </div>
        </div>
    </section>

    <div id="project-details" class="project-single-details">
    @component('components.section', [
        'titolo' => $progetto->problemi_titolo_1,
        'items' => $progetto->getProblemi(),
        'eyebrow' => 'Problemi sul campo',
        'theme' => 'problem',
    ])
    @endcomponent

    @component('components.section', [
        'titolo' => $progetto->soluzioni_titolo_1,
        'items' => $progetto->getSoluzioni(),
        'eyebrow' => 'Soluzioni concrete',
        'theme' => 'solution',
    ])
    @endcomponent
    </div>

    <section id="project-donation" class="project-donation-section">
        <div class="ui-container">
            <div class="project-donation-section__head">
                <span class="project-single-kicker">Sostieni ora</span>
                <h2>Trasforma il tuo supporto in azione concreta.</h2>
                <p>Ogni donazione aiuta PAC a portare risorse, formazione e presenza operativa dove il progetto ne ha piu bisogno.</p>
            </div>

            <div class="project-donation-card">
                <div class="project-donation-card__story">
                    <figure>
                        <img
                            src="{{ $progetto->featured_image }}"
                            alt="{{ $progetto->titolo_card }}"
                            title="{{ $progetto->titolo_card }}"
                            loading="lazy"
                            decoding="async" />
                    </figure>
                    <div class="project-donation-card__copy">
                        <span>Donazione progetto</span>
                        <h3>{{ $progetto->titolo_card }}</h3>
                        <div class="project-donation-card__text">
                            {!! $progetto->content !!}
                        </div>
                        <ul>
                            <li>Importo libero o suggerito</li>
                            <li>Pagamento sicuro con Stripe</li>
                            <li>Conferma e ringraziamento via email</li>
                        </ul>
                    </div>
                </div>

                <div class="project-donation-card__form">
                    <div class="project-donation-card__sticky">
                        @include('components.donation-form', [
                            'projectId' => $progetto->id,
                            'thankYouUrl' => $thankYouUrl,
                        ])
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
