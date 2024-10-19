<?php

namespace Widget;
use WP_Widget;
use Core\App;
class MenuWidget extends WP_Widget
{
    public function __construct() {
        parent::__construct(
            'menu_widget',
            __('Menu Widget', 'text_domain'),
            ['description' => __('A widget to display a menu', 'text_domain')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        $menu_name = !empty($instance['menu_name']) ? $instance['menu_name'] : 'header-menu';
        $menu_items = wp_get_nav_menu_items($menu_name);
        if (!is_array($menu_items)) {
            $menu_items = [];
        }

        echo App::blade('view')->make('partials.'.camelToKebab($menu_name), ['menu' => $menu_items])->render();
        echo $args['after_widget'];
    }
}