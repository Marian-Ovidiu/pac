<section class="bg-white pt-3">
    <div class="container sm:py-2  px-8 py-8 mx-auto text-center">

        <div class="flex justify-center sm:justify-between">
            
            <div class="flex flex-col items-center w-1/2 sm:w-2/5">
                <?php if($titolo_duo_logo_1): ?>
                    <h2 class="font-nunitoBold text-base sm:text-lg lg:text-xl font-bold text-custom-dark-green">
                        <?php echo e($titolo_duo_logo_1); ?>

                    </h2>
                <?php endif; ?>

                <?php if($immagine_duo_logo_1): ?>
                    <div class="mt-2 flex justify-center">
                        <img src="<?php echo e($immagine_duo_logo_1); ?>" alt="Logo"
                            class="object-contain w-auto h-[75px] md:h-[100px] lg:h-[150px]">
                    </div>
                <?php endif; ?>
            </div>

            
            <div class="flex flex-col items-center w-1/2 sm:w-2/5">
                <?php if($titolo_duo_logo_2): ?>
                    <h2 class="font-nunitoBold text-base sm:text-lg lg:text-xl font-bold text-custom-dark-green">
                        <?php echo e($titolo_duo_logo_2); ?>

                    </h2>
                <?php endif; ?>

                <?php if($immagine_duo_logo_2): ?>
                    <div class="mt-2 flex justify-center">
                        <img src="<?php echo e($immagine_duo_logo_2); ?>" alt="Logo"
                        class="object-contain w-auto h-[75px] md:h-[100px] lg:h-[150px]">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        
        <?php if($sottotitolo_comune): ?>
            <p class="mt-6 text-gray-400 text-[11px] italic opacity-75 max-w-2xl mx-auto">
                <?php echo e($sottotitolo_comune); ?>

            </p>
        <?php endif; ?>

    </div>
</section>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/duo-logo.blade.php ENDPATH**/ ?>