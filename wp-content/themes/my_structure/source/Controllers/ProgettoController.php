<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\Options\OpzioniArchivioProgettoFields;
use Models\Progetto;

class ProgettoController extends BaseController
{
    public function archive()
    {
        $progetti = Progetto::prioritizeFlagship(Progetto::all());
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
        $opzioniArchivio = OpzioniArchivioProgettoFields::get('option');
        $this->render('archivio-progetto', [
            'progetti'              => $progetti,
            'opzioniArchivio'       => $opzioniArchivio,
            'latestPosts'            => get_posts([
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 2,
                'orderby' => 'date',
                'order' => 'DESC',
            ]),
        ]);
    }

    public function single()
    {
        $this->addJs('stripe', 'https://js.stripe.com/v3/', [], true);
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
            'progetto'              => $progetto,
            'projectUpdates'         => $progetto->getProjectUpdates(),
            'latestPosts'            => get_posts([
                'post_type' => 'post',
                'post_status' => 'publish',
                'posts_per_page' => 2,
                'orderby' => 'date',
                'order' => 'DESC',
            ]),
        ]);
    }
}
