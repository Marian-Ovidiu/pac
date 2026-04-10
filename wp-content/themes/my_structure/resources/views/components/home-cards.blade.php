<section class="ui-section-tight">
    <div class="ui-container">
        <div class="ui-grid md:grid-cols-2 xl:grid-cols-3">
            @foreach($progetti as $progetto)
                @if(!empty($progetto['titolo']))
                    <article class="ui-card !rounded-none group relative overflow-hidden">
                        @if(!empty($progetto['cta']['url']))
                            <a
                                href="{{ $progetto['cta']['url'] }}"
                                aria-label="{{ $progetto['cta']['title'] ?? $progetto['titolo'] }}"
                                class="absolute inset-0 z-10">
                                <span class="sr-only">{{ $progetto['cta']['title'] ?? $progetto['titolo'] }}</span>
                            </a>
                        @endif

                        <figure class="relative h-[21rem] overflow-hidden">
                            <img
                                src="{{ $progetto['immagine']['url'] ?? '' }}"
                                alt="{{ $progetto['immagine']['alt'] ?? 'Immagine del progetto' }}"
                                title="{{ $progetto['immagine']['title'] ?? '' }}"
                                class="h-full w-full object-cover transition duration-500 group-hover:scale-105"
                                loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-[rgba(18,32,24,0.92)] via-[rgba(18,32,24,0.08)] to-transparent"></div>
                            <div class="absolute bottom-0 left-0 right-0 p-6 text-white">
                                <span class="ui-pill border-white/20 bg-white/10 text-white/85">Progetto</span>
                                <h3 class="mt-3 font-nunitoBold text-2xl leading-tight text-white">
                                    {{ $progetto['titolo'] }}
                                </h3>
                            </div>
                        </figure>

                        <div class="flex h-full flex-col justify-between gap-5 px-6 py-6">
                            <p class="text-sm leading-7 text-custom-stone">
                                {{ $progetto['immagine']['caption'] ?? 'Approfondisci il progetto e scopri come sostenerlo.' }}
                            </p>
                            @if(!empty($progetto['cta']['url']) && !empty($progetto['cta']['title']))
                                <div class="mt-4 flex justify-center">
                                    <a href="{{ $progetto['cta']['url'] }}" aria-label="{{ $progetto['cta']['title'] }}" class="ui-button-ghost relative z-20 shrink-0">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke-width="1" class="stroke-current">
                                            <path d="M22 13.4375C22 17.2087 22 19.0944 20.8284 20.2659C19.6569 21.4375 17.7712 21.4375 14 21.4375H10C6.22876 21.4375 4.34315 21.4375 3.17157 20.2659C2 19.0944 2 17.2087 2 13.4375C2 9.66626 2 7.78065 3.17157 6.60907C4.34315 5.4375 6.22876 5.4375 10 5.4375H14C17.7712 5.4375 19.6569 5.4375 20.8284 6.60907C21.4921 7.27271 21.7798 8.16545 21.9045 9.50024" stroke-linecap="round"></path>
                                            <path d="M3.98779 6C4.10022 5.06898 4.33494 4.42559 4.82498 3.93726C5.76553 3 7.27932 3 10.3069 3H13.5181C16.5457 3 18.0595 3 19 3.93726C19.4901 4.42559 19.7248 5.06898 19.8372 6"></path>
                                            <circle cx="17.5" cy="9.9375" r="1.5"></circle>
                                            <path d="M2 13.9376L3.75159 12.405C4.66286 11.6077 6.03628 11.6534 6.89249 12.5096L11.1822 16.7993C11.8694 17.4866 12.9512 17.5803 13.7464 17.0214L14.0446 16.8119C15.1888 16.0077 16.7369 16.1009 17.7765 17.0365L21 19.9376" stroke-linecap="round"></path>
                                        </svg>
                                        <span>{{ $progetto['cta']['title'] }}</span>
                                    </a>
                                </div>
                            @endif
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    </div>
</section>
