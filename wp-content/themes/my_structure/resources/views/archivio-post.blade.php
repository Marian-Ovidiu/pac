@php
    /** @var Models\Options\OpzioniGlobaliFields $fields */
    $allPosts = array_values($posts ?? []);
    $featuredPost = array_shift($allPosts);
    $journalTitle = $fields->title_blog ?: 'Diario di bordo';
    $journalSubtitle = $fields->subtitle_blog ?: 'Aggiornamenti e storie pubblicati da PAC.';
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="journal-hero">
    <div class="site-container journal-hero__inner">
        <h1>{{ $journalTitle }}</h1>
        <p>{{ $journalSubtitle }}</p>
    </div>
</section>

<section class="section journal-archive" aria-labelledby="journal-latest-title">
    <div class="site-container">
        @if($featuredPost)
            <div class="section-heading"><h2 id="journal-latest-title">L'ultima nota dal campo</h2></div>
            @include('components.article-teaser', ['post' => $featuredPost, 'featured' => true])

            @if(!empty($allPosts))
                <div class="section-heading journal-archive__more"><h2>Altri aggiornamenti</h2></div>
                <div class="article-grid">
                    @foreach($allPosts as $post)
                        @include('components.article-teaser', ['post' => $post])
                    @endforeach
                </div>
            @endif

            @if(($maxPages ?? 1) > 1)
                <nav class="pagination" aria-label="Pagine del Diario">
                    {!! paginate_links([
                        'current' => $paged,
                        'total' => $maxPages,
                        'prev_text' => '← Precedenti',
                        'next_text' => 'Successivi →',
                        'type' => 'list',
                    ]) !!}
                </nav>
            @endif
        @else
            @include('components.empty-state', [
                'title' => 'Il Diario non contiene ancora articoli',
                'text' => 'Nel frattempo puoi consultare le missioni pubblicate.',
                'actionUrl' => home_url('/4-progetti-antibracconaggio-sociale/'),
                'actionLabel' => 'Scopri le missioni',
            ])
        @endif
    </div>
</section>

@include('components.call-to-action-band', [
    'id' => 'journal-final-cta',
    'title' => 'Approfondisci una missione.',
    'text' => 'Dagli aggiornamenti ai progetti che puoi sostenere.',
    'url' => home_url('/4-progetti-antibracconaggio-sociale/'),
    'label' => 'Vai alle missioni',
])
@endsection
