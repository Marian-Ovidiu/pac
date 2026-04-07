<section class="ui-section-tight">
    <div class="ui-container">
        <div class="ui-image-frame overflow-hidden rounded-[2.5rem]" role="region" aria-roledescription="carousel" aria-label="Contenuti in evidenza">
            <div class="swiper-container logo-carousel h-[72vh] min-h-[32rem] opacity-0 transition-opacity duration-300" x-init="$nextTick(() => setTimeout(() => $el.classList.remove('opacity-0'), 100))">
                <div class="swiper-wrapper h-full" aria-live="polite">
                    @foreach($slides as $slide)
                        @if($slide['url'] && $slide['titolo'] && $slide['testo'])
                            <div class="swiper-slide h-full w-full" role="group" aria-label="{{ $slide['titolo'] }}">
                                <section
                                    x-data="{ loaded: false }"
                                    x-intersect="loaded = true"
                                    :style="loaded
                                        ? `background-image: url('{{ $slide['url'] }}'); background-size: cover; background-position: center;`
                                        : 'background-color: #d8c8ae;'"
                                    class="flex h-full w-full items-end bg-cover bg-center bg-no-repeat">
                                    <div class="ui-noise flex h-full w-full items-end bg-gradient-to-t from-[rgba(18,32,24,0.88)] via-[rgba(18,32,24,0.35)] to-transparent p-6 sm:p-8 lg:p-12">
                                        <div class="w-full">
                                            <div class="ui-panel max-w-3xl p-6 sm:p-8">
                                                <span class="ui-kicker border-white/15 bg-white/10 text-white">Project Africa Conservation</span>
                                                <h2 class="mt-5 font-nunitoBold text-3xl leading-tight text-white sm:text-4xl lg:text-5xl">
                                                    {{ $slide['titolo'] }}
                                                </h2>
                                                <p class="mt-4 max-w-2xl text-sm leading-7 text-white/80 sm:text-base">
                                                    {{ $slide['testo'] }}
                                                </p>
                                                <div class="mt-8 flex flex-wrap items-center gap-3">
                                                    @if($slide['cta_url'] && $slide['cta_title'])
                                                        <a href="{{ $slide['cta_url'] }}" aria-label="{{ $slide['cta_title'] }}" class="ui-button">
                                                            {{ $slide['cta_title'] }}
                                                        </a>
                                                    @endif
                                                    @if($slide['caption'])
                                                        <span class="ui-pill border-white/20 bg-white/10 text-white/85">{{ $slide['caption'] }}</span>
                                                    @endif
                                                </div>
                                                @if($slide['description'])
                                                    <div class="sr-only">{{ $slide['description'] }}</div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <x-img :acf="$slide" class="sr-only" />
                                </section>
                            </div>
                        @endif
                    @endforeach
                </div>
                <div class="swiper-pagination !bottom-6"></div>
            </div>
        </div>
    </div>
</section>
