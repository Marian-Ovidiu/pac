<?php

if (!function_exists('vite_asset')) {
    function vite_asset($path)
    {
        $manifestPath = get_template_directory() . '/public/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            error_log('Il manifest non è stato trovato: ' . $manifestPath);
            return null;
        }

        $manifest = json_decode(file_get_contents($manifestPath), true);
        $key = 'assets/' . ltrim($path, '/');

        if (isset($manifest[$key])) {
            return get_template_directory_uri() . '/public/' . $manifest[$key]['file'];
        }

        error_log('Asset non trovato nel manifest: ' . $key);
        return null;
    }
}

if (!function_exists('my_theme_setup')) {
    function my_theme_setup() {
        add_base_js();
        add_base_css();
        register_my_widgets();
        register_menus();
    }
}
if (!function_exists('add_base_css')) {
    function add_base_css() {
        add_action('wp_enqueue_scripts', function() {
            $fullSrc = vite_asset('scss/style.scss');
            wp_enqueue_style('style', $fullSrc);
        });
    }
}

if (!function_exists('add_base_js')) {
    function add_base_js() {
        $fullSrc = vite_asset('js/main.js');
        add_action('wp_enqueue_scripts', function () use($fullSrc) {
            wp_enqueue_script('main', $fullSrc, []);
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

if (!function_exists('camelToKebab')) {
    function camelToKebab($string) {
        $kebab = strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
        return $kebab;
    }
}



