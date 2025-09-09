<?php if($immagine_missione_url): ?>
    <figure class="my-6">
        <img src="<?php echo e($immagine_missione_url); ?>"
             alt="<?php echo e($immagine_missione_alt ?? $titolo_missione); ?>"
             title="<?php echo e($immagine_missione_title ?? ''); ?>"
             class="mx-auto w-full max-w-4xl rounded-lg"
             loading="lazy" decoding="async">
        <?php if($immagine_missione_caption): ?>
            <figcaption class="text-sm text-gray-500 italic mt-2"><?php echo e($immagine_missione_caption); ?></figcaption>
        <?php endif; ?>
        <?php if($immagine_missione_description): ?>
            <div class="sr-only"><?php echo e($immagine_missione_description); ?></div>
        <?php endif; ?>
    </figure>
<?php endif; ?>
<?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/components/missione.blade.php ENDPATH**/ ?>