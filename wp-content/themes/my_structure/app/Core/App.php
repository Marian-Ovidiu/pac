<?php

namespace Core;

class App extends Init
{
    public function init()
    {
        parent::init();
        $this->registerHook();
        $this->registerProviders();
    }

    public function registerHook()
    {
        add_action('after_setup_theme', 'my_theme_setup');
    }

    public function registerProviders()
    {
        $providers = require get_template_directory() . '/app/Config/providers.php';
        foreach ($providers as $provider) {
            if(class_exists($provider)){
                $provider = new $provider();
                $provider->register();
            }
        }
    }
}