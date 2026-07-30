@php
    $typeLabels = [
        'Water & Health',
        'Healthcare',
        'Protection',
        'Surveillance',
    ];
@endphp

<section class="fd-operations" aria-label="Progetti e operazioni PAC">
    <div class="ui-container">
        <ol class="fd-operations__list" role="list">
            @foreach($progetti as $index => $progetto)
                @if(!empty($progetto['titolo']))
                    @php
                        $num       = str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT);
                        $typeLabel = $typeLabels[$index] ?? 'Field program';
                        $cardImg   = is_array($progetto['immagine'] ?? null) ? $progetto['immagine'] : [];
                        $cardImgUrl = theme_acf_image_url($progetto['immagine'] ?? null);
                        $cardImgAlt = trim($cardImg['alt'] ?? '') ?: 'Progetto ' . $progetto['titolo'] . ' &#8212; PAC';
                        $cardCaption = $cardImg['caption'] ?? '';
                        $ctaUrl    = $progetto['cta']['url'] ?? '';
                        $ctaTitle  = $progetto['cta']['title'] ?? 'Scopri';
                    @endphp

                    <li class="fd-operations__row {{ $index === 0 ? 'fd-operations__row--featured' : '' }}">

                        {{-- Row number --}}
                        <span class="fd-operations__num" aria-hidden="true">#{{ $num }}</span>

                        {{-- Thumbnail --}}
                        <div class="fd-operations__thumb" aria-hidden="{{ $cardImgUrl ? 'false' : 'true' }}">
                            @if($cardImgUrl)
                                <img src="{{ esc_url($cardImgUrl) }}"
                                     alt="{{ $cardImgAlt }}"
                                     loading="lazy"
                                     decoding="async">
                            @endif
                        </div>

                        {{-- Body --}}
                        <div class="fd-operations__body">
                            <span class="fd-operations__type">{{ $typeLabel }}</span>
                            <h3 class="fd-operations__title">{{ $progetto['titolo'] }}</h3>
                            @if($cardCaption)
                                <p class="fd-operations__desc">{{ wp_trim_words($cardCaption, 18) }}</p>
                            @endif
                        </div>

                        {{-- Status + CTA --}}
                        <div class="fd-operations__tail">
                            <span class="fd-operations__badge">[ACTIVE]</span>
                            @if($ctaUrl)
                                <a href="{{ esc_url($ctaUrl) }}"
                                   class="fd-operations__cta"
                                   aria-label="{{ $ctaTitle }}: {{ $progetto['titolo'] }} &#8212; PAC">
                                    {{ $ctaTitle }} &#8594;
                                </a>
                            @endif
                        </div>

                    </li>
                @endif
            @endforeach
        </ol>
    </div>
</section>
