<section class="bg-white {{isset($class) ? $class : ''}}">
    <div class="container flex flex-col items-center px-4 mx-auto text-center">
        @if($titolo)
            <h2 class="text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
                {{$titolo}}
            </h2>
        @endif
        @if($sottotitolo)
            <p class="block max-w-4xl mt-4 text-gray-500">
                {!! $sottotitolo !!}
            </p>
        @endif
        @if(isset($highlight) && $highlight)
            <div class="min-h-[1.5rem]" x-data="typingEffect()">
                <p class="block max-w-4xl mt-4 text-gray-500 text-center">
                    {{$text_base_highlight}} <span x-text="displayText"></span>
                </p>
            </div>
        @endif
    </div>
</section>