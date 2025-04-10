function donationFormData(progettoId) {
    return {
        step: 1,
        selectedAmount: null,
        customAmount: '',
        loading: false,
        stripe: null,
        formData: {
            name: '',
            surname: '',
            phone: '',
            email: '',
            codiceFiscale: '',
        },
        createIntent() {
            this.loading = true;
            let selectedDonationAmount = this.customAmount || this.selectedAmount;
            selectedDonationAmount = selectedDonationAmount * 100;

            let call = new window.ApiService();
            call.post('/create-payment-intent', {
                'amount': selectedDonationAmount,
                'progetto_id': progettoId
            }).then(response => {
                this.clientSecret = response.clientSecret;
                this.stripe = Stripe('pk_live_xxxxx'); // Usa la tua chiave pubblica Stripe

                this.elements = this.stripe.elements({
                    clientSecret: this.clientSecret,
                    paymentMethodCreation: 'manual'
                });

                const paymentElement = this.elements.create('payment');
                paymentElement.mount('#payment-element-' + progettoId);

                // ✅ Configura il pulsante Google Pay
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
                    const elements = this.stripe.elements();
                    const prButton = elements.create("paymentRequestButton", {
                        paymentRequest: paymentRequest,
                    });
                    prButton.mount("#google-pay-button");
                }
            });
        },
        async submitForm() {
            this.loading = true;
            const thankYouUrl = document.querySelector(`#thank-you-url`).value;
            const { error } = await this.stripe.confirmPayment({
                elements: this.elements,
                confirmParams: {
                  return_url: thankYouUrl,
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
            window.location.href = thankYouUrl;
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


