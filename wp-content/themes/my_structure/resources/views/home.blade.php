<?php
/**
 * @var \Models\HomeFields $data
 */
?>
@extends('layouts.mainLayout')
@section('content')
    <header class="sr-only">
        <h1>Project Africa Conservation - Un futuro per la fauna e le comunità</h1>
    </header>
        @include('components.slider', [
        // SLIDE 1
        'immagine_1_url' => $data->immagine_1['url'] ?? null,
        'immagine_1_alt' => $data->immagine_1['alt'] ?? null,
        'immagine_1_title' => $data->immagine_1['title'] ?? null,
        'immagine_1_caption' => $data->immagine_1['caption'] ?? null,
        'immagine_1_description' => $data->immagine_1['description'] ?? null,
        'titolo_1' => $data->titolo_1 ?? null,
        'testo_1' => $data->testo_1 ?? null,
        'cta_1_url' => $data->cta_1['url'] ?? null,
        'cta_1_title' => $data->cta_1['title'] ?? null,

        // SLIDE 2
        'immagine_2_url' => $data->immagine_2['url'] ?? null,
        'immagine_2_alt' => $data->immagine_2['alt'] ?? null,
        'immagine_2_title' => $data->immagine_2['title'] ?? null,
        'immagine_2_caption' => $data->immagine_2['caption'] ?? null,
        'immagine_2_description' => $data->immagine_2['description'] ?? null,
        'titolo_2' => $data->titolo_2 ?? null,
        'testo_2' => $data->testo_2 ?? null,
        'cta_2_url' => $data->cta_2['url'] ?? null,
        'cta_2_title' => $data->cta_2['title'] ?? null,

        // SLIDE 3
        'immagine_3_url' => $data->immagine_3['url'] ?? null,
        'immagine_3_alt' => $data->immagine_3['alt'] ?? null,
        'immagine_3_title' => $data->immagine_3['title'] ?? null,
        'immagine_3_caption' => $data->immagine_3['caption'] ?? null,
        'immagine_3_description' => $data->immagine_3['description'] ?? null,
        'titolo_3' => $data->titolo_3 ?? null,
        'testo_3' => $data->testo_3 ?? null,
        'cta_3_url' => $data->cta_3['url'] ?? null,
        'cta_3_title' => $data->cta_3['title'] ?? null,

        // SLIDE 4
        'immagine_4_url' => $data->immagine_4['url'] ?? null,
        'immagine_4_alt' => $data->immagine_4['alt'] ?? null,
        'immagine_4_title' => $data->immagine_4['title'] ?? null,
        'immagine_4_caption' => $data->immagine_4['caption'] ?? null,
        'immagine_4_description' => $data->immagine_4['description'] ?? null,
        'titolo_4' => $data->titolo_4 ?? null,
        'testo_4' => $data->testo_4 ?? null,
        'cta_4_url' => $data->cta_4['url'] ?? null,
        'cta_4_title' => $data->cta_4['title'] ?? null,
    ])


    @include('components.mono-logo', [
        'titolo_monologo' => $mono_fields->titolo_monologo,
        'sottotitolo_monologo' => $mono_fields->sottotitolo_monologo,
        'immagine_monologo' =>$mono_fields->immagine_monologo['url']
    ])

    @include('components.missione', [
        'titolo_missione' => $data->titolo_missione,
        'testo_missione' => $data->testo_missione,
        'cta_missione_dona_ora_url' => $data->cta_missione_dona_ora['url'], 'cta_missione_dona_ora_titolo' => $data->cta_missione_dona_ora['title'],
        'cta_missione_galleria_url' => $data->cta_missione_galleria['url'], 'cta_missione_galleria_titolo' => $data->cta_missione_galleria['title'],
    ])
    {{--@include('components.linear-slider')--}}
    @include('components.testo-sottotesto',[
        'titolo' => $data->titolo_progetti,
        'sottotitolo' => $data->descrizione_progetti,
    ])
    @include('components.home-cards', ['progetti' => $data->progetti])
    @include('components.home-mobile-cards', ['progetti' => $data->progetti])
    {{--<div class="container mx-auto py-12">
        <div class="flex flex-col items-center justify-center md:flex-row-reverse">
            @include('components.testo-sottotesto',[
                'titolo' => $data->titolo_chart,
                'sottotitolo' => $data->descrizione_chart,
            ])
            @include('components.chart')
        </div>
    </div>--}}
    @include('components.aziende', [
        'titolo' => $data->titolo_azienda,
        'descrizione' => $data->descrizione_azienda,
        'cta' => $data->cta_azienda,
        'immagine' => $data->immagine_azienda,
    ])
@stop