<section class="ui-companies">
    <div class="ui-container">
        <article class="ui-companies__panel">
            @if(!empty($immagine_url))
                <div class="ui-companies__background" aria-hidden="true">
                    <img
                        src="{{ $immagine_url }}"
                        alt=""
                        class="ui-companies__background-image"
                        loading="lazy"
                        decoding="async">
                </div>
            @endif

            <div class="ui-companies__overlay"></div>

            <div class="ui-companies__content">
                <div class="ui-companies__eyebrow">
                    <span class="ui-companies__eyebrow-icon" aria-hidden="true"></span>
                    <span>PAC for companies</span>
                </div>

                @if($titolo)
                    <h2 class="ui-companies__title">{{ $titolo }}</h2>
                @endif

                @if($descrizione)
                    <div class="ui-companies__text">{!! $descrizione !!}</div>
                @endif

                @if(isset($cta) && !empty($cta['url']) && !empty($cta['title']))
                    <div class="ui-companies__actions">
                        <a href="{{ $cta['url'] }}" aria-label="{{ $cta['title'] }}" class="ui-companies__button">
                            <span>{{ $cta['title'] }}</span>
                        </a>
                    </div>
                @endif
            </div>

            @if($immagine_caption)
                <div class="sr-only">{{ $immagine_caption }}</div>
            @endif
            @if($immagine_description)
                <div class="sr-only">{{ $immagine_description }}</div>
            @endif
        </article>
    </div>
</section>
