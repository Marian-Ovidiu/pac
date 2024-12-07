<?php
/**
 * @var \Models\Progetto $progetto
 */
?>
@php
    $thankYouUrl = get_permalink(pll_get_post(377, pll_current_language()));
@endphp
@extends('layouts.mainLayout')
@section('content')
    <section class="relative py-10 overflow-hidden bg-black sm:py-16 lg:py-24 xl:py-32">
        <div class="absolute inset-0">
            <img class="object-cover w-full h-full md:object-left md:scale-150 md:origin-top-left" src="{{$progetto->immagine_hero['url']}}" alt="" />
        </div>
        <div class="absolute inset-0 hidden bg-gradient-to-r md:block from-black to-transparent"></div>
        <div class="absolute inset-0 block bg-black/60 md:hidden"></div>
        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl" x-data="typingEffect()">
            <div class="text-center md:w-2/3 lg:w-1/2 xl:w-1/2 md:text-left">
                <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl">{{$progetto->titolo_hero}}</h1>
                <div class="min-h-[1.5rem] mt-4 text-base text-gray-200 ">
                    {!! $progetto->testo_hero !!}
                </div>
            </div>
        </div>
    </section>

    <section class="container bg-white flex-column lg:flex lg:flex-row mx-auto">
        <div class="container px-6 mx-auto">
            @component('components.section', ['titolo' => $progetto->problemi_titolo_1, 'items' => [
                [
                    'sottoTitolo' => $progetto->problemi_sotto_titolo_1,
                    'testo' => $progetto->problemi_testo_1,
                    'immagini' => [
                        $progetto->problemi_immagine_1_1,
                        $progetto->problemi_immagine_1_2,
                        $progetto->problemi_immagine_1_3,
                    ]
                ],
                [
                    'sottoTitolo' => $progetto->problemi_sotto_titolo_2,
                    'testo' => $progetto->problemi_testo_2,
                    'immagini' => [
                        $progetto->problemi_immagine_2_1,
                        $progetto->problemi_immagine_2_2,
                        $progetto->problemi_immagine_2_3,
                    ]
                ],
                [
                    'sottoTitolo' => $progetto->problemi_sotto_titolo_3,
                    'testo' => $progetto->problemi_testo_3,
                    'immagini' => [
                        $progetto->problemi_immagine_3_1,
                        $progetto->problemi_immagine_3_2,
                        $progetto->problemi_immagine_3_3,
                    ]
                ]
            ]])
            @endcomponent
        </div>
        <div class="container px-6 mx-auto">
            @component('components.section', ['titolo' => $progetto->soluzioni_titolo_1, 'items' => [
                           [
                               'sottoTitolo' => $progetto->soluzioni_sotto_titolo_1,
                               'testo' => $progetto->soluzioni_testo_1,
                               'immagini' => [
                                   $progetto->soluzioni_immagine_1_1,
                                   $progetto->soluzioni_immagine_1_2,
                                   $progetto->soluzioni_immagine_1_3,
                               ]
                           ],
                           [
                               'sottoTitolo' => $progetto->soluzioni_sotto_titolo_2,
                               'testo' => $progetto->soluzioni_testo_2,
                               'immagini' => [
                                   $progetto->soluzioni_immagine_2_1,
                                   $progetto->soluzioni_immagine_2_2,
                                   $progetto->soluzioni_immagine_2_3,
                               ]
                           ],
                           [
                               'sottoTitolo' => $progetto->soluzioni_sotto_titolo_3,
                               'testo' => $progetto->soluzioni_testo_3,
                               'immagini' => []
                           ]
                       ]])
            @endcomponent
        </div>
    </section>

    <section class="py-10 sm:py-16 lg:py-24">
        <div class="max-w-5xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:items-stretch md:grid-cols-2 gap-y-5">
                <div class="h-full flex flex-col justify-center">
                    <div class="relative h-full py-10">
                        <div class="absolute inset-0">
                            <img class="object-cover w-full h-full md:object-left md:origin-top-left" src="{{$progetto->featured_image}}" alt="" />
                        </div>
                        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
                            <div class="text-center md:text-left">
                                <h3 class="font-bold leading-tight text-white text-4xl lg:text-5xl">{{$progetto->titolo_card}}</h3>
                                <p class="mt-4 text-base text-gray-200">{!! $progetto->content !!}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="h-full">
                    <input type="hidden" id="thank-you-url" value="{{ $thankYouUrl }}">
                    <div class="container mx-auto" x-data="donationFormData({{ $progetto->id }})" x-ref="donationForm" id="{{$progetto->id}}">
                        <div class="mx-auto flex justify-center flex-col items-center max-w-screen-lg px-6">
                            <!-- Step 1: Selezione dell'importo -->
                            <div class="w-full text-center" x-show="step === 1">
                                <p class="font-serif text-xl font-bold text-custom-dark-green">{{load_static_strings('Scegli quanto donare')}}</p>
                                <div class="mt-4 mx-auto grid grid-cols-2 gap-2 lg:max-w-xl">
                                    <button
                                            @click="selectedAmount = 20; customAmount = ''"
                                            :class="selectedAmount === 20 && !customAmount ? 'bg-custom-dark-green text-white' : 'bg-custom-light-green text-custom-dark-green'"
                                            class="rounded-lg px-4 py-2 font-medium active:scale-95"
                                    >20€</button>
                                    <button
                                            @click="selectedAmount = 50; customAmount = ''"
                                            :class="selectedAmount === 50 && !customAmount ? 'bg-custom-dark-green text-white' : 'bg-custom-light-green text-custom-dark-green'"
                                            class="rounded-lg px-4 py-2 font-medium active:scale-95"
                                    >50€</button>
                                    <button
                                            @click="selectedAmount = 80; customAmount = ''"
                                            :class="selectedAmount === 80 && !customAmount ? 'bg-custom-dark-green text-white' : 'bg-custom-light-green text-custom-dark-green'"
                                            class="rounded-lg px-4 py-2 font-medium active:scale-95"
                                    >80€</button>
                                    <button
                                            @click="selectedAmount = 150; customAmount = ''"
                                            :class="selectedAmount === 150 && !customAmount ? 'bg-custom-dark-green text-white' : 'bg-custom-light-green text-custom-dark-green'"
                                            class="rounded-lg px-4 py-2 font-medium active:scale-95"
                                    >150€</button>
                                </div>
                            </div>

                            <!-- Step 1: Importo personalizzato -->
                            <div class="w-full text-center" x-show="step === 1">
                                <p class="mt-8 font-serif text-xl font-bold text-custom-dark-green">{{ load_static_strings('Oppure scegli tu l\'importo') }}</p>
                                <div class="w-full mx-auto md:w-1/2 px-3 mb-2 md:mb-0 flex flex-row justify-center items-center mt-4">
                                    <input x-model="customAmount" @input="selectedAmount = null" class="appearance-none block w-full rounded py-3 px-4 mb-3 leading-tight" type="number" placeholder="{{load_static_strings('Scegli importo')}}">
                                    <div class="decimals h-full px-4">,00</div>
                                </div>
                            </div>

                            <!-- Pulsante per avanzare allo step 2 dal primo step -->
                            <div  x-show="step === 1" class="buttons flex justify-between flex-row w-full text-center">
                                <a href="{{$progetto->url}}">
                                    <button class="mt-4 min-w-32 rounded-full border-emerald-500 bg-custom-dark-green px-5 py-4 text-lg font-bold text-white transition hover:translate-y-1">
                                        {{load_static_strings('Scopri di più')}}
                                    </button>
                                </a>
                                <button @click="step = 2"
                                        :disabled="!(selectedAmount || customAmount)"
                                        :class="(selectedAmount || customAmount) ? 'bg-custom-dark-green hover:translate-y-1' : 'bg-gray-400 cursor-not-allowed'"
                                        class="mt-4 min-w-32 rounded-full border-emerald-500 px-5 py-4 text-lg font-bold text-white transition">
                                    {{ load_static_strings('Avanti') }}
                                </button>
                            </div>


                            <!-- Step 2: Dettagli di fatturazione -->
                            <div x-show="step === 2" class="w-full text-center">
                                <p class="mt-8 font-serif text-xl font-bold text-custom-dark-green">{{load_static_strings('Dettagli di fatturazione')}}</p>
                                <div class="mt-4 mx-auto grid grid-cols-1 gap-6 lg:max-w-xl">
                                    <input x-model="formData.name" type="text" placeholder="{{load_static_strings('Nome')}}" name="name" required class="w-full rounded-lg border-gray-300 px-4 py-2"/>
                                    <input x-model="formData.surname" type="text" placeholder="{{load_static_strings('Cognome')}}" name="surname" required class="w-full rounded-lg border-gray-300 px-4 py-2"/>
                                    <input x-model="formData.phone" type="number" placeholder="{{load_static_strings('Numero di telefono')}}" name="phone" class="w-full rounded-lg border-gray-300 px-4 py-2"/>
                                    <input x-model="formData.email" type="email" placeholder="{{load_static_strings('Email')}}" name="email" required class="w-full rounded-lg border-gray-300 px-4 py-2"/>
                                    <input x-model="formData.codiceFiscale" type="text" placeholder="{{load_static_strings('Codice Fiscale')}}" name="codiceFiscale" class="w-full rounded-lg border-gray-300 px-4 py-2"/>
                                </div>
                                <div class="buttons flex justify-between flex-row">
                                    <button @click="step = 1" class="mt-4 min-w-32 rounded-full border-emerald-500 bg-custom-dark-green px-5 py-4 text-lg font-bold text-white transition hover:translate-y-1">
                                        {{load_static_strings('Indietro')}}
                                    </button>
                                    <button
                                            @click="createIntent()"
                                            id="call-intent"
                                            :disabled="!(formData.name && formData.surname && formData.email && formData.phone)"
                                            :class="(formData.name && formData.surname && formData.email && formData.phone) ? 'bg-custom-dark-green hover:translate-y-1' : 'bg-gray-400 cursor-not-allowed'"
                                            class="mt-4 min-w-32 rounded-full border-emerald-500 px-5 py-4 text-lg font-bold text-white transition">
                                        <span x-show="!loading">{{load_static_strings('Avanti')}}</span>
                                        <span x-show="loading" class="loader"></span>
                                    </button>
                                </div>
                            </div>

                            <!-- Step 3: Dati della carta di credito -->
                            <div x-show="step === 3" class="w-full text-center">
                                <p class="mt-8 font-serif text-xl font-bold text-custom-dark-green">{{load_static_strings('Dati della carta di credito')}}</p>
                                <div class="mt-4 mx-auto grid grid-cols-1 gap-6 lg:max-w-xl">
                                    @foreach($pagamenti_disponibili as $p)
                                        @if($p->id === 'stripe')
                                            <div id="card-element-container-{{$progetto->id}}">
                                                <form id="payment-form-{{$progetto->id}}">
                                                    <div id="payment-element-{{$progetto->id}}">
                                                        <!-- Elemento di Stripe per la carta di credito -->
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            {!! '<button data-gateway-id="' . esc_attr( $p->id ) . '">' . esc_html( $p->get_title() ) . '</button>' !!}
                                        @endif
                                    @endforeach
                                </div>
                                <div class="buttons flex justify-between flex-row">
                                    <button @click="step = 2" class="mt-4 min-w-32 rounded-full border-emerald-500 bg-custom-dark-green px-5 py-4 text-lg font-bold text-white transition hover:translate-y-1">
                                        {{load_static_strings('Indietro')}}
                                    </button>
                                    <button @click="submitForm()" class="mt-4 min-w-32 rounded-full border-emerald-500 px-5 py-4 text-lg font-bold text-white transition bg-custom-dark-green hover:translate-y-1">
                                        {{load_static_strings('Dona ora')}}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
