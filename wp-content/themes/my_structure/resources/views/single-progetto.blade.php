@php
    /** @var \Models\Progetto $progetto */
    $thankYouUrl = home_url('/grazie/');
    $missionTitle = $progetto->titolo_hero ?: $progetto->titolo_card;
    $isFlagship = $progetto->isFlagship();
    $objectives = $progetto->getObjectives();
    $expectedImpact = $progetto->getExpectedImpact();
    $projectUpdates = array_values($projectUpdates ?? []);
@endphp
@extends('layouts.mainLayout')

@section('content')
<article class="project-page">
    <header class="page-hero page-hero--project">
        <div class="site-container page-hero__grid">
            <div class="page-hero__copy">
                <p class="context-label">{{ $isFlagship ? 'Progetto umanitario PAC' : 'Missione PAC' }}</p>
                <h1>{{ $missionTitle }}</h1>
                @if(!empty($progetto->testo_hero))
                    <div class="page-hero__lead rich-text">{!! $progetto->testo_hero !!}</div>
                @endif
                @if(!empty($progetto->project_location))
                    <p class="project-location">{{ $progetto->project_location }}</p>
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

    @if($isFlagship && !empty($progetto->project_intro))
    <section class="section section--paper project-introduction" aria-labelledby="project-introduction-title">
        <div class="site-container editorial-split">
            <div><h2 id="project-introduction-title">Il progetto</h2></div>
            <div class="rich-text">{!! wp_kses_post($progetto->project_intro) !!}</div>
        </div>
    </section>
    @endif

    <div id="mission-details">
        @include('components.section', [
            'titolo' => $progetto->problemi_titolo_1 ?: 'Perché serve',
            'items' => $progetto->getProblemi(),
            'theme' => 'need',
        ])

        @if($isFlagship && (!empty($progetto->project_why_title) || !empty($progetto->project_why_text)))
        <section class="section section--paper project-why" aria-labelledby="project-why-title">
            <div class="site-container editorial-split">
                <div><h2 id="project-why-title">{{ $progetto->project_why_title ?: 'Perché è importante' }}</h2></div>
                @if(!empty($progetto->project_why_text))
                    <div class="rich-text">{!! wp_kses_post($progetto->project_why_text) !!}</div>
                @endif
            </div>
        </section>
        @endif

        @if($isFlagship && !empty($objectives))
        <section class="section project-objectives" aria-labelledby="project-objectives-title">
            <div class="site-container">
                <div class="section-heading">
                    <h2 id="project-objectives-title">Cosa vogliamo realizzare</h2>
                    <p>Obiettivi concreti che guidano il progetto e la rendicontazione degli aggiornamenti.</p>
                </div>
                <div class="proof-list">
                    @foreach($objectives as $objective)
                        <article data-reveal>
                            <h3>{{ $objective['title'] }}</h3>
                            @if($objective['text'] !== '')
                                <div class="rich-text">{!! wp_kses_post(wpautop($objective['text'])) !!}</div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        @else
            @include('components.section', [
                'titolo' => $progetto->soluzioni_titolo_1 ?: 'Cosa fa PAC',
                'items' => $progetto->getSoluzioni(),
                'theme' => 'action',
            ])
        @endif
    </div>

    @if($isFlagship)
        @include('components.project-progress', ['project' => $progetto])

        @if(!empty($expectedImpact))
        <section class="section section--paper project-impact" aria-labelledby="project-impact-title">
            <div class="site-container">
                <div class="section-heading">
                    <h2 id="project-impact-title">L’impatto atteso</h2>
                    <p>Questi sono risultati attesi, non risultati già raggiunti. Verranno verificati attraverso gli aggiornamenti del progetto.</p>
                </div>
                <div class="proof-list">
                    @foreach($expectedImpact as $impact)
                        <article>
                            <h3>{{ $impact['title'] }}</h3>
                            @if($impact['text'] !== '')
                                <div class="rich-text">{!! wp_kses_post(wpautop($impact['text'])) !!}</div>
                            @endif
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <section class="section project-updates" aria-labelledby="project-updates-title">
            <div class="site-container">
                <div class="section-heading">
                    <h2 id="project-updates-title">Aggiornamenti del progetto</h2>
                    <p>Qui verranno pubblicati avanzamento, passaggi completati e documentazione disponibile.</p>
                </div>
                @if(!empty($projectUpdates))
                    <div class="article-grid">
                        @foreach($projectUpdates as $post)
                            @include('components.article-teaser', ['post' => $post])
                        @endforeach
                    </div>
                @else
                    <div class="empty-state project-updates__empty" role="status">
                        <span class="empty-state__mark" aria-hidden="true"></span>
                        <h3>Nessun aggiornamento pubblicato</h3>
                        <p>Il progetto è pronto ad accogliere le prime note verificate dal campo.</p>
                    </div>
                @endif
            </div>
        </section>
    @elseif(!empty($latestPosts))
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
