@extends('layouts.mainLayout')

@section('content')
<section class="py-10 sm:py-16 lg:py-24">
    <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 items-center gap-8 md:grid-cols-2">
            <div class="relative h-full w-full overflow-hidden rounded-lg shadow-lg">
                <img
                    src="{{ $fields->immagine['url'] }}"
                    alt="{{ $fields->immagine['alt'] ?? $fields->titolo }}"
                    loading="lazy"
                    decoding="async"
                    class="h-full w-full object-cover object-center"
                    width="800"
                    height="600">
            </div>

            <div class="space-y-6 text-center md:text-left">
                <h1 class="text-4xl font-extrabold leading-tight text-custom-dark-green sm:text-5xl">
                    {{ $fields->titolo }}
                </h1>

                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $fields->testo !!}
                </div>

                @if(!empty($fields->cta['url']) && !empty($fields->cta['title']))
                    <div>
                        <a
                            href="{{ $fields->cta['url'] }}"
                            aria-label="Vai alla sezione: {{ $fields->cta['title'] }}"
                            class="mt-4 inline-flex rounded-full bg-custom-dark-green px-6 py-3 text-lg font-bold text-white transition-all hover:-translate-y-1 hover:shadow-xl">
                            {{ $fields->cta['title'] }}
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
@stop
