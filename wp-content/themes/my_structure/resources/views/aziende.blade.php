@php
    /** @var Models\AziendeFields $fields */
    $contactEmail = 'info@project-africa-conservation.org';
    $collaborationWays = [
        ['title' => 'Contributo a una missione', 'text' => 'Scegliere insieme un progetto pubblicato e definire una forma di sostegno coerente con il bisogno descritto.'],
        ['title' => 'Iniziativa condivisa', 'text' => 'Valutare una raccolta fondi o un’attività aziendale collegata a una missione PAC.'],
        ['title' => 'Supporto continuativo', 'text' => 'Aprire un confronto su una collaborazione nel tempo e sulle modalità di aggiornamento disponibili.'],
    ];
    $processSteps = [
        ['title' => 'Raccontaci la proposta', 'text' => 'Obiettivi, disponibilità e missioni di interesse aiutano a inquadrare la richiesta.'],
        ['title' => 'Verifichiamo il perimetro', 'text' => 'PAC valuta coerenza, fattibilità e contenuti effettivamente disponibili.'],
        ['title' => 'Definiamo le modalità', 'text' => 'Contributo, comunicazione e aggiornamenti vengono concordati prima dell’avvio.'],
        ['title' => 'Manteniamo il contatto', 'text' => 'La relazione prosegue attraverso i canali e i materiali realmente concordati.'],
    ];
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="page-hero page-hero--companies">
    <div class="site-container page-hero__grid">
        <div class="page-hero__copy">
            <h1>{{ $fields->hero_titolo ?: 'Costruiamo collaborazioni legate a missioni reali.' }}</h1>
            <p class="page-hero__lead">{{ wp_strip_all_tags($fields->hero_sottotitolo ?: 'Se la tua azienda vuole sostenere PAC, partiamo dagli obiettivi, dai progetti pubblicati e da ciò che possiamo documentare insieme.') }}</p>
            <div class="page-hero__actions">
                <a class="button" href="#partner-contact">Proponi una collaborazione</a>
                <a class="text-link text-link--large" href="#collaboration-ways">Scopri le modalità <span aria-hidden="true">↓</span></a>
            </div>
        </div>
        @include('components.media-figure', [
            'image' => theme_media_or_generated(
                $fields->immagine_hero ?? null,
                'pac-companies-collaboration-illustrative',
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

<section id="collaboration-ways" class="section section--paper" aria-labelledby="collaboration-ways-title">
    <div class="site-container editorial-split">
        <div class="section-heading">
            <h2 id="collaboration-ways-title">{{ $fields->come_titolo ?: 'Tre modi per iniziare il confronto.' }}</h2>
            <p>{{ wp_strip_all_tags($fields->come_testo ?: 'La forma della collaborazione dipende dalla missione, dalle risorse disponibili e dagli obiettivi condivisi.') }}</p>
        </div>
        <div class="editorial-rows">
            @foreach($collaborationWays as $way)
                <article><h3>{{ $way['title'] }}</h3><p>{{ $way['text'] }}</p></article>
            @endforeach
        </div>
    </div>
</section>

<section class="section" aria-labelledby="partner-process-title">
    <div class="site-container">
        <div class="section-heading">
            <h2 id="partner-process-title">Dal primo contatto a un accordo chiaro.</h2>
            <p>Il processo serve a evitare promesse generiche e a collegare ogni proposta a contenuti e attività verificabili.</p>
        </div>
        <ol class="process-list">
            @foreach($processSteps as $step)
                <li><h3>{{ $step['title'] }}</h3><p>{{ $step['text'] }}</p></li>
            @endforeach
        </ol>
    </div>
</section>

<section class="section section--forest" aria-labelledby="partner-transparency-title">
    <div class="site-container editorial-split">
        <div><h2 id="partner-transparency-title">Visibilità soltanto su basi concordate.</h2></div>
        <div>
            <p>Loghi, risultati e materiali di collaborazione vengono pubblicati dopo un accordo reale e con l’autorizzazione delle parti coinvolte. Le missioni disponibili sono il riferimento per iniziare un confronto concreto.</p>
            <a class="text-link text-link--light" href="{{ esc_url(home_url('/4-progetti-antibracconaggio-sociale/')) }}">Consulta le missioni pubblicate <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>

<section class="section partner-faq" aria-labelledby="partner-faq-title">
    <div class="site-container editorial-split">
        <div><h2 id="partner-faq-title">Prima di scriverci</h2></div>
        <div class="faq-list">
            <details>
                <summary>È necessario scegliere subito una missione?</summary>
                <p>No. Puoi indicare interessi e obiettivi nel messaggio; il confronto serve anche a individuare il progetto più coerente.</p>
            </details>
            <details>
                <summary>La proposta viene pubblicata automaticamente?</summary>
                <p>No. L’invio del form apre un contatto e non autorizza PAC a pubblicare logo, nome o contenuti dell’azienda.</p>
            </details>
            <details>
                <summary>Come riceveremo gli aggiornamenti?</summary>
                <p>Canali, frequenza e materiali disponibili vengono definiti nel perimetro della collaborazione.</p>
            </details>
        </div>
    </div>
</section>

<section id="partner-contact" class="section section--paper partner-contact" aria-labelledby="partner-contact-title">
    <div class="site-container partner-contact__grid">
        <div>
            <h2 id="partner-contact-title">Raccontaci la tua proposta.</h2>
            <p>Descrivi obiettivi, tipo di coinvolgimento e missioni di interesse. Ti ricontatteremo usando i dati inseriti nel modulo.</p>
            <p><a class="text-link" href="mailto:{{ $contactEmail }}">{{ $contactEmail }}</a></p>
        </div>
        <div class="partner-contact__form">
            @if(!empty($fields->shortcode_form))
                {!! apply_filters('the_content', do_shortcode($fields->shortcode_form)) !!}
            @else
                @include('components.empty-state', [
                    'title' => 'Il modulo non è disponibile',
                    'text' => 'Puoi inviare la proposta tramite email.',
                    'actionUrl' => 'mailto:' . $contactEmail,
                    'actionLabel' => 'Scrivi a PAC',
                ])
            @endif
        </div>
    </div>
</section>
@endsection
