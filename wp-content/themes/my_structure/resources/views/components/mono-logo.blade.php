@php
    $partnerImage = is_array($immagine_monologo ?? null) ? $immagine_monologo : null;
    $partnerAlt   = trim($partnerImage['alt'] ?? '') ?: 'Partner di Project Africa Conservation';
@endphp

@if($titolo_monologo || $partnerImage)
<div class="fd-partner-strip" @if($titolo_monologo) aria-label="Partner in campo" @endif>
    <div class="ui-container fd-partner-strip__inner">

        <span class="fd-partner-strip__label" aria-hidden="true">&#9670;</span>

        @if($titolo_monologo)
            <p class="fd-partner-strip__quote">{{ $titolo_monologo }}</p>
        @endif

        @if($sottotitolo_monologo)
            <span class="fd-partner-strip__meta">{{ $sottotitolo_monologo }}</span>
        @endif

        @if($partnerImage)
            <div class="fd-partner-strip__logo">
                <img src="{{ esc_url($partnerImage['url']) }}"
                     alt="{{ $partnerAlt }}"
                     loading="lazy"
                     decoding="async">
            </div>
        @endif

    </div>
</div>
@endif
