<?php

namespace Models;

use Core\Bases\BaseGroupAcf;

class HomeFields extends BaseGroupAcf
{

    public $immagine_1;
    public $immagine_2;
    public $immagine_3;
    public $titolo_1;
    public $titolo_2;
    public $titolo_3;
    public $testo_1;
    public $testo_2;
    public $testo_3;
    public $cta_1;
    public $cta_2;
    public $cta_3;
    public $titolo_missione;
    public $testo_missione;
    public $cta_missione_dona_ora;
    public $cta_missione_galleria;
    public function __construct($postId = null) {
        parent::__construct('group_6712db9b59faa', $postId ?: get_the_ID());
        $this->defineAttributes();
    }

    public function defineAttributes()
    {
        //Slider
        $this->addField('immagine_1');
        $this->addField('immagine_2');
        $this->addField('immagine_3');
        $this->addField('titolo_1');
        $this->addField('titolo_2');
        $this->addField('titolo_3');
        $this->addField('testo_1');
        $this->addField('testo_2');
        $this->addField('testo_3');
        $this->addField('cta_1');
        $this->addField('cta_2');
        $this->addField('cta_3');

        //La nostra missione
        $this->addField('titolo_missione');
        $this->addField('testo_missione');
        $this->addField('cta_missione_dona_ora');
        $this->addField('cta_missione_galleria');
    }
}