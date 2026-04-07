<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\Options\OpzioniArchivioProgettoFields;
use Models\Progetto;

class ProgettoController extends BaseController
{
    public function archive()
    {
        $progetti = Progetto::all();
        $available_gateways = [];
        if (function_exists('WC') && class_exists('\WC_Payment_Gateways')) {
            $wc = \WC();
            if ($wc && $wc->payment_gateways) {
                $available_gateways = $wc->payment_gateways->get_available_payment_gateways();
            }
        }
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $opzioniArchivio = OpzioniArchivioProgettoFields::get('option');
        $this->addVarJs('main', 'texts', [
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
        $available_gateways = [];
        if (function_exists('WC') && class_exists('\WC_Payment_Gateways')) {
            $wc = \WC();
            if ($wc && $wc->payment_gateways) {
                $available_gateways = $wc->payment_gateways->get_available_payment_gateways();
            }
        }
        $this->addJs('progetto-slider', 'progettoSlider.js', ['main'], true);
        $progetto = Progetto::find(get_the_ID());

        if (! $progetto) {
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
