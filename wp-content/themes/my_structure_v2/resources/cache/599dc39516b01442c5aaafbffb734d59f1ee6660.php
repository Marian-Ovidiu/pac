<section class="bg-white pb-6 pt-0">
    <div class="container flex flex-col items-center px-4 mx-auto text-center">
        <?php if($titolo_monologo): ?>
            <h2 class="font-nunitoBold text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
                <?php echo e($titolo_monologo); ?>

            </h2>
        <?php endif; ?>
        <?php if($immagine_monologo): ?>
            <div class="mt-6 w-48 h-auto">
                <img src="<?php echo e($immagine_monologo['url']); ?>" alt="Bonfante-e-Chiarle-2019" class="w-full h-auto">
            </div>
        <?php endif; ?>
        <?php if($sottotitolo_monologo): ?>
            <p class="block max-w-4xl mt-2 text-gray-400 text-[11px] italic opacity-75">
                <?php echo e($sottotitolo_monologo); ?>

            </p>
        <?php endif; ?>
    </div>
</section><?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/components/mono-logo.blade.php ENDPATH**/ ?>