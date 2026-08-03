@extends('layouts.mainLayout')

@section('content')
<section class="journal-hero search-hero">
    <div class="site-container journal-hero__inner">
        <p class="eyebrow">Ricerca</p>
        <h1>{{ $query !== '' ? 'Risultati per “' . $query . '”' : 'Cerca nel sito' }}</h1>
    </div>
</section>

<section class="section search-results" aria-label="Risultati della ricerca">
    <div class="site-container">
        @if(!empty($posts))
            <div class="article-grid">
                @foreach($posts as $result)
                    @if($result->post_type === 'post')
                        @include('components.article-teaser', ['post' => $result])
                    @else
                        @php
                            $postTypeObject = get_post_type_object($result->post_type);
                            $resultTypeLabel = $postTypeObject && isset($postTypeObject->labels->singular_name)
                                ? $postTypeObject->labels->singular_name
                                : 'Contenuto';
                        @endphp
                        <article class="search-result">
                            <p class="eyebrow">{{ $resultTypeLabel }}</p>
                            <h2><a href="{{ esc_url(get_permalink($result)) }}">{{ get_the_title($result) }}</a></h2>
                            @if(has_excerpt($result))<p>{{ get_the_excerpt($result) }}</p>@endif
                            <a class="text-link" href="{{ esc_url(get_permalink($result)) }}">Apri <span aria-hidden="true">→</span></a>
                        </article>
                    @endif
                @endforeach
            </div>
        @else
            @include('components.empty-state', [
                'title' => 'Nessun contenuto trovato',
                'text' => $query !== '' ? 'Prova con parole diverse oppure consulta le missioni pubblicate.' : 'Inserisci una parola nel campo di ricerca.',
                'actionUrl' => home_url('/4-progetti-antibracconaggio-sociale/'),
                'actionLabel' => 'Scopri le missioni',
            ])
        @endif
    </div>
</section>
@endsection
