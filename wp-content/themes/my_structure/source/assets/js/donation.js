document.addEventListener('DOMContentLoaded', function () {
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
        setupGooglePay(amount, progettoId) {
            const paymentRequest = stripe.paymentRequest({
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
                let googlePayButton = document.getElementById("google-pay-button-" + progettoId);
        
                if (result && googlePayButton) {
                    googlePayButton.style.display = "block";
                    const elements = stripe.elements();
                    const prButton = elements.create("paymentRequestButton", {
                        paymentRequest: paymentRequest,
                    });
        
                    prButton.mount("#google-pay-button-" + progettoId);
                } else {
                    console.error(`Google Pay non disponibile o elemento non trovato per il progetto ${progettoId}.`);
                }
            }).catch(error => {
                console.error("Errore nel controllo di Google Pay:", error);
            });
        },
        async submitForm() {
            this.loading = true;
            const thankYouUrl = document.querySelector(`#thank-you-url`).value;
            const { error, paymentIntent } = await this.stripe.confirmPayment({
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
                console.error('Errore durante la conferma del pagamento:', error.message);
                alert("Errore durante il pagamento: " + error.message);
                this.loading = false;
                return;
            }

            let selectedDonationAmount = this.customAmount || this.selectedAmount;

            let call = new window.ApiService();
            call.post('/complete-donation', {
                name: this.formData.name,
                surname: this.formData.surname,
                phone: this.formData.phone,
                email: this.formData.email,
                codiceFiscale: this.formData.codiceFiscale,
                progettoId: progettoId,
                amount: selectedDonationAmount
            }).then(response => {
                if (response.success) {
                    window.location.href = thankYouUrl;
                } else {
                    alert("Errore nella creazione dell'ordine");
                }
                this.loading = false;
            }).catch(error => {
                console.error('Errore durante la creazione dell’ordine:', error);
                this.loading = false;
            });
        }
    };
}
});