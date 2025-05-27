@php
    /**
     * @var Models\Options\OpzioniGlobaliFields $fields
     */
@endphp
@extends('layouts.mainLayout')

@section('content')
    <section class="relative bg-gradient-to-b from-[#f5fef4] to-[#ffffff] py-20 sm:py-24 lg:py-28 overflow-hidden px-4 sm:px-6 lg:px-12">
        <div class="absolute top-0 left-0 w-full h-full opacity-10 pointer-events-none"
            style="background-image: url('/images/pattern-leaves.svg'); background-repeat: repeat; background-size: 400px;">
        </div>

        <div class="relative z-10 mx-auto max-w-4xl text-center animate-fadeIn">
            <h1 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold text-custom-dark-green font-nunitoBold leading-tight drop-shadow-sm">
                {{ $fields->title_blog }}
            </h1>
            <div class="mx-auto mt-3 max-w-2xl">
                <p class="text-base sm:text-lg text-custom-dark-green font-nunitoRegular opacity-90">
                    {{ $fields->subtitle_blog }}
                </p>
            </div>
            <div class="mt-5 mx-auto w-20 h-1 bg-custom-green rounded-full"></div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 sm:gap-10 mt-16">
            @foreach ($posts as $post)
                <article
                    class="bg-white border border-[#e1f5d8] rounded-2xl shadow-sm hover:shadow-xl transition duration-300 ease-in-out overflow-hidden animate-fadeInUp">
                    <a href="{{ get_permalink($post->ID) }}" class="block w-full bg-gray-100 overflow-hidden">
                        <img src="{{ get_the_post_thumbnail_url($post->ID, 'medium') }}" alt="{{ $post->title }}"
                             class="object-cover w-full h-auto max-h-64 rounded-t-2xl" />
                    </a>
                    <div class="p-6">
                        <span
                            class="inline-block px-3 py-1 text-[10px] font-medium text-custom-dark-green bg-custom-green bg-opacity-20 rounded-full uppercase tracking-wide font-nunitoSansLight">Ghana</span>
                        <h2
                            class="mt-2 text-base font-semibold text-custom-dark-green hover:text-custom-green transition font-nunitoBold leading-snug">
                            <a href=""{{ get_permalink($post->ID) }}">{{ $post->post_title }}</a>
                        </h2>
                        <p class="mt-2 text-[13px] text-[#5c4433] leading-snug font-nunitoRegular">
                            {!! the_excerpt() !!}
                        </p>
                        <div class="mt-4 text-[12px] text-[#967148] font-nunitoSansRegular">
                            Scritto da <strong>Marian Ov.</strong> {{ date('d M Y', strtotime($post->post_date)) }}
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </section>
@stop
