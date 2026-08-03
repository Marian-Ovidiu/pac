@extends('layouts.mainLayout')

@section('content')
<section class="special-state special-state--404">
    <div class="site-container special-state__inner">
        <p class="eyebrow">Errore 404</p>
        <h1>La traccia si interrompe qui.</h1>
        <p>La pagina potrebbe essere stata spostata o non essere più disponibile.</p>
        <div class="special-state__actions">
            <a class="button" href="{{ esc_url(home_url('/')) }}">Torna alla Home</a>
            <a class="text-link text-link--large" href="{{ esc_url(home_url('/4-progetti-antibracconaggio-sociale/')) }}">Esplora le missioni <span aria-hidden="true">→</span></a>
        </div>
    </div>
</section>
@endsection
