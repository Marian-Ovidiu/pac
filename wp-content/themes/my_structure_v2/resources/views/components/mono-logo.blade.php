<section class="bg-white pb-6 pt-0">
    <div class="container flex flex-col items-center px-4 mx-auto text-center">
        @if($titolo_monologo)
            <h2 class="font-nunitoBold text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
                {{ $titolo_monologo }}
            </h2>
        @endif
        @if($immagine_monologo)
            <div class="mt-6 w-48 h-auto">
                <img src="{{$immagine_monologo['url']}}" alt="Bonfante-e-Chiarle-2019" class="w-full h-auto">
            </div>
        @endif
        @if($sottotitolo_monologo)
            <p class="block max-w-4xl mt-2 text-gray-400 text-[11px] italic opacity-75">
                {{ $sottotitolo_monologo }}
            </p>
        @endif
    </div>
</section>