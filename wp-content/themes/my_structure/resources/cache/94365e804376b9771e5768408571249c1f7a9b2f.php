<?php
/**
 * @var \Models\HomeFields $data
 */
?>

<?php $__env->startSection('content'); ?>
    <header class="sr-only">
        <h1>Project Africa Conservation - Un futuro per la fauna e le comunità</h1>
    </header>
    <?php echo $__env->make('components.slider', [
        'immagine_1_url' => $data->immagine_1['url'], 'titolo_1' => $data->titolo_1, 'immagine_1_alt' => $data->immagine_1['alt'],
        'testo_1' => $data->testo_1, 'cta_1_url' => $data->cta_1['url'], 'cta_1_title' => $data->cta_1['title'],
        'immagine_2_url' => $data->immagine_2['url'], 'immagine_2_alt' => $data->immagine_2['alt'], 'titolo_2' => $data->titolo_2,
        'testo_2' => $data->testo_2, 'cta_2_url' => $data->cta_2['url'], 'cta_2_title' => $data->cta_2['title'],
        'immagine_3_url' => $data->immagine_3['url'], 'immagine_3_alt' => $data->immagine_3['alt'], 'titolo_3' => $data->titolo_3,
        'testo_3' => $data->testo_3, 'cta_3_url' => $data->cta_3['url'], 'cta_3_title' => $data->cta_3['title']
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.missione', [
        'titolo_missione' => $data->titolo_missione,
        'testo_missione' => $data->testo_missione,
        'cta_missione_dona_ora_url' => $data->cta_missione_dona_ora['url'], 'cta_missione_dona_ora_titolo' => $data->cta_missione_dona_ora['title'],
        'cta_missione_galleria_url' => $data->cta_missione_galleria['url'], 'cta_missione_galleria_titolo' => $data->cta_missione_galleria['title'],
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('components.testo-sottotesto',[
        'titolo' => $data->titolo_progetti,
        'sottotitolo' => $data->descrizione_progetti,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.home-cards', ['progetti' => $data->progetti], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('components.home-mobile-cards', ['progetti' => $data->progetti], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    
    <?php echo $__env->make('components.aziende', [
        'titolo' => $data->titolo_azienda,
        'descrizione' => $data->descrizione_azienda,
        'cta' => $data->cta_azienda,
        'immagine' => $data->immagine_azienda,
    ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/home.blade.php ENDPATH**/ ?>