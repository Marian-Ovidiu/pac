<?php
namespace Core;

class App extends Init
{
    public function init()
    {
        parent::init();
        $this->registerHook();
        $this->registerFilters();
        $this->registerProviders();
    }

    public function registerHook()
    {
        add_action('after_setup_theme', 'my_theme_setup');
        add_action('widgets_init', 'register_my_widgets');
        add_action('admin_menu', 'my_custom_options_page');
        add_action('admin_head', 'acf_form_head');
    }

    public function registerFilters()
    {
        add_filter('acf/location/rule_types', 'acf_location_rules_types');
        add_filter('acf/location/rule_values/page', 'acf_location_rule_values_page');
        add_filter('acf/location/rule_match/page', 'my_acf_location_options_page', 10, 3);
        add_filter('wpseo_sitemap_entry', 'exclude_page_from_sitemap', 10, 3);
    }

    public function registerProviders()
    {
        $providers = require get_template_directory() . '/app/Config/providers.php';
        foreach ($providers as $provider) {
            if (class_exists($provider)) {
                (new $provider())->register();
            }
        }
    }
}
