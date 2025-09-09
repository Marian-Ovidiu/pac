<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;
use Models\DuoFields;
use Models\LinearSlider;
use Models\MonoFields;

class HomeController extends BaseController {
    public function index() {
        $data = HomeFields::get(get_the_ID());
        $this->addJs('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], true);
        $this->addJs('homeSlider', 'homeSlider.js', ['swiper-js'], true, '7.0');
        $this->addCss('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        $mono = MonoFields::get(get_the_ID()) ?? null;
        $this->render('home', [
            'data'=> $data,
            'titolo_monologo' => $mono->titolo_monologo,
            'sottotitolo_monologo' => $mono->sottotitolo_monologo,
            'immagine_monologo' => $mono->immagine_monologo,
        ]);
    }
}
