<!DOCTYPE html>
<html lang="<?php echo e(function_exists('pll_current_language') ? pll_current_language() : 'it'); ?>">
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
    <?php
        $hreflangs = function_exists('pll_get_the_languages') ? pll_get_the_languages(['raw' => 1]) : [];
    ?>
    <?php if(isset($hreflangs)): ?>
    <?php $__currentLoopData = $hreflangs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $lang): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <link rel="alternate" hreflang="<?php echo e($lang['locale']); ?>" href="<?php echo e($lang['url']); ?>" />
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php endif; ?>
    <link rel="canonical" href="<?php echo e(get_permalink()); ?>">

    <link rel="manifest" href="/site.webmanifest">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300&display=swap" rel="stylesheet">
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="flex flex-col min-h-screen font-nunitoSansRegular">
   <?php wp_head(); ?>
    @widget('LanguageMenu')
    <?php switch(pll_current_language()):
        case ('it'): ?>
            @widget('HeaderMenu')
            <?php break; ?>
        <?php case ('en'): ?>
            @widget('HeaderMenuEnglish')
            <?php break; ?>
        <?php case ('fr'): ?>
            @widget('HeaderMenuFrancais')
            <?php break; ?>
        <?php case ('de'): ?>
            @widget('HeaderMenuDeutsch')
            <?php break; ?>
        <?php default: ?>
            @widget('HeaderMenu')
            <?php break; ?>
    <?php endswitch; ?>

    <main class="flex-grow main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="text-white">
        <?php switch(pll_current_language()):
            case ('it'): ?>
                @widget('FooterMenu')
                <?php break; ?>
            <?php case ('en'): ?>
                @widget('FooterMenuEnglish')
                <?php break; ?>
            <?php case ('fr'): ?>
                @widget('FooterMenuFrancais')
                <?php break; ?>
            <?php case ('de'): ?>
                @widget('FooterMenuDeutsch')
                <?php break; ?>
            <?php default: ?>
                @widget('FooterMenu')
                <?php break; ?>
        <?php endswitch; ?>
    </footer>
    <?php echo $__env->yieldContent('scripts'); ?>
   <?php wp_footer(); ?>
</body>
</html>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php ENDPATH**/ ?>