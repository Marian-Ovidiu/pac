<div class="container mx-auto py-8">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 px-4">
        @foreach($progetti as $key => $progetto)
            @if($progetto['titolo'])
                <article class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105">
                    <figure class="relative h-64 w-full overflow-hidden">
                        <img 
                            src="{{ $progetto['immagine']['url'] ?? '' }}" 
                            alt="{{ $progetto['immagine']['alt'] ?? 'Immagine del progetto' }}" 
                            title="{{ $progetto['immagine']['title'] ?? '' }}" 
                            class="h-full w-full object-cover object-center" 
                            loading="lazy"
                        >
                        <figcaption class="sr-only">
                            @if(!empty($progetto['immagine']['caption']))
                                {{ $progetto['immagine']['caption'] }}
                            @endif
                            @if(!empty($progetto['immagine']['description']))
                                - {{ $progetto['immagine']['description'] }}
                            @endif
                        </figcaption>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/0"></div>
                    </figure>

                    <div class="bg-gradient-to-t from-custom-green/30 via-custom-green to-custom-green px-6 py-4">
                        <h3 class="text-lg font-bold uppercase text-center text-gray-800 dark:text-white">
                            {{ $progetto['titolo'] }}
                        </h3>

                        <div class="mt-4 flex justify-center">
                            <a href="{{ $progetto['cta']['url'] }}" aria-label="{{ $progetto['cta']['title'] }}"
                               class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-custom-dark-green bg-custom-light-green rounded-lg shadow hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80 transition duration-300">
                                @include('svg.gallery')
                                <span class="ml-2">{{ $progetto['cta']['title'] }}</span>
                            </a>
                        </div>
                    </div>
                </article>
            @endif
        @endforeach
    </div>
</div>
