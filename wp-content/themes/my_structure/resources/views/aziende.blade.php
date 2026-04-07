@php
    /**
     * @var Models\AziendeFields $fields
     */
@endphp
@extends('layouts.mainLayout')
@section('content')
    <section class="relative overflow-hidden bg-black py-10 sm:py-16 lg:py-24 xl:py-32">
        <div class="absolute inset-0">
            <img
                class="h-full w-full object-cover md:origin-top-left md:scale-150 md:object-left"
                src="{{ $fields->immagine_hero['url'] }}"
                alt="{{ $fields->immagine_hero['alt'] ?? ($fields->hero_titolo ?? 'Aziende') }}" />
        </div>
        <div class="absolute inset-0 hidden bg-gradient-to-r from-black to-transparent md:block"></div>
        <div class="absolute inset-0 block bg-black/60 md:hidden"></div>
        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8" x-data="typingEffect()">
            <div class="text-center md:w-2/3 md:text-left lg:w-1/2 xl:w-1/2">
                @if($fields->hero_titolo)
                    <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">{{ $fields->hero_titolo }}</h1>
                @endif
                @if($fields->hero_sottotitolo)
                    <div class="mt-4 min-h-[1.5rem] text-base text-gray-200">
                        {!! $fields->hero_sottotitolo !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="container mx-auto">
        <div class="flex flex-col items-center justify-center md:flex-row">
            <div class="py-8">
                @include('components.testo-sottotesto',[
                   'titolo' => $fields->perche_titolo,
                   'sottotitolo' => $fields->perche_testo,
                   'highlight' => false,
                ])
            </div>

            <div class="pt-8">
                @include('components.aziende', [
                     'titolo' => $fields->come_titolo,
                     'descrizione' => $fields->come_testo,
                     'cta' => null,
                     'immagine_url' => $fields->immagine_banner['url'] ?? null,
                     'immagine_alt' => $fields->immagine_banner['alt'] ?? null,
                     'immagine_title' => $fields->immagine_banner['title'] ?? null,
                     'immagine_caption' => $fields->immagine_banner['caption'] ?? null,
                     'immagine_description' => $fields->immagine_banner['description'] ?? null,
                     'class' => 'md:w-2/3',
                 ])
            </div>
        </div>
    </div>

    <div class="container mx-auto">
        <section class="bg-gray-100 py-10 sm:py-16 lg:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center">
                   @if($fields->form_titolo)
                        <h2 class="text-3xl font-bold leading-tight text-custom-dark-green sm:text-4xl lg:text-5xl">{{ $fields->form_titolo }}</h2>
                   @endif
                   @if($fields->form_testo)
                       <p class="mx-auto mt-4 max-w-xl text-base leading-relaxed text-gray-500">{!! $fields->form_testo !!}</p>
                   @endif
                </div>
                @if($fields->shortcode_form)
                    {!! apply_filters('the_content', wpautop(do_shortcode($fields->shortcode_form))) !!}
                @endif
            </div>
        </section>
    </div>
@endsection
