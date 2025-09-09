<div class="w-full relative h-[75vh] overflow-hidden mb-12">
    <div class="swiper-container logo-carousel h-full opacity-0 transition-opacity duration-300" x-init="$nextTick(() => setTimeout(() => $el.classList.remove('opacity-0'), 100))">
        <div class="swiper-wrapper sw-wrapper-linear-custom h-full" aria-live="polite">
            <?php $__currentLoopData = $slides; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slide): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php if($slide['url'] && $slide['titolo'] && $slide['testo']): ?>
                    <div class="swiper-slide !h-full w-full">
                        <section x-data="{ loaded: false }"
                                 x-intersect="loaded = true"
                                 :style="loaded
                                 ? `background-image: url('<?php echo e($slide['url']); ?>'); background-size: cover; background-position: center;`
                                 : 'background-color: #ccc;'"
                                 class="overflow-hidden bg-cover bg-center bg-no-repeat transition-all w-full h-full flex justify-center items-center"
                                 aria-label="<?php echo e($slide['alt'] ?? $slide['titolo']); ?>"
                                 role="region">
                            <div class="bg-black/25 h-full w-full p-6 sm:p-8 md:p-12 lg:p-16 flex justify-center items-center">
                                <div class="text-center flex flex-col items-center justify-center w-full max-w-screen-md px-4">
                                    <h3 class="text-2xl sm:text-3xl md:text-5xl font-bold text-white font-nunitoBold">
                                        <?php echo e($slide['titolo']); ?>

                                    </h3>
                                    <p class="text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSansRegular">
                                        <?php echo e($slide['testo']); ?>

                                    </p>
                                    <?php if($slide['caption']): ?>
                                        <p class="text-sm text-white/80 italic mt-2"><?php echo e($slide['caption']); ?></p>
                                    <?php endif; ?>
                                    <?php if($slide['cta_url'] && $slide['cta_title']): ?>
                                        <div class="mt-4 sm:mt-8">
                                            <a href="<?php echo e($slide['cta_url']); ?>" aria-label="<?php echo e($slide['cta_title']); ?>" role="button"
                                               class="inline-block rounded-full px-8 py-3 bg-custom-green text-sm font-medium text-white transition focus:outline-none focus:ring focus:ring-yellow-400 font-nunitoSansRegular">
                                                <?php echo e($slide['cta_title']); ?>

                                            </a>
                                        </div>
                                    <?php endif; ?>
                                    <?php if($slide['description']): ?>
                                        <div class="sr-only"><?php echo e($slide['description']); ?></div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php if (isset($component)) { $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4 = $component; } ?>
<?php $component = $__env->getContainer()->make(Illuminate\View\AnonymousComponent::class, ['view' => 'components.img','data' => ['acf' => $slide,'class' => 'sr-only']]); ?>
<?php $component->withName('img'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php $component->withAttributes(['acf' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($slide),'class' => 'sr-only']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4)): ?>
<?php $component = $__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4; ?>
<?php unset($__componentOriginalc254754b9d5db91d5165876f9d051922ca0066f4); ?>
<?php endif; ?>
                        </section>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>
</div>
<?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/components/slider.blade.php ENDPATH**/ ?>