@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
@endphp
<footer class="ui-section">
    <div class="ui-container">
        <div class="ui-panel ui-noise rounded-[2.75rem] px-6 py-8 sm:px-8 sm:py-10 lg:px-10 lg:py-12">
            <div class="grid gap-10 lg:grid-cols-[0.95fr_1.05fr]">
                <div class="space-y-6">
                    <div class="ui-brand-lockup">
                        @if ($logoUrl)
                            <div class="ui-brand-mark h-[4.75rem] w-[4.75rem] border-white/20 bg-white">
                                <img class="h-full w-full object-cover" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                            </div>
                        @endif
                        <div>
                            <p class="ui-brand-tag !text-white/65">Project Africa Conservation</p>
                            <h2 class="font-nunitoBold text-2xl leading-tight text-white sm:text-[2rem]">Conservazione, comunita, continuita</h2>
                        </div>
                    </div>
                    <p class="max-w-lg text-sm leading-7 text-white/78 sm:text-[15px]">
                        La differenza la fai tu: ogni piccolo gesto crea un cambiamento concreto per la fauna e per le comunita locali.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        <a href="mailto:info@project-africa-conservation.org" class="ui-button-ghost">info@project-africa-conservation.org</a>
                        <span class="ui-pill border-white/15 bg-white/10 text-white/80">Via Cavour 7 - 12042, Bra (CN)</span>
                    </div>
                </div>

                <div class="grid gap-8 md:grid-cols-[1fr_auto]">
                    <nav aria-label="Navigazione footer">
                        <ul class="grid gap-3 sm:grid-cols-2">
                            @foreach($menu as $item)
                                <li>
                                    <a class="block rounded-2xl border border-white/10 bg-white/10 px-4 py-3.5 text-sm font-medium text-white/90 transition hover:bg-white/20" href="{{ $item->url }}">
                                        {{ $item->title }}
                                    </a>
                                </li>
                                @if(!empty($item->children))
                                    @foreach($item->children as $subitem)
                                        <li>
                                            <a class="block rounded-2xl border border-white/10 bg-white/10 px-4 py-3.5 text-sm font-medium text-white/90 transition hover:bg-white/20" href="{{ $subitem->url }}">
                                                {{ $subitem->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </nav>

                    <ul class="flex flex-wrap items-start gap-3" aria-label="Social media">
                        <li>
                            <a href="https://www.facebook.com/share/15kZKmU4gr/" rel="noreferrer noopener" target="_blank" class="ui-button-ghost !rounded-full !bg-white/10 !px-4 !py-3 !text-white hover:!bg-white/18" aria-label="Facebook">
                                Facebook
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==" rel="noreferrer noopener" target="_blank" class="ui-button-ghost !rounded-full !bg-white/10 !px-4 !py-3 !text-white hover:!bg-white/18" aria-label="Instagram">
                                Instagram
                            </a>
                        </li>
                        <li>
                            <a href="https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/" rel="noreferrer noopener" target="_blank" class="ui-button-ghost !rounded-full !bg-white/10 !px-4 !py-3 !text-white hover:!bg-white/18" aria-label="LinkedIn">
                                LinkedIn
                            </a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-10 flex flex-col gap-3 border-t border-white/10 pt-5 text-xs text-white/65 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; 2024. PAC - Project Africa Conservation A.P.S. Tutti i diritti riservati.</p>
                <p>Questo sito e protetto da reCAPTCHA ed e soggetto alla Privacy Policy e ai Termini di Servizio di Google.</p>
            </div>
        </div>
    </div>
</footer>
