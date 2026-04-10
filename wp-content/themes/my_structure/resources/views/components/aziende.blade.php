<section class="ui-section-tight">
    <div class="ui-container">
        <div class="ui-panel !rounded-none overflow-hidden">
            <div class="grid items-stretch md:grid-cols-[1.05fr_0.95fr]">
                @if(!empty($immagine_url))
                    <figure class="relative min-h-[20rem] overflow-hidden">
                        <img
                            class="h-full w-full object-cover"
                            src="{{ $immagine_url }}"
                            alt="{{ $immagine_alt ?? $titolo }}"
                            title="{{ $immagine_title ?? '' }}"
                            loading="lazy"
                            decoding="async">
                        @if($immagine_caption)
                            <figcaption class="absolute bottom-0 left-0 right-0 bg-black/35 px-5 py-4 text-sm text-white/85">{{ $immagine_caption }}</figcaption>
                        @endif
                        @if($immagine_description)
                            <div class="sr-only">{{ $immagine_description }}</div>
                        @endif
                    </figure>
                @endif

                <div class="flex flex-col justify-center p-7 sm:p-10">
                    <span class="ui-kicker border-white/15 bg-white/10 text-white">Aziende</span>
                    <h2 class="mt-5 text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">{{ $titolo }}</h2>
                    <div class="mt-5 max-w-2xl text-base leading-7 text-white/80">{!! $descrizione !!}</div>

                    @if(isset($cta) && !empty($cta['url']) && !empty($cta['title']))
                        <div class="mt-8">
                            <a href="{{ $cta['url'] }}" aria-label="{{ $cta['title'] }}" class="ui-button-ghost">
                                @include('svg.gallery')
                                <span>{{ $cta['title'] }}</span>
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
