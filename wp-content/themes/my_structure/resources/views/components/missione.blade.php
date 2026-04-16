@php
    $missionBody = trim(strip_tags($testo_missione ?? ''));
    $missionTitle = $titolo_missione ?: 'La nostra missione va oltre la geografia.';
    $missionDescription = $missionBody ?: 'Project Africa Conservation crede che la stabilita ambientale a lungo termine sia possibile solo con empowerment delle comunita e presenza costante sul campo.';
    $missionImageAlt = trim($immagine_missione_alt ?? '') ?: 'Missione di Project Africa Conservation: ' . $missionTitle;
@endphp

<section class="ui-mission" aria-label="{{ $missionTitle }}">
    <div class="ui-container">
        <div class="hidden lg:grid ui-mission__desktop">
            <div class="ui-mission__media-panel">
                @if($immagine_missione_url)
                    <figure class="ui-mission__media-frame">
                        <img
                            src="{{ $immagine_missione_url }}"
                            alt="{{ $missionImageAlt }}"
                            class="ui-mission__media-image"
                            loading="lazy"
                            decoding="async">
                    </figure>
                @else
                    <div class="ui-mission__media-frame ui-mission__media-frame--mock" aria-hidden="true"></div>
                @endif
            </div>

            <div class="ui-mission__copy-panel">
                <div class="ui-mission__copy-inner">
                    <span class="ui-mission__eyebrow">La nostra missione</span>
                    <h2 class="ui-mission__title">{{ $missionTitle }}</h2>
                    <div class="ui-mission__divider" aria-hidden="true"></div>
                    <p class="ui-mission__quote">"{{ $missionDescription }}"</p>

                    <div class="ui-mission__actions">
                        @if(!empty($cta_missione_dona_ora_url) && !empty($cta_missione_dona_ora_titolo))
                            <a href="{{ $cta_missione_dona_ora_url }}" aria-label="{{ $cta_missione_dona_ora_titolo }} - {{ $missionTitle }}" class="ui-mission__button ui-mission__button--primary">
                                {{ $cta_missione_dona_ora_titolo }}
                            </a>
                        @endif
                        @if(!empty($cta_missione_galleria_url) && !empty($cta_missione_galleria_titolo))
                            <a href="{{ $cta_missione_galleria_url }}" aria-label="{{ $cta_missione_galleria_titolo }} - {{ $missionTitle }}" class="ui-mission__button ui-mission__button--secondary">
                                {{ $cta_missione_galleria_titolo }}
                            </a>
                        @endif
                    </div>
                </div>

                <div class="ui-mission__shape" aria-hidden="true"></div>
            </div>
        </div>

        <div class="lg:hidden">
            <article class="ui-mission__mobile-card">
                @if($immagine_missione_url)
                    <figure class="ui-mission__mobile-media">
                        <img
                            src="{{ $immagine_missione_url }}"
                            alt="{{ $missionImageAlt }}"
                            class="ui-mission__mobile-image"
                            loading="lazy"
                            decoding="async">
                    </figure>
                @else
                    <div class="ui-mission__mobile-media ui-mission__mobile-media--mock" aria-hidden="true"></div>
                @endif

                <div class="ui-mission__mobile-copy">
                    <span class="ui-mission__eyebrow ui-mission__eyebrow--mobile">La nostra missione</span>
                    <h2 class="ui-mission__mobile-title">{{ $missionTitle }}</h2>
                    <p class="ui-mission__mobile-text">{{ $missionDescription }}</p>

                    <div class="ui-mission__mobile-actions">
                        @if(!empty($cta_missione_dona_ora_url) && !empty($cta_missione_dona_ora_titolo))
                            <a href="{{ $cta_missione_dona_ora_url }}" aria-label="{{ $cta_missione_dona_ora_titolo }} - {{ $missionTitle }}" class="ui-mission__button ui-mission__button--primary ui-mission__button--block">
                                {{ $cta_missione_dona_ora_titolo }}
                            </a>
                        @endif
                        @if(!empty($cta_missione_galleria_url) && !empty($cta_missione_galleria_titolo))
                            <a href="{{ $cta_missione_galleria_url }}" aria-label="{{ $cta_missione_galleria_titolo }} - {{ $missionTitle }}" class="ui-mission__button ui-mission__button--outline ui-mission__button--block">
                                {{ $cta_missione_galleria_titolo }}
                            </a>
                        @endif
                    </div>
                </div>
            </article>
        </div>
    </div>
</section>
