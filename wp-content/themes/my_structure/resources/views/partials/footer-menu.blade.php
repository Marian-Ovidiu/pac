@php
    $options = \Models\Options\OpzioniGlobaliFields::get();
    $logoUrl = $options->logo['url'] ?? null;
@endphp
<footer class="ui-section">
    <div class="ui-container">
        <div class="ui-panel ui-noise rounded-[2.5rem] px-6 py-8 sm:px-8 lg:px-10">
            <div class="grid gap-8 lg:grid-cols-[0.9fr_1.1fr]">
                <div class="space-y-5">
                    <div class="flex items-center gap-4">
                        @if ($logoUrl)
                            <div class="ui-image-frame h-16 w-16 overflow-hidden rounded-full border-white/15">
                                <img class="h-full w-full object-cover" src="{{ $logoUrl }}" alt="Project Africa Conservation logo" />
                            </div>
                        @endif
                        <div>
                            <p class="text-xs uppercase tracking-[0.2em] text-white/70">Project Africa Conservation</p>
                            <h2 class="font-nunitoBold text-2xl text-white">Conservazione, comunita, continuita</h2>
                        </div>
                    </div>
                    <p class="max-w-md text-sm leading-7 text-white/75">
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
                                    <a class="block rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/20" href="{{ $item->url }}">
                                        {{ $item->title }}
                                    </a>
                                </li>
                                @if(!empty($item->children))
                                    @foreach($item->children as $subitem)
                                        <li>
                                            <a class="block rounded-2xl border border-white/10 bg-white/10 px-4 py-3 text-sm font-medium text-white/90 transition hover:bg-white/20" href="{{ $subitem->url }}">
                                                {{ $subitem->title }}
                                            </a>
                                        </li>
                                    @endforeach
                                @endif
                            @endforeach
                        </ul>
                    </nav>

                    <ul class="flex items-start gap-3" aria-label="Social media">
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

            <div class="mt-8 flex flex-col gap-3 border-t border-white/10 pt-5 text-xs text-white/65 sm:flex-row sm:items-center sm:justify-between">
                <p>&copy; 2024. PAC - Project Africa Conservation A.P.S. Tutti i diritti riservati.</p>
                <p>Questo sito e protetto da reCAPTCHA ed e soggetto alla Privacy Policy e ai Termini di Servizio di Google.</p>
            </div>
        </div>
    </div>
</footer>
