<section class="bg-white">
    <div class="container flex flex-col items-center px-4 py-12 mx-auto text-center">
        <?php if($titolo_missione): ?>
            <h2 class="text-nunitoRegular text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
                <?php echo e($titolo_missione); ?>

            </h2>
        <?php endif; ?>
        <?php if($testo_missione): ?>
            <p class="block max-w-4xl mt-4 text-gray-500">
                <?php echo $testo_missione; ?>

            </p>
        <?php endif; ?>

        <div class="mt-6">
            <?php if($cta_missione_dona_ora_url): ?>
                <a href="<?php echo e($cta_missione_dona_ora_url); ?>" class="group inline-flex items-center justify-center w-full px-4 py-2.5 overflow-hidden text-sm text-white transition-colors duration-300 bg-custom-green rounded-lg shadow sm:w-auto sm:mx-2 hover:bg-custom-light-green hover:text-custom-dark-green focus:ring focus:ring-gray-300 focus:ring-opacity-80">
                    <?php echo $__env->make('svg.charity', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <?php echo $__env->make('svg.charity-dark', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <span class="mx-2">
                        <?php echo e($cta_missione_dona_ora_titolo); ?>

                    </span>
                </a>
            <?php endif; ?>
            <?php if($cta_missione_galleria_url): ?>
                <a href="<?php echo e($cta_missione_galleria_url); ?>"
                   class="inline-flex items-center justify-center w-full px-4 py-2.5 mt-4 overflow-hidden text-sm text-custom-dark-green transition-colors duration-300 bg-custom-light-green rounded-lg shadow sm:w-auto sm:mx-2 sm:mt-0 hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80">
                    <?php echo $__env->make('svg.gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    <span class="mx-2">
                        <?php echo e($cta_missione_galleria_titolo); ?>

                    </span>
                </a>
            <?php endif; ?>
        </div>
    </div>
</section><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/missione.blade.php ENDPATH**/ ?>