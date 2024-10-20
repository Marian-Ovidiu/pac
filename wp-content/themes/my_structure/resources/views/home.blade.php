<?php
/**
 * @var \Models\HomeFields $data
 */
?>
@extends('layouts.mainLayout')
@section('content')
    @include('components.slider', [
        'immagine_1_url' => $data->immagine_1['url'], 'titolo_1' => $data->titolo_1, 'testo_1' => $data->testo_1, 'cta_1_url' => $data->cta_1['url'], 'cta_1_title' => $data->cta_1['title'],
        'immagine_2_url' => $data->immagine_2['url'], 'titolo_2' => $data->titolo_2, 'testo_2' => $data->testo_2, 'cta_2_url' => $data->cta_2['url'], 'cta_2_title' => $data->cta_2['title'],
        'immagine_3_url' => $data->immagine_3['url'], 'titolo_3' => $data->titolo_3, 'testo_3' => $data->testo_3, 'cta_3_url' => $data->cta_3['url'], 'cta_3_title' => $data->cta_3['title'],
    ])
    @include('components.missione', [
        'titolo_missione' => $data->titolo_missione,
        'testo_missione' => $data->testo_missione,
        'cta_missione_dona_ora_url' => $data->cta_missione_dona_ora['url'], 'cta_missione_dona_ora_titolo' => $data->cta_missione_dona_ora['title'],
        'cta_missione_galleria_url' => $data->cta_missione_galleria['url'], 'cta_missione_galleria_titolo' => $data->cta_missione_galleria['title'],
    ])

    @include('components.linear-slider')
@stop