<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;
use Models\DuoFields;

class HomeController extends BaseController {
    public function index() {

        $data = HomeFields::get(get_the_ID());

        $this->addJs('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], true);
        $this->addJs('homeSlider', 'homeSlider.js', ['swiper-js'], true, '6.8');
        $this->addCss('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        $this->render('home', ['data'=> $data, 'duo_fields' => DuoFields::get(get_the_ID()) ?? null]);
    }
}
