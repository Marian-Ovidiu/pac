<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;
use Models\MonoFields;

class HomeController extends BaseController
{
    public function index()
    {
        $data = HomeFields::get(get_the_ID());
        $this->addJs('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], true);
        $this->addJs('homeSlider', 'homeSlider.js', ['swiper-js'], true, '7.0');
        $this->addCss('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        $mono = MonoFields::get(get_the_ID()) ?? null;
        $canonical = get_permalink(get_queried_object_id());
        $this->render('home', [
            'data' => $data,
            'titolo_monologo' => $mono->titolo_monologo,
            'sottotitolo_monologo' => $mono->sottotitolo_monologo,
            'immagine_monologo' => $mono->immagine_monologo,
            'canonical' => $canonical
        ]);
    }
}
