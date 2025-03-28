<?php
/**
 * @var \Models\HomeFields $data
 */
?>

<?php $__env->startSection('content'); ?>
    <header class="sr-only">
        <h1>Project Africa Conservation - Un futuro per la fauna e le comunità</h1>
    </header>
        <?php echo $__env->make('components.slider', ["slides" => [
            [
                'url' => $data->immagine_1['url'] ?? null,
                'alt' => $data->immagine_1['alt'] ?? null,
                'title' => $data->immagine_1['title'] ?? null,
                'caption' => $data->immagine_1['caption'] ?? null,
                'description' => $data->immagine_1['description'] ?? null,
                'titolo' => $data->titolo_1 ?? null,
                'testo' => $data->testo_1 ?? null,
                'cta_url' => $data->cta_1['url'] ?? null,
                'cta_title' => $data->cta_1['title'] ?? null,
            ],
            [
                'url' => $data->immagine_2['url'] ?? null,
                'alt' => $data->immagine_2['alt'] ?? null,
                'title' => $data->immagine_2['title'] ?? null,
                'caption' => $data->immagine_2['caption'] ?? null,
                'description' => $data->immagine_2['description'] ?? null,
                'titolo' => $data->titolo_2 ?? null,
                'testo' => $data->testo_2 ?? null,
                'cta_url' => $data->cta_2['url'] ?? null,
                'cta_title' => $data->cta_2['title'] ?? null,
            ],
            [
                'url' => $data->immagine_3['url'] ?? null,
                'alt' => $data->immagine_3['alt'] ?? null,
                'title' => $data->immagine_3['title'] ?? null,
                'caption' => $data->immagine_3['caption'] ?? null,
                'description' => $data->immagine_3['description'] ?? null,
                'titolo' => $data->titolo_3 ?? null,
                'testo' => $data->testo_3 ?? null,
                'cta_url' => $data->cta_3['url'] ?? null,
                'cta_title' => $data->cta_3['title'] ?? null,
            ],
            [
                'url' => $data->immagine_4['url'] ?? null,
                'alt' => $data->immagine_4['alt'] ?? null,
                'title' => $data->immagine_4['title'] ?? null,
                'caption' => $data->immagine_4['caption'] ?? null,
                'description' => $data->immagine_4['description'] ?? null,
                'titolo' => $data->titolo_4 ?? null,
                'testo' => $data->testo_4 ?? null,
                'cta_url' => $data->cta_4['url'] ?? null,
                'cta_title' => $data->cta_4['title'] ?? null,
            ],
        ]]
        , \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


    <?php echo $__env->make('components.mono-logo', [
        'titolo_monologo' => $mono_fields->titolo_monologo,
        'sottotitolo_monologo' => $mono_fields->sottotitolo_monologo,
        'immagine_monologo' =>$mono_fields->immagine_monologo['url']
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('components.missione', [
        'titolo_missione' => $data->titolo_missione ?? null,
        'testo_missione' => $data->testo_missione ?? null,

        'immagine_missione_url' => $data->immagine_missione['url'] ?? null,
        'immagine_missione_alt' => $data->immagine_missione['alt'] ?? null,
        'immagine_missione_title' => $data->immagine_missione['title'] ?? null,
        'immagine_missione_caption' => $data->immagine_missione['caption'] ?? null,
        'immagine_missione_description' => $data->immagine_missione['description'] ?? null,

        'cta_missione_dona_ora_url' => $data->cta_missione_dona_ora['url'] ?? null,
        'cta_missione_dona_ora_titolo' => $data->cta_missione_dona_ora['title'] ?? null,

        'cta_missione_galleria_url' => $data->cta_missione_galleria['url'] ?? null,
        'cta_missione_galleria_titolo' => $data->cta_missione_galleria['title'] ?? null,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    
    <?php echo $__env->make('components.testo-sottotesto',[
        'titolo' => $data->titolo_progetti,
        'sottotitolo' => $data->descrizione_progetti,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <?php echo $__env->make('components.home-cards', ['progetti' => $data->progetti], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    
    
    <?php echo $__env->make('components.aziende', [
        'titolo' => $data->titolo_azienda ?? null,
        'descrizione' => $data->descrizione_azienda ?? null,
        'cta' => $data->cta_azienda ?? null,
        'immagine_url' => $data->immagine_azienda['url'] ?? null,
        'immagine_alt' => $data->immagine_azienda['alt'] ?? null,
        'immagine_title' => $data->immagine_azienda['title'] ?? null,
        'immagine_caption' => $data->immagine_azienda['caption'] ?? null,
        'immagine_description' => $data->immagine_azienda['description'] ?? null,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/home.blade.php ENDPATH**/ ?>