<?php

namespace Controllers;

use Core\Bases\BaseController;

class HomeController extends BaseController {
    public function index() {
        $this->addJs('homeSlider', 'homeSlider.js');
        $this->addCss('homeSlider', 'homeSlider.css');
        /*$this->addVarJs('testAjax', 'var_test', ['foo' => 'bar'], true);*/
        $this->render('home');
    }
}
