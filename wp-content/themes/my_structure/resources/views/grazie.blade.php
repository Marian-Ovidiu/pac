@extends('layouts.mainLayout')

@section('content')
    <section class="py-10 sm:py-16 lg:py-24">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:items-stretch md:grid-cols-2 gap-y-5">
                <div class="h-full flex flex-col justify-center">
                    <div class="relative h-full py-10">
                        <div class="absolute inset-0">
                            <img class="object-cover w-full h-full md:object-left md:origin-top-left" src="{{$fields->immagine['url']}}" alt="{{$fields->immagine['alt']}}" />
                        </div>
                        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
                            <div class="text-center md:text-left">
                                <h3 class="font-bold leading-tight text-white text-4xl lg:text-5xl">{{$fields->titolo}}</h3>
                                <p class="mt-4 text-base text-gray-200">{!! $fields->testo !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <div class="container mx-auto">
                        <div class="mx-auto flex justify-center flex-col items-center max-w-screen-lg px-6">
                            <a href="{{$fields->cta['url']}}">
                                <button class="mt-4 min-w-32 rounded-full border-emerald-500 bg-custom-dark-green px-5 py-4 text-lg font-bold text-white transition hover:translate-y-1">
                                    {{$fields->cta['title']}}
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
