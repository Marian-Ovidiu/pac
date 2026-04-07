<?php

namespace Core\Bases;

use Core\App;

abstract class BaseController
{
    public function __construct() {}

    public static function call($method, $params = [])
    {
        $instance = new static;
        if (method_exists($instance, $method)) {
            call_user_func_array([$instance, $method], $params);
        } else {
            throw new \Exception("Method $method not found in " . static::class);
        }
    }

    protected function addCss($handle, $src, $deps = [], $ver = false)
    {
        if (filter_var($src, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//', $src)) {
            wp_enqueue_style($handle, $src, $deps, $ver);
            return;
        }

        $fullSrc = vite_asset($src);
        if (!$fullSrc) {
            return;
        }

        wp_enqueue_style($handle, $fullSrc, $deps, null);
    }

    protected function addJs($handle, $src, $deps = [], $in_footer = false, $ver = false)
    {
        add_action('wp_enqueue_scripts', function () use ($handle, $src, $deps, $ver, $in_footer) {
            if (filter_var($src, FILTER_VALIDATE_URL) && preg_match('/^https?:\/\//', $src)) {
                wp_enqueue_script($handle, $src, $deps, $ver, $in_footer);
                return;
            }

            $assetPath = 'js/' . ltrim($src, '/');
            $fullSrc = vite_asset($assetPath);

            if (!$fullSrc) {
                return;
            }

            $cssAssets = function_exists('vite_asset_css') ? vite_asset_css($assetPath) : [];

            foreach ($cssAssets as $index => $cssUrl) {
                $styleHandle = sprintf('%s-css-%d', $handle, $index);
                if (!wp_style_is($styleHandle, 'enqueued')) {
                    wp_enqueue_style($styleHandle, $cssUrl, [], null);
                }
            }

            wp_enqueue_script($handle, $fullSrc, $deps, null, $in_footer);
        });
    }

    protected function addVarJs($handle, $var_name, $data, $in_footer = false, $ver = false)
    {
        add_action('wp_enqueue_scripts', function () use ($handle, $var_name, $data, $ver, $in_footer) {
            if (!wp_script_is($handle, 'registered') && !wp_script_is($handle, 'enqueued')) {
                wp_register_script($handle, false, [], $ver, $in_footer);
                wp_enqueue_script($handle);
            }

            wp_localize_script($handle, $var_name, $data);
        });
    }

    protected function render($view, $data = [])
    {
        echo App::blade()->make($view, $data)->render();
    }
}
