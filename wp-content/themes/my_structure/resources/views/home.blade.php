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
        'immagine_1_url' => $data->immagine_1['url'], 'titolo_1' => $data->titolo_1, 'immagine_1_alt' => $data->immagine_1['alt'],
        'testo_1' => $data->testo_1, 'cta_1_url' => $data->cta_1['url'], 'cta_1_title' => $data->cta_1['title'],
        'immagine_2_url' => $data->immagine_2['url'], 'immagine_2_alt' => $data->immagine_2['alt'], 'titolo_2' => $data->titolo_2,
        'testo_2' => $data->testo_2, 'cta_2_url' => $data->cta_2['url'], 'cta_2_title' => $data->cta_2['title'],
        'immagine_3_url' => $data->immagine_3['url'], 'immagine_3_alt' => $data->immagine_3['alt'], 'titolo_3' => $data->titolo_3,
        'testo_3' => $data->testo_3, 'cta_3_url' => $data->cta_3['url'], 'cta_3_title' => $data->cta_3['title']
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