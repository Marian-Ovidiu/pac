<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title', 'Il mio sito'); ?></title>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="flex flex-col min-h-screen">
   <?php wp_head(); ?>
    <header>
        <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenu']); ?>
    </header>

    <main class="flex-grow main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="text-white">
     
    </footer>
    <?php echo $__env->yieldContent('scripts'); ?>
   <?php wp_footer(); ?>
</body>
</html>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php ENDPATH**/ ?>