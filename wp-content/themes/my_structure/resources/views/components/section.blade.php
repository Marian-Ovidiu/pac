@php
    $sectionItems = array_values(array_filter($items ?? [], static function ($item) {
        return !empty($item['sottoTitolo']) || !empty($item['testo']) || !empty($item['immagini']);
    }));
    $sectionId = 'project-section-' . sanitize_title($titolo ?: uniqid());
@endphp

@if(!empty($sectionItems))
<section class="section narrative-section narrative-section--{{ $theme ?? 'default' }}" aria-labelledby="{{ $sectionId }}">
    <div class="site-container">
        <div class="section-heading">
            <h2 id="{{ $sectionId }}">{{ $titolo }}</h2>
        </div>
        <div class="narrative-section__items">
            @foreach($sectionItems as $index => $item)
                <article class="narrative-entry" data-reveal>
                    <div class="narrative-entry__copy">
                        @if(!empty($item['sottoTitolo']))<h3>{{ $item['sottoTitolo'] }}</h3>@endif
                        @if(!empty($item['testo']))<div class="rich-text">{!! $item['testo'] !!}</div>@endif
                    </div>
                    @if(!empty($item['immagini']))
                        <div class="narrative-entry__media">
                            @foreach(array_slice(array_values($item['immagini']), 0, 3) as $image)
                                @include('components.media-figure', [
                                    'image' => $image,
                                    'alt' => $item['sottoTitolo'] ?? $titolo,
                                    'ratio' => 'card',
                                ])
                            @endforeach
                        </div>
                    @endif
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif
