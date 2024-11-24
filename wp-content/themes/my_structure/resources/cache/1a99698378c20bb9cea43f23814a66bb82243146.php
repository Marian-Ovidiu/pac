<div class="flex justify-end pt-6">
    <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="flag mx-3">
        <a href="<?php echo e($language->url); ?>">
            <?php echo $language->title; ?>

        </a>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/partials/language-menu.blade.php ENDPATH**/ ?>