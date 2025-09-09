<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\Options\OpzioniGlobaliFields;
use WP_Query;

class PostController extends BaseController
{
    public function archive()
    {
        $query = new WP_Query([
            'post_type'      => 'post',
            'post_status'    => 'publish',
            'posts_per_page' => -1, // tutti senza limiti
        ]);
        $canonical = get_permalink(get_queried_object_id());

        $this->render(
            'archivio-post',
            [
                'fields' => OpzioniGlobaliFields::get(),
                'posts' => $query->get_posts(),
                'canonical' => $canonical
            ]

        );
    }

    public function single()
    {
        global $post;
        $canonical = get_permalink(get_queried_object_id());
        $this->render('singolo-post', [
            'post'    => $post,
            'title'   => get_the_title(),
            'content' => apply_filters('the_content', get_the_content()),
            'author'  => get_the_author(),
            'date'    => get_the_date(),
            'canonical' => $canonical
        ]);
    }
}
