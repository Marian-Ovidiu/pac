function donationFormData(progettoId, thankYouUrl = false) {
    return {
        step: 1,
        thankYouUrl: thankYouUrl || window.location.origin + '/grazie',
        selectedAmount: null,
        customAmount: '',
        recaptchaToken: '',
        loading: false,
        stripe: null,
        elements: null,
        clientSecret: null,
        formData: {
            name: '',
            surname: '',
            phone: '',
            email: '',
            codiceFiscale: '',
        },
        createIntent() {
            console.log('ciao0');
            this.loading = true;
            const selectedDonationAmount = (this.customAmount || this.selectedAmount) * 100;

            const call = new window.ApiService();
            console.log('ciao1');
            call.post('/create-payment-intent', {
                amount: selectedDonationAmount,
                progetto_id: this.progettoId
            }).then(response => {
                console.log("🎯 Risposta completa:", response);

                const clientSecret = response.clientSecret || response?.data?.clientSecret;

                if (!clientSecret) {
                    console.error("💥 clientSecret è undefined!");
                    this.loading = false;
                    alert('Errore nel pagamento. Riprova più tardi.');
                    return;
                }

                this.clientSecret = clientSecret;
                this.stripe = Stripe('pk_live_51QQqzmP9ji9EUZt5LkB8kShCP2rhsd195h5SlYAzUb3gGabZ8R8Uinp0TiDGKXqFsBu7oCPVL7of79NbNSGrAr3u00xFyOm6u8'); // <-- metti la tua chiave pubblica vera

                this.elements = this.stripe.elements({
                    clientSecret: this.clientSecret
                });

                const paymentElement = this.elements.create('payment');
                paymentElement.mount(`#payment-element-${progettoId}`);

                this.setupGooglePay(selectedDonationAmount, progettoId);
                console.log('ciao2');
                this.loading = false;
                this.step = 3;
            }).catch(err => {
                console.log('ciao4');
                console.error("❌ Errore chiamata API Stripe:", err);
                this.loading = false;
                alert('Errore durante la connessione. Riprova più tardi.');
            });
        },

        setupGooglePay(amount, progettoId) {
            console.log('ciao5');

            const paymentRequest = this.stripe.paymentRequest({
                country: 'IT',
                currency: 'eur',
                total: {
                    label: 'Donazione',
                    amount: amount
                },
                requestPayerName: true,
                requestPayerEmail: true
            });
            console.log('ciao6');
            paymentRequest.canMakePayment().then(result => {
                const id = `google-pay-button-${progettoId}`;
                const button = document.getElementById(id);
                if (result && button) {
                    button.style.display = "block";
                    const prButton = this.elements.create("paymentRequestButton", { paymentRequest });
                    prButton.mount(`#${id}`);
                }
            });
        },

        async submitForm() {
            this.loading = true;

            try {
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
                    console.error('❌ Errore durante il pagamento:', error.message);
                    alert("Errore durante il pagamento: " + error.message);
                    this.loading = false;
                    return;
                }
            } catch (err) {
                console.error('❌ Errore Stripe:', err.message);
                this.loading = false;
                alert('Errore interno. Riprova.');
            }
        }
    };
}

function typingEffect() {
    if (typeof highlights !== 'undefined') {
        return {
            texts: highlights,
            currentText: 0,
            displayText: "",
            speed: 100,
            pauseBetweenTexts: 1000,
            startTyping() {
                this.displayText = "";
                let fullText = this.texts[this.currentText];
                let i = 0;

                let typingInterval = setInterval(() => {
                    if (i < fullText.length) {
                        this.displayText += fullText[i];
                        i++;
                    } else {
                        clearInterval(typingInterval);
                        setTimeout(() => {
                            this.currentText++;
                            if (this.currentText >= this.texts.length) {
                                this.currentText = 0;
                            }
                            this.startTyping();
                        }, this.pauseBetweenTexts);
                    }
                }, this.speed);
            },
            init() {
                this.startTyping();
            }
        };
    }
}