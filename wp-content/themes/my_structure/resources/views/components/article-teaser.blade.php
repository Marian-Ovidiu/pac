@php
    $articleId = (int) ($post->ID ?? 0);
    $articleUrl = $articleId ? get_permalink($articleId) : '#';
    $articleTitle = $articleId ? get_the_title($articleId) : '';
    $articleExcerpt = $articleId ? get_the_excerpt($articleId) : '';
    $articleImageId = $articleId ? get_post_thumbnail_id($articleId) : 0;
    $categories = $articleId ? get_the_category($articleId) : [];
@endphp

<article class="article-teaser {{ !empty($featured) ? 'article-teaser--featured' : '' }}">
    @include('components.media-figure', [
        'image' => $articleImageId,
        'alt' => $articleTitle,
        'ratio' => !empty($featured) ? 'landscape' : 'card',
        'loading' => !empty($featured) ? 'eager' : 'lazy',
        'class' => 'article-teaser__media',
    ])
    <div class="article-teaser__body">
        <div class="article-meta">
            <time datetime="{{ esc_attr(get_the_date('c', $articleId)) }}">{{ get_the_date('j F Y', $articleId) }}</time>
            @if(!empty($categories))<span>{{ $categories[0]->name }}</span>@endif
        </div>
        <h2><a href="{{ esc_url($articleUrl) }}">{{ $articleTitle }}</a></h2>
        @if($articleExcerpt !== '')<p>{{ wp_trim_words(wp_strip_all_tags($articleExcerpt), !empty($featured) ? 34 : 24, '…') }}</p>@endif
        <a class="text-link" href="{{ esc_url($articleUrl) }}">Leggi la nota dal campo <span aria-hidden="true">→</span></a>
    </div>
</article>
