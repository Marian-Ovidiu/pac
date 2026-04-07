<?php

namespace Controllers;

use Core\Bases\BaseController;
use Models\HomeFields;
use Models\MonoFields;

class HomeController extends BaseController {
    public function index() {
        $data = HomeFields::get(get_the_ID());
        $this->addJs('home-slider', 'homeSlider.js', ['main'], true);
        $mono = MonoFields::get(get_the_ID()) ?? null;
        $this->render('home', [
            'data'=> $data,
            'titolo_monologo' => $mono->titolo_monologo,
            'sottotitolo_monologo' => $mono->sottotitolo_monologo,
            'immagine_monologo' => $mono->immagine_monologo,
        ]);
    }
}
