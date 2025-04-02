<section class="bg-white pt-3">
    <div class="container sm:py-2  px-8 py-8 mx-auto text-center">

        <div class="flex justify-center sm:justify-between">
            {{-- Blocco logo 1 --}}
            <div class="flex flex-col items-center w-1/2 sm:w-2/5">
                @if ($titolo_duo_logo_1)
                    <h2 class="font-nunitoBold text-base sm:text-lg lg:text-xl font-bold text-custom-dark-green">
                        {{ $titolo_duo_logo_1 }}
                    </h2>
                @endif

                @if ($immagine_duo_logo_1)
                    <div class="mt-2 flex justify-center">
                        <img src="{{ $immagine_duo_logo_1 }}" alt="Logo"
                            class="object-contain w-auto h-[75px] md:h-[100px] lg:h-[150px]">
                    </div>
                @endif
            </div>

            {{-- Blocco logo 2 --}}
            <div class="flex flex-col items-center w-1/2 sm:w-2/5">
                @if ($titolo_duo_logo_2)
                    <h2 class="font-nunitoBold text-base sm:text-lg lg:text-xl font-bold text-custom-dark-green">
                        {{ $titolo_duo_logo_2 }}
                    </h2>
                @endif

                @if ($immagine_duo_logo_2)
                    <div class="mt-2 flex justify-center">
                        <img src="{{ $immagine_duo_logo_2 }}" alt="Logo"
                        class="object-contain w-auto h-[75px] md:h-[100px] lg:h-[150px]">
                    </div>
                @endif
            </div>
        </div>

        {{-- Sottotitolo comune --}}
        @if ($sottotitolo_comune)
            <p class="mt-6 text-gray-400 text-[11px] italic opacity-75 max-w-2xl mx-auto">
                {{ $sottotitolo_comune }}
            </p>
        @endif

    </div>
</section>
