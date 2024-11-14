<div>
    <?php if(isset($titolo)): ?>
        <div class="px-6">
            <h2 class="text-2xl text-custom-dark-green font-semibold capitalize lg:text-3xl pt-10"><?php echo e($titolo); ?></h2>
        </div>
    <?php endif; ?>
    <?php $__currentLoopData = $items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mt-8 px-6 lg:-mx-6 lg:flex-col lg:items-center lg:justify-center">
            <div class="mt-6 lg:mt-0 lg:mx-6">
                <?php if(isset($item['sottoTitolo'])): ?>
                    <h3 class="block mt-4 text-xl font-semibold text-gray-800">
                        <?php echo e($item['sottoTitolo']); ?>

                    </h3>
                <?php endif; ?>
                <?php if(isset($item['testo'])): ?>
                    <p class="mt-3 text-sm text-gray-500 md:text-sm">
                        <?php echo $item['testo']; ?>

                    </p>
                <?php endif; ?>
            </div>

            <?php
                $immagini = array_filter($item['immagini'], fn($img) => isset($img['url']));
                $immaginiCount = count($immagini);
            ?>

            <div class="relative w-full max-w-lg mt-4 mx-auto overflow-hidden" <?php if($immaginiCount > 1): ?> x-data="{ current: 0 }" <?php endif; ?>>
                <?php if($immaginiCount > 1): ?>
                    <!-- Galleria con più immagini -->
                    <div class="flex transition-transform duration-700" :style="`transform: translateX(-${current * 100}%)`">
                        <?php $__currentLoopData = $immagini; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $immagine): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <img src="<?php echo e($immagine['url']); ?>" alt="<?php echo e($immagine['alt'] ?? ''); ?>" loading="lazy" class="w-full" />
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <button @click="current = (current + 1) % <?php echo e($immaginiCount); ?>" aria-label="Prossima immagine" class="absolute right-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                    </button>
                    <button @click="current = (current - 1 + <?php echo e($immaginiCount); ?>) % <?php echo e($immaginiCount); ?>" aria-label="Immagine precedente" class="absolute left-0 top-1/2 transform -translate-y-1/2 text-white p-2 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                    </button>
                <?php elseif($immaginiCount === 1): ?>
                    <!-- Singola immagine fissa senza navigazione -->
                    <img src="<?php echo e($immagini[0]['url']); ?>" alt="<?php echo e($immagini[0]['alt'] ?? ''); ?>" loading="lazy" class="w-full" />
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/section.blade.php ENDPATH**/ ?>