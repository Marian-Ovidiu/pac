<?php
    /**
     * @var Models\AziendeFields $fields
     */
?>

<?php $__env->startSection('content'); ?>
    <section class="relative py-10 overflow-hidden bg-black sm:py-16 lg:py-24 xl:py-32">
        <div class="absolute inset-0">
            <img class="object-cover w-full h-full md:object-left md:scale-150 md:origin-top-left" src="<?php echo e($fields->immagine_hero['url']); ?>" alt="" />
        </div>
        <div class="absolute inset-0 hidden bg-gradient-to-r md:block from-black to-transparent"></div>
        <div class="absolute inset-0 block bg-black/60 md:hidden"></div>
        <div class="relative px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl" x-data="typingEffect()">
            <div class="text-center md:w-2/3 lg:w-1/2 xl:w-1/2 md:text-left">
                <?php if($fields->hero_titolo): ?>
                    <h1 class="text-3xl font-bold leading-tight text-white sm:text-4xl lg:text-5xl"><?php echo e($fields->hero_titolo); ?></h1>
                <?php endif; ?>
                <?php if($fields->hero_sottotitolo): ?>
                    <div class="min-h-[1.5rem] mt-4 text-base text-gray-200 ">
                        <?php echo $fields->hero_sottotitolo; ?>

                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
    <div class="container mx-auto">
        <div class="flex flex-col md:flex-row justify-center items-center">
            <div class="py-8">
                <?php echo $__env->make('components.testo-sottotesto',[
                   'titolo' => $fields->perche_titolo,
                   'sottotitolo' => $fields->perche_testo,
                   'highlight' => false,
                ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="pt-8">
                <?php echo $__env->make('components.aziende', [
                     'titolo' => $fields->come_titolo,
                     'descrizione' => $fields->come_testo,
                     'cta' => null,
                     'immagine' => $fields->immagine_banner,
                     'class' => 'md:w-2/3',
                 ], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>
        </div>
    </div>

    <div class="container mx-auto">
        <section class="py-10 bg-gray-100 sm:py-16 lg:py-24">
            <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
                <div class="max-w-2xl mx-auto text-center">
                   <?php if($fields->form_titolo): ?>
                        <h2 class="text-3xl font-bold leading-tight text-custom-dark-green sm:text-4xl lg:text-5xl"><?php echo e($fields->form_titolo); ?></h2>
                   <?php endif; ?>
                   <?php if($fields->form_testo): ?>
                       <p class="max-w-xl mx-auto mt-4 text-base leading-relaxed text-gray-500"><?php echo $fields->form_testo; ?></p>
                   <?php endif; ?>
                </div>
                <?php if($fields->shortcode_form): ?>
                    <?php echo do_shortcode($fields->shortcode_form); ?>

                <?php endif; ?>
                
            </div>
        </section>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/aziende.blade.php ENDPATH**/ ?>