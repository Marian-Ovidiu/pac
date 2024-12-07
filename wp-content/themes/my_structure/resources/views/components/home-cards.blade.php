<div class="container mx-auto">
    <div class="flex hidden items-center justify-center bg-white sm:block md:block lg:block">
        <div class="grid grid-cols-1 gap-5 md:grid-cols-2 lg:grid-cols-3">
            @foreach($progetti as $key => $progetto)
                @if($progetto['titolo'])
                    <article class="out-group mb-4" style="z-index: {{$key+10}}">
                        <div class="flex flex-col items-center justify-center w-full max-w-sm mx-auto">
                            <div class="w-full h-64 bg-gray-300 bg-center bg-cover rounded-lg shadow-md relative"
                                 style="background-image: url({{$progetto['immagine']['url']}})" aria-label="{{ $progetto['immagine']['alt'] }}">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/0"></div>
                            </div>

                            <div class="w-full px-4 -mt-10 overflow-hidden rounded-lg shadow-lg bg-gradient-to-t from-custom-green/30 via-custom-green to-custom-green" style="z-index: {{$key+11}}">
                                <h3 class="py-2 font-bold tracking-wide text-center text-gray-800 uppercase dark:text-white">{{$progetto['titolo']}}</h3>
                                <div class="flex items-center justify-center px-3 py-2">
                                    <a href="{{$progetto['cta']['url']}}" aria-label="{{$progetto['cta']['title']}}"
                                       class="inline-flex items-center justify-center w-full px-4 py-2.5 overflow-hidden text-sm text-custom-dark-green transition-colors duration-300 bg-custom-light-green rounded-lg shadow sm:w-auto sm:mx-2 sm:mt-0 hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80">
                                        @include('svg.gallery')
                                        <span class="mx-2">
                                        {{$progetto['cta']['title']}}
                                    </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    </div>
</div>

