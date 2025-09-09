<?php

namespace Models\Options;

use Core\Bases\BaseGroupAcf;

class OpzioniArchivioProgettoFields extends BaseGroupAcf
{
    protected $groupKey = 'group_672763c42d4a1';

    public $immagine_hero;
    public $titolo_hero;
    public $highlights_frase_1;
    public $highlights_frase_2;
    public $highlights_frase_3;
    public $testo_sotto_hero;
    public function __construct($postId = null) {
        parent::__construct($this->groupKey, $postId);
        $this->defineAttributes();
    }

    public function defineAttributes()
    {
        $this->addField('immagine_hero');
        $this->addField('titolo_hero');
        $this->addField('highlights_frase_1');
        $this->addField('highlights_frase_2');
        $this->addField('highlights_frase_3');
        $this->addField('testo_sotto_hero');
    }
}