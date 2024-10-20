<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;

class HomeController extends BaseController {
    public function index() {

        $data = HomeFields::get(get_the_ID());

        $this->addJs('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', [], true);
        $this->addJs('homeSlider', 'homeSlider.js', ['swiper-js'], true);
        $this->addCss('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
        $this->addCss('homeSlider', 'homeSlider.css');
        /*$this->addVarJs('testAjax', 'var_test', ['foo' => 'bar'], true);*/
        $this->render('home', ['data'=> $data]);
    }
}
