@php
    $stats = [
        ['value' => (string) count($projects), 'label' => 'Progetti attivi'],
        ['value' => '3',                        'label' => 'Aree di impatto'],
        ['value' => '365',                      'label' => 'Giorni sul campo'],
    ];

    $highlights = [
        'Tutela della fauna e anti-bracconaggio.',
        'Progetti sociali radicati nelle comunit&#224; locali.',
        'Presenza continua, relazioni stabili, risultati misurabili.',
    ];

    $aboutImage    = is_array($image ?? null) ? $image : null;
    $aboutImageUrl = $aboutImage['url'] ?? '';
    $aboutImageAlt = trim($aboutImage['alt'] ?? '') ?: 'Project Africa Conservation &#8212; ' . $title;
@endphp

<section class="fd-mission" aria-labelledby="fd-mission-title">
    <div class="ui-container fd-mission__inner">

        {{-- ── Copy column ── --}}
        <div class="fd-mission__copy">
            <span class="fd-label">Missione operativa</span>

            <h2 class="fd-mission__title" id="fd-mission-title">{{ $title }}</h2>

            <p class="fd-mission__body">{{ $description }}</p>

            <ul class="fd-mission__highlights" aria-label="Punti chiave">
                @foreach($highlights as $item)
                    <li>{!! $item !!}</li>
                @endforeach
            </ul>

            <div class="fd-mission__actions">
                @if(!empty($primaryCta['url']) && !empty($primaryCta['title']))
                    <a href="{{ esc_url($primaryCta['url']) }}"
                       aria-label="{{ $primaryCta['title'] }} &#8212; Project Africa Conservation"
                       class="field-dispatch-button field-dispatch-button--primary">
                        {{ $primaryCta['title'] }}
                        <span aria-hidden="true">&#8594;</span>
                    </a>
                @endif
                @if(!empty($secondaryCta['url']) && !empty($secondaryCta['title']))
                    <a href="{{ esc_url($secondaryCta['url']) }}"
                       aria-label="{{ $secondaryCta['title'] }} &#8212; Project Africa Conservation"
                       class="field-dispatch-button field-dispatch-button--secondary">
                        {{ $secondaryCta['title'] }}
                    </a>
                @endif
            </div>
        </div>

        {{-- ── Visual column ── --}}
        <div class="fd-mission__visual">
            @if($aboutImageUrl)
                <figure class="fd-mission__photo">
                    <img src="{{ esc_url($aboutImageUrl) }}"
                         alt="{{ $aboutImageAlt }}"
                         loading="lazy"
                         decoding="async">
                    <figcaption class="fd-mission__photo-caption">
                        <span>{{ $aboutImage['caption'] ?? 'Attivit&#224; sul campo &#8212; PAC' }}</span>
                    </figcaption>
                </figure>
            @endif

            <dl class="fd-mission__metrics" aria-label="Numeri PAC">
                @foreach($stats as $stat)
                    <div>
                        <dd>{{ $stat['value'] }}</dd>
                        <dt>{{ $stat['label'] }}</dt>
                    </div>
                @endforeach
            </dl>
        </div>

    </div>
</section>
