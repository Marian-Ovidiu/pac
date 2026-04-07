<section class="ui-section-tight">
    <div class="ui-container">
        <div class="ui-card-soft mx-auto flex max-w-3xl flex-col items-center gap-5 px-6 py-8 text-center sm:px-10">
            @if($immagine_monologo)
                <div class="ui-image-frame w-36 overflow-hidden rounded-full border-white/70 shadow-soft">
                    <img src="{{ $immagine_monologo['url'] }}" alt="{{ $immagine_monologo['alt'] ?? ($titolo_monologo ?? 'Partner') }}" class="h-36 w-full object-cover">
                </div>
            @endif
            @if($titolo_monologo)
                <h2 class="font-nunitoBold text-2xl text-custom-ink sm:text-3xl">
                    {{ $titolo_monologo }}
                </h2>
            @endif
            @if($sottotitolo_monologo)
                <p class="max-w-2xl text-sm italic leading-7 text-custom-stone sm:text-base">
                    {{ $sottotitolo_monologo }}
                </p>
            @endif
        </div>
    </div>
</section>
