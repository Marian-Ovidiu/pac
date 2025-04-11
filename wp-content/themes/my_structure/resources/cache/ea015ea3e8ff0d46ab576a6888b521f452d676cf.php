<?php $__env->startSection('content'); ?>
<section class="py-10 sm:py-16 lg:py-24">
    <div class="max-w-6xl px-4 mx-auto sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <!-- Immagine -->
            <div class="relative w-full h-full rounded-lg overflow-hidden shadow-lg">
                <img 
                    src="<?php echo e($fields->immagine['url']); ?>" 
                    alt="<?php echo e($fields->immagine['alt'] ?? $fields->titolo); ?>" 
                    loading="lazy" 
                    decoding="async" 
                    class="w-full h-full object-cover object-center"
                    width="800" 
                    height="600"
                >
            </div>

            <!-- Contenuto -->
            <div class="text-center md:text-left space-y-6">
                <h2 class="text-4xl sm:text-5xl font-extrabold text-custom-dark-green leading-tight">
                    <?php echo e($fields->titolo); ?>

                </h2>
                
                <div class="prose prose-lg text-gray-700 max-w-none">
                    <?php echo $fields->testo; ?>

                </div>

                <div>
                    <a href="<?php echo e($fields->cta['url']); ?>" aria-label="Vai alla sezione: <?php echo e($fields->cta['title']); ?>">
                        <button class="mt-4 rounded-full bg-custom-dark-green px-6 py-3 text-lg font-bold text-white transition-all hover:-translate-y-1 hover:shadow-xl">
                            <?php echo e($fields->cta['title']); ?>

                        </button>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/grazie.blade.php ENDPATH**/ ?>