<section class="bg-white">
    <div class="container flex flex-col items-center px-4 py-12 mx-auto text-center">
        <h2 class="text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
            {{$titolo_missione}}
        </h2>

        <p class="block max-w-4xl mt-4 text-gray-500">
            {!! $testo_missione !!}
        </p>

        <div class="mt-6">
            <a href="{{$cta_missione_dona_ora_url}}" class="group inline-flex items-center justify-center w-full px-4 py-2.5 overflow-hidden text-sm text-white transition-colors duration-300 bg-custom-green rounded-lg shadow sm:w-auto sm:mx-2 hover:bg-custom-light-green hover:text-custom-dark-green focus:ring focus:ring-gray-300 focus:ring-opacity-80">
                @include('svg.charity')
                @include('svg.charity-dark')
                <span class="mx-2">
                    {{$cta_missione_dona_ora_titolo}}
                </span>
            </a>

            <a href="{{$cta_missione_galleria_url}}"
               class="inline-flex items-center justify-center w-full px-4 py-2.5 mt-4 overflow-hidden text-sm text-custom-dark-green transition-colors duration-300 bg-custom-light-green rounded-lg shadow sm:w-auto sm:mx-2 sm:mt-0 hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80">
                @include('svg.gallery')
                <span class="mx-2">
                    {{$cta_missione_galleria_titolo}}
                </span>
            </a>
        </div>
    </div>
</section>