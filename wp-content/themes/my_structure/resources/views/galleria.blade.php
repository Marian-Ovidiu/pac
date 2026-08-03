@php
    $heroImage = $galleria->immagine_12 ?: $galleria->immagine_1;
    $chapters = [
        ['title' => 'Sguardi dal campo', 'text' => $galleria->descrizione_1 ?? '', 'items' => $galleria->data[0] ?? []],
        ['title' => $galleria->testo_8 ?: 'K-9 in azione', 'text' => $galleria->descrizione_2 ?? '', 'items' => $galleria->data[1] ?? []],
        ['title' => $galleria->testo_11 ?: 'Unità e cooperazione', 'text' => $galleria->descrizione_3 ?? '', 'items' => $galleria->data[2] ?? []],
    ];
    $availableImageCount = 0;
    foreach ($chapters as $chapter) {
        foreach ($chapter['items'] as $item) {
            if (theme_media_data($item['immagine'] ?? null)['available']) {
                $availableImageCount++;
            }
        }
    }
@endphp
@extends('layouts.mainLayout')

@section('content')
<section class="page-hero page-hero--gallery">
    <div class="site-container page-hero__grid">
        <div class="page-hero__copy">
            <h1>{{ $galleria->titolo ?: 'Il nostro viaggio in qualche scatto.' }}</h1>
            @if(!empty($galleria->descrizione))
                <div class="page-hero__lead rich-text">{!! $galleria->descrizione !!}</div>
            @endif
            <a class="button" href="#gallery-chapters">Esplora il racconto</a>
        </div>
        @include('components.media-figure', [
            'image' => $heroImage,
            'alt' => $galleria->titolo ?: 'Galleria PAC',
            'ratio' => 'hero',
            'loading' => 'eager',
            'class' => 'page-hero__media',
        ])
    </div>
</section>

@if($availableImageCount > 0)
<div class="gallery-count">
    <div class="site-container"><p>{{ $availableImageCount }} {{ $availableImageCount === 1 ? 'immagine disponibile' : 'immagini disponibili' }} in questa installazione.</p></div>
</div>
@endif

<div id="gallery-chapters" class="gallery-chapters">
    @foreach($chapters as $chapterIndex => $chapter)
        @php
            $availableItems = [];
            foreach ($chapter['items'] as $item) {
                if (theme_media_data($item['immagine'] ?? null)['available']) {
                    $availableItems[] = $item;
                }
            }
        @endphp
        <section class="section {{ $chapterIndex % 2 === 1 ? 'section--paper' : '' }} gallery-chapter" aria-labelledby="gallery-chapter-{{ $chapterIndex }}">
            <div class="site-container">
                <div class="section-heading">
                    <h2 id="gallery-chapter-{{ $chapterIndex }}">{{ $chapter['title'] }}</h2>
                    @if(!empty($chapter['text']))<div class="rich-text">{!! $chapter['text'] !!}</div>@endif
                </div>

                @if(!empty($availableItems))
                    <div class="gallery-grid">
                        @foreach($availableItems as $item)
                            @include('components.media-figure', [
                                'image' => $item['immagine'],
                                'alt' => $item['testo'] ?: $chapter['title'],
                                'caption' => $item['testo'] ?? '',
                                'ratio' => 'card',
                                'class' => 'gallery-grid__item',
                            ])
                        @endforeach
                    </div>
                @elseif($chapterIndex === 0)
                    @include('components.empty-state', [
                        'title' => 'Le fotografie non sono disponibili in locale',
                        'text' => 'I testi del racconto restano accessibili. Le immagini originali dovranno essere sincronizzate da uploads prima della validazione finale.',
                    ])
                @endif
            </div>
        </section>
    @endforeach
</div>

@include('components.call-to-action-band', [
    'id' => 'gallery-final-cta',
    'title' => 'Dal racconto alla missione.',
    'text' => 'Approfondisci i progetti pubblicati e scegli se sostenerne uno.',
    'url' => home_url('/4-progetti-antibracconaggio-sociale/'),
    'label' => 'Scopri le missioni',
])
@endsection
