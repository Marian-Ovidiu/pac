@extends('layouts.mainLayout')
@section('content')
    @php
        $thankYouUrl = '/grazie';
        $img = $opzioniArchivio->immagine_hero ?? [];
        $typingTitoli = array_values(array_filter([
            $opzioniArchivio->highlights_frase_1 ?? null,
            $opzioniArchivio->highlights_frase_2 ?? null,
            $opzioniArchivio->highlights_frase_3 ?? null,
        ]));
    @endphp

    <section class="ui-section-tight">
        <div class="ui-container">
            <div class="ui-panel overflow-hidden rounded-[2.75rem]">
                <div class="grid items-stretch lg:grid-cols-[1.05fr_0.95fr]">
                    <div class="p-7 sm:p-10 lg:p-12">
                        <span class="ui-kicker border-white/15 bg-white/10 text-white">Archivio progetti</span>
                        <h1 class="mt-5 font-nunitoBold text-4xl leading-[1.04] text-white sm:text-5xl lg:text-6xl">
                            {{ $opzioniArchivio->titolo_hero }}
                        </h1>
                        @if(!empty($typingTitoli))
                            <div x-data="typingEffect({{ json_encode($typingTitoli) }})" class="mt-5">
                                <p class="text-lg font-medium text-white/80 sm:text-xl">
                                    <span x-text="displayText"></span>
                                </p>
                            </div>
                        @endif
                        @if(!empty($opzioniArchivio->testo_sotto_hero))
                            <div class="mt-6 max-w-2xl text-base leading-7 text-white/75">
                                {!! $opzioniArchivio->testo_sotto_hero !!}
                            </div>
                        @endif
                        <div class="mt-8 flex flex-wrap gap-3">
                            <span class="ui-pill border-white/20 bg-white/10 text-white/80">Sostieni un progetto specifico</span>
                            <span class="ui-pill border-white/20 bg-white/10 text-white/80">Donazione guidata in 3 step</span>
                        </div>
                    </div>

                    @if (!empty($img['url']))
                        <figure class="min-h-[24rem] lg:min-h-full">
                            <img
                                src="{{ $img['url'] }}"
                                alt="{{ $img['alt'] ?? ($opzioniArchivio->titolo_hero ?? 'Progetti') }}"
                                title="{{ $img['title'] ?? '' }}"
                                width="{{ $img['width'] ?? '' }}"
                                height="{{ $img['height'] ?? '' }}"
                                class="h-full w-full object-cover"
                                loading="lazy">
                        </figure>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <section class="ui-section">
        <div class="ui-container">
            <div class="space-y-10">
                @foreach ($progetti as $index => $progetto)
                    <article class="ui-card overflow-hidden">
                        <div class="grid items-stretch gap-0 lg:grid-cols-[1.05fr_0.95fr]">
                            <div class="{{ $index % 2 === 1 ? 'lg:order-2' : '' }}">
                                <div class="relative h-full min-h-[24rem]">
                                    <img
                                        src="{{ $progetto->featured_image }}"
                                        alt="{{ $progetto->titolo_card }}"
                                        title="{{ $progetto->titolo_card }}"
                                        class="h-full w-full object-cover"
                                        loading="lazy"
                                        decoding="async" />
                                    <div class="absolute inset-0 bg-gradient-to-t from-[rgba(18,32,24,0.88)] via-[rgba(18,32,24,0.2)] to-transparent"></div>
                                    <div class="absolute inset-x-0 bottom-0 p-6 sm:p-8">
                                        <span class="ui-pill border-white/20 bg-white/10 text-white/80">In evidenza</span>
                                        <h2 class="mt-4 font-nunitoBold text-3xl leading-tight text-white sm:text-4xl">
                                            {{ $progetto->titolo_card }}
                                        </h2>
                                        <div class="ui-project-copy mt-4 max-w-2xl text-white/80 prose-p:text-white/80">
                                            {!! $progetto->content !!}
                                        </div>
                                        <div class="mt-6">
                                            <a href="{{ get_permalink($progetto->id) }}" class="ui-button-ghost">
                                                Scopri il progetto
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="p-4 sm:p-6 lg:p-8 {{ $index % 2 === 1 ? 'lg:order-1' : '' }}">
                                @include('components.donation-form', [
                                    'projectId' => $progetto->id,
                                    'thankYouUrl' => $thankYouUrl,
                                ])
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>
@stop
