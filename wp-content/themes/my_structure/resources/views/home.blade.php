<?php
/**
 * @var \Models\HomeFields $data
 */
?>
@php
    $projects = array_values(array_filter([
        [
            'immagine' => $data->immagine_tutti_progetti ?? null,
            'titolo' => $data->titolo_tutti_progetti ?? null,
            'cta' => $data->cta_tutti_progetti ?? null,
        ],
        [
            'immagine' => $data->immagine_sociale_ghana ?? null,
            'titolo' => $data->titolo_sociale_ghana ?? null,
            'cta' => $data->cta_sociale_ghana ?? null,
        ],
        [
            'immagine' => $data->immagine_sociale_nigeria ?? null,
            'titolo' => $data->titolo_sociale_nigeria ?? null,
            'cta' => $data->cta_sociale_nigeria ?? null,
        ],
        [
            'immagine' => $data->immagine_antibracconaggio ?? null,
            'titolo' => $data->titolo_antibracconaggio ?? null,
            'cta' => $data->cta_antibracconaggio ?? null,
        ],
        [
            'immagine' => $data->immagine_cani ?? null,
            'titolo' => $data->titolo_cani ?? null,
            'cta' => $data->cta_cani ?? null,
        ],
    ], static function ($project) {
        return !empty($project['titolo']) || !empty($project['immagine']) || !empty($project['cta']);
    }));

    $featuredProjects = array_slice($projects, 0, 4);
    $heroTitle = $data->titolo_progetti ?? 'Proteggiamo la fauna. Rafforziamo le comunita.';
    $heroDescription = trim(strip_tags($data->descrizione_progetti ?? 'Sosteniamo progetti concreti tra tutela della fauna, antibracconaggio e sviluppo sociale, con una presenza costante sul campo.'));

@endphp
@extends('layouts.mainLayout')
@section('content')
    <header class="sr-only">
        <h1>Project Africa Conservation - Un futuro per la fauna e le comunita</h1>
    </header>

    @include('components.home-hero', [
        'title' => $heroTitle,
        'description' => $heroDescription,
        'featuredProjects' => $featuredProjects,
        'primaryCta' => $data->cta_missione_dona_ora ?? null,
        'secondaryCta' => $data->cta_missione_galleria ?? null,
    ])


    @include('components.mono-logo', [
        'titolo_monologo' => $titolo_monologo,
        'immagine_monologo' => $immagine_monologo,
        'sottotitolo_monologo' => $sottotitolo_monologo,
    ])

    @include('components.home-about', [
        'title' => $data->titolo_progetti ?? 'Conservazione sul campo, relazioni durature, impatto misurabile.',
        'description' => strip_tags($data->descrizione_progetti ?? 'Sosteniamo progetti concreti tra tutela della fauna, antibracconaggio e sviluppo sociale. Ogni pagina del sito deve aiutare il visitatore a capire, fidarsi e agire.'),
        'projects' => $projects,
        'image' => $data->immagine_missione ?? ($featuredProjects[0]['immagine'] ?? null),
        'primaryCta' => $data->cta_missione_dona_ora ?? null,
        'secondaryCta' => $data->cta_missione_galleria ?? null,
    ])

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
