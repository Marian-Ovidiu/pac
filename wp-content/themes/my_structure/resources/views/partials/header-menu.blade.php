@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
@endphp
<header x-data="{ open: false }" class="ui-section-tight pb-3">
    <div class="ui-container">
        <div class="ui-card-soft px-4 py-4 sm:px-6 sm:py-5">
            <nav class="flex items-center justify-between gap-6" aria-label="Navigazione principale">
                <a href="{{ home_url('/') }}" title="{{ __('Home', 'text_domain') }}" class="ui-brand-lockup shrink-0">
                    @if ($logoUrl)
                        <div class="flex h-16 w-auto items-center sm:h-[4.5rem]">
                            <img class="h-full w-auto object-contain" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                        </div>
                    @endif
                </a>

                <button
                    @click="open = !open"
                    type="button"
                    class="ui-button-secondary !px-4 !py-2.5 lg:hidden"
                    aria-controls="mobile-primary-menu"
                    :aria-expanded="open ? 'true' : 'false'"
                    :aria-label="open ? 'Chiudi menu principale' : 'Apri menu principale'">
                    Menu
                </button>

                <ul class="ml-auto hidden items-center gap-2 xl:gap-3 lg:flex">
                    @foreach ($menu as $item)
                        <li class="group relative">
                            <a href="{{ $item->url }}" class="ui-button-secondary !rounded-full !border border-transparent !bg-transparent !px-4 !py-2.5 !shadow-none hover:!border-custom-dark-green/10 hover:!bg-white/70">
                                {{ $item->title }}
                            </a>
                            @if (!empty($item->children))
                                <ul class="absolute right-0 top-full z-50 hidden min-w-[15rem] rounded-3xl border border-custom-clay/60 bg-white/96 p-3 shadow-card backdrop-blur-sm group-hover:block group-focus-within:block">
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
                        <li class="rounded-3xl bg-white/70 p-3 shadow-soft">
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
