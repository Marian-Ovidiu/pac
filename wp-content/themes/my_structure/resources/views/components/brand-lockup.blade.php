@php
    $brandOptions = $options ?? \Models\Options\OpzioniGlobaliFields::get();
    $brandLogo = theme_media_data($brandOptions->logo ?? null, 'Project Africa Conservation');
    $brandClass = trim('brand-lockup ' . ($class ?? ''));
@endphp

<span class="{{ $brandClass }}">
    @if($brandLogo['available'])
        <img
            class="brand-lockup__image"
            src="{{ esc_url($brandLogo['url']) }}"
            alt="{{ esc_attr($brandLogo['alt']) }}"
            width="{{ $brandLogo['width'] }}"
            height="{{ $brandLogo['height'] }}">
    @else
        <span class="brand-lockup__pictogram" aria-hidden="true"></span>
    @endif
    <span class="brand-lockup__copy">
        <span class="brand-lockup__name">PAC</span>
        <span class="brand-lockup__descriptor">Project Africa Conservation</span>
    </span>
</span>
