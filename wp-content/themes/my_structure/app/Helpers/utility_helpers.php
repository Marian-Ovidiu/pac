<?php

use Dotenv\Dotenv;

if (!function_exists('vite_manifest')) {
    function vite_manifest() {
        static $manifest = null;

        if ($manifest !== null) {
            return $manifest;
        }

        $manifestPath = get_template_directory() . '/public/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            error_log('Manifest Vite non trovato: ' . $manifestPath);
            $manifest = [];
            return $manifest;
        }

        $decoded = json_decode((string) file_get_contents($manifestPath), true);

        if (!is_array($decoded)) {
            error_log('Manifest Vite non valido: ' . $manifestPath);
            $manifest = [];
            return $manifest;
        }

        $manifest = $decoded;

        return $manifest;
    }
}

if (!function_exists('vite_entry_key')) {
    function vite_entry_key($path) {
        return 'assets/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('vite_entry')) {
    function vite_entry($path) {
        $manifest = vite_manifest();
        $key = vite_entry_key($path);

        return isset($manifest[$key]) && is_array($manifest[$key])
            ? $manifest[$key]
            : null;
    }
}

if (!function_exists('vite_asset')) {
    function vite_asset($path) {
        $entry = vite_entry($path);

        if (!$entry || empty($entry['file'])) {
            error_log('Asset non trovato nel manifest: ' . vite_entry_key($path));
            return null;
        }

        return get_template_directory_uri() . '/public/' . ltrim((string) $entry['file'], '/');
    }
}

if (!function_exists('vite_asset_css')) {
    function vite_asset_css($path) {
        $entry = vite_entry($path);

        if (!$entry || empty($entry['css']) || !is_array($entry['css'])) {
            return [];
        }

        return array_values(array_map(
            static function ($cssFile) {
                return get_template_directory_uri() . '/public/' . ltrim((string) $cssFile, '/');
            },
            $entry['css']
        ));
    }
}

if (!function_exists('camelToKebab')) {
    function camelToKebab($string) {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
    }
}

if (!function_exists('my_env')) {
    function my_env($key, $default = null) {
        static $envs = null;

        if ($envs === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $envs = $dotenv->safeLoad();
        }

        return $envs[$key] ?? $default;
    }
}

if (!function_exists('resource_path')) {
    function resource_path($path = '') {
        return __DIR__ . '/../../resources/' . $path;
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/../../' . $path;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $configurations = [
            'laravel-translatable-string-exporter' => [
                'lang_path' => 'resources/lang',
                'strings_path' => [
                    resource_path('views'),
                ],
                'string_functions' => ['__', 'trans'],
            ],
        ];
        return $configurations[$key] ?? $default;
    }
}

if (!function_exists('theme_seo_plugin_active')) {
    function theme_seo_plugin_active() {
        return defined('RANK_MATH_VERSION')
            || defined('WPSEO_VERSION')
            || class_exists('RankMath')
            || function_exists('rank_math');
    }
}

if (!function_exists('theme_meta_description')) {
    function theme_meta_description() {
        if (is_singular()) {
            $post = get_queried_object();

            if ($post instanceof WP_Post) {
                $excerpt = has_excerpt($post) ? get_the_excerpt($post) : '';

                if ($excerpt !== '') {
                    return wp_strip_all_tags($excerpt, true);
                }

                $content = wp_strip_all_tags(strip_shortcodes((string) $post->post_content), true);
                $content = trim(preg_replace('/\s+/', ' ', $content));

                if ($content !== '') {
                    return wp_trim_words($content, 30, '...');
                }
            }
        }

        $description = get_bloginfo('description', 'display');

        if (is_string($description) && $description !== '') {
            return wp_strip_all_tags($description, true);
        }

        return 'Sito ufficiale PAC - Project Africa Conservation.';
    }
}

if (!function_exists('theme_schema_graph')) {
    function theme_schema_graph() {
        if (!is_front_page()) {
            return null;
        }

        $options = class_exists('\Models\Options\OpzioniGlobaliFields')
            ? \Models\Options\OpzioniGlobaliFields::get()
            : null;

        $logo = is_object($options) && !empty($options->logo['url'])
            ? $options->logo['url']
            : null;

        $sameAs = array_values(array_filter([
            'https://www.facebook.com/15kZKmU4gr/',
            'https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==',
            'https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/',
        ]));

        $graph = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => get_bloginfo('name', 'display'),
            'url' => home_url('/'),
        ];

        if ($logo) {
            $graph['logo'] = $logo;
        }

        if (!empty($sameAs)) {
            $graph['sameAs'] = $sameAs;
        }

        return $graph;
    }
}
