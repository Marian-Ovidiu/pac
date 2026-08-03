@php
    $thankYouImage = theme_media_or_generated(
        $fields->immagine ?? null,
        'pac-thanks-botanical-decorative',
        '',
        '',
        true
    );
    $heading = trim((string) ($fields->titolo ?? '')) ?: 'Grazie per il tuo sostegno.';
    $message = trim(wp_strip_all_tags((string) ($fields->testo ?? '')));
    $supportEmail = 'info@project-africa-conservation.org';
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="thank-you-hero">
    <div class="site-container thank-you-hero__grid">
        <div class="thank-you-hero__copy">
            <p class="eyebrow">Conferma</p>
            <h1>{{ $heading }}</h1>
            @if($message !== '')
                <p class="thank-you-hero__lead">{{ $message }}</p>
            @else
                <p class="thank-you-hero__lead">Se hai appena completato la donazione, PAC sta registrando l’esito comunicato dal sistema di pagamento.</p>
            @endif
            <div class="thank-you-actions">
                <a class="button" href="{{ esc_url(home_url('/diario-di-bordo/')) }}">Leggi il Diario</a>
                <a class="text-link text-link--large" href="{{ esc_url(home_url('/4-progetti-antibracconaggio-sociale/')) }}">Scopri le altre missioni <span aria-hidden="true">→</span></a>
            </div>
        </div>

        @include('components.media-figure', [
            'image' => $thankYouImage,
            'alt' => '',
            'ratio' => 'hero',
            'loading' => 'eager',
            'class' => 'thank-you-hero__media',
        ])
    </div>
</section>

<section class="section section--paper" aria-labelledby="after-donation-title">
    <div class="site-container editorial-split">
        <div class="section-heading">
            <p class="eyebrow">Cosa succede ora</p>
            <h2 id="after-donation-title">Dopo una donazione completata.</h2>
        </div>
        <ol class="thank-you-steps">
            <li><h3>Controlla la tua email</h3><p>Dopo la registrazione PAC invia un messaggio di conferma all’indirizzo usato durante la donazione.</p></li>
            <li><h3>Conserva la conferma</h3><p>Il messaggio riepiloga soltanto le informazioni registrate dal sistema.</p></li>
            <li><h3>Se qualcosa non torna</h3><p>Scrivi a <a href="mailto:{{ $supportEmail }}">{{ $supportEmail }}</a> senza condividere dati bancari o numeri completi di carta.</p></li>
        </ol>
    </div>
</section>

<section class="section thank-you-support" aria-labelledby="thank-you-support-title">
    <div class="site-container thank-you-support__inner">
        <div>
            <p class="eyebrow">Assistenza</p>
            <h2 id="thank-you-support-title">Hai bisogno di aiuto?</h2>
        </div>
        <p>Indica nel messaggio il tuo nome e la data approssimativa della donazione. Non inviare dati della carta.</p>
        <a class="button button--secondary" href="mailto:{{ $supportEmail }}">Contatta PAC</a>
    </div>
</section>
@endsection
