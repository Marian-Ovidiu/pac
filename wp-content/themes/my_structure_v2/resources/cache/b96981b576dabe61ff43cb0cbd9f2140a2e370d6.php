<div class="container mx-auto py-8">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 px-4">
        <?php $__currentLoopData = $progetti; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $progetto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($progetto['titolo']): ?>
                <article class="bg-white rounded-lg shadow-md overflow-hidden transform transition-transform hover:scale-105">
                    <figure class="relative h-64 w-full overflow-hidden">
                        <img 
                            src="<?php echo e($progetto['immagine']['url'] ?? ''); ?>" 
                            alt="<?php echo e($progetto['immagine']['alt'] ?? 'Immagine del progetto'); ?>" 
                            title="<?php echo e($progetto['immagine']['title'] ?? ''); ?>" 
                            class="h-full w-full object-cover object-center" 
                            loading="lazy"
                        >
                        <figcaption class="sr-only">
                            <?php if(!empty($progetto['immagine']['caption'])): ?>
                                <?php echo e($progetto['immagine']['caption']); ?>

                            <?php endif; ?>
                            <?php if(!empty($progetto['immagine']['description'])): ?>
                                - <?php echo e($progetto['immagine']['description']); ?>

                            <?php endif; ?>
                        </figcaption>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/0"></div>
                    </figure>

                    <div class="bg-gradient-to-t from-custom-green/30 via-custom-green to-custom-green px-6 py-4">
                        <h3 class="text-lg font-bold uppercase text-center text-gray-800 dark:text-white">
                            <?php echo e($progetto['titolo']); ?>

                        </h3>

                        <div class="mt-4 flex justify-center">
                            <a href="<?php echo e($progetto['cta']['url']); ?>" aria-label="<?php echo e($progetto['cta']['title']); ?>"
                               class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-custom-dark-green bg-custom-light-green rounded-lg shadow hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80 transition duration-300">
                                <?php echo $__env->make('svg.gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                <span class="ml-2"><?php echo e($progetto['cta']['title']); ?></span>
                            </a>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div>
<?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/components/home-cards.blade.php ENDPATH**/ ?>