<div class="w-full relative h-[75vh]">
    <div class="swiper-container logo-carousel h-[65vh]">
        <div class="swiper-wrapper sw-wrapper-linear sw-wrapper-linear-custom h-full !h-full" aria-live="polite">
            
            @if($immagine_1_url && $titolo_1 && $testo_1)
                <div class="swiper-slide !h-full">
                    <section x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             :style="loaded
                             ? `background-image: url('{{ $immagine_1_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                             class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all w-full h-full justify-center items-center"
                             aria-label="{{ $immagine_1_alt ?? $titolo_1 }}"
                             role="region">
                        <div class="bg-black h-[75vh] p-8 md:p-12 lg:px-16 lg:py-24 content-slide  content-slide-container flex justify-center items-center">
                            <div class="text-center flex flex-col items-center justify-center content-slide md:w-[75%]">
                                <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">
                                    {{ $titolo_1 }}
                                </h3>
                                <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                    {{ $testo_1 }}
                                </p>
                                @if($immagine_1_caption)
                                    <p class="text-sm text-white/80 italic mt-2">{{ $immagine_1_caption }}</p>
                                @endif
                                @if($cta_1_url && $cta_1_title)
                                    <div class="mt-4 sm:mt-8">
                                        <a href="{{ $cta_1_url }}" aria-label="{{ $cta_1_title }}" role="button"
                                           class="inline-block rounded-full px-12 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                            {{ $cta_1_title }}
                                        </a>
                                    </div>
                                @endif
                                @if($immagine_1_description)
                                    <div class="sr-only">{{ $immagine_1_description }}</div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ $immagine_1_url }}" alt="{{ $immagine_1_alt ?? $titolo_1 }}" title="{{ $immagine_1_title ?? '' }}" class="sr-only" loading="lazy" decoding="async">
                    </section>
                </div>
            @endif

            @if($immagine_2_url && $titolo_2 && $testo_2 && $cta_2_url && $cta_2_title)
                <div class="swiper-slide !h-full">
                    <section x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             :style="loaded
                             ? `background-image: url('{{ $immagine_2_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                             class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all w-full h-full justify-center items-center"
                             aria-label="{{ $immagine_2_alt ?? $titolo_2 }}"
                             role="region">
                        <div class="bg-black/25 h-[75vh] p-8 md:p-12 lg:px-16 lg:py-24 content-slide content-slide-container flex justify-center items-center">
                            <div class="text-center flex flex-col items-center justify-center content-slide md:w-[75%]">
                                <h3 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white font-nunitoBold break-words text-center leading-tight max-w-xs">
                                    {{ $titolo_2 }}
                                </h3>
                                <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                    {{ $testo_2 }}
                                </p>
                                @if($immagine_2_caption)
                                    <p class="text-sm text-white/80 italic mt-2">{{ $immagine_2_caption }}</p>
                                @endif
                                <div class="mt-4 sm:mt-8">
                                    <a href="{{ $cta_2_url }}" aria-label="{{ $cta_2_title }}" role="button"
                                       class="inline-block rounded-full px-12 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                        {{ $cta_2_title }}
                                    </a>
                                </div>
                                @if($immagine_2_description)
                                    <div class="sr-only">{{ $immagine_2_description }}</div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ $immagine_2_url }}" alt="{{ $immagine_2_alt ?? $titolo_2 }}" title="{{ $immagine_2_title ?? '' }}" class="sr-only" loading="lazy" decoding="async">
                    </section>
                </div>
            @endif

            @if($immagine_3_url && $titolo_3 && $testo_3 && $cta_3_url && $cta_3_title)
                <div class="swiper-slide !h-full">
                    <section x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             :style="loaded
                             ? `background-image: url('{{ $immagine_3_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                             class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all w-full h-full justify-center items-center"
                             aria-label="{{ $immagine_3_alt ?? $titolo_3 }}"
                             role="region">
                        <div class="bg-black/25 h-[75vh] p-8 md:p-12 lg:px-16 lg:py-24 content-slide content-slide-container flex justify-center items-center">
                            <div class="text-center flex flex-col items-center justify-center content-slide md:w-[75%]">
                                <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">
                                    {{ $titolo_3 }}
                                </h3>
                                <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                    {{ $testo_3 }}
                                </p>
                                @if($immagine_3_caption)
                                    <p class="text-sm text-white/80 italic mt-2">{{ $immagine_3_caption }}</p>
                                @endif
                                <div class="mt-4 sm:mt-8">
                                    <a href="{{ $cta_3_url }}" aria-label="{{ $cta_3_title }}" role="button"
                                       class="inline-block rounded-full px-12 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                        {{ $cta_3_title }}
                                    </a>
                                </div>
                                @if($immagine_3_description)
                                    <div class="sr-only">{{ $immagine_3_description }}</div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ $immagine_3_url }}" alt="{{ $immagine_3_alt ?? $titolo_3 }}" title="{{ $immagine_3_title ?? '' }}" class="sr-only" loading="lazy" decoding="async">
                    </section>
                </div>
            @endif

            @if($immagine_4_url && $titolo_4 && $testo_4 && $cta_4_url && $cta_4_title)
                <div class="swiper-slide !h-full">
                    <section x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             :style="loaded
                             ? `background-image: url('{{ $immagine_4_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                             class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all w-full h-full justify-center items-center"
                             aria-label="{{ $immagine_4_alt ?? $titolo_4 }}"
                             role="region">
                        <div class="bg-black/25 h-[75vh] p-8 md:p-12 lg:px-16 lg:py-24 content-slide content-slide-container flex justify-center items-center">
                            <div class="text-center flex flex-col items-center justify-center content-slide md:w-[75%]">
                                <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">
                                    {{ $titolo_4 }}
                                </h3>
                                <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                    {{ $testo_4 }}
                                </p>
                                @if($immagine_4_caption)
                                    <p class="text-sm text-white/80 italic mt-2">{{ $immagine_4_caption }}</p>
                                @endif
                                <div class="mt-4 sm:mt-8">
                                    <a href="{{ $cta_4_url }}" aria-label="{{ $cta_4_title }}" role="button"
                                       class="inline-block rounded-full px-12 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                        {{ $cta_4_title }}
                                    </a>
                                </div>
                                @if($immagine_4_description)
                                    <div class="sr-only">{{ $immagine_4_description }}</div>
                                @endif
                            </div>
                        </div>
                        <img src="{{ $immagine_4_url }}" alt="{{ $immagine_4_alt ?? $titolo_4 }}" title="{{ $immagine_4_title ?? '' }}" class="sr-only" loading="lazy" decoding="async">
                    </section>
                </div>
            @endif

        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
