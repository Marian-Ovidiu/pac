<section class="ui-projects-intro {{ isset($class) ? $class : '' }}">
    <div class="ui-container">
        <div class="ui-projects-intro__desktop hidden lg:grid">
            <div class="ui-projects-intro__copy">
                <span class="ui-projects-intro__eyebrow">Active initiatives</span>
                @if($titolo)
                    <h2 class="ui-projects-intro__title">{{ $titolo }}</h2>
                @endif
                @if($sottotitolo)
                    <div class="ui-projects-intro__text">
                        {!! $sottotitolo !!}
                    </div>
                @endif
            </div>

            <div class="ui-projects-intro__action">
                <a href="{{ home_url('/progetti') }}" class="ui-projects-intro__link">
                    <span>Vedi tutti i progetti</span>
                    <span aria-hidden="true">&rarr;</span>
                </a>
            </div>
        </div>

        <div class="lg:hidden ui-projects-intro__mobile">
            <span class="ui-projects-intro__eyebrow">Active projects</span>
            @if($titolo)
                <h2 class="ui-projects-intro__mobile-title">{{ $titolo }}</h2>
            @endif
            @if($sottotitolo)
                <div class="ui-projects-intro__mobile-text">
                    {!! $sottotitolo !!}
                </div>
            @endif
        </div>
    </div>
</section>
