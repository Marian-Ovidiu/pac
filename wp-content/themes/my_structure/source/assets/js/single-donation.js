function donationFormData(progettoId) {
    const thankYouUrl = document.querySelector('#thank-you-url')?.value || '/grazie';

    if (typeof window.donationFormData !== 'function') {
        throw new Error('donationFormData non disponibile nel bundle principale.');
    }

    return window.donationFormData(progettoId, thankYouUrl);
}
