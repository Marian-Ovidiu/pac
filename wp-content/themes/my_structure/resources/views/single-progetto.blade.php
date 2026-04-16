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
    <section class="ui-section-tight ui-project-hero">
        <div class="ui-panel ui-project-hero__panel overflow-hidden">
                <div class="grid items-stretch lg:grid-cols-[1fr_1fr]">
                    <div class="p-7 sm:p-10 lg:p-12">
                        <a href="{{ home_url('/4-progetti-antibracconaggio-sociale') }}" class="ui-button-ghost mb-5">
                            Torna ai progetti
                        </a>
                        <span class="ui-kicker border-white/15 bg-white/10 text-white">Scheda progetto</span>
                        <h1 class="mt-5 font-nunitoBold text-4xl leading-[1.04] text-white sm:text-5xl lg:text-6xl">
                            {{ $progetto->titolo_hero }}
                        </h1>
                        <div class="ui-richtext mt-6 max-w-2xl prose-p:text-white prose-p:opacity-80 prose-strong:text-white prose-headings:text-white">
                            {!! $progetto->testo_hero !!}
                        </div>

                        @if (function_exists('pll_get_the_languages'))
                            @php $languages = pll_get_the_languages(['raw' => 1]); @endphp
                            @if(!empty($languages))
                                <div class="mt-8 flex flex-wrap gap-2">
                                    @foreach ($languages as $lang)
                                        <a href="{{ $lang['url'] }}" class="ui-pill {{ $lang['current_lang'] ? '!bg-white !text-custom-dark-green' : 'border-white/20 bg-white/10 text-white/80' }}">
                                            {{ strtoupper($lang['slug']) }}
                                        </a>
                                    @endforeach
                                </div>
                            @endif
                        @endif
                    </div>

                    @if(!empty($img['url']))
                        <figure class="min-h-[24rem]">
                            <img
                                src="{{ $img['url'] }}"
                                alt="{{ $img['alt'] ?? $progetto->titolo_hero }}"
                                class="h-full w-full object-cover object-top"
                                loading="eager"
                                decoding="async"
                                width="1920"
                                height="1080">
                        </figure>
                    @endif
                </div>
        </div>
    </section>

    @component('components.section', [
        'titolo' => $progetto->problemi_titolo_1,
        'items' => $progetto->getProblemi(),
    ])
    @endcomponent

    @component('components.section', [
        'titolo' => $progetto->soluzioni_titolo_1,
        'items' => $progetto->getSoluzioni(),
    ])
    @endcomponent

    <section class="ui-section">
        <div class="ui-container">
            <div class="ui-card overflow-hidden">
                <div class="grid lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="relative min-h-[24rem]">
                        <img
                            src="{{ $progetto->featured_image }}"
                            alt="{{ $progetto->titolo_card }}"
                            title="{{ $progetto->titolo_card }}"
                            class="h-full w-full object-cover"
                            loading="lazy"
                            decoding="async" />
                        <div class="absolute inset-0 bg-gradient-to-t from-[rgba(18,32,24,0.88)] via-[rgba(18,32,24,0.18)] to-transparent"></div>
                        <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                            <span class="ui-pill border-white/20 bg-white/10 text-white/80">Sostieni ora</span>
                            <h2 class="mt-4 font-nunitoBold text-3xl leading-tight text-white sm:text-4xl">
                                {{ $progetto->titolo_card }}
                            </h2>
                            <div class="ui-project-copy mt-4 max-w-2xl text-white/80 prose-p:text-white/80">
                                {!! $progetto->content !!}
                            </div>
                        </div>
                    </div>

                    <div class="p-4 sm:p-6 lg:p-8">
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
