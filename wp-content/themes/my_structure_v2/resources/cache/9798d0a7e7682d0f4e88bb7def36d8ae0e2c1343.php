
<?php $__env->startSection('content'); ?>


<?php $__env->startSection('content'); ?>
    <section class="relative py-12 px-4 sm:px-6 lg:px-12 bg-gradient-to-b from-[#f5fef4] to-white">
        <div class="max-w-3xl mx-auto bg-white border border-[#e1f5d8] rounded-2xl shadow-sm p-6 sm:p-8">

            
            <h1 class="text-2xl sm:text-3xl lg:text-4xl font-nunitoBold text-custom-dark-green leading-tight mb-2">
                <?php echo e($title); ?>

            </h1>

            
            <div class="text-[13px] text-[#5c4433] font-nunitoSansRegular mb-6 flex flex-wrap gap-3">
                <div class="flex items-center gap-1">
                    <svg class="text-custom-green" fill="currentColor" width="14" height="14" viewBox="0 0 512 512">
                        <path d="M256,0C114.837,0,0,114.837,0,256s114.837,256,256,256s256-114.837,256-256S397.163,0,256,0z M277.333,256
                            c0,11.797-9.536,21.333-21.333,21.333h-85.333c-11.797,0-21.333-9.536-21.333-21.333s9.536-21.333,21.333-21.333h64v-128
                            c0-11.797,9.536-21.333,21.333-21.333s21.333,9.536,21.333,21.333V256z"/>
                    </svg>
                    <span><?php echo e($date); ?></span>
                </div>

                <div class="flex items-center gap-1">
                    <svg class="text-custom-green" fill="currentColor" width="16" height="16" viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/>
                    </svg>
                    <span><?php echo e($author); ?></span>
                </div>
            </div>

            
            <div class="prose prose-sm sm:prose lg:prose-lg max-w-none text-[#5c4433] font-nunitoRegular">
                <?php echo $content; ?>

            </div>

        </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('layouts.mainLayout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/singolo-post.blade.php ENDPATH**/ ?>