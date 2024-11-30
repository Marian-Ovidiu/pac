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
                call.post('/create-payment-intent', {'amount' : selectedDonationAmount, 'progetto_id': progettoId}).then(response => {

                    this.clientSecret = response.clientSecret;

                    this.stripe = Stripe('pk_live_51QQqzmP9ji9EUZt5LkB8kShCP2rhsd195h5SlYAzUb3gGabZ8R8Uinp0TiDGKXqFsBu7oCPVL7of79NbNSGrAr3u00xFyOm6u8');
                    this.elements = this.stripe.elements({
                        clientSecret: this.clientSecret,
                        paymentMethodCreation: 'manual'
                    });

                    const paymentElement = this.elements.create('payment');
                    paymentElement.mount('#payment-element-' + progettoId);

                    this.loading = false;
                    this.step = 3;
                })
                .catch(error => {
                    console.error('Errore nella richiesta:', error);
                });
            },
            async submitForm(){
                this.loading = true;
                const { error, paymentIntent } = await this.stripe.confirmPayment({
                    elements: this.elements,
                    confirmParams: {
                        return_url: 'https://project-africa-conservation.org',
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
                const paymentMethodType = paymentIntent.payment_method_types[0];
                console.log(paymentMethodType);
                if (paymentMethodType === 'card'){

                    let elements = this.elements;
                    elements.submit();
                    const { error, paymentMethod } = await this.stripe.createPaymentMethod({
                        elements,
                        params: {
                            billing_details: {
                                name: this.formData.name + ' ' +this.formData.surname,
                                email: this.formData.email,
                            }
                        }
                    });

                   if (error) {
                      console.error(error.message);
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
                        paymentMethodId: paymentMethod.id,
                        progettoId: progettoId,
                        amount: selectedDonationAmount
                    })
                    .then(response => {
                        if (response.success) {
                            console.log(response);
                            window.location.href = response.redirect;
                        } else {
                            console.log(response);
                            alert("Errore nella creazione dell'ordine");
                        }
                        this.loading = false;
                    })
                    .catch(error => {
                        console.error('Errore durante la creazione dell’ordine:', error);
                        this.loading = false;
                    });
                } else {
                    console.log(paymentIntent.next_action.redirect_to_url.url);
                  /*  this.stripe.confirmPayment({
                        elements: this.elements,
                        confirmParams: {
                            return_url: 'http://pac.localhost',
                        },
                    }).then(function(result) {
                        if (result.error) {
                            console.error(result.error.message);
                        }
                    });*/

                }
            }
        };
    }

    function typingEffect() {
        if(typeof highlights !== 'undefined') {
            return {
                texts: highlights,
                currentText: 0,
                displayText: "",
                speed: 100, // Velocità di digitazione (in ms)
                pauseBetweenTexts: 1000,
                startTyping() {
                    this.displayText = ""; // Resetta il testo visualizzato
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
                                    this.currentText = 0; // Ritorna al primo testo
                                }
                                this.startTyping();
                            }, this.pauseBetweenTexts); // Ritardo prima del prossimo testo
                        }
                    }, this.speed);
                },
                init() {
                    this.startTyping();
                }
            };
        }
    }
