<section class="ui-section-tight {{ isset($class) ? $class : '' }}">
    <div class="ui-container">
        <div class="mx-auto max-w-3xl text-center">
            @if($titolo)
                <h2 class="ui-title">{{ $titolo }}</h2>
            @endif
            @if($sottotitolo)
                <div class="ui-richtext mx-auto mt-5 max-w-3xl text-center">
                    {!! $sottotitolo !!}
                </div>
            @endif
            @if(isset($highlight) && $highlight)
                <div class="mt-5 min-h-[1.5rem]" x-data="typingEffect()">
                    <p class="mx-auto max-w-3xl text-base font-medium text-custom-stone sm:text-lg">
                        {{ $text_base_highlight }} <span class="font-semibold text-custom-dark-green" x-text="displayText"></span>
                    </p>
                </div>
            @endif
        </div>
    </div>
</section>
