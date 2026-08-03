@php
    /** @var \Models\HomeFields $data */
    $missions = array_values(array_filter($missions ?? []));
    $missionCount = count($missions);
    $missionsUrl = home_url('/4-progetti-antibracconaggio-sociale/');
    $galleryUrl = home_url('/galleria/');
    $companiesUrl = home_url('/aziende/');
    $journalUrl = home_url('/diario-di-bordo/');
    $heroImage = theme_media_or_generated(
        $data->immagine_1 ?? null,
        'pac-home-hero-illustrative',
        'Paesaggio illustrativo con sentiero tra erbe e un’acacia.',
        'Immagine illustrativa generata con IA.'
    );
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="page-hero page-hero--home">
    <div class="site-container page-hero__grid">
        <div class="page-hero__copy">
            <h1>Proteggiamo chi protegge l'Africa.</h1>
            <p class="page-hero__lead">Con ranger, unità K-9 e comunità locali trasformiamo il sostegno in presenza, strumenti e futuro.</p>
            <div class="page-hero__actions">
                <a class="button" href="{{ esc_url($missionsUrl) }}">Sostieni una missione</a>
                <a class="text-link text-link--large" href="#come-lavoriamo">Scopri cosa facciamo <span aria-hidden="true">↓</span></a>
            </div>
        </div>
        @include('components.media-figure', [
            'image' => $heroImage,
            'alt' => $data->titolo_1 ?? 'Attività di Project Africa Conservation',
            'ratio' => 'hero',
            'loading' => 'eager',
            'class' => 'page-hero__media',
        ])
    </div>
</section>

<section class="content-index" aria-label="Contenuti pubblicati">
    <div class="site-container content-index__inner">
        <p>Un punto di accesso trasparente al lavoro raccontato in questo sito.</p>
        <dl>
            <div><dt>Missioni pubblicate</dt><dd>{{ $missionCount }}</dd></div>
            <div><dt>Note dal campo</dt><dd>{{ (int) ($publishedPostCount ?? 0) }}</dd></div>
        </dl>
    </div>
</section>

<section id="come-lavoriamo" class="section section--paper">
    <div class="site-container editorial-split">
        <div class="section-heading">
            <h2>Una rete di azioni, non interventi isolati.</h2>
            <p>{{ wp_strip_all_tags($data->descrizione_progetti ?? 'PAC collega protezione della fauna, supporto operativo e progetti sociali con le comunità locali.') }}</p>
        </div>
        <div class="proof-list">
            <article>
                <h3>Ranger e unità K-9</h3>
                <p>Le missioni antibracconaggio e cinofile raccontano bisogni, rischi e forme di supporto operativo.</p>
            </article>
            <article>
                <h3>Comunità e progetti sociali</h3>
                <p>I progetti in Ghana e Nigeria documentano interventi rivolti a formazione, strutture e inclusione.</p>
            </article>
            <article>
                <h3>Contenuti dal campo</h3>
                <p>Diario e Galleria raccolgono gli aggiornamenti effettivamente disponibili, senza trasformare dati mancanti in promesse.</p>
            </article>
        </div>
    </div>
</section>

<section class="section" aria-labelledby="home-missions-title">
    <div class="site-container">
        <div class="section-heading section-heading--row">
            <div>
                <h2 id="home-missions-title">{{ $data->titolo_progetti ?: 'Scegli la missione che vuoi sostenere.' }}</h2>
                <p>Ogni scheda apre il bisogno, l'azione prevista e il percorso di donazione del singolo progetto.</p>
            </div>
            <a class="text-link" href="{{ esc_url($missionsUrl) }}">Vedi tutte le missioni <span aria-hidden="true">→</span></a>
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
                        'url' => get_permalink($mission->id),
                        'loading' => $index < 2 ? 'eager' : 'lazy',
                    ])
                @endforeach
            </div>
        @else
            @include('components.empty-state', [
                'title' => 'Le missioni non sono ancora disponibili',
                'text' => 'Torna al Diario per leggere gli aggiornamenti pubblicati.',
                'actionUrl' => $journalUrl,
                'actionLabel' => 'Vai al Diario',
            ])
        @endif
    </div>
</section>

@if(!empty($latestPost))
<section class="section section--forest field-note" data-reveal aria-labelledby="field-note-title">
    <div class="site-container field-note__grid">
        <div>
            <p class="context-label">Dal Diario · {{ get_the_date('j F Y', $latestPost->ID) }}</p>
            <h2 id="field-note-title">{{ get_the_title($latestPost->ID) }}</h2>
        </div>
        <div>
            <p>{{ wp_trim_words(wp_strip_all_tags(get_the_excerpt($latestPost->ID)), 38, '…') }}</p>
            <a class="text-link text-link--light" href="{{ esc_url(get_permalink($latestPost->ID)) }}">Leggi la nota dal campo <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>
@endif

<section class="section section--paper">
    <div class="site-container transparency-block">
        <div>
            <h2>{{ $data->titolo_chart ?: 'Prima di donare, puoi leggere dove interviene ogni missione.' }}</h2>
            <p>Le pagine delle missioni raccolgono bisogni, azioni e aggiornamenti disponibili nei contenuti PAC. Percentuali e risultati vengono pubblicati soltanto insieme a una fonte verificabile.</p>
        </div>
        <a class="button button--secondary" href="{{ esc_url($missionsUrl) }}">Confronta le missioni</a>
    </div>
</section>

<section class="section partnership-callout">
    <div class="site-container editorial-split">
        <div>
            <h2>{{ $data->titolo_azienda ?: 'La tua azienda vuole collaborare con PAC?' }}</h2>
        </div>
        <div>
            <p>La pagina Aziende raccoglie le modalità di contatto e il form dedicato alle proposte di partnership.</p>
            <a class="text-link" href="{{ esc_url($companiesUrl) }}">Scopri come contattarci <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

@include('components.call-to-action-band', [
    'id' => 'home-final-cta',
    'title' => 'Scegli la missione che senti più vicina.',
    'text' => 'Leggi il progetto prima di decidere come sostenerlo.',
    'url' => $missionsUrl,
    'label' => 'Scopri le missioni',
])
@endsection
