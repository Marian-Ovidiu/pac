@extends('layouts.mainLayout')

@section('content')
<article class="article-page">
    <header class="article-header">
        <div class="site-container article-header__inner">
            <div class="article-meta">
                @if(!empty($categories))<span>{{ $categories[0]->name }}</span>@endif
                <time datetime="{{ esc_attr(get_the_date('c', $post)) }}">{{ $date }}</time>
                <span>{{ (int) $readingTime }} min di lettura</span>
            </div>
            <h1>{{ $title }}</h1>
            @if(!empty($post->post_excerpt))<p>{{ $post->post_excerpt }}</p>@endif
        </div>
    </header>

    @if(!empty($featuredImageId))
        <div class="site-container article-lead-media">
            @include('components.media-figure', [
                'image' => $featuredImageId,
                'alt' => $title,
                'ratio' => 'landscape',
                'loading' => 'eager',
            ])
        </div>
    @endif

    <div class="section article-body">
        <div class="article-content">
            {!! $content !!}
        </div>
    </div>
</article>

@if(!empty($relatedPosts))
<section class="section section--paper" aria-labelledby="related-posts-title">
    <div class="site-container">
        <div class="section-heading"><h2 id="related-posts-title">Continua dal Diario</h2></div>
        <div class="article-grid">
            @foreach($relatedPosts as $relatedPost)
                @include('components.article-teaser', ['post' => $relatedPost])
            @endforeach
        </div>
    </div>
</section>
@endif

@include('components.call-to-action-band', [
    'id' => 'article-final-cta',
    'title' => 'Scopri le missioni PAC.',
    'text' => 'Leggi bisogni e azioni prima di scegliere come contribuire.',
    'url' => home_url('/4-progetti-antibracconaggio-sociale/'),
    'label' => 'Esplora le missioni',
])
@endsection
