<section class="ui-section-tight">
    <div class="ui-container">
        <div class="ui-grid md:grid-cols-2 xl:grid-cols-3">
            @foreach($progetti as $progetto)
                @if(!empty($progetto['titolo']))
                    <article class="ui-card group overflow-hidden">
                        <figure class="relative h-80 overflow-hidden">
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

                        <div class="flex items-center justify-between gap-4 px-6 py-5">
                            <p class="text-sm leading-6 text-custom-stone">
                                {{ $progetto['immagine']['caption'] ?? 'Approfondisci il progetto e scopri come sostenerlo.' }}
                            </p>
                            @if(!empty($progetto['cta']['url']) && !empty($progetto['cta']['title']))
                                <a href="{{ $progetto['cta']['url'] }}" aria-label="{{ $progetto['cta']['title'] }}" class="ui-button-ghost shrink-0">
                                    {{ $progetto['cta']['title'] }}
                                </a>
                            @endif
                        </div>
                    </article>
                @endif
            @endforeach
        </div>
    </div>
</section>
