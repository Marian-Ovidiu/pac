<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\Options\OpzioniArchivioProgettoFields;
use Models\Progetto;

class ProgettoController extends BaseController
{
    public function archive()
    {
        $progetti           = Progetto::all();
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $this->addJs('stripe', 'donation.js', ['stripe'], true, '1.1');
        $opzioniArchivio = OpzioniArchivioProgettoFields::get('option');
        $this->addVarJs('texts', 'texts', [
            $opzioniArchivio->highlights_frase_1 ?? '',
            $opzioniArchivio->highlights_frase_2 ?? '',
            $opzioniArchivio->highlights_frase_3 ?? '',
        ], true, 1.0);

        $this->render('archivio-progetto', [
            'progetti'              => $progetti,
            'pagamenti_disponibili' => $available_gateways,
            'opzioniArchivio'       => $opzioniArchivio,
        ]);
    }

    public function single()
    {
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $this->addJs('single-donation', 'single-donation.js', ['stripe'], true, '2.1');
        $available_gateways = WC()->payment_gateways->get_available_payment_gateways();
        $this->addJs('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], true);
        $this->addCss('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        $this->addJs('progetto', 'progettoSlider.js', ['swiper-js'], true, '6.8.1');
        $progetto = Progetto::find(get_the_ID());

        if (!$progetto) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
            include get_404_template();
            exit;
        }
        $this->render('single-progetto', [
            'progetto'              => Progetto::find(get_the_ID()),
            'pagamenti_disponibili' => $available_gateways,
        ]);
    }
}
