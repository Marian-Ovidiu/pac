<?php
namespace Controllers;

use Core\Bases\BaseController;
use Models\AziendeFields;
use Models\GalleriaFields;
use Models\Grazie;

class PageController extends BaseController
{
    public function galleria()
    {
        // wp_localize_script: global `highlights` (allineato a ProgettoController::archive). galleria.blade.php usa ancora json_encode inline per typingEffect().
        $this->addVarJs('main', 'highlights', GalleriaFields::get()->highlights);
        $this->render('galleria', ['galleria' => GalleriaFields::get()]);
    }

    public function aziende()
    {
        $fields = AziendeFields::get();
        $this->render('aziende', ['fields' => $fields]);
    }
    public function grazie()
    {
        $fields = Grazie::get();
        $this->render('grazie', ['fields' => $fields]);
    }
}
