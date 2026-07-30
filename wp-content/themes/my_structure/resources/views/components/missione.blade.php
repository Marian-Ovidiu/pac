@php
    $missionTitle    = $titolo_missione ?: 'La nostra missione va oltre la geografia.';
    $missionBody     = trim(strip_tags($testo_missione ?? ''));
    $missionDesc     = $missionBody ?: 'Project Africa Conservation crede che la stabilit&#224; ambientale a lungo termine sia possibile solo con empowerment delle comunit&#224; e presenza costante sul campo.';
    $missionImageAlt = trim($immagine_missione_alt ?? '') ?: 'Missione &#8212; Project Africa Conservation';
@endphp

<section class="fd-fieldnote" aria-label="{{ $missionTitle }}">
    <div class="ui-container fd-fieldnote__inner">

        <div class="fd-fieldnote__meta">
            <span class="fd-label fd-label--light">Field Note</span>
        </div>

        <blockquote class="fd-fieldnote__quote">
            <p>&#8220;{{ $missionDesc }}&#8221;</p>
        </blockquote>

        <div class="fd-fieldnote__footer">
            @if(!empty($cta_missione_dona_ora_url) && !empty($cta_missione_dona_ora_titolo))
                <a href="{{ esc_url($cta_missione_dona_ora_url) }}"
                   aria-label="{{ $cta_missione_dona_ora_titolo }} &#8212; {{ $missionTitle }}"
                   class="fd-fieldnote__cta">
                    {{ $cta_missione_dona_ora_titolo }}
                    <span aria-hidden="true">&#8594;</span>
                </a>
            @endif
            @if(!empty($cta_missione_galleria_url) && !empty($cta_missione_galleria_titolo))
                <a href="{{ esc_url($cta_missione_galleria_url) }}"
                   aria-label="{{ $cta_missione_galleria_titolo }} &#8212; {{ $missionTitle }}"
                   class="fd-fieldnote__link">
                    {{ $cta_missione_galleria_titolo }}
                </a>
            @endif
        </div>

    </div>
</section>
