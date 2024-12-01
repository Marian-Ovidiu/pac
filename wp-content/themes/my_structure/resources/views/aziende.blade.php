@php
    /**
     * @var Models\AziendeFields $fields
     */
@endphp
@extends('layouts.mainLayout')
@section('content')
    <section class="relative py-10 overflow-hidden bg-black sm:py-16 lg:py-24 xl:py-32">
        <div class="absolute inset-0">
            <img class="object-cover w-full h-full md:object-left md:scale-150 md:origin-top-left" src="{{$fields->immagine_hero['url']}}" alt="" />
        </div>
        <div class="absolute inset-0 hidden bg-gradient-to-r md:block from-black to-transparent"></div>
        <div class="absolute inset-0 block bg-black/60 md:hidden"></div>
        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl" x-data="typingEffect()">
            <div class="text-center md:w-2/3 lg:w-1/2 xl:w-1/2 md:text-left">
                @if($fields->hero_titolo)
                    <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">{{$fields->hero_titolo}}</h1>
                @endif
                @if($fields->hero_sottotitolo)
                    <div class="min-h-[1.5rem] mt-4 text-base text-gray-200 ">
                        {!! $fields->hero_sottotitolo !!}
                    </div>
                @endif
            </div>
        </div>
    </section>
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row justify-center items-center">
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
                     'immagine' => $fields->immagine_banner,
                     'class' => 'md:w-2/3',
                 ])
            </div>
        </div>
    </div>

    <div class="container mx-auto">
        <section class="py-10 bg-gray-100 sm:py-16 lg:py-24">
            <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="max-w-2xl mx-auto text-center">
                   @if($fields->form_titolo)
                        <h2 class="text-3xl font-bold leading-tight text-custom-dark-green sm:text-4xl lg:text-5xl">{{$fields->form_titolo}}</h2>
                   @endif
                   @if($fields->form_testo)
                       <p class="max-w-xl mx-auto mt-4 text-base leading-relaxed text-gray-500">{!! $fields->form_testo !!}</p>
                   @endif
                </div>
                @if($fields->shortcode_form)
                    {!! do_shortcode($fields->shortcode_form) !!}
                @endif
                {{--<div class="max-w-5xl mx-auto mt-12 sm:mt-16">
                    <div class="mt-6 overflow-hidden bg-white rounded-xl">
                        <div class="px-6 py-12 sm:p-12">
                            <h3 class="text-3xl font-semibold text-center text-custom-dark-green">Scrivici</h3>

                            <form action="#" method="POST" class="mt-14">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-5 gap-y-4">
                                    <div>
                                        <label for="" class="text-base font-medium text-gray-900"> Nome </label>
                                        <div class="mt-2.5 relative">
                                            <input type="text" name="" id="" placeholder="Il tuo nome" class="block w-full px-4 py-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600" />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="" class="text-base font-medium text-gray-900"> Email </label>
                                        <div class="mt-2.5 relative">
                                            <input type="email" name="" id="" placeholder="Email" class="block w-full px-4 py-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600" />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="" class="text-base font-medium text-gray-900"> Numero di telefono </label>
                                        <div class="mt-2.5 relative">
                                            <input type="tel" name="" id="" placeholder="Numero di telefono" class="block w-full px-4 py-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600" />
                                        </div>
                                    </div>

                                    <div>
                                        <label for="" class="text-base font-medium text-gray-900"> Ragione Sociale </label>
                                        <div class="mt-2.5 relative">
                                            <input type="text" name="" id="" placeholder="Nome azienda o ragione sociale" class="block w-full px-4 py-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md focus:outline-none focus:border-blue-600 caret-blue-600" />
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <label for="" class="text-base font-medium text-gray-900"> Messaggio </label>
                                        <div class="mt-2.5 relative">
                                            <textarea name="" id="" placeholder="" class="block w-full px-4 py-4 text-black placeholder-gray-500 transition-all duration-200 bg-white border border-gray-200 rounded-md resize-y focus:outline-none focus:border-blue-600 caret-blue-600" rows="4"></textarea>
                                        </div>
                                    </div>

                                    <div class="sm:col-span-2">
                                        <button type="submit" class="inline-flex items-center justify-center w-full px-4 py-4 mt-2 text-base font-semibold text-white transition-all duration-200 bg-custom-dark-green border border-transparent rounded-md focus:outline-none hover:bg-blue-700 focus:bg-blue-700">
                                            Invia
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>--}}
            </div>
        </section>
    </div>
@endsection