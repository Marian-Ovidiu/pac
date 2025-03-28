<div class="w-full relative h-[75vh]">
    <div class="swiper-container logo-carousel h-[75vh] opacity-0 transition-opacity duration-300" x-init="$nextTick(() => setTimeout(() => $el.classList.remove('opacity-0'), 100))">
        <div class="swiper-wrapper sw-wrapper-linear-custom h-full !h-full" aria-live="polite">
            @foreach($slides as $slide)
                @if($slide['url'] && $slide['titolo'] && $slide['testo'])
                    <div class="swiper-slide !h-full">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                                 ? `background-image: url('{{ $slide['url'] }}'); background-size: cover;`
                                 : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all w-full h-full justify-center items-center"
                                 aria-label="{{ $slide['alt'] ?? $slide['titolo'] }}"
                                 role="region">
                            <div class="bg-black/25 h-[75vh] p-8 md:p-12 lg:px-16 lg:py-24 content-slide content-slide-container flex justify-center items-center">
                                <div class="text-center flex flex-col items-center justify-center content-slide md:w-[75%]">
                                    <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">
                                        {{ $slide['titolo'] }}
                                    </h3>
                                    <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        {{ $slide['testo'] }}
                                    </p>
                                    @if($slide['caption'])
                                        <p class="text-sm text-white/80 italic mt-2">{{ $slide['caption'] }}</p>
                                    @endif
                                    @if($slide['cta_url'] && $slide['cta_title'])
                                        <div class="mt-4 sm:mt-8">
                                            <a href="{{ $slide['cta_url'] }}" aria-label="{{ $slide['cta_title'] }}" role="button"
                                               class="inline-block rounded-full px-12 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                                {{ $slide['cta_title'] }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($slide['description'])
                                        <div class="sr-only">{{ $slide['description'] }}</div>
                                    @endif
                                </div>
                            </div>
                            <img src="{{ $slide['url'] }}" alt="{{ $slide['alt'] ?? $slide['titolo'] }}" title="{{ $slide['title'] ?? '' }}" class="sr-only" loading="lazy" decoding="async">
                        </section>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
