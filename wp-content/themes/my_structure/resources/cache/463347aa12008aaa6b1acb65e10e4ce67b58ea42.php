<div class="w-full relative">
    <div class="swiper vertical-slide-carousel swiper-container relative">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo e($data->immagine_1['url']); ?>')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito"><?php echo e($data->titolo_1); ?></h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                <?php echo e($data->testo_1); ?>

                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="<?php echo e($data->cta_1['url']); ?>" class="inline-block rounded-full px-12 py-3 bg-[#84CE59]
                                    text-sm font-medium text-white transition focus:outline-none
                                    focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    <?php echo e($data->cta_1['title']); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="swiper-slide">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo e($data->immagine_2['url']); ?>')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito"><?php echo e($data->titolo_2); ?></h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                <?php echo e($data->testo_2); ?>

                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="<?php echo e($data->cta_2['url']); ?>" class="inline-block rounded-full px-12 py-3 bg-[#84CE59]
                                        text-sm font-medium text-white transition focus:outline-none
                                        focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    <?php echo e($data->cta_2['title']); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="swiper-slide">
                <section class="overflow-hidden bg-cover bg-center bg-no-repeat" style="background-image: url('<?php echo e($data->immagine_3['url']); ?>')">
                    <div class="bg-black/25 p-8 md:p-12 lg:px-16 lg:py-24 content-slide">
                        <div class="text-center flex flex-col items-center justify-center content-slide">
                            <h2 class="text-2xl font-bold text-white sm:text-3xl md:text-5xl font-nunito"><?php echo e($data->titolo_3); ?></h2>
                            <p class="text-center max-w-lg text-white/90 mt-4 md:mt-6 md:text-lg md:leading-relaxed font-nunitoSans">
                                <?php echo e($data->testo_3); ?>

                            </p>
                            <div class="mt-4 sm:mt-8">
                                <a href="<?php echo e($data->cta_3['url']); ?>" class="inline-block rounded-full px-12 py-3 bg-[#84CE59]
                                    text-sm font-medium text-white transition focus:outline-none
                                    focus:ring focus:ring-yellow-400 font-nunitoSans">
                                    <?php echo e($data->cta_3['title']); ?>

                                </a>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
        <div class="swiper-pagination !top-1/3 !translate-y-8"></div>
    </div>
</div><?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/components/slider.blade.php ENDPATH**/ ?>