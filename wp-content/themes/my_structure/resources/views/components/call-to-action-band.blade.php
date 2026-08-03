<section class="cta-band" aria-labelledby="{{ $id ?? 'cta-band-title' }}">
    <div class="site-container cta-band__inner">
        <div>
            <h2 id="{{ $id ?? 'cta-band-title' }}">{{ $title }}</h2>
            @if(!empty($text))<p>{{ $text }}</p>@endif
        </div>
        <a class="button button--light" href="{{ esc_url($url) }}">{{ $label }}</a>
    </div>
</section>
