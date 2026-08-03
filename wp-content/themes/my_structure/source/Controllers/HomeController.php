<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;
use Models\MonoFields;
use Models\Progetto;

class HomeController extends BaseController {
    public function index() {
        $data = HomeFields::get(get_the_ID());
        $mono = MonoFields::get(get_the_ID()) ?? null;
        $latestPosts = get_posts([
            'post_type' => 'post',
            'post_status' => 'publish',
            'posts_per_page' => 1,
            'orderby' => 'date',
            'order' => 'DESC',
        ]);
        $this->render('home', [
            'data'=> $data,
            'missions' => Progetto::all(),
            'latestPost' => $latestPosts[0] ?? null,
            'publishedPostCount' => (int) wp_count_posts('post')->publish,
            'titolo_monologo' => $mono->titolo_monologo,
            'sottotitolo_monologo' => $mono->sottotitolo_monologo,
            'immagine_monologo' => $mono->immagine_monologo,
        ]);
    }
}
