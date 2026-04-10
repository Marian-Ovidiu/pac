<section class="ui-projects-grid">
    <div class="ui-container">
        <div class="ui-projects-grid__wrap">
            @foreach($progetti as $index => $progetto)
                @if(!empty($progetto['titolo']))
                    @php
                        $badge = match($index) {
                            0 => 'Water & Health',
                            1 => 'Healthcare',
                            2 => 'Protection',
                            3 => 'Surveillance',
                            default => 'Field program',
                        };
                    @endphp

                    <article class="ui-project-card">
                        @if(!empty($progetto['cta']['url']))
                            <a
                                href="{{ $progetto['cta']['url'] }}"
                                aria-label="{{ $progetto['cta']['title'] ?? $progetto['titolo'] }}"
                                class="absolute inset-0 z-10">
                                <span class="sr-only">{{ $progetto['cta']['title'] ?? $progetto['titolo'] }}</span>
                            </a>
                        @endif

                        <figure class="ui-project-card__media">
                            @if(!empty($progetto['immagine']['url']))
                                <img
                                    src="{{ $progetto['immagine']['url'] }}"
                                    alt="{{ $progetto['immagine']['alt'] ?? 'Immagine del progetto' }}"
                                    title="{{ $progetto['immagine']['title'] ?? '' }}"
                                    class="ui-project-card__image"
                                    loading="lazy"
                                    decoding="async">
                            @else
                                <div class="ui-project-card__placeholder" aria-hidden="true">
                                    <svg viewBox="0 0 48 48" fill="none" class="h-14 w-14">
                                        <path d="M8 36L18.5 24.5C20.2 22.6 23.2 22.5 25.1 24.2L29 27.8L33.1 22.8C34.9 20.5 38.3 20.2 40.6 22L44 24.7V40H8V36Z" fill="currentColor" opacity=".28"/>
                                        <circle cx="18" cy="16" r="4.5" fill="currentColor" opacity=".28"/>
                                    </svg>
                                </div>
                            @endif

                            <span class="ui-project-card__badge">{{ $badge }}</span>
                        </figure>

                        <div class="ui-project-card__body">
                            <h3 class="ui-project-card__title">{{ $progetto['titolo'] }}</h3>
                            <p class="ui-project-card__text">
                                {{ $progetto['immagine']['caption'] ?? 'Approfondisci questo programma sul campo e scopri come sostenerlo.' }}
                            </p>

                            @if(!empty($progetto['cta']['url']) && !empty($progetto['cta']['title']))
                                <div class="ui-project-card__footer">
                                    <a href="{{ $progetto['cta']['url'] }}" aria-label="{{ $progetto['cta']['title'] }}" class="ui-project-card__button relative z-20">
                                        {{ $progetto['cta']['title'] }}
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
