@if($immagine_missione_url)
    <figure class="my-6">
        <img src="{{ $immagine_missione_url }}"
             alt="{{ $immagine_missione_alt ?? $titolo_missione }}"
             title="{{ $immagine_missione_title ?? '' }}"
             class="mx-auto w-full max-w-4xl rounded-lg"
             loading="lazy" decoding="async">
        @if($immagine_missione_caption)
            <figcaption class="text-sm text-gray-500 italic mt-2">{{ $immagine_missione_caption }}</figcaption>
        @endif
        @if($immagine_missione_description)
            <div class="sr-only">{{ $immagine_missione_description }}</div>
        @endif
    </figure>
@endif
