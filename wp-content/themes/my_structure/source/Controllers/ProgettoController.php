<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\Progetto;
use Models\Options\OpzioniArchivioProgettoFields;

class ProgettoController extends BaseController
{
    public function archive()
    {
        $progetti = Progetto::all();
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $this->addJs('donation', 'donation.js', ['stripe'], true);
        $opzioniArchivio = OpzioniArchivioProgettoFields::get('progetto');
        $this->addVarJs('donation', 'highlights', [
            $opzioniArchivio->highlights_frase_1 ?? '',
            $opzioniArchivio->highlights_frase_2 ?? '',
            $opzioniArchivio->highlights_frase_3 ?? '',
        ]);

        $this->render('archivio-progetto', [
            'progetti' => $progetti,
            'pagamenti_disponibili' => $available_gateways,
            'opzioniArchivio' => $opzioniArchivio
        ]);
    }

    public function single()
    {
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $this->addJs('single-donation', 'single-donation-test.js', ['stripe'], true);
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

        $this->render('single-progetto', [
            'progetto' => Progetto::find(get_the_ID()),
            'pagamenti_disponibili' => $available_gateways
        ]);
    }
}