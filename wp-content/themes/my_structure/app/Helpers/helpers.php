<?php

use Dotenv\Dotenv;

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
            $fullSrcStyle = vite_asset('scss/style.scss');
            wp_enqueue_style('style', $fullSrcStyle);
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

if (!function_exists('my_env')) {
    function my_env($key, $default = null)
    {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $my_envs = $dotenv->load();

        if (!isset($my_envs[$key])) {
            return $default;
        }

        return $my_envs[$key];
    }
}

/*if (!function_exists('do_shortcode')) {
    function do_shortcode( $content, $ignore_html = false ) {
        global $shortcode_tags;

        if ( ! str_contains( $content, '[' ) ) {
            return $content;
        }

        if ( empty( $shortcode_tags ) || ! is_array( $shortcode_tags ) ) {
            return $content;
        }

        // Find all registered tag names in $content.
        preg_match_all( '@\[([^<>&/\[\]\x00-\x20=]++)@', $content, $matches );
        $tagnames = array_intersect( array_keys( $shortcode_tags ), $matches[1] );

        if ( empty( $tagnames ) ) {
            return $content;
        }

        // Ensure this context is only added once if shortcodes are nested.
        $has_filter   = has_filter( 'wp_get_attachment_image_context', '_filter_do_shortcode_context' );
        $filter_added = false;

        if ( ! $has_filter ) {
            $filter_added = add_filter( 'wp_get_attachment_image_context', '_filter_do_shortcode_context' );
        }

        $content = do_shortcodes_in_html_tags( $content, $ignore_html, $tagnames );

        $pattern = get_shortcode_regex( $tagnames );
        $content = preg_replace_callback( "/$pattern/", 'do_shortcode_tag', $content );

        // Always restore square braces so we don't break things like <!--[if IE ]>.
        $content = unescape_invalid_shortcodes( $content );

        // Only remove the filter if it was added in this scope.
        if ( $filter_added ) {
            remove_filter( 'wp_get_attachment_image_context', '_filter_do_shortcode_context' );
        }

        return $content;
    }

}*/



