<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo $__env->yieldContent('meta_description', 'Sito ufficiale PAC - Project Africa Conservation, dedicato alla protezione della fauna e allo sviluppo sociale.'); ?>">
    <title><?php echo $__env->yieldContent('title', 'PAC - Project Africa Conservation'); ?></title>

    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Project Africa Conservation",
          "url": "https://project-africa-conservation.org",
          "logo": "https://project-africa-conservation.org/wp-content/uploads/2024/12/cropped-pittogramma-1.png",
          "sameAs": [
            "https://www.facebook.com/15kZKmU4gr/",
            "https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==",
            "https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/"
          ]
        }
    </script>
    <link rel="canonical" href="<?php echo e(get_permalink()); ?>">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300&display=swap" rel="stylesheet">
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="flex flex-col min-h-screen font-nunitoSansRegular">
    <?php wp_head(); ?>
    <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenu']); ?>
    

    <main class="flex-grow main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="text-white">
         <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenu']); ?>
        
    </footer>
    <?php echo $__env->yieldContent('scripts'); ?>
    <?php wp_footer(); ?>
</body>
</html>
<?php /**PATH C:\MAMP\htdocs\pac\wp-content\themes\my_structure\resources\views/layouts/mainLayout.blade.php ENDPATH**/ ?>