<div class="w-full relative">
    <div class="swiper vertical-slide-carousel swiper-container relative">
        <div class="swiper-wrapper sw-wrapper-vertical">
            <div class="swiper-slide swiper-slide-vertical">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $immagine_1_url }}')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito">{{$titolo_1}}</h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                {{$testo_1}}
                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="{{$cta_1_url}}" class="inline-block rounded-full px-12 py-3 bg-custom-green
                                    text-sm font-medium text-white transition focus:outline-none
                                    focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    {{$cta_1_title}}
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="swiper-slide swiper-slide-vertical">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $immagine_2_url }}')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito">{{$titolo_2}}</h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                {{$testo_2}}
                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="{{$cta_2_url}}" class="inline-block rounded-full px-12 py-3 bg-custom-green
                                        text-sm font-medium text-white transition focus:outline-none
                                        focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    {{$cta_2_title}}
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="swiper-slide swiper-slide-vertical">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('{{ $immagine_3_url }}')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito">{{$titolo_3}}</h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                {{$testo_3}}
                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="{{$cta_3_url}}" class="inline-block rounded-full px-12 py-3 bg-custom-green
                                    text-sm font-medium text-white transition focus:outline-none
                                    focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    {{$cta_3_title}}
                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="swiper-pagination !top-1/3 !translate-y-8"></div>
    </div>
</div>