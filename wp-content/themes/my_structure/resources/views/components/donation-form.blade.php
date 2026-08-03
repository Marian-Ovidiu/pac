@php
    $heading = $heading ?? 'Sostieni questa missione';
@endphp

<div
    x-data="donationFormData"
    x-init="init({{ (int) $projectId }}, '{{ esc_js($thankYouUrl) }}')"
    class="donation-panel"
    :aria-labelledby="'donation-heading-' + progettoId"
    :aria-busy="loading.toString()">
    <ol class="donation-steps" aria-label="Avanzamento donazione">
        <template x-for="(label, i) in ['Importo', 'Dati', 'Pagamento']" :key="i">
            <li :class="{ 'is-current': step === i + 1, 'is-complete': step > i + 1 }" :aria-current="step === i + 1 ? 'step' : null">
                <span aria-hidden="true" x-text="i + 1"></span>
                <span x-text="label"></span>
            </li>
        </template>
    </ol>

    <h2 :id="'donation-heading-' + progettoId">{{ load_static_strings($heading) }}</h2>
    <p class="donation-panel__trust">Pagamento protetto da Stripe. Conferma e ricevuta vengono inviate via email.</p>

    <div class="form-status" aria-live="polite" aria-atomic="true">
        <p x-show="statusMessage" x-text="statusMessage"></p>
        <p x-show="errorMessage" x-text="errorMessage" class="form-error form-error--summary" role="alert"></p>
    </div>

    <template x-if="step === 1">
        <div class="donation-panel__step">
            <fieldset>
                <legend>{{ load_static_strings('Quanto vuoi donare?') }}</legend>
                <div class="donation-amounts">
                    <template x-for="amount in [5, 25, 50, 100]" :key="amount">
                        <button
                            type="button"
                            @click="selectedAmount = amount; customAmount = ''; showAmountError = false; errorMessage = ''"
                            :aria-pressed="(selectedAmount === amount && !customAmount).toString()"
                            :class="{ 'is-selected': selectedAmount === amount && !customAmount }">
                            <span x-text="amount + ' EUR'"></span>
                        </button>
                    </template>
                </div>
            </fieldset>

            <div class="form-field">
                <label :for="'donation-amount-' + progettoId">{{ load_static_strings('Oppure inserisci un importo personalizzato') }}</label>
                <div class="input-with-suffix">
                    <input
                        x-model="customAmount"
                        @input="selectedAmount = null; showAmountError = false; errorMessage = ''"
                        :id="'donation-amount-' + progettoId"
                        :aria-describedby="'donation-amount-help-' + progettoId"
                        :aria-invalid="showAmountError.toString()"
                        type="number"
                        inputmode="decimal"
                        min="1"
                        step="1"
                        placeholder="{{ load_static_strings('Inserisci importo') }}">
                    <span aria-hidden="true">EUR</span>
                </div>
                <p :id="'donation-amount-help-' + progettoId" class="form-help">Importo minimo: 1 EUR.</p>
                <template x-if="showAmountError">
                    <p class="form-error" role="alert">{{ load_static_strings('Seleziona o inserisci un importo valido') }}</p>
                </template>
            </div>

            <div class="form-actions form-actions--end">
                <button type="button" @click.prevent="goToStep(2)" :disabled="loading" class="button">
                    {{ load_static_strings('Avanti') }}
                </button>
            </div>
        </div>
    </template>

    <template x-if="step === 2">
        <div class="donation-panel__step">
            <div class="form-field">
                <label :for="'donation-name-' + progettoId">{{ load_static_strings('Nome') }}</label>
                <input x-model="formData.name" @blur="touched.name = true" :id="'donation-name-' + progettoId" :aria-invalid="(touched.name && formData.name === '').toString()" type="text" autocomplete="given-name" required>
                <template x-if="touched.name && formData.name === ''"><p class="form-error" role="alert">{{ load_static_strings('Il nome è obbligatorio') }}</p></template>
            </div>

            <div class="form-field">
                <label :for="'donation-surname-' + progettoId">{{ load_static_strings('Cognome') }}</label>
                <input x-model="formData.surname" @blur="touched.surname = true" :id="'donation-surname-' + progettoId" :aria-invalid="(touched.surname && formData.surname === '').toString()" type="text" autocomplete="family-name" required>
                <template x-if="touched.surname && formData.surname === ''"><p class="form-error" role="alert">{{ load_static_strings('Il cognome è obbligatorio') }}</p></template>
            </div>

            <div class="form-field">
                <label :for="'donation-email-' + progettoId">{{ load_static_strings('Email') }}</label>
                <input x-model="formData.email" @blur="touched.email = true" :id="'donation-email-' + progettoId" :aria-invalid="(touched.email && (formData.email === '' || !formData.email.includes('@'))).toString()" type="email" autocomplete="email" required>
                <template x-if="touched.email && (formData.email === '' || !formData.email.includes('@'))"><p class="form-error" role="alert">{{ load_static_strings('Inserisci un indirizzo email valido') }}</p></template>
            </div>

            <div class="form-field">
                <label :for="'donation-phone-' + progettoId">{{ load_static_strings('Telefono') }}</label>
                <input x-model="formData.phone" @blur="touched.phone = true" :id="'donation-phone-' + progettoId" :aria-invalid="(touched.phone && formData.phone === '').toString()" type="tel" autocomplete="tel" required>
                <template x-if="touched.phone && formData.phone === ''"><p class="form-error" role="alert">{{ load_static_strings('Il telefono è obbligatorio') }}</p></template>
            </div>

            <div class="form-field">
                <label :for="'donation-cf-' + progettoId">{{ load_static_strings('Codice fiscale') }} <span>({{ load_static_strings('opzionale') }})</span></label>
                <input x-model="formData.codiceFiscale" :id="'donation-cf-' + progettoId" type="text" autocomplete="off">
            </div>

            <div class="form-actions">
                <button type="button" @click="step = 1; errorMessage = ''" class="button button--secondary">{{ load_static_strings('Indietro') }}</button>
                <button
                    type="button"
                    @click="touched.name = true; touched.surname = true; touched.email = true; touched.phone = true; goToStep(3)"
                    :disabled="loading || !isUserDataValid()"
                    class="button">
                    <span x-show="!loading">{{ load_static_strings('Procedi al pagamento') }}</span>
                    <span x-show="loading">{{ load_static_strings('Preparazione…') }}</span>
                </button>
            </div>
        </div>
    </template>

    <template x-if="step === 3">
        <div class="donation-panel__step">
            <h3>{{ load_static_strings('Metodo di pagamento') }}</h3>
            <div class="payment-element-shell">
                <div :id="'google-pay-button-' + progettoId" style="display: none;"></div>
                <form :id="'payment-form-' + progettoId" @submit.prevent="submitForm">
                    <div :id="'payment-element-' + progettoId"></div>
                </form>
            </div>

            <div class="form-actions">
                <button type="button" @click="step = 2; errorMessage = ''" :disabled="loading" class="button button--secondary">{{ load_static_strings('Indietro') }}</button>
                <button type="button" @click="submitForm()" :disabled="loading" class="button">
                    <span x-show="!loading">{{ load_static_strings('Dona ora') }}</span>
                    <span x-show="loading">{{ load_static_strings('Elaborazione…') }}</span>
                </button>
            </div>
        </div>
    </template>
</div>
