<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\GalleriaFields;
use Models\AziendeFields;
use Models\Progetti;
use Models\Progetto;
use Models\Grazie;

class PageController extends BaseController {
    public function galleria()
    {
        $this->addJs('highlight', 'highlight.js', [], true);
        $this->addVarJs('highlight', 'highlights', GalleriaFields::get()->highlights);
        $this->render('galleria', ['galleria' => GalleriaFields::get()]);
    }

    public function aziende()
    {
        $fields = AziendeFields::get();
        $this->render('aziende', ['fields' => $fields]);
    }
    public function grazie()
    {
        $fields = Grazie::get();
        $this->render('grazie', ['fields' => $fields]);
    }

    public function progetti()
    {
        $fields = Progetti::get();
        $progetti = [];
        foreach ($fields->progetti as $progetto) {
            $progetti[] = Progetto::find($progetto);
        }
        $fields->progetti = $progetti;
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();

        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $this->addJs('donation', 'donation.js', ['stripe'], true);

        $this->addVarJs('donation', 'highlights', [
            $fields->highlights_frase_1 ?? '',
            $fields->highlights_frase_2 ?? '',
            $fields->highlights_frase_3 ?? '',
        ]);
        $this->render('archivio-progetto', [
            'fields' => $fields,
            'pagamenti_disponibili' => $available_gateways,
        ]);
    }
}
