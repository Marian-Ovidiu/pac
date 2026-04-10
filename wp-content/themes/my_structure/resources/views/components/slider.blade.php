<section class="ui-section-tight ui-section-fullbleed">
    <div class="ui-image-frame !rounded-none overflow-hidden" role="region" aria-roledescription="carousel" aria-label="Contenuti in evidenza">
        <div class="swiper-container logo-carousel h-[74vh] min-h-[34rem] opacity-0 transition-opacity duration-300 lg:min-h-[38rem]" x-init="$nextTick(() => setTimeout(() => $el.classList.remove('opacity-0'), 100))">
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
                                    <div class="ui-noise flex h-full w-full items-end bg-gradient-to-t from-[rgba(18,32,24,0.9)] via-[rgba(18,32,24,0.38)] to-transparent p-5 sm:p-8 lg:p-12">
                                        <div class="mx-auto w-full max-w-[82rem] px-0 sm:px-0 lg:px-0">
                                            <div class="max-w-3xl border-0 bg-transparent p-6 text-white shadow-none sm:p-8 lg:p-10">
                                                <h2 class="mt-5 font-nunitoBold text-3xl leading-[1.04] text-white sm:text-4xl lg:text-5xl">
                                                    {{ $slide['titolo'] }}
                                                </h2>
                                                <p class="mt-4 max-w-2xl text-sm leading-7 text-white/82 sm:text-base">
                                                    {{ $slide['testo'] }}
                                                </p>
                                                <div class="mt-9 flex flex-wrap items-center gap-3">
                                                    @if($slide['cta_url'] && $slide['cta_title'])
                                                        <a href="{{ $slide['cta_url'] }}" aria-label="{{ $slide['cta_title'] }}" class="ui-button">
                                                            {{ $slide['cta_title'] }}
                                                        </a>
                                                    @endif
                                                </div>
                                                @if($slide['caption'] || $slide['description'])
                                                    <div class="sr-only">{{ $slide['description'] ?: $slide['caption'] }}</div>
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
</section>
