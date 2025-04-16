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
    <link rel="canonical" href="<?php echo e(get_permalink()); ?>">
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo e(my_env('RECAPTCHA_SITE_KEY')); ?>" async defer></script>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@300&display=swap" rel="stylesheet">
    <?php echo $__env->yieldContent('head'); ?>
</head>
<body class="flex flex-col min-h-screen font-nunitoSansRegular">
    <?php wp_head(); ?>
    <?php the_widget('Widget\MenuWidget', ['menu_name' => 'LanguageMenu']); ?>
    <?php switch(pll_current_language()):
        case ('it'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenu']); ?>         <?php break; ?>
        <?php case ('en'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenuEnglish']); ?>  <?php break; ?>
        <?php case ('fr'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenuFrancais']); ?> <?php break; ?>
        <?php case ('de'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenuDeutsch']); ?>  <?php break; ?>
        <?php default: ?>      <?php the_widget('Widget\MenuWidget', ['menu_name' => 'HeaderMenu']); ?>         <?php break; ?>
    <?php endswitch; ?>

    <main class="flex-grow main">
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <footer class="text-white">
        <?php switch(pll_current_language()):
            case ('it'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenu']); ?>         <?php break; ?>
            <?php case ('en'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenuEnglish']); ?>  <?php break; ?>
            <?php case ('fr'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenuFrancais']); ?> <?php break; ?>
            <?php case ('de'): ?>   <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenuDeutsch']); ?>  <?php break; ?>
            <?php default: ?>      <?php the_widget('Widget\MenuWidget', ['menu_name' => 'FooterMenu']); ?>         <?php break; ?>
        <?php endswitch; ?>
    </footer>
    <?php echo $__env->yieldContent('scripts'); ?>
    <script>
        window.setRecaptchaToken = function(token) {
            document.querySelectorAll('[x-data]').forEach(el => {
                if (el.__x && el.__x.$data && 'recaptchaToken' in el.__x.$data) {
                    el.__x.$data.recaptchaToken = token;
                }
            });
        }

        grecaptcha.ready(function () {
            grecaptcha.execute('<?php echo e(my_env('RECAPTCHA_SITE_KEY')); ?>', { action: 'donazione' }).then(function (token) {
                window.setRecaptchaToken(token);
            });
        });
    </script>
    <?php wp_footer(); ?>
</body>
</html>
<?php /**PATH /Users/editweb2/Sites/01progetti-test/pac/wp-content/themes/my_structure/resources/views/layouts/mainLayout.blade.php ENDPATH**/ ?>