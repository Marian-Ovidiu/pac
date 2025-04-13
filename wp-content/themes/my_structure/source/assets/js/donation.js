export default function donationFormData(progettoId, thankYouUrl) {
    return {
        progettoId,
        thankYouUrl: null,
        step: 1,
        selectedAmount: null,
        customAmount: '',
        showAmountError: false,
        loading: false,
        stripe: null,
        clientSecret: null,
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
        init(progettoId, thankYouUrl) {
            this.progettoId = progettoId;
            this.thankYouUrl = thankYouUrl;
        },
        async createIntent() {
            this.loading = true;
            const amount = (this.customAmount || this.selectedAmount) * 100;
            const call = new window.ApiService();

            try {
                const res = await call.post('/create-payment-intent', {
                    amount,
                    progetto_id: this.progettoId,
                });

                this.clientSecret = res.data.clientSecret;
                this.stripe = Stripe('pk_live_51QQqzmP9ji9EUZt5LkB8kShCP2rhsd195h5SlYAzUb3gGabZ8R8Uinp0TiDGKXqFsBu7oCPVL7of79NbNSGrAr3u00xFyOm6u8');
                this.elements = this.stripe.elements({ clientSecret: this.clientSecret });

                // STEP 3 → Alpine render → mount elementi
                this.step = 3;

                await this.$nextTick(() => {
                    // Stripe
                    const paymentElement = this.elements.create('payment');
                    paymentElement.mount(`#payment-element-${this.progettoId}`);

                    // Google Pay
                    const gpayEl = document.getElementById(`google-pay-button-${this.progettoId}`);
                    if (gpayEl) gpayEl.innerHTML = '';
                    this.setupGooglePay(amount);

                    // PayPal
                    const paypalEl = document.getElementById(`paypal-button-container-${this.progettoId}`);
                    if (paypalEl && window.paypal) {
                        paypal.Buttons({
                            createOrder: (data, actions) => {
                                return actions.order.create({
                                    purchase_units: [{
                                        amount: {
                                            value: ((this.customAmount || this.selectedAmount)).toString()
                                        }
                                    }]
                                });
                            },
                            onApprove: (data, actions) => {
                                return actions.order.capture().then(details => {
                                    alert(`Pagamento completato da ${details.payer.name.given_name}`);
                                    window.location.href = this.thankYouUrl;
                                });
                            },
                            onError: (err) => {
                                console.error('Errore PayPal:', err);
                                alert("Errore PayPal: " + err.message);
                            }
                        }).render(`#paypal-button-container-${this.progettoId}`);
                    }
                });

            } catch (err) {
                console.error("Errore nella creazione dell'intent:", err);
                alert("Errore: " + err.message);
            } finally {
                this.loading = false;
            }
        },
        async setupGooglePay(amount) {
            const paymentRequest = this.stripe.paymentRequest({
                country: 'IT',
                currency: 'eur',
                total: { label: 'Donazione', amount },
                requestPayerName: true,
                requestPayerEmail: true
            });

            try {
                const result = await paymentRequest.canMakePayment();

                const googlePayButton = document.getElementById(`google-pay-button-${this.progettoId}`);
                if (result && googlePayButton) {
                    googlePayButton.style.display = "block";

                    const prButton = this.elements.create("paymentRequestButton", {
                        paymentRequest
                    });

                    prButton.mount(`#google-pay-button-${this.progettoId}`);
                } else {
                    console.warn("Google Pay non disponibile o elemento non trovato.");
                }
            } catch (error) {
                console.error("Errore nel controllo di Google Pay:", error);
            }
        },
        async submitForm() {
            this.loading = true;
        
            try {
                const token = await grecaptcha.execute('6LeD2BIrAAAAAMW5YdtryPjvrOPPA_ScO2ZWYUUV', { action: 'donazione' });
                this.recaptchaToken = token;
        
                const { error } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        return_url: this.thankYouUrl,
                        payment_method_data: {
                            billing_details: {
                                name: `${this.formData.name} ${this.formData.surname}`,
                                email: this.formData.email,
                            }
                        }
                    }
                });
        
                if (error) {
                    console.error('Errore durante il pagamento:', error.message);
                    alert("Errore durante il pagamento: " + error.message);
                    this.loading = false;
                    return;
                }
            } catch (err) {
                console.error('Errore Stripe o ReCaptcha:', err.message);
                this.loading = false;
            }
        },        
        isAmountValid() {
            return this.selectedAmount || (this.customAmount && this.customAmount > 0);
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
            } else {
                if (n === 2) this.showAmountError = true;
            }
        }
    };
}
