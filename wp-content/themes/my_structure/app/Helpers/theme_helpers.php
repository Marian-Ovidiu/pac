<?php

if (!function_exists('my_theme_setup')) {
    function my_theme_setup() {
        disable_woocommerce_assets();
        add_base_js();
        add_base_css();
        register_menus();
    }
}

if (!function_exists('add_base_css')) {
    function add_base_css() {
        add_action('wp_enqueue_scripts', function() {
            $fullSrcStyle = vite_asset('scss/style.scss');
            if ($fullSrcStyle) {
                wp_enqueue_style('theme-style', $fullSrcStyle, [], null);
            }
        });
    }
}

if (!function_exists('add_base_js')) {
    function add_base_js() {
        add_action('wp_enqueue_scripts', function () {
            $fullSrc = vite_asset('js/main.js');
            $publishableKey = my_env('PUBLISHABLE_KEY') ?: my_env('TEST_PUBLISHABLE_KEY', '');

            if (!$fullSrc) {
                return;
            }

            $cssAssets = function_exists('vite_asset_css') ? vite_asset_css('js/main.js') : [];

            foreach ($cssAssets as $index => $cssUrl) {
                $styleHandle = sprintf('main-css-%d', $index);
                if (!wp_style_is($styleHandle, 'enqueued')) {
                    wp_enqueue_style($styleHandle, $cssUrl, [], null);
                }
            }

            wp_enqueue_script('main', $fullSrc, ['jquery'], null, true);
            wp_localize_script('main', 'pacPayments', [
                'ajaxUrl'        => admin_url('admin-ajax.php'),
                'nonce'          => wp_create_nonce('pac_stripe_donation'),
                'publishableKey' => $publishableKey,
                'actions'        => [
                    'createIntent' => 'pac_create_payment_intent',
                    'complete'     => 'pac_complete_donation',
                ],
            ]);
            wp_script_add_data('main', 'data-iub-consent', 'necessary');
        });
    }
}

if (!function_exists('register_my_widgets')) {
    function register_my_widgets() {
        register_widget('Widget\MenuWidget');
    }
}

if (!function_exists('register_menus')) {
    function register_menus()
    {
        add_theme_support('menus');
        $menus = include get_template_directory() . '/app/Config/menus.php';
        register_nav_menus($menus);
    }
}


if (!function_exists('exclude_page_from_sitemap')) {
    function exclude_page_from_sitemap($url, $type, $object)
    {
        switch ($type){
            case 'category':
            case 'author':
                return false;
            default:
                return $url;
        }
    }
}
