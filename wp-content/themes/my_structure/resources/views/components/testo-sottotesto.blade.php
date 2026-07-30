<section class="fd-ops-intro {{ isset($class) ? $class : '' }}" aria-labelledby="fd-ops-intro-title">
    <div class="ui-container fd-ops-intro__inner">

        <div class="fd-ops-intro__head">
            <span class="fd-label">Operazioni attive</span>
            @if($titolo)
                <h2 class="fd-ops-intro__title" id="fd-ops-intro-title">{{ $titolo }}</h2>
            @endif
        </div>

        <div class="fd-ops-intro__aside">
            @if($sottotitolo)
                <p class="fd-ops-intro__text">{!! strip_tags($sottotitolo) !!}</p>
            @endif
            <a href="{{ home_url('/progetti') }}"
               class="fd-ops-intro__link"
               aria-label="Vedi tutti i progetti &#8212; Project Africa Conservation">
                Vedi tutti i progetti &#8594;
            </a>
        </div>

    </div>
</section>
