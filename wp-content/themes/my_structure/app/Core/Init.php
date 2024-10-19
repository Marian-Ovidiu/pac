<?php

namespace Core;

abstract class Init extends Singleton
{
    protected static $blade;
    abstract protected function registerHook();

    public function init()
    {
        $this->setup();
    }

    protected function setup()
    {
        $this->initBlade();
        $this->registerHook();
    }

    protected function initBlade()
    {
        $viewPaths = [get_template_directory() . '/resources/views'];
        $cachePath = get_template_directory() . '/resources/cache';

        $bladeManager = new BladeManager($viewPaths, $cachePath);

        static::$blade = $bladeManager->getBlade();
    }

    public static function blade()
    {
        return static::$blade;
    }

    public static function config($key)
    {
        echo "Configuration loaded for key: $key.";
    }
}
