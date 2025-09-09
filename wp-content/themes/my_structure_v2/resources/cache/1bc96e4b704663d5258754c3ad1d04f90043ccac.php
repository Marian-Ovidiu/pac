<section class="bg-white <?php echo e(isset($class) ? $class : ''); ?>">
    <div class="container flex flex-col items-center px-4 mx-auto text-center">
        <?php if($titolo): ?>
            <h2 class="text-2xl font-bold tracking-tight text-custom-dark-green xl:text-3xl">
                <?php echo e($titolo); ?>

            </h2>
        <?php endif; ?>
        <?php if($sottotitolo): ?>
            <p class="block max-w-4xl mt-4 text-gray-500">
                <?php echo $sottotitolo; ?>

            </p>
        <?php endif; ?>
        <?php if(isset($highlight) && $highlight): ?>
            <div class="min-h-[1.5rem]" x-data="typingEffect()">
                <p class="block max-w-4xl mt-4 text-gray-500 text-center">
                    <?php echo e($text_base_highlight); ?> <span x-text="displayText"></span>
                </p>
            </div>
        <?php endif; ?>
    </div>
</section><?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/components/testo-sottotesto.blade.php ENDPATH**/ ?>