function donationFormData(progettoId, thankYouUrl) {
    return {
        step: 1,
        thankYouUrl: thankYouUrl || window.location.origin + '/grazie', // fallback nel caso
        selectedAmount: null,
        customAmount: '',
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
            this.loading = true;
            const selectedDonationAmount = (this.customAmount || this.selectedAmount) * 100;

            const call = new window.ApiService();
            call.post('/create-payment-intent', {
                amount: selectedDonationAmount,
                progetto_id: progettoId
            }).then(response => {
                this.clientSecret = response.clientSecret;
                this.stripe = Stripe('pk_live_51QQqzmP9ji9EUZt5LkB8kShCP2rhsd195h5SlYAzUb3gGabZ8R8Uinp0TiDGKXqFsBu7oCPVL7of79NbNSGrAr3u00xFyOm6u8'); // 🔐 la tua public key
                this.elements = this.stripe.elements({ clientSecret: this.clientSecret });

                const paymentElement = this.elements.create('payment');
                paymentElement.mount(`#payment-element-${progettoId}`);

                this.setupGooglePay(selectedDonationAmount);

                this.loading = false;
                this.step = 3;
            }).catch(error => {
                console.error('Errore nella richiesta:', error);
            });
        },
        setupGooglePay(amount) {
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

            paymentRequest.canMakePayment().then(result => {
                if (result) {
                    document.getElementById("google-pay-button").style.display = "block";
                    const prButton = this.elements.create("paymentRequestButton", { paymentRequest });
                    prButton.mount("#google-pay-button");
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
                    console.error('Errore durante il pagamento:', error.message);
                    alert("Errore durante il pagamento: " + error.message);
                    this.loading = false;
                    return;
                }
            } catch (err) {
                console.error('Errore Stripe:', err.message);
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


