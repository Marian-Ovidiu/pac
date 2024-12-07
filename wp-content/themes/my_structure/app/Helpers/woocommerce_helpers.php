<?php

if (!function_exists('disable_woocommerce_features')) {
    function disable_woocommerce_features() {
        remove_post_type_support('product', 'title');
        remove_post_type_support('product', 'editor');
        unregister_post_type('product');
        remove_action('woocommerce_after_register_post_type', 'woocommerce_register_taxonomy');
        load_textdomain('woocommerce-gateway-stripe', '/path/to/language/file');
    }
}

if (!function_exists('disable_woocommerce_pages')) {
    function disable_woocommerce_pages($page_id, $page) {
        if (in_array($page, ['shop', 'cart', 'checkout', 'myaccount'])) {
            return false;
        }
        return $page_id;
    }
}

if (!function_exists('disable_woocommerce_assets')) {
    function disable_woocommerce_assets() {
        add_action('wp_enqueue_scripts', function() {
            if (!is_woocommerce() && !is_cart() && !is_checkout()) {
                wp_dequeue_style('woocommerce-layout');
                wp_dequeue_style('woocommerce-general');
                wp_dequeue_style('woocommerce-smallscreen');

                wp_dequeue_script('wc-cart-fragments');
                wp_dequeue_script('woocommerce');
                wp_dequeue_script('wc-checkout');
                wp_dequeue_script('wc-add-to-cart');
            }
        }, 99);
    }
}
