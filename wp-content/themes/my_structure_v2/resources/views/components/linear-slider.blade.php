<div class="swiper-container logo-marquee w-full my-4 overflow-hidden">
    <div class="swiper-wrapper sw-wrapper-linear">
        @if (isset($linearSlider) && !empty($linearSlider))
            @php
                $logos = [
                    [
                        'logo' => $linearSlider->logo_1,
                        'title' => $linearSlider->titolo_logo_1 ?? null,
                    ],
                    [
                        'logo' => $linearSlider->logo_2,
                        'title' => $linearSlider->titolo_logo_2 ?? null,
                    ],
                    [
                        'logo' => $linearSlider->logo_3,
                        'title' => $linearSlider->titolo_logo_3 ?? null,
                    ],
                ];
                $loopLogos = array_merge($logos, $logos);
            @endphp

            @foreach ($loopLogos as $item)
                @if (isset($item['logo']['url']) && isset($item['logo']['title']))
                    <div class="swiper-slide !w-auto flex flex-col items-center justify-center px-4 py-2">
                        <div class="text-center mb-2">
                            <p class="font-bold text-sm sm:text-base custom-dark-green whitespace-nowrap">
                                {{ $item['title'] }}
                            </p>
                        </div>
                        <img src="{{ $item['logo']['url'] }}" alt="{{ $item['logo']['title'] }}"
                            class="max-h-20 object-contain">
                    </div>
                @endif
            @endforeach
        @else
            <div class="swiper-slide">
                <p class="text-sm text-gray-500">No logos available</p>
            </div>
        @endif
    </div>
</div>

<div class="text-gray-600 text-xs text-center mt-2 mx-4">
    * La nostra presenza su questo sito non implica sponsorizzazione o contributi economici
</div>
