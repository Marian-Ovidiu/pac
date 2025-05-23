<?php

if (! function_exists('disable_woocommerce_features')) {
    function disable_woocommerce_features()
    {
        // Disattiva funzionalità WooCommerce
        remove_post_type_support('product', 'title');
        remove_post_type_support('product', 'editor');
        unregister_post_type('product');
        remove_action('woocommerce_after_register_post_type', 'woocommerce_register_taxonomy');

        // Carica le traduzioni solo se il file esiste
        $mo = get_template_directory() . '/languages/woocommerce-gateway-stripe-it_IT.mo';
        if (file_exists($mo)) {
            load_textdomain('woocommerce-gateway-stripe', $mo);
        }
    }
}


if (! function_exists('custom_load_textdomain')) {
    function custom_load_textdomain()
    {
        load_textdomain(
            'woocommerce-gateway-stripe',
            get_template_directory() . '/languages/woocommerce-gateway-stripe-it_IT.mo'
        );
    }
}

if (! function_exists('disable_woocommerce_pages')) {
    function disable_woocommerce_pages($page_id, $page)
    {
        if (in_array($page, ['shop', 'cart', 'checkout', 'myaccount'])) {
            return false;
        }
        return $page_id;
    }
}

if (! function_exists('disable_woocommerce_assets')) {
    function disable_woocommerce_assets()
    {
        if (! class_exists('WooCommerce') || ! function_exists('is_woocommerce')) {
            return;
        }

        add_action('wp_enqueue_scripts', function () {
            if (! is_woocommerce() && ! is_cart() && ! is_checkout()) {
                require_once get_template_directory() . '/app/Helpers/woocommerce_helpers.php';

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
if (! function_exists('tp_redirect')) {
    function tp_redirect()
    {
        if (
            function_exists('is_cart') &&
            function_exists('is_checkout') &&
            (is_cart() || is_checkout())
        ) {
            return;
        }
        remove_all_actions('woocommerce_init');
        remove_all_actions('woocommerce_loaded');
        remove_all_filters('template_include');
    }
}
