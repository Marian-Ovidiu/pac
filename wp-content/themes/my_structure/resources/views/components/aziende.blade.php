@php
    $fundTitle = $titolo ?: 'Finanzia una missione reale.';
    $fundDesc  = !empty($descrizione) ? strip_tags($descrizione) : 'Le nostre operazioni hanno un costo reale. Ogni contributo si traduce in ore di pattuglia, equipaggiamento K-9 e formazione per i ranger. Non raccogliamo fondi generici &#8212; ogni euro raggiunge il campo.';
    $ctaUrl    = $cta['url'] ?? '';
    $ctaTitle  = $cta['title'] ?? 'Supporta le operazioni';

    $allocations = [
        ['code' => 'K-9',  'label' => 'Unit&#224; K-9',      'desc' => 'Addestramento e mantenimento cani da pista'],
        ['code' => 'RNG',  'label' => 'Ranger',               'desc' => 'Stipendi, equipaggiamento e formazione'],
        ['code' => 'EQP',  'label' => 'Equipaggiamento',      'desc' => 'Veicoli, radio, strumentazione'],
        ['code' => 'SRV',  'label' => 'Sorveglianza',         'desc' => 'Tecnologia, fototrappole, monitoraggio'],
    ];
@endphp

<section class="fd-fund" aria-labelledby="fd-fund-title">
    <div class="ui-container fd-fund__inner">

        {{-- ── Copy left ── --}}
        <div class="fd-fund__copy">
            <span class="fd-label fd-label--light">Fund the Mission</span>
            <h2 class="fd-fund__title" id="fd-fund-title">{{ $fundTitle }}</h2>
            <p class="fd-fund__body">{{ $fundDesc }}</p>

            @if($ctaUrl)
                <a href="{{ esc_url($ctaUrl) }}"
                   class="fd-fund__cta"
                   aria-label="{{ $ctaTitle }} &#8212; Project Africa Conservation">
                    {{ $ctaTitle }}
                    <span aria-hidden="true">&#8594;</span>
                </a>
            @endif

            @if(!empty($immagine_url))
                <figure class="fd-fund__photo">
                    <img src="{{ esc_url($immagine_url) }}"
                         alt="{{ $immagine_alt ?: 'Operazioni sul campo &#8212; PAC' }}"
                         loading="lazy"
                         decoding="async">
                    @if($immagine_caption)
                        <figcaption>{{ $immagine_caption }}</figcaption>
                    @endif
                </figure>
            @endif
        </div>

        {{-- ── Allocation panel right ── --}}
        <div class="fd-fund__panel" role="group" aria-label="Dove va il tuo contributo">
            <div class="fd-fund__panel-head">
                <span>Allocazione fondi</span>
                <span>Dove va il tuo contributo</span>
            </div>

            <ul class="fd-fund__alloc" role="list">
                @foreach($allocations as $i => $item)
                    <li class="fd-fund__alloc-row {{ $i === 1 ? 'fd-fund__alloc-row--active' : '' }}">
                        <span class="fd-fund__alloc-code">[{{ $item['code'] }}]</span>
                        <div class="fd-fund__alloc-body">
                            <span class="fd-fund__alloc-label">{!! $item['label'] !!}</span>
                            <span class="fd-fund__alloc-desc">{{ $item['desc'] }}</span>
                        </div>
                        @if($i === 1)
                            <span class="fd-fund__alloc-mark" aria-hidden="true">&#9670;</span>
                        @endif
                    </li>
                @endforeach
            </ul>

            @if($ctaUrl)
                <div class="fd-fund__panel-foot">
                    <a href="{{ esc_url($ctaUrl) }}"
                       class="fd-fund__panel-cta"
                       aria-label="{{ $ctaTitle }} &#8212; Project Africa Conservation">
                        {{ $ctaTitle }} &#8594;
                    </a>
                    <span class="fd-fund__panel-note">Donazione sicura &#183; Ricevuta via email</span>
                </div>
            @endif
        </div>

    </div>

    {{-- ── Trust strip ── --}}
    <div class="fd-fund__trust">
        <div class="ui-container fd-fund__trust-inner">
            <span>Registrata in Italia</span>
            <span aria-hidden="true">&#183;</span>
            <span>Rendiconto annuale disponibile</span>
            <span aria-hidden="true">&#183;</span>
            <span>Operazioni in 6 paesi africani</span>
            <span aria-hidden="true">&#183;</span>
            <span>GDPR compliant</span>
        </div>
    </div>
</section>
