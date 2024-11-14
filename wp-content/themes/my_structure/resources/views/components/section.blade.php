<div>
    @if(isset($titolo))
        <div class="px-6">
            <h2 class="text-2xl text-custom-dark-green font-semibold capitalize lg:text-3xl pt-10">{{ $titolo }}</h2>
        </div>
    @endif
    @foreach ($items as $item)
        <div class="mt-8 px-6 lg:-mx-6 lg:flex-col lg:items-center lg:justify-center">
            <div class="mt-6 lg:mt-0 lg:mx-6">
                @if(isset($item['sottoTitolo']))
                    <h3 class="block mt-4 text-xl font-semibold text-gray-800">
                        {{ $item['sottoTitolo'] }}
                    </h3>
                @endif
                @if(isset($item['testo']))
                    <p class="mt-3 text-sm text-gray-500 md:text-sm">
                        {!! $item['testo'] !!}
                    </p>
                @endif
            </div>

            @php
                $immagini = array_filter($item['immagini'], fn($img) => isset($img['url']));
                $immaginiCount = count($immagini);
            @endphp

            <div class="relative w-full max-w-lg mt-4 mx-auto overflow-hidden" @if($immaginiCount > 1) x-data="{ current: 0 }" @endif>
                @if($immaginiCount > 1)
                    <!-- Galleria con più immagini -->
                    <div class="flex transition-transform duration-700" :style="`transform: translateX(-${current * 100}%)`">
                        @foreach($immagini as $immagine)
                            <img src="{{ $immagine['url'] }}" alt="{{ $immagine['alt'] ?? '' }}" loading="lazy" class="w-full" />
                        @endforeach
                    </div>
                    <button @click="current = (current + 1) % {{ $immaginiCount }}" aria-label="Prossima immagine" class="absolute right-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button @click="current = (current - 1 + {{ $immaginiCount }}) % {{ $immaginiCount }}" aria-label="Immagine precedente" class="absolute left-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                @elseif($immaginiCount === 1)
                    <!-- Singola immagine fissa senza navigazione -->
                    <img src="{{ $immagini[0]['url'] }}" alt="{{ $immagini[0]['alt'] ?? '' }}" loading="lazy" class="w-full" />
                @endif
            </div>
        </div>
    @endforeach
</div>
