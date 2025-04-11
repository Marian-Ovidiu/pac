<?php
use Dotenv\Dotenv;

if (!function_exists('vite_asset')) {
    function vite_asset($path) {
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

if (!function_exists('camelToKebab')) {
    function camelToKebab($string) {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
    }
}

if (!function_exists('my_env')) {
    function my_env($key, $default = null) {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $my_envs = $dotenv->load();

        if (!isset($my_envs[$key])) {
            return $default;
        }

        return $my_envs[$key];
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