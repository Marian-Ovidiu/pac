<?php

if (!function_exists('my_theme_setup')) {
    function my_theme_setup() {
        // SEO / markup: immagine in evidenza (Rank Math e social usano spesso la featured image); markup HTML5 pulito.
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

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

/**
 * Bundle Vite = ES modules (import/export). WordPress aggiunge <script> senza type → errore "Cannot use import statement outside a module".
 */
if (!function_exists('pac_vite_script_type_module')) {
    function pac_vite_script_type_module($tag, $handle, $src) {
        $vite_module_handles = ['main', 'home-slider', 'progetto-slider'];

        if (!in_array($handle, $vite_module_handles, true)) {
            return $tag;
        }

        if (strpos($tag, 'type="module"') !== false) {
            return $tag;
        }

        return (string) preg_replace('/<script\b/', '<script type="module"', $tag, 1);
    }
}

add_filter('script_loader_tag', 'pac_vite_script_type_module', 10, 3);

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

/**
 * Archivi blog category / post_tag non usati in front: 404 + esclusione dalle sitemap.
 * Le tassonomie restano in admin (assegna categorie ai post se serve); spariscono solo le URL di archivio.
 */
if (!function_exists('pac_disable_category_tag_archives')) {
    function pac_disable_category_tag_archives() {
        if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        if (is_category() || is_tag()) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
        }
    }
}

add_action('template_redirect', 'pac_disable_category_tag_archives', 2);

add_filter('wp_sitemaps_taxonomies', static function ($taxonomies) {
    if (!is_array($taxonomies)) {
        return $taxonomies;
    }
    unset($taxonomies['category'], $taxonomies['post_tag']);

    return $taxonomies;
}, 10, 1);

add_filter('rank_math/sitemap/exclude_taxonomy', static function ($exclude, $type) {
    if (in_array((string) $type, ['category', 'post_tag'], true)) {
        return true;
    }

    return $exclude;
}, 10, 2);
