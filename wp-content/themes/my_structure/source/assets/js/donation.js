const PENDING_DONATION_KEY = 'pac_pending_donation';

function getPaymentsConfig() {
    return window.pacPayments || {};
}

function getAjaxUrl() {
    return getPaymentsConfig().ajaxUrl || `${window.location.origin}/wp-admin/admin-ajax.php`;
}

function getNonce() {
    return getPaymentsConfig().nonce || '';
}

function getPublishableKey() {
    return getPaymentsConfig().publishableKey || '';
}

function getActions() {
    const actions = getPaymentsConfig().actions || {};

    return {
        createIntent: actions.createIntent || 'pac_create_payment_intent',
        complete: actions.complete || 'pac_complete_donation',
    };
}

function savePendingDonation(payload) {
    window.sessionStorage.setItem(PENDING_DONATION_KEY, JSON.stringify(payload));
}

function getPendingDonation() {
    const payload = window.sessionStorage.getItem(PENDING_DONATION_KEY);

    if (!payload) {
        return null;
    }

    try {
        return JSON.parse(payload);
    } catch (error) {
        console.error('Pending donation payload non valido:', error);
        return null;
    }
}

function clearPendingDonation() {
    window.sessionStorage.removeItem(PENDING_DONATION_KEY);
}

async function callPaymentAction(action, payload) {
    const api = new window.ApiService();

    return api.postForm(getAjaxUrl(), {
        action,
        nonce: getNonce(),
        ...payload,
    });
}

async function finalizeDonation(payload) {
    const response = await callPaymentAction(getActions().complete, payload);

    if (!response?.success) {
        throw new Error(response?.data?.message || 'Errore nella finalizzazione della donazione.');
    }

    clearPendingDonation();
    return response.data;
}

function cleanPaymentQueryString() {
    const url = new URL(window.location.href);
    url.searchParams.delete('payment_intent');
    url.searchParams.delete('payment_intent_client_secret');
    url.searchParams.delete('redirect_status');
    window.history.replaceState({}, document.title, url.toString());
}

export async function resumePendingDonation() {
    const params = new URLSearchParams(window.location.search);
    const paymentIntentId = params.get('payment_intent');
    const redirectStatus = params.get('redirect_status');
    const pendingDonation = getPendingDonation();

    if (!paymentIntentId || !pendingDonation || (redirectStatus && redirectStatus !== 'succeeded')) {
        return;
    }

    try {
        await finalizeDonation({
            ...pendingDonation,
            payment_intent_id: paymentIntentId,
        });
        cleanPaymentQueryString();
    } catch (error) {
        console.error('Errore nel resume della donazione:', error);
    }
}

export default function donationFormData(progettoId, thankYouUrl) {
    return {
        progettoId,
        thankYouUrl,
        step: 1,
        selectedAmount: null,
        customAmount: '',
        showAmountError: false,
        loading: false,
        stripe: null,
        clientSecret: null,
        paymentIntentId: null,
        elements: null,
        touched: {
            name: false,
            surname: false,
            email: false,
            phone: false,
        },
        formData: {
            name: '',
            surname: '',
            phone: '',
            email: '',
            codiceFiscale: '',
        },
        init(id, thankYouUrlValue) {
            this.progettoId = id;
            this.thankYouUrl = thankYouUrlValue;
        },
        getAmountInCents() {
            const amount = Number(this.customAmount || this.selectedAmount);

            if (!Number.isFinite(amount) || amount <= 0) {
                return 0;
            }

            return Math.round(amount * 100);
        },
        buildDonationPayload() {
            return {
                progetto_id: this.progettoId,
                expected_amount_cents: this.getAmountInCents(),
                name: this.formData.name.trim(),
                surname: this.formData.surname.trim(),
                phone: this.formData.phone.trim(),
                email: this.formData.email.trim(),
                codice_fiscale: this.formData.codiceFiscale.trim(),
            };
        },
        async createIntent() {
            this.loading = true;
            const amountCents = this.getAmountInCents();
            const publishableKey = getPublishableKey();

            try {
                if (amountCents < 100) {
                    throw new Error('Inserisci un importo valido (minimo 1 euro).');
                }

                if (!publishableKey) {
                    throw new Error('Configurazione Stripe non disponibile.');
                }

                const res = await callPaymentAction(getActions().createIntent, {
                    amount_cents: amountCents,
                    progetto_id: this.progettoId,
                });

                if (!res?.success || !res?.data?.clientSecret) {
                    throw new Error(res?.data?.message || 'clientSecret mancante o risposta non valida.');
                }

                this.clientSecret = res.data.clientSecret;
                this.paymentIntentId = res.data.paymentIntentId || null;
                this.stripe = Stripe(publishableKey);
                this.elements = this.stripe.elements({ clientSecret: this.clientSecret });
                this.step = 3;

                await this.$nextTick(() => {
                    const paymentElement = this.elements.create('payment');
                    paymentElement.mount(`#payment-element-${this.progettoId}`);
                    this.setupGooglePay(amountCents);
                });
            } catch (error) {
                console.error("Errore nella creazione dell'intent:", error);
                alert(error.message || 'Errore nella creazione del pagamento.');
            } finally {
                this.loading = false;
            }
        },
        async setupGooglePay(amountCents) {
            const paymentRequest = this.stripe.paymentRequest({
                country: 'IT',
                currency: 'eur',
                total: { label: 'Donazione', amount: amountCents },
                requestPayerName: true,
                requestPayerEmail: true
            });

            try {
                const result = await paymentRequest.canMakePayment();
                const googlePayButton = document.getElementById(`google-pay-button-${this.progettoId}`);

                if (result && googlePayButton) {
                    googlePayButton.style.display = 'block';

                    const prButton = this.elements.create('paymentRequestButton', {
                        paymentRequest
                    });

                    prButton.mount(`#google-pay-button-${this.progettoId}`);
                }
            } catch (error) {
                console.error('Errore nel controllo di Google Pay:', error);
            }
        },
        async finalizeInlinePayment(paymentIntentId) {
            const payload = {
                ...this.buildDonationPayload(),
                payment_intent_id: paymentIntentId,
            };

            savePendingDonation(payload);
            await finalizeDonation(payload);
        },
        async submitForm() {
            if (this.loading) {
                return;
            }

            this.loading = true;

            try {
                savePendingDonation(this.buildDonationPayload());

                const { error, paymentIntent } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        return_url: this.thankYouUrl,
                        payment_method_data: {
                            billing_details: {
                                name: `${this.formData.name} ${this.formData.surname}`.trim(),
                                email: this.formData.email,
                            }
                        }
                    },
                    redirect: 'if_required'
                });

                if (error) {
                    clearPendingDonation();
                    throw new Error(error.message || 'Errore durante il pagamento.');
                }

                if (paymentIntent?.status === 'succeeded') {
                    await this.finalizeInlinePayment(paymentIntent.id);
                    window.location.href = this.thankYouUrl;
                }
            } catch (error) {
                console.error('Errore Stripe:', error);
                alert(error.message || 'Errore durante il pagamento.');
            } finally {
                this.loading = false;
            }
        },
        isAmountValid() {
            return this.getAmountInCents() >= 100;
        },
        isUserDataValid() {
            return this.formData.name && this.formData.surname && this.formData.email && this.formData.phone;
        },
        goToStep(n) {
            if (n === 1) {
                this.step = 1;
            } else if (n === 2 && this.isAmountValid()) {
                this.step = 2;
                this.showAmountError = false;
            } else if (n === 3 && this.isAmountValid() && this.isUserDataValid()) {
                this.createIntent();
            } else if (n === 2) {
                this.showAmountError = true;
            }
        }
    };
}
