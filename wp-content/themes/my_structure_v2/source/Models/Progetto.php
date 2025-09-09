<?php
namespace Models;

use Core\Bases\BasePostType;

class Progetto extends BasePostType
{
    public static $postType = 'progetto';
    public $name;
    public $titolo_card;
    public $immagine_hero;
    public $titolo_hero;
    public $testo_hero;
    public $problemi_titolo_1;
    public $problemi_sotto_titolo_1;
    public $problemi_testo_1;
    public $problemi_immagine_1_1;
    public $problemi_immagine_1_2;
    public $problemi_immagine_1_3;
    public $problemi_sotto_titolo_2;
    public $problemi_testo_2;
    public $problemi_immagine_2_1;
    public $problemi_immagine_2_2;
    public $problemi_immagine_2_3;
    public $problemi_sotto_titolo_3;
    public $problemi_testo_3;
    public $problemi_immagine_3_1;
    public $problemi_immagine_3_2;
    public $problemi_immagine_3_3;
    public $soluzioni_titolo_1;
    public $soluzioni_sotto_titolo_1;
    public $soluzioni_testo_1;
    public $soluzioni_immagine_1_1;
    public $soluzioni_immagine_1_2;
    public $soluzioni_immagine_1_3;
    public $soluzioni_sotto_titolo_2;
    public $soluzioni_testo_2;
    public $soluzioni_immagine_2_1;
    public $soluzioni_immagine_2_2;
    public $soluzioni_immagine_2_3;
    public $soluzioni_sotto_titolo_3;
    public $soluzioni_testo_3;
    public $soluzioni_immagine_3_1;
    public $soluzioni_immagine_3_2;
    public $soluzioni_immagine_3_3;

    public function __construct($post = null)
    {
        parent::__construct($post);
    }

    public function defineOtherAttributes($post)
    {
        $this->name          = get_field('name', $this->id);
        $this->titolo_card   = get_field('titolo_card', $this->id);
        $this->immagine_hero = get_field('immagine_hero', $this->id);
        $this->titolo_hero   = get_field('titolo_hero', $this->id);
        $this->testo_hero    = get_field('testo_hero', $this->id);

        $this->problemi_titolo_1       = get_field('problemi_titolo_1', $this->id);
        $this->problemi_sotto_titolo_1 = get_field('problemi_sotto_titolo_1', $this->id);
        $this->problemi_testo_1        = get_field('problemi_testo_1', $this->id);
        $this->problemi_immagine_1_1   = get_field('problemi_immagine_1_1', $this->id);
        $this->problemi_immagine_1_2   = get_field('problemi_immagine_1_2', $this->id);
        $this->problemi_immagine_1_3   = get_field('problemi_immagine_1_3', $this->id);

        $this->problemi_sotto_titolo_2 = get_field('problemi_sotto_titolo_2', $this->id);
        $this->problemi_testo_2        = get_field('problemi_testo_2', $this->id);
        $this->problemi_immagine_2_1   = get_field('problemi_immagine_2_1', $this->id);
        $this->problemi_immagine_2_2   = get_field('problemi_immagine_2_2', $this->id);
        $this->problemi_immagine_2_3   = get_field('problemi_immagine_2_3', $this->id);

        $this->problemi_sotto_titolo_3 = get_field('problemi_sotto_titolo_3', $this->id);
        $this->problemi_testo_3        = get_field('problemi_testo_3', $this->id);
        $this->problemi_immagine_3_1   = get_field('problemi_immagine_3_1', $this->id);
        $this->problemi_immagine_3_2   = get_field('problemi_immagine_3_2', $this->id);
        $this->problemi_immagine_3_3   = get_field('problemi_immagine_3_3', $this->id);

        $this->soluzioni_titolo_1       = get_field('soluzioni_titolo_1', $this->id);
        $this->soluzioni_sotto_titolo_1 = get_field('soluzioni_sottotitolo_1', $this->id);
        $this->soluzioni_testo_1        = get_field('soluzioni_testo_1', $this->id);
        $this->soluzioni_immagine_1_1   = get_field('soluzioni_immagine_1_1', $this->id);
        $this->soluzioni_immagine_1_2   = get_field('soluzioni_immagine_1_2', $this->id);
        $this->soluzioni_immagine_1_3   = get_field('soluzioni_immagine_1_3', $this->id);

        $this->soluzioni_sotto_titolo_2 = get_field('soluzioni_sottotitolo_2', $this->id);
        $this->soluzioni_testo_2        = get_field('soluzioni_testo_2', $this->id);
        $this->soluzioni_immagine_2_1   = get_field('soluzioni_immagine_2_1', $this->id);
        $this->soluzioni_immagine_2_2   = get_field('soluzioni_immagine_2_2', $this->id);
        $this->soluzioni_immagine_2_3   = get_field('soluzioni_immagine_2_3', $this->id);

        $this->soluzioni_sotto_titolo_3 = get_field('soluzioni_sottotitolo_3', $this->id);
        $this->soluzioni_testo_3        = get_field('soluzioni_testo_3', $this->id);
        $this->soluzioni_immagine_3_1   = get_field('soluzioni_immagine_3_1', $this->id);
        $this->soluzioni_immagine_3_2   = get_field('soluzioni_immagine_3_2', $this->id);
        $this->soluzioni_immagine_3_3   = get_field('soluzioni_immagine_3_3', $this->id);
    }

    public function getProblemi()
    {
        return [
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_1,
                'testo'       => $this->problemi_testo_1,
                'immagini'    => array_filter([$this->problemi_immagine_1_1, $this->problemi_immagine_1_2, $this->problemi_immagine_1_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_2,
                'testo'       => $this->problemi_testo_2,
                'immagini'    => array_filter([$this->problemi_immagine_2_1, $this->problemi_immagine_2_2, $this->problemi_immagine_2_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->problemi_sotto_titolo_3,
                'testo'       => $this->problemi_testo_3,
                'immagini'    => array_filter([$this->problemi_immagine_3_1, $this->problemi_immagine_3_2, $this->problemi_immagine_3_3], fn($img) => isset($img['url'])),
            ],
        ];
    }

    public function getSoluzioni()
    {
        return [
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_1,
                'testo'       => $this->soluzioni_testo_1,
                'immagini'    => array_filter([$this->soluzioni_immagine_1_1, $this->soluzioni_immagine_1_2, $this->soluzioni_immagine_1_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_2,
                'testo'       => $this->soluzioni_testo_2,
                'immagini'    => array_filter([$this->soluzioni_immagine_2_1, $this->soluzioni_immagine_2_2, $this->soluzioni_immagine_2_3], fn($img) => isset($img['url'])),
            ],
            [
                'sottoTitolo' => $this->soluzioni_sotto_titolo_3,
                'testo'       => $this->soluzioni_testo_3,
                'immagini'    => array_filter([$this->soluzioni_immagine_3_1, $this->soluzioni_immagine_3_2, $this->soluzioni_immagine_3_3], fn($img) => isset($img['url'])),
            ],
        ];
    }

}
