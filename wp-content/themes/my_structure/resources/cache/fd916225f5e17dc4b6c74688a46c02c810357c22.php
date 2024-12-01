<div class="flex justify-center mx-auto max-w-lg overflow-y-hidden sm:hidden py-8">
    <div class="flex flex-col gap-2 px-4">
        <?php $__currentLoopData = $progetti; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $progetto): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if($key === 0): ?>
                <article class="out-group mb-4" style="z-index: <?php echo e($key+10); ?>">
                    <div class="flex flex-col items-center justify-center w-full max-w-sm mx-auto">
                        <div class="w-full h-64 bg-gray-300 bg-center bg-cover rounded-lg shadow-md relative"
                             style="background-image: url(<?php echo e($progetto['immagine']['url']); ?>)" aria-label="<?php echo e($progetto['immagine']['alt']); ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/0"></div>
                        </div>

                        <div class="w-full px-4 -mt-10 overflow-hidden rounded-lg shadow-lg md:w-64 bg-gradient-to-t from-custom-green/30 via-custom-green to-custom-green" style="z-index: <?php echo e($key+11); ?>">
                            <h3 class="py-2 font-bold tracking-wide text-center text-gray-800 uppercase dark:text-white"><?php echo e($progetto['titolo']); ?></h3>
                            <div class="flex items-center justify-center px-3 py-2">
                                <a href="<?php echo e($progetto['cta']['url']); ?>" aria-label="<?php echo e($progetto['cta']['title']); ?>"
                                   class="inline-flex items-center justify-center w-full px-4 py-2.5 overflow-hidden text-sm text-custom-dark-green transition-colors duration-300 bg-custom-light-green rounded-lg shadow sm:w-auto sm:mx-2 sm:mt-0 hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80">
                                    <?php echo $__env->make('svg.gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <span class="mx-2">
                                        <?php echo e($progetto['cta']['title']); ?>

                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php else: ?>
                <article x-data="{
                            isAtOrAboveCenter: false,
                            checkIfAtOrAboveCenter(el) {
                                const rect = el.getBoundingClientRect();
                                const elementCenter = rect.top;
                                const viewportCenter = window.innerHeight / 5;
                                this.isAtOrAboveCenter = elementCenter <= viewportCenter;
                            }
                         }"
                     x-intersect="checkIfAtOrAboveCenter($el)"
                     @scroll.window="checkIfAtOrAboveCenter($el)"
                     :class="{ 'overlap': isAtOrAboveCenter }"
                     class="out-group mb-4" style="z-index: <?php echo e($key+10); ?>">
                    <div class="flex flex-col items-center justify-center w-full max-w-sm mx-auto">
                        <div class="w-full h-64 bg-gray-300 bg-center bg-cover rounded-lg shadow-md relative"
                             style="background-image: url(<?php echo e($progetto['immagine']['url']); ?>)" aria-label="<?php echo e($progetto['immagine']['alt']); ?>">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-black/0"></div>
                        </div>

                        <div class="w-full px-4 -mt-10 overflow-hidden rounded-lg shadow-lg md:w-64 bg-gradient-to-t from-custom-green/30 via-custom-green to-custom-green" style="z-index: <?php echo e($key+11); ?>">
                            <h3 class="py-2 font-bold tracking-wide text-center text-gray-800 uppercase dark:text-white"><?php echo e($progetto['titolo']); ?></h3>
                            <div class="flex items-center justify-center px-3 py-2">
                                <a href="<?php echo e($progetto['cta']['url']); ?>" aria-label="<?php echo e($progetto['cta']['title']); ?>"
                                   class="inline-flex items-center justify-center w-full px-4 py-2.5 overflow-hidden text-sm text-custom-dark-green transition-colors duration-300 bg-custom-light-green rounded-lg shadow sm:w-auto sm:mx-2 sm:mt-0 hover:bg-custom-green hover:text-white focus:ring focus:bg-custom-light-green focus:ring-opacity-80">
                                    <?php echo $__env->make('svg.gallery', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                                    <span class="mx-2">
                                        <?php echo e($progetto['cta']['title']); ?>

                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
</div><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/home-mobile-cards.blade.php ENDPATH**/ ?>