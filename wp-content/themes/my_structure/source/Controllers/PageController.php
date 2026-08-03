<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\AziendeFields;
use Models\GalleriaFields;
use Models\Grazie;
use WP_Query;

class PageController extends BaseController
{
    public function galleria()
    {
        $this->render('galleria', ['galleria' => GalleriaFields::get()]);
    }

    public function aziende()
    {
        $fields = AziendeFields::get();
        if (empty($fields->shortcode_form)) {
            $pageTitle = strtolower((string) get_the_title());
            $formLanguage = str_contains($pageTitle, 'english') ? 'ENG'
                : (str_contains($pageTitle, 'fran') ? 'FR'
                : (str_contains($pageTitle, 'deutsch') ? 'DE' : 'ITA'));
            $forms = get_posts([
                'post_type' => 'wpcf7_contact_form',
                'post_status' => 'publish',
                'posts_per_page' => 1,
                's' => 'Modulo aziende ' . $formLanguage,
            ]);
            if (!empty($forms[0])) {
                $fields->shortcode_form = sprintf(
                    '[contact-form-7 id="%d" title="%s"]',
                    (int) $forms[0]->ID,
                    esc_attr($forms[0]->post_title)
                );
            }
        }
        $this->render('aziende', ['fields' => $fields]);
    }
    public function grazie()
    {
        $fields = Grazie::get();
        $this->render('grazie', ['fields' => $fields]);
    }

    public function notFound()
    {
        status_header(404);
        nocache_headers();
        $this->render('404');
    }

    public function search()
    {
        global $wp_query;

        $this->render('search', [
            'query' => get_search_query(),
            'posts' => $wp_query instanceof WP_Query ? $wp_query->posts : [],
        ]);
    }
}
