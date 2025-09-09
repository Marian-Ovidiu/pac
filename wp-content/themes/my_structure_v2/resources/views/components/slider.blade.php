<div class="w-full relative h-[75vh] overflow-hidden mb-12">
    <div class="swiper-container logo-carousel h-full opacity-0 transition-opacity duration-300" x-init="$nextTick(() => setTimeout(() => $el.classList.remove('opacity-0'), 100))">
        <div class="swiper-wrapper sw-wrapper-linear-custom h-full" aria-live="polite">
            @foreach($slides as $slide)
                @if($slide['url'] && $slide['titolo'] && $slide['testo'])
                    <div class="swiper-slide !h-full w-full">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                                 ? `background-image: url('{{ $slide['url'] }}'); background-size: cover; background-position: center;`
                                 : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat transition-all w-full h-full flex justify-center items-center"
                                 aria-label="{{ $slide['alt'] ?? $slide['titolo'] }}"
                                 role="region">
                            <div class="bg-black/25 h-full w-full p-6 sm:p-8 md:p-12 lg:p-16 flex justify-center items-center">
                                <div class="text-center flex flex-col items-center justify-center w-full max-w-screen-md px-4">
                                    <h3 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white font-nunitoBold">
                                        {{ $slide['titolo'] }}
                                    </h3>
                                    <p class="text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        {{ $slide['testo'] }}
                                    </p>
                                    @if($slide['caption'])
                                        <p class="text-sm text-white/80 italic mt-2">{{ $slide['caption'] }}</p>
                                    @endif
                                    @if($slide['cta_url'] && $slide['cta_title'])
                                        <div class="mt-4 sm:mt-8">
                                            <a href="{{ $slide['cta_url'] }}" aria-label="{{ $slide['cta_title'] }}" role="button"
                                               class="inline-block rounded-full px-8 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                                {{ $slide['cta_title'] }}
                                            </a>
                                        </div>
                                    @endif
                                    @if($slide['description'])
                                        <div class="sr-only">{{ $slide['description'] }}</div>
                                    @endif
                                </div>
                            </div>
                            <x-img :acf="$slide" class="sr-only" />
                        </section>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
