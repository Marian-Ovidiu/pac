<?php
/**
 * @var \Models\HomeFields $data
 */
?>
@php
    $projects = is_array($data->progetti ?? null) ? array_values(array_filter($data->progetti)) : [];
    $slides = [
        [
            'url' => $data->immagine_1['url'] ?? null,
            'alt' => $data->immagine_1['alt'] ?? null,
            'title' => $data->immagine_1['title'] ?? null,
            'caption' => $data->immagine_1['caption'] ?? null,
            'description' => $data->immagine_1['description'] ?? null,
            'titolo' => $data->titolo_1 ?? null,
            'testo' => $data->testo_1 ?? null,
            'cta_url' => $data->cta_1['url'] ?? null,
            'cta_title' => $data->cta_1['title'] ?? null,
        ],
        [
            'url' => $data->immagine_2['url'] ?? null,
            'alt' => $data->immagine_2['alt'] ?? null,
            'title' => $data->immagine_2['title'] ?? null,
            'caption' => $data->immagine_2['caption'] ?? null,
            'description' => $data->immagine_2['description'] ?? null,
            'titolo' => $data->titolo_2 ?? null,
            'testo' => $data->testo_2 ?? null,
            'cta_url' => $data->cta_2['url'] ?? null,
            'cta_title' => $data->cta_2['title'] ?? null,
        ],
        [
            'url' => $data->immagine_3['url'] ?? null,
            'alt' => $data->immagine_3['alt'] ?? null,
            'title' => $data->immagine_3['title'] ?? null,
            'caption' => $data->immagine_3['caption'] ?? null,
            'description' => $data->immagine_3['description'] ?? null,
            'titolo' => $data->titolo_3 ?? null,
            'testo' => $data->testo_3 ?? null,
            'cta_url' => $data->cta_3['url'] ?? null,
            'cta_title' => $data->cta_3['title'] ?? null,
        ],
        [
            'url' => $data->immagine_4['url'] ?? null,
            'alt' => $data->immagine_4['alt'] ?? null,
            'title' => $data->immagine_4['title'] ?? null,
            'caption' => $data->immagine_4['caption'] ?? null,
            'description' => $data->immagine_4['description'] ?? null,
            'titolo' => $data->titolo_4 ?? null,
            'testo' => $data->testo_4 ?? null,
            'cta_url' => $data->cta_4['url'] ?? null,
            'cta_title' => $data->cta_4['title'] ?? null,
        ],
    ];
@endphp
@extends('layouts.mainLayout')
@section('content')
    <header class="sr-only">
        <h1>Project Africa Conservation - Un futuro per la fauna e le comunita</h1>
    </header>

    @include('components.slider', ['slides' => $slides])

    <section class="ui-section-tight">
        <div class="ui-container">
            <div class="ui-grid lg:grid-cols-[1.15fr_0.85fr]">
                <div class="ui-card p-7 sm:p-10">
                    <span class="ui-kicker">Chi siamo</span>
                    <h2 class="ui-title mt-5">
                        {{ $data->titolo_progetti ?? 'Conservazione sul campo, relazioni durature, impatto misurabile.' }}
                    </h2>
                    <p class="ui-subtitle mt-5">
                        {{ strip_tags($data->descrizione_progetti ?? 'Sosteniamo progetti concreti tra tutela della fauna, antibracconaggio e sviluppo sociale. Ogni pagina del sito deve aiutare il visitatore a capire, fidarsi e agire.') }}
                    </p>
                    <div class="mt-8 grid gap-4 sm:grid-cols-3">
                        <div class="ui-stat">
                            <div class="text-3xl font-bold text-custom-dark-green">{{ count($projects) }}</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.18em] text-custom-stone">progetti in evidenza</div>
                        </div>
                        <div class="ui-stat">
                            <div class="text-3xl font-bold text-custom-dark-green">3</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.18em] text-custom-stone">aree di impatto</div>
                        </div>
                        <div class="ui-stat">
                            <div class="text-3xl font-bold text-custom-dark-green">1</div>
                            <div class="mt-1 text-xs uppercase tracking-[0.18em] text-custom-stone">rete coordinata</div>
                        </div>
                    </div>
                </div>

                @include('components.mono-logo', [
                    'titolo_monologo' => $titolo_monologo,
                    'immagine_monologo' => $immagine_monologo,
                    'sottotitolo_monologo' => $sottotitolo_monologo,
                ])
            </div>
        </div>
    </section>

    @include('components.missione', [
        'titolo_missione' => $data->titolo_missione ?? null,
        'testo_missione' => $data->testo_missione ?? null,
        'immagine_missione_url' => $data->immagine_missione['url'] ?? null,
        'immagine_missione_alt' => $data->immagine_missione['alt'] ?? null,
        'immagine_missione_title' => $data->immagine_missione['title'] ?? null,
        'immagine_missione_caption' => $data->immagine_missione['caption'] ?? null,
        'immagine_missione_description' => $data->immagine_missione['description'] ?? null,
        'cta_missione_dona_ora_url' => $data->cta_missione_dona_ora['url'] ?? null,
        'cta_missione_dona_ora_titolo' => $data->cta_missione_dona_ora['title'] ?? null,
        'cta_missione_galleria_url' => $data->cta_missione_galleria['url'] ?? null,
        'cta_missione_galleria_titolo' => $data->cta_missione_galleria['title'] ?? null,
    ])

    @include('components.testo-sottotesto', [
        'titolo' => $data->titolo_progetti,
        'sottotitolo' => $data->descrizione_progetti,
    ])

    @include('components.home-cards', ['progetti' => $projects])

    @include('components.aziende', [
        'titolo' => $data->titolo_azienda ?? null,
        'descrizione' => $data->descrizione_azienda ?? null,
        'cta' => $data->cta_azienda ?? null,
        'immagine_url' => $data->immagine_azienda['url'] ?? null,
        'immagine_alt' => $data->immagine_azienda['alt'] ?? null,
        'immagine_title' => $data->immagine_azienda['title'] ?? null,
        'immagine_caption' => $data->immagine_azienda['caption'] ?? null,
        'immagine_description' => $data->immagine_azienda['description'] ?? null,
    ])
@stop
