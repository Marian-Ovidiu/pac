@php
    $missions = array_values($progetti ?? []);
    $missionCount = count($missions);
    $heroTitle = wp_strip_all_tags($opzioniArchivio->titolo_hero ?? 'Ogni progetto, una missione.');
    $areas = array_values(array_filter([
        $opzioniArchivio->highlights_frase_1 ?? null,
        $opzioniArchivio->highlights_frase_2 ?? null,
        $opzioniArchivio->highlights_frase_3 ?? null,
    ]));
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="page-hero">
    <div class="site-container page-hero__grid">
        <div class="page-hero__copy">
            <h1>{{ $heroTitle }}</h1>
            @if(!empty($opzioniArchivio->testo_sotto_hero))
                <p class="page-hero__lead">{{ wp_strip_all_tags($opzioniArchivio->testo_sotto_hero) }}</p>
            @endif
            <a class="button" href="#mission-list">Scegli una missione</a>
        </div>
        @include('components.media-figure', [
            'image' => theme_media_or_generated(
                $opzioniArchivio->immagine_hero ?? null,
                'pac-missions-archive-illustrative',
                '',
                '',
                true
            ),
            'alt' => '',
            'ratio' => 'hero',
            'loading' => 'eager',
            'class' => 'page-hero__media',
        ])
    </div>
</section>

@if(!empty($areas))
<section class="intervention-areas" aria-labelledby="intervention-areas-title">
    <div class="site-container intervention-areas__inner">
        <h2 id="intervention-areas-title">Aree raccontate dalle missioni</h2>
        <ul>
            @foreach($areas as $area)<li>{{ $area }}</li>@endforeach
        </ul>
    </div>
</section>
@endif

<section id="mission-list" class="section" aria-labelledby="mission-list-title">
    <div class="site-container">
        <div class="section-heading section-heading--row">
            <div>
                <h2 id="mission-list-title">Scegli la missione che vuoi sostenere.</h2>
                <p>{{ $missionCount }} {{ $missionCount === 1 ? 'missione pubblicata' : 'missioni pubblicate' }}. Ogni scheda usa i contenuti disponibili nel progetto, senza aggiungere risultati non documentati.</p>
            </div>
        </div>

        @if(!empty($missions))
            <div class="mission-grid">
                @foreach($missions as $index => $mission)
                    @include('components.mission-card', [
                        'title' => $mission->titolo_card ?: $mission->title,
                        'summary' => $mission->testo_hero ?: $mission->content,
                        'need' => $mission->problemi_testo_1,
                        'action' => $mission->soluzioni_testo_1,
                        'image' => $mission->immagine_hero ?: $mission->featured_image,
                        'mission' => $mission,
                        'featured' => $mission->isFlagship(),
                        'url' => get_permalink($mission->id),
                        'loading' => $index < 2 ? 'eager' : 'lazy',
                    ])
                @endforeach
            </div>
        @else
            @include('components.empty-state', [
                'title' => 'Nessuna missione pubblicata',
                'text' => 'Consulta il Diario per gli aggiornamenti disponibili.',
                'actionUrl' => home_url('/diario-di-bordo/'),
                'actionLabel' => 'Vai al Diario',
            ])
        @endif
    </div>
</section>

<section class="section section--paper">
    <div class="site-container transparency-block">
        <div>
            <h2>Trasparenza significa poter leggere prima di scegliere.</h2>
            <p>Ogni pagina missione mette in relazione il bisogno descritto, le azioni proposte e il form dedicato al progetto. Non mostriamo percentuali di allocazione prive di una fonte disponibile.</p>
        </div>
        <a class="button button--secondary" href="{{ esc_url(home_url('/diario-di-bordo/')) }}">Leggi gli aggiornamenti</a>
    </div>
</section>

@if(!empty($latestPosts))
<section class="section" aria-labelledby="project-journal-title">
    <div class="site-container">
        <div class="section-heading"><h2 id="project-journal-title">Dal Diario di bordo</h2><p>Gli ultimi contenuti pubblicati da PAC.</p></div>
        <div class="article-grid">
            @foreach($latestPosts as $post)
                @include('components.article-teaser', ['post' => $post])
            @endforeach
        </div>
    </div>
</section>
@endif
@endsection
