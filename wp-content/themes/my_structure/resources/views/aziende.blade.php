@php
    /**
     * @var Models\AziendeFields $fields
     */
    $normalizeImage = static function ($image) {
        if (is_array($image)) {
            return $image;
        }

        if (is_numeric($image)) {
            $imageId = (int) $image;
            $src = wp_get_attachment_image_src($imageId, 'full');

            if (!$src) {
                return [];
            }

            return [
                'url' => $src[0],
                'width' => $src[1] ?? null,
                'height' => $src[2] ?? null,
                'alt' => get_post_meta($imageId, '_wp_attachment_image_alt', true),
                'title' => get_the_title($imageId),
                'caption' => wp_get_attachment_caption($imageId),
                'description' => get_post_field('post_content', $imageId),
            ];
        }

        if (is_string($image) && filter_var($image, FILTER_VALIDATE_URL)) {
            return ['url' => $image];
        }

        return [];
    };

    $heroImage = $normalizeImage($fields->immagine_hero ?? null);
    $bannerImage = $normalizeImage($fields->immagine_banner ?? null);

    $partnerBenefits = [
        [
            'number' => '01',
            'title' => 'Impatto reale e misurabile',
            'text' => 'I fondi vengono destinati ai progetti sul campo, con aggiornamenti chiari e trasparenza sull’avanzamento.',
        ],
        [
            'number' => '02',
            'title' => 'Visibilita e reputazione',
            'text' => 'Il tuo brand entra nella comunicazione ufficiale PAC, rafforzando un posizionamento positivo e credibile.',
        ],
        [
            'number' => '03',
            'title' => 'Valore sociale',
            'text' => 'Clienti, collaboratori e comunita vedono un impegno concreto verso un futuro piu giusto e sostenibile.',
        ],
    ];

    $collaborationWays = [
        [
            'title' => 'Donazioni dirette',
            'text' => 'Sostieni finanziariamente uno o piu progetti specifici, con obiettivi chiari e rendicontazione.',
        ],
        [
            'title' => 'Sponsorizzazioni',
            'text' => 'Associa il tuo brand a una missione PAC e rendi visibile il contributo nei materiali di progetto.',
        ],
        [
            'title' => 'Eventi aziendali solidali',
            'text' => 'Coinvolgi team, clienti o stakeholder in raccolte fondi, campagne interne ed eventi dedicati.',
        ],
    ];

    $impactItems = [
        ['value' => 'Scuole', 'label' => 'spazi educativi e strutture locali'],
        ['value' => 'Materiali', 'label' => 'strumenti didattici e beni essenziali'],
        ['value' => 'Ranger', 'label' => 'supporto operativo sul territorio'],
        ['value' => 'Comunita', 'label' => 'azioni concrete per famiglie e bambini'],
    ];
@endphp
@extends('layouts.mainLayout')
@section('content')
    <section class="companies-hero">
        <div class="companies-hero__overlay" aria-hidden="true"></div>

        <div class="ui-container">
            <div class="companies-hero__layout">
                <div class="companies-hero__content">
                    <span class="companies-kicker companies-kicker--light">Partnership aziendali</span>
                    <h1>{{ $fields->hero_titolo ?: 'Collaboriamo per il Futuro dell’Africa' }}</h1>
                    <div class="companies-hero__text">
                        {!! $fields->hero_sottotitolo !!}
                    </div>
                    <div class="companies-hero__actions">
                        <a href="#companies-contact" class="companies-button companies-button--primary">Diventa azienda partner</a>
                        <a href="#companies-how" class="companies-button companies-button--secondary">Scopri come collaborare</a>
                    </div>
                    <dl class="companies-hero__proof" aria-label="Valori della partnership PAC">
                        <div>
                            <dt>Trasparenza</dt>
                            <dd>aggiornamenti sui progetti</dd>
                        </div>
                        <div>
                            <dt>Impatto</dt>
                            <dd>azioni sul campo</dd>
                        </div>
                        <div>
                            <dt>Valore</dt>
                            <dd>CSR e reputazione</dd>
                        </div>
                    </dl>
                </div>

                @if(!empty($heroImage['url']))
                    <figure class="companies-hero__media">
                        <img
                            src="{{ $heroImage['url'] }}"
                            alt="{{ $heroImage['alt'] ?? ($fields->hero_titolo ?? 'Aziende') }}"
                            class="companies-hero__image"
                            loading="eager"
                            decoding="async" />
                        <figcaption>
                            <span>Partnership PAC</span>
                            <strong>Fiducia, rispetto e impatto condiviso</strong>
                        </figcaption>
                    </figure>
                @endif
            </div>
        </div>
    </section>

    <section class="companies-benefits">
        <div class="ui-container">
            <div class="companies-section-head">
                <span class="companies-kicker">Perche scegliere PAC</span>
                <h2>{{ $fields->perche_titolo ?: 'Perché collaborare con noi?' }}</h2>
                <p>Una partnership PAC unisce responsabilita sociale, benefici reputazionali e risultati concreti per persone, fauna e territori.</p>
            </div>

            <div class="companies-benefits__grid">
                @foreach($partnerBenefits as $benefit)
                    <article class="companies-benefit-card">
                        <span>{{ $benefit['number'] }}</span>
                        <h3>{{ $benefit['title'] }}</h3>
                        <p>{{ $benefit['text'] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section id="companies-how" class="companies-how">
        <div class="ui-container">
            <div class="companies-how__layout">
                <figure class="companies-how__media">
                    @if(!empty($bannerImage['url']))
                        <img
                            src="{{ $bannerImage['url'] }}"
                            alt="{{ $bannerImage['alt'] ?? 'Elefante africano nella savana' }}"
                            title="{{ $bannerImage['title'] ?? '' }}"
                            loading="lazy"
                            decoding="async" />
                    @endif
                    <figcaption>
                        <span>Partnership PAC</span>
                        <strong>Conservazione, educazione e comunita</strong>
                    </figcaption>
                </figure>

                <div class="companies-how__content">
                    <span class="companies-kicker">Modalita di collaborazione</span>
                    <h2>{{ $fields->come_titolo ?: 'Come la tua azienda può aiutare?' }}</h2>
                    <p>Ogni azienda puo costruire una forma di supporto coerente con obiettivi, valori e capacita di coinvolgimento.</p>

                    <div class="companies-how__items">
                        @foreach($collaborationWays as $index => $way)
                            <article>
                                <span>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}</span>
                                <div>
                                    <h3>{{ $way['title'] }}</h3>
                                    <p>{{ $way['text'] }}</p>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    <a href="#companies-contact" class="companies-inline-link">Scopri le modalita <span aria-hidden="true">&rarr;</span></a>
                </div>
            </div>
        </div>
    </section>

    <section class="companies-impact">
        <div class="ui-container">
            <div class="companies-impact__intro">
                <span class="companies-kicker companies-kicker--light">Dove va il tuo supporto</span>
                <h2>Dal contributo aziendale a risultati concreti.</h2>
                <p>Colleghiamo ogni partnership a bisogni reali: educazione, protezione della fauna, supporto ai ranger e sviluppo delle comunita locali.</p>
            </div>

            <div class="companies-impact__grid">
                @foreach($impactItems as $item)
                    <article>
                        <strong>{{ $item['value'] }}</strong>
                        <span>{{ $item['label'] }}</span>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="companies-trust">
        <div class="ui-container">
            <div class="companies-trust__panel">
                <div>
                    <span class="companies-kicker">Trust & accountability</span>
                    <h2>Una collaborazione seria, chiara, rendicontabile.</h2>
                </div>
                <p>Il nostro obiettivo e costruire partnership sostenibili nel tempo: definiamo insieme priorita, messaggi, modalita di coinvolgimento e aggiornamenti da condividere con stakeholder, team e clienti.</p>
                <div class="companies-trust__logos" aria-label="Spazi logo partner">
                    <span>Partner</span>
                    <span>CSR</span>
                    <span>Impact</span>
                    <span>PAC</span>
                </div>
            </div>
        </div>
    </section>

    <section id="companies-contact" class="companies-contact">
        <div class="ui-container">
            <div class="companies-contact__head">
                <span class="companies-kicker">Contatto partnership</span>
                <h2>{{ $fields->form_titolo ?: 'Diventa un partner oggi stesso' }}</h2>
                <p>{!! $fields->form_testo !!}</p>
            </div>

            <div class="companies-contact__card">
                <div class="companies-contact__aside">
                    <h3>Costruiamo insieme la tua partnership.</h3>
                    <p>Raccontaci obiettivi, valori e tipo di coinvolgimento. Ti risponderemo con una proposta concreta e sostenibile.</p>
                    <ul>
                        <li>Analisi del progetto piu coerente</li>
                        <li>Modalita di contributo e comunicazione</li>
                        <li>Follow-up e aggiornamenti sul campo</li>
                    </ul>
                </div>

                <div class="companies-contact__form">
                    @if($fields->shortcode_form)
                        {!! apply_filters('the_content', wpautop(do_shortcode($fields->shortcode_form))) !!}
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection
