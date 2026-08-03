@php
    /** @var \Models\Progetto $progetto */
    $thankYouUrl = home_url('/grazie/');
    $missionTitle = $progetto->titolo_hero ?: $progetto->titolo_card;
@endphp
@extends('layouts.mainLayout')

@section('content')
<article class="project-page">
    <header class="page-hero page-hero--project">
        <div class="site-container page-hero__grid">
            <div class="page-hero__copy">
                <p class="context-label">Missione PAC</p>
                <h1>{{ $missionTitle }}</h1>
                @if(!empty($progetto->testo_hero))
                    <div class="page-hero__lead rich-text">{!! $progetto->testo_hero !!}</div>
                @endif
                <div class="page-hero__actions">
                    <a class="button" href="#donation">Sostieni questa missione</a>
                    <a class="text-link text-link--large" href="#mission-details">Leggi il progetto <span aria-hidden="true">↓</span></a>
                </div>
            </div>
            @include('components.media-figure', [
                'image' => theme_mission_media($progetto, $progetto->immagine_hero ?: $progetto->featured_image),
                'alt' => $progetto->titolo_card ?: $missionTitle,
                'ratio' => 'hero',
                'loading' => 'eager',
                'class' => 'page-hero__media',
            ])
        </div>
    </header>

    <div id="mission-details">
        @include('components.section', [
            'titolo' => $progetto->problemi_titolo_1 ?: 'Perché serve',
            'items' => $progetto->getProblemi(),
            'theme' => 'need',
        ])
        @include('components.section', [
            'titolo' => $progetto->soluzioni_titolo_1 ?: 'Cosa fa PAC',
            'items' => $progetto->getSoluzioni(),
            'theme' => 'action',
        ])
    </div>

    @if(!empty($latestPosts))
    <section class="section section--paper" aria-labelledby="project-updates-title">
        <div class="site-container">
            <div class="section-heading">
                <h2 id="project-updates-title">Aggiornamenti pubblicati da PAC</h2>
                <p>Queste note appartengono al Diario generale. Gli aggiornamenti specifici della missione vengono indicati soltanto quando esiste una relazione editoriale esplicita.</p>
            </div>
            <div class="article-grid">
                @foreach($latestPosts as $post)
                    @include('components.article-teaser', ['post' => $post])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <section id="donation" class="section donation-section" aria-labelledby="donation-section-title">
        <div class="site-container donation-section__grid">
            <div class="donation-section__story">
                <h2 id="donation-section-title">Trasforma il sostegno in un'azione per questa missione.</h2>
                <div class="rich-text">{!! $progetto->content !!}</div>
                <ul class="trust-list">
                    <li>Importo libero a partire da 1 EUR</li>
                    <li>Pagamento protetto gestito da Stripe</li>
                    <li>Conferma inviata all'indirizzo email indicato</li>
                </ul>
                <p class="form-help">Percentuali e destinazioni vengono pubblicate insieme alla relativa documentazione. Prima di procedere puoi rileggere bisogno e azioni descritte sopra.</p>
            </div>
            <div class="donation-section__panel">
                @include('components.donation-form', [
                    'projectId' => $progetto->id,
                    'thankYouUrl' => $thankYouUrl,
                    'heading' => 'Sostieni ' . ($progetto->titolo_card ?: 'questa missione'),
                ])
            </div>
        </div>
    </section>

    <section class="section section--forest other-support" aria-labelledby="other-support-title">
        <div class="site-container editorial-split">
            <div><h2 id="other-support-title">Vuoi sostenere PAC in un altro modo?</h2></div>
            <div>
                <p>Puoi condividere una missione, leggere gli aggiornamenti oppure proporre una collaborazione aziendale.</p>
                <div class="link-row">
                    <a class="text-link text-link--light" href="{{ esc_url(home_url('/diario-di-bordo/')) }}">Leggi il Diario <span aria-hidden="true">→</span></a>
                    <a class="text-link text-link--light" href="{{ esc_url(home_url('/aziende/')) }}">Collabora come azienda <span aria-hidden="true">→</span></a>
                </div>
            </div>
        </div>
    </section>
</article>

<a class="mobile-donation-cta" href="#donation">Sostieni questa missione</a>
@endsection
