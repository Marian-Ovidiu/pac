@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
@endphp
<header x-data="{ open: false }" class="ui-section-tight pb-4">
    <div class="ui-container">
        <div class="ui-card-soft px-4 py-4 sm:px-6">
            <nav class="flex items-center justify-between gap-6" aria-label="Navigazione principale">
                <a href="{{ home_url('/') }}" title="{{ __('Home', 'text_domain') }}" class="flex items-center gap-4">
                    @if ($logoUrl)
                        <div class="ui-image-frame h-14 w-14 overflow-hidden rounded-full border-white/70 shadow-soft">
                            <img class="h-full w-full object-cover" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                        </div>
                    @endif
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.18em] text-custom-stone">PAC</p>
                        <p class="font-nunitoBold text-lg leading-tight text-custom-ink sm:text-xl">Project Africa Conservation</p>
                    </div>
                </a>

                <button
                    @click="open = !open"
                    type="button"
                    class="ui-button-secondary !px-4 !py-2 lg:hidden"
                    aria-controls="mobile-primary-menu"
                    :aria-expanded="open ? 'true' : 'false'"
                    :aria-label="open ? 'Chiudi menu principale' : 'Apri menu principale'">
                    Menu
                </button>

                <ul class="ml-auto hidden items-center gap-3 lg:flex">
                    @foreach ($menu as $item)
                        <li class="group relative">
                            <a href="{{ $item->url }}" class="ui-button-secondary !rounded-full !border-none !bg-transparent !px-4 !py-2 !shadow-none">
                                {{ $item->title }}
                            </a>
                            @if (!empty($item->children))
                                <ul class="absolute right-0 top-full z-50 hidden min-w-[14rem] rounded-3xl border border-custom-clay/60 bg-white p-3 shadow-card group-hover:block group-focus-within:block">
                                    @foreach ($item->children as $subitem)
                                        <li>
                                            <a href="{{ $subitem->url }}" class="block rounded-2xl px-4 py-3 text-sm font-medium text-custom-ink hover:bg-custom-sand">
                                                {{ $subitem->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>

            <nav id="mobile-primary-menu" x-show="open" @click.away="open = false" class="mt-5 border-t border-custom-clay/50 pt-4 lg:hidden" aria-label="Navigazione mobile">
                <ul class="space-y-3">
                    @foreach ($menu as $item)
                        <li class="rounded-3xl bg-white/60 p-3">
                            <a href="{{ $item->url }}" class="block text-base font-semibold text-custom-ink">
                                {{ $item->title }}
                            </a>
                            @if (!empty($item->children))
                                <ul class="mt-3 space-y-2 border-l border-custom-clay pl-4">
                                    @foreach ($item->children as $subitem)
                                        <li>
                                            <a href="{{ $subitem->url }}" class="block text-sm text-custom-stone">
                                                {{ $subitem->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</header>
