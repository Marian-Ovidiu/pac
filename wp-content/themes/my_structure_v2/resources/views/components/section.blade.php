<section class="py-12 md:py-20 bg-white">
    <div class="container mx-auto px-4 sm:px-6">
        @if ($titolo)
            <h2 class="text-3xl font-nunitoBold text-custom-dark-green text-center mb-12 md:mb-20 lg:text-4xl">
                {{ $titolo }}
            </h2>
        @endif

        <div class="space-y-12 md:space-y-16 max-w-7xl mx-auto">
            @foreach ($items as $index => $item)
                @php
                    $hasImage = isset($item['immagini']) && count($item['immagini']) > 0;
                @endphp
        
                <article class="bg-white rounded-2xl shadow-md overflow-hidden border border-gray-100">
                    <div class="flex flex-col {{ $hasImage ? 'md:flex-row' : 'items-center text-center' }} {{ $hasImage && $index % 2 === 1 ? 'md:flex-row-reverse' : '' }}">
                        
                        {{-- Sezione immagine (solo se presente) --}}
                        @if($hasImage)
                            <div class="md:w-1/2 p-6 md:p-8">
                                <div class="overflow-hidden rounded-xl">
                                    <div class="h-[150px] sm:h-[250px] lg:h-[300px] xl:h-[300px] 2xl:h-[300px] swiper swiper-progetto w-full" role="group" aria-label="Image slider">
                                        <div class="swiper-wrapper">
                                            @foreach ($item['immagini'] as $img)
                                                <figure class="swiper-slide h-[150px] sm:h-[250px] lg:h-[300px] xl:h-[300px] 2xl:h-[300px]">
                                                    <img 
                                                        src="{{ $img['url'] }}" 
                                                        alt="{{ $img['alt'] ?? ($item['sottoTitolo'] ?? 'Project image') }}" 
                                                        title="{{ $img['title'] ?? ($item['sottoTitolo'] ?? '') }}"
                                                        class="w-full object-cover"
                                                        loading="lazy"
                                                    >
                                                    @if(isset($img['caption']) && $img['caption'])
                                                        <figcaption class="sr-only">{{ $img['caption'] }}</figcaption>
                                                    @endif
                                                </figure>
                                            @endforeach
                                        </div>
                                        <div class="swiper-pagination mt-4" aria-hidden="true"></div>
                                    </div>
                                </div>
                            </div>
                        @endif
        
                        {{-- Sezione testo --}}
                        <div class="{{ $hasImage ? 'md:w-1/2 p-6 md:p-8 flex flex-col justify-center border-t md:border-t-0 md:border-l border-gray-100' : 'p-6 sm:p-10' }}">
                            <div class="{{ $hasImage ? '' : 'max-w-xl mx-auto' }}">
                                @if (!empty($item['sottoTitolo']))
                                    <h3 class="text-xl md:text-2xl font-nunitoSansRegular text-custom-dark-green mb-3 md:mb-4">
                                        {{ $item['sottoTitolo'] }}
                                    </h3>
                                @endif
        
                                @if (!empty($item['testo']))
                                    <div class="prose text-gray-600 font-nunitoSansLight">
                                        {!! $item['testo'] !!}
                                    </div>
                                @endif
                            </div>
                        </div>
        
                    </div>
                </article>
            @endforeach
        </div>
        
    </div>
</section>