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
        'immagine_1_url' => isset($data->immagine_1['url']) && $data->immagine_1['url'] ? $data->immagine_1['url'] : null,
        'titolo_1' => $data->titolo_1,
        'immagine_1_alt' => isset($data->immagine_1['alt']) && $data->immagine_1['alt'] ? $data->immagine_1['alt'] : null,
        'testo_1' => $data->testo_1,
        'cta_1_url' => isset($data->cta_1['url']) && $data->cta_1['url'] ? $data->cta_1['url'] : null,
        'cta_1_title' => isset($data->cta_1['title']) && $data->cta_1['title'] ? $data->cta_1['title'] : null,

        'immagine_2_url' => isset($data->immagine_2['url']) && $data->immagine_2['url'] ? $data->immagine_2['url'] : null,
        'immagine_2_alt' => isset($data->immagine_2['alt']) && $data->immagine_2['alt'] ? $data->immagine_2['alt'] : null,
        'titolo_2' => $data->titolo_2,
        'testo_2' => $data->testo_2,
        'cta_2_url' => isset($data->cta_2['url']) && $data->cta_2['url'] ? $data->cta_2['url'] : null,
        'cta_2_title' => isset($data->cta_2['title']) && $data->cta_2['title'] ? $data->cta_2['title'] : null,

        'immagine_3_url' => isset($data->immagine_3['url']) && $data->immagine_3['url'] ? $data->immagine_3['url'] : null,
        'immagine_3_alt' => isset($data->immagine_3['alt']) && $data->immagine_3['alt'] ? $data->immagine_3['alt'] : null,
        'titolo_3' => $data->titolo_3,
        'testo_3' => $data->testo_3,
        'cta_3_url' => isset($data->cta_3['url']) && $data->cta_3['url'] ? $data->cta_3['url'] : null,
        'cta_3_title' => isset($data->cta_3['title']) && $data->cta_3['title'] ? $data->cta_3['title'] : null,

        'immagine_4_url' => isset($data->immagine_4['url']) && $data->immagine_4['url'] ? $data->immagine_4['url'] : null,
        'immagine_4_alt' => isset($data->immagine_4['alt']) && $data->immagine_4['alt'] ? $data->immagine_4['alt'] : null,
        'titolo_4' => $data->titolo_4,
        'testo_4' => $data->testo_4,
        'cta_4_url' => isset($data->cta_4['url']) && $data->cta_4['url'] ? $data->cta_4['url'] : null,
        'cta_4_title' => isset($data->cta_4['title']) && $data->cta_4['title'] ? $data->cta_4['title'] : null,
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