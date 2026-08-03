@php
    $media = theme_media_data($image ?? null, $alt ?? '');
    $figureClass = trim('media-figure ' . ($class ?? ''));
    $loadingMode = $loading ?? 'lazy';
    $ratio = $ratio ?? 'landscape';
    $captionText = trim((string) ($caption ?? $media['caption']));
    $generatedSources = $media['generated_asset'] !== ''
        ? theme_generated_media_sources($media['generated_asset'], $ratio)
        : null;
@endphp

<figure class="{{ $figureClass }} media-figure--{{ $ratio }} {{ $media['available'] ? 'is-available' : 'is-missing' }}"
    @if($media['generated_asset'] !== '') data-media-asset="{{ esc_attr($media['generated_asset']) }}" data-generated-illustrative="true" @endif>
    <div class="media-figure__frame">
        @if($media['available'])
            @if($generatedSources)
                <picture>
                    @if($ratio !== 'card')
                        <source media="(max-width: 767px)" type="image/avif" srcset="{{ esc_attr($generatedSources['mobile']['avif']) }}" sizes="100vw">
                        <source media="(max-width: 767px)" type="image/webp" srcset="{{ esc_attr($generatedSources['mobile']['webp']) }}" sizes="100vw">
                    @endif
                    <source type="image/avif" srcset="{{ esc_attr($generatedSources['desktop']['avif']) }}" sizes="{{ esc_attr($sizes ?? $media['sizes']) }}">
                    <source type="image/webp" srcset="{{ esc_attr($generatedSources['desktop']['webp']) }}" sizes="{{ esc_attr($sizes ?? $media['sizes']) }}">
                    <img
                        src="{{ esc_url($generatedSources['fallback']) }}"
                        srcset="{{ esc_attr($generatedSources['desktop']['jpg']) }}"
                        alt="{{ esc_attr($media['alt']) }}"
                        width="{{ $generatedSources['width'] }}"
                        height="{{ $generatedSources['height'] }}"
                        sizes="{{ esc_attr($sizes ?? $media['sizes']) }}"
                        loading="{{ $loadingMode }}"
                        @if($loadingMode === 'eager') fetchpriority="high" @endif
                        decoding="async">
                </picture>
            @else
                <img
                    src="{{ esc_url($media['url']) }}"
                    alt="{{ esc_attr($media['alt']) }}"
                    width="{{ $media['width'] }}"
                    height="{{ $media['height'] }}"
                    @if($media['srcset'] !== '') srcset="{{ esc_attr($media['srcset']) }}" @endif
                    sizes="{{ esc_attr($sizes ?? $media['sizes']) }}"
                    loading="{{ $loadingMode }}"
                    @if($loadingMode === 'eager') fetchpriority="high" @endif
                    decoding="async">
            @endif
        @else
            <div class="media-figure__fallback" role="img" aria-label="{{ esc_attr($alt ?? 'Immagine non disponibile') }}">
                <span class="media-figure__fallback-mark" aria-hidden="true"></span>
                <span>Documentazione fotografica non disponibile</span>
            </div>
        @endif
    </div>
    @if($captionText !== '')
        <figcaption>{{ $captionText }}</figcaption>
    @endif
</figure>
