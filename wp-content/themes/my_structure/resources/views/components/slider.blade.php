<div class="w-full relative">
    <div class="swiper vertical-slide-carousel swiper-container relative">
        <div class="swiper-wrapper sw-wrapper-vertical" aria-live="polite">
            @if($immagine_1_url && $titolo_1 && $testo_1)
                <div class="swiper-slide swiper-slide-vertical">
                    <section x-data="{ loaded: false }"
                             x-intersect="loaded = true"
                             :style="loaded
                             ? `background-image: url('{{ $immagine_1_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                             class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all"
                             aria-label="Conservazione della fauna in Africa - Scopri di più">
                        <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                            <div class="text-center flex flex-col items-center justify-center content-slide">
                                <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">{{$titolo_1}}</h3>
                                <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                    {{$testo_1}}
                                </p>
                                @if($cta_1_url && $cta_1_title)
                                <div class="mt-4 sm:mt-8">
                                    <a href="{{$cta_1_url}}" aria-label="{{$cta_1_title}}" role="button"
                                       class="inline-block rounded-full px-12 py-3 bg-custom-green
                                          text-sm font-medium text-white transition focus:outline-none
                                          focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                        {{$cta_1_title}}
                                    </a>
                                </div>
                                @endif
                            </div>
                        </div>
                    </section>
                </div>
            @endif
            @if($immagine_2_url && $titolo_2 && $testo_2 && $cta_2_url && $cta_2_title)
                    <div class="swiper-slide swiper-slide-vertical">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                             ? `background-image: url('{{ $immagine_2_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all"
                                 aria-label="Conservazione della fauna in Africa - Scopri di più">
                            <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                                <div class="text-center flex flex-col items-center justify-center content-slide">
                                    <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">{{$titolo_2}}</h3>
                                    <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        {{$testo_2}}
                                    </p>
                                    <div class="mt-4 sm:mt-8">
                                        <a href="{{$cta_2_url}}" aria-label="{{$cta_2_title}}" role="button"
                                           class="inline-block rounded-full px-12 py-3 bg-custom-green
                                          text-sm font-medium text-white transition focus:outline-none
                                          focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                            {{$cta_2_title}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
            @endif
            @if($immagine_3_url && $titolo_3 && $testo_3 && $cta_3_url && $cta_3_title)
                    <div class="swiper-slide swiper-slide-vertical">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                             ? `background-image: url('{{ $immagine_3_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all"
                                 aria-label="Conservazione della fauna in Africa - Scopri di più">
                            <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                                <div class="text-center flex flex-col items-center justify-center content-slide">
                                    <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">{{$titolo_3}}</h3>
                                    <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        {{$testo_3}}
                                    </p>
                                    <div class="mt-4 sm:mt-8">
                                        <a href="{{$cta_3_url}}" aria-label="{{$cta_3_title}}" role="button"
                                           class="inline-block rounded-full px-12 py-3 bg-custom-green
                                          text-sm font-medium text-white transition focus:outline-none
                                          focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                            {{$cta_3_title}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
            @endif
            @if($immagine_4_url && $titolo_4 && $testo_4 && $cta_4_url && $cta_4_title)
                    <div class="swiper-slide swiper-slide-vertical">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                             ? `background-image: url('{{ $immagine_4_url }}'); background-size: cover;`
                             : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat lazy-bg transition-all"
                                 aria-label="Conservazione della fauna in Africa - Scopri di più">
                            <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                                <div class="text-center flex flex-col items-center justify-center content-slide">
                                    <h3 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunitoBold">{{$titolo_4}}</h3>
                                    <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        {{$testo_4}}
                                    </p>
                                    <div class="mt-4 sm:mt-8">
                                        <a href="{{$cta_4_url}}" aria-label="{{$cta_4_title}}" role="button"
                                           class="inline-block rounded-full px-12 py-3 bg-custom-green
                                          text-sm font-medium text-white transition focus:outline-none
                                          focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                            {{$cta_4_title}}
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>
            @endif

        </div>
        <div class="swiper-pagination !top-1/3 !translate-y-8"></div>
    </div>
</div>