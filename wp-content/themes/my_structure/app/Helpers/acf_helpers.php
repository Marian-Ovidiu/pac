<?php

use Core\App;

if (!function_exists('my_custom_options_page')) {
    function my_custom_options_page()
    {
        add_menu_page(
            'Impostazioni Generali',
            'Opzioni Generali',
            'manage_options',
            'opzioni-generali',
            function (){
                my_custom_options_page_html('generali');
            }
        );
        add_menu_page(
            'Opzioni Archvio Progetto',
            'Opzioni Archvio Progetto',
            'manage_options',
            'opzioni-archivio-progetto',
            function (){
                my_custom_options_page_html('archive-progetto');
            }
        );
    }
}

if (!function_exists('my_custom_options_page_html')) {
    function my_custom_options_page_html($page)
    {
        if (!current_user_can('manage_options')) {
            return;
        }

        switch ($page) {
            case 'generali':
                echo App::blade()->make('optionPages.generals', [])->render();
                break;
            case 'archive-progetto':
                echo App::blade()->make('optionPages.archivioOpzioniProgetto', [])->render();
                break;
        }
    }
}

if (!function_exists('acf_location_rules_types')) {
    function acf_location_rules_types( $choices ) {
        $choices['Basic']['page'] = 'Pagina Opzioni';
        return $choices;
    }
}

if (!function_exists('acf_location_rule_values_page')) {
    function acf_location_rule_values_page( $choices ) {
        $choices['opzioni-generali'] = 'Opzioni Generali';
        $choices['opzioni-archivio-progetto'] = 'Opzioni Archivio Progetto';
        return $choices;
    }
}

if (!function_exists('my_acf_location_options_page')) {
    function my_acf_location_options_page($match, $rule, $options) {
        $page = isset($_GET['page'])
            ? sanitize_key(wp_unslash($_GET['page']))
            : '';

        if ($page !== '') {
            switch ($page) {
                case 'opzioni-generali':
                case 'opzioni-archivio-progetto':
                    $match = true;
                    break;
                default:
                    break;
            }
        }
        return $match;
    }
}
