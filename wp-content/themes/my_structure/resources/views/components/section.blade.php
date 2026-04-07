<section class="ui-section">
    <div class="ui-container">
        @if ($titolo)
            <div class="mx-auto mb-12 max-w-3xl text-center">
                <span class="ui-kicker mb-5">Approfondimento</span>
                <h2 class="ui-title">{{ $titolo }}</h2>
            </div>
        @endif

        <div class="space-y-8">
            @foreach ($items as $index => $item)
                @php
                    $hasImage = isset($item['immagini']) && count($item['immagini']) > 0;
                @endphp

                <article class="ui-card overflow-hidden">
                    <div class="grid {{ $hasImage ? 'lg:grid-cols-[0.92fr_1.08fr]' : '' }}">
                        @if($hasImage)
                            <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }} border-b border-custom-clay/40 lg:border-b-0">
                                <div class="swiper swiper-progetto h-[18rem] w-full sm:h-[24rem]" role="group" aria-roledescription="carousel" aria-label="Galleria immagini del progetto">
                                    <div class="swiper-wrapper">
                                        @foreach ($item['immagini'] as $img)
                                            <figure class="swiper-slide h-[18rem] sm:h-[24rem]">
                                                <img
                                                    src="{{ $img['url'] }}"
                                                    alt="{{ $img['alt'] ?? ($item['sottoTitolo'] ?? 'Immagine progetto') }}"
                                                    title="{{ $img['title'] ?? ($item['sottoTitolo'] ?? '') }}"
                                                    class="h-full w-full object-cover"
                                                    loading="lazy">
                                                @if(!empty($img['caption']))
                                                    <figcaption class="sr-only">{{ $img['caption'] }}</figcaption>
                                                @endif
                                            </figure>
                                        @endforeach
                                    </div>
                                    <div class="swiper-pagination !bottom-4" aria-hidden="true"></div>
                                </div>
                            </div>
                        @endif

                        <div class="flex flex-col justify-center p-6 sm:p-8 lg:p-10 {{ $hasImage && $index % 2 === 1 ? 'lg:order-1' : '' }}">
                            @if (!empty($item['sottoTitolo']))
                                <span class="ui-kicker mb-4">Sul campo</span>
                                <h3 class="font-nunitoBold text-2xl text-custom-ink sm:text-3xl">
                                    {{ $item['sottoTitolo'] }}
                                </h3>
                            @endif

                            @if (!empty($item['testo']))
                                <div class="ui-richtext mt-4">
                                    {!! $item['testo'] !!}
                                </div>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
