<section class="ui-section">
    <div class="ui-container">
        <div class="ui-grid items-center lg:grid-cols-[1.1fr_0.9fr]">
            <div class="ui-card p-7 sm:p-10">
                <span class="ui-kicker">Missione</span>
                @if($titolo_missione)
                    <h2 class="ui-title mt-5">{{ $titolo_missione }}</h2>
                @endif
                @if($testo_missione)
                    <div class="ui-richtext mt-5">
                        {!! $testo_missione !!}
                    </div>
                @endif
                <div class="mt-8 flex flex-wrap gap-3">
                    @if(!empty($cta_missione_dona_ora_url) && !empty($cta_missione_dona_ora_titolo))
                        <a href="{{ $cta_missione_dona_ora_url }}" class="ui-button">
                            {{ $cta_missione_dona_ora_titolo }}
                        </a>
                    @endif
                    @if(!empty($cta_missione_galleria_url) && !empty($cta_missione_galleria_titolo))
                        <a href="{{ $cta_missione_galleria_url }}" class="ui-button-secondary">
                            {{ $cta_missione_galleria_titolo }}
                        </a>
                    @endif
                </div>
            </div>

            @if($immagine_missione_url)
                <figure class="ui-image-frame relative h-full min-h-[24rem] overflow-hidden rounded-[2rem]">
                    <img src="{{ $immagine_missione_url }}"
                         alt="{{ $immagine_missione_alt ?? $titolo_missione }}"
                         title="{{ $immagine_missione_title ?? '' }}"
                         class="h-full w-full object-cover"
                         loading="lazy"
                         decoding="async">
                    <div class="absolute inset-x-0 bottom-0 bg-gradient-to-t from-[rgba(18,32,24,0.72)] to-transparent p-6 text-white">
                        @if($immagine_missione_caption)
                            <figcaption class="text-sm text-white/85">{{ $immagine_missione_caption }}</figcaption>
                        @endif
                        @if($immagine_missione_description)
                            <div class="sr-only">{{ $immagine_missione_description }}</div>
                        @endif
                    </div>
                </figure>
            @endif
        </div>
    </div>
</section>
