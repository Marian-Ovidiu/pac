@php
    $heading = $heading ?? 'Fai una donazione al nostro progetto';
@endphp

<div
    x-data="donationFormData"
    x-init="init({{ (int) $projectId }}, '{{ esc_js($thankYouUrl) }}')"
    class="ui-donation-card"
    :aria-labelledby="'donation-heading-' + progettoId">
    <div class="mb-6 flex flex-wrap gap-2">
        <template x-for="(label, i) in ['Importo', 'Dati', 'Pagamento']" :key="i">
            <button
                type="button"
                @click="goToStep(i + 1)"
                class="ui-step"
                :class="step === i + 1 ? 'ui-step-active' : ''">
                <span x-text="label"></span>
            </button>
        </template>
    </div>

    <h2 :id="'donation-heading-' + progettoId" class="font-nunitoBold text-2xl text-custom-ink">
        {{ load_static_strings($heading) }}
    </h2>

    <template x-if="step === 1">
        <div class="mt-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-custom-stone">
                {{ load_static_strings('Quanto vuoi donare?') }}
            </p>

            <div class="mt-4 grid grid-cols-2 gap-3 sm:grid-cols-4">
                <template x-for="amount in [5, 25, 50, 100]" :key="amount">
                    <button
                        type="button"
                        @click="selectedAmount = amount; customAmount = ''; showAmountError = false"
                        :class="selectedAmount === amount && !customAmount ? 'ui-button' : 'ui-button-secondary'"
                        class="!w-full justify-center">
                        <span x-text="amount + ' EUR'"></span>
                    </button>
                </template>
            </div>

            <label class="mt-6 block text-sm font-semibold uppercase tracking-[0.16em] text-custom-stone" :for="'donation-amount-' + progettoId">
                {{ load_static_strings('Oppure inserisci un importo personalizzato') }}
            </label>
            <div class="mt-3 flex items-center gap-3">
                <input
                    x-model="customAmount"
                    @input="selectedAmount = null; showAmountError = false"
                    :id="'donation-amount-' + progettoId"
                    type="number"
                    min="1"
                    placeholder="{{ load_static_strings('Inserisci importo') }}"
                    class="ui-input">
                <span class="ui-pill shrink-0">EUR</span>
            </div>

            <template x-if="showAmountError">
                <p class="mt-2 text-sm text-red-600" role="alert">
                    {{ load_static_strings('Seleziona o inserisci un importo valido') }}
                </p>
            </template>

            <div class="mt-8 flex justify-end">
                <button type="button" @click.prevent="goToStep(2)" :disabled="!isAmountValid()" class="ui-button disabled:cursor-not-allowed disabled:opacity-50">
                    {{ load_static_strings('Avanti') }}
                </button>
            </div>
        </div>
    </template>

    <template x-if="step === 2">
        <div class="mt-6 space-y-4">
            <div>
                <label class="mb-2 block text-sm font-medium text-custom-ink" :for="'donation-name-' + progettoId">{{ load_static_strings('Nome') }}</label>
                <input x-model="formData.name" @blur="touched.name = true" :id="'donation-name-' + progettoId" type="text" autocomplete="given-name" required class="ui-input">
                <template x-if="touched.name && formData.name === ''">
                    <p class="mt-2 text-sm text-red-600" role="alert">{{ load_static_strings('Il nome e obbligatorio') }}</p>
                </template>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-custom-ink" :for="'donation-surname-' + progettoId">{{ load_static_strings('Cognome') }}</label>
                <input x-model="formData.surname" @blur="touched.surname = true" :id="'donation-surname-' + progettoId" type="text" autocomplete="family-name" required class="ui-input">
                <template x-if="touched.surname && formData.surname === ''">
                    <p class="mt-2 text-sm text-red-600" role="alert">{{ load_static_strings('Il cognome e obbligatorio') }}</p>
                </template>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-custom-ink" :for="'donation-email-' + progettoId">{{ load_static_strings('Email') }}</label>
                <input x-model="formData.email" @blur="touched.email = true" :id="'donation-email-' + progettoId" type="email" autocomplete="email" required class="ui-input">
                <template x-if="touched.email && (formData.email === '' || !formData.email.includes('@'))">
                    <p class="mt-2 text-sm text-red-600" role="alert">{{ load_static_strings('Inserisci una email valida') }}</p>
                </template>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-custom-ink" :for="'donation-phone-' + progettoId">{{ load_static_strings('Telefono') }}</label>
                <input x-model="formData.phone" @blur="touched.phone = true" :id="'donation-phone-' + progettoId" type="tel" autocomplete="tel" required class="ui-input">
                <template x-if="touched.phone && formData.phone === ''">
                    <p class="mt-2 text-sm text-red-600" role="alert">{{ load_static_strings('Il telefono e obbligatorio') }}</p>
                </template>
            </div>

            <div>
                <label class="mb-2 block text-sm font-medium text-custom-ink" :for="'donation-cf-' + progettoId">
                    {{ load_static_strings('Codice Fiscale') }}
                    <span class="ml-1 text-xs text-custom-stone">({{ load_static_strings('opzionale') }})</span>
                </label>
                <input x-model="formData.codiceFiscale" :id="'donation-cf-' + progettoId" type="text" class="ui-input">
            </div>

            <div class="mt-8 flex flex-wrap justify-between gap-3">
                <button type="button" @click="step = 1" class="ui-button-secondary">
                    {{ load_static_strings('Indietro') }}
                </button>
                <button
                    type="button"
                    @click="touched.name = true; touched.surname = true; touched.email = true; touched.phone = true; goToStep(3)"
                    :disabled="!isUserDataValid()"
                    class="ui-button disabled:cursor-not-allowed disabled:opacity-50">
                    {{ load_static_strings('Procedi al pagamento') }}
                </button>
            </div>
        </div>
    </template>

    <template x-if="step === 3">
        <div class="mt-6">
            <p class="text-sm font-semibold uppercase tracking-[0.16em] text-custom-stone">
                {{ load_static_strings('Metodo di pagamento') }}
            </p>

            <div class="mt-4 space-y-4 rounded-3xl border border-custom-clay/60 bg-custom-sand p-4">
                <div :id="'google-pay-button-' + progettoId" style="display: none;"></div>
                <form :id="'payment-form-' + progettoId" @submit.prevent="submitForm">
                    <div :id="'payment-element-' + progettoId" class="mb-4"></div>
                </form>
            </div>

            <div class="mt-8 flex flex-wrap justify-between gap-3">
                <button type="button" @click="step = 2" class="ui-button-secondary">
                    {{ load_static_strings('Indietro') }}
                </button>
                <button type="button" @click="submitForm()" class="ui-button">
                    {{ load_static_strings('Dona ora') }}
                </button>
            </div>
        </div>
    </template>
</div>
