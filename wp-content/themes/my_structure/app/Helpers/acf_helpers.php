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

if (!function_exists('pac_register_project_story_fields')) {
    /**
     * Campi riusabili per i progetti umanitari con una narrazione estesa.
     *
     * Il gruppo vive nel codice così struttura e template arrivano insieme in
     * ogni ambiente; i contenuti continuano a essere gestiti dal database ACF.
     */
    function pac_register_project_story_fields()
    {
        if (!function_exists('acf_add_local_field_group')) {
            return;
        }

        acf_add_local_field_group([
            'key' => 'group_pac_project_story',
            'title' => 'Struttura progetto umanitario',
            'fields' => [
                [
                    'key' => 'field_pac_project_template',
                    'label' => 'Presentazione',
                    'name' => 'pac_project_template',
                    'type' => 'select',
                    'choices' => [
                        'standard' => 'Standard',
                        'flagship' => 'Progetto prioritario',
                    ],
                    'default_value' => 'standard',
                    'return_format' => 'value',
                    'ui' => 1,
                ],
                [
                    'key' => 'field_pac_project_location',
                    'label' => 'Località',
                    'name' => 'pac_project_location',
                    'type' => 'text',
                    'instructions' => 'Inserire soltanto una località verificata.',
                ],
                [
                    'key' => 'field_pac_project_visual_theme',
                    'label' => 'Fallback visuale',
                    'name' => 'pac_project_visual_theme',
                    'type' => 'select',
                    'choices' => [
                        '' => 'Nessuno',
                        'education' => 'Istruzione',
                        'community' => 'Comunità',
                        'conservation' => 'Conservazione',
                        'k9' => 'Unità K-9',
                    ],
                    'default_value' => '',
                    'return_format' => 'value',
                    'ui' => 1,
                ],
                [
                    'key' => 'field_pac_project_intro',
                    'label' => 'Introduzione',
                    'name' => 'pac_project_intro',
                    'type' => 'wysiwyg',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ],
                [
                    'key' => 'field_pac_project_why_title',
                    'label' => 'Titolo: perché è importante',
                    'name' => 'pac_project_why_title',
                    'type' => 'text',
                ],
                [
                    'key' => 'field_pac_project_why_text',
                    'label' => 'Perché è importante',
                    'name' => 'pac_project_why_text',
                    'type' => 'wysiwyg',
                    'tabs' => 'visual',
                    'toolbar' => 'basic',
                    'media_upload' => 0,
                    'delay' => 0,
                ],
                [
                    'key' => 'field_pac_project_objectives',
                    'label' => 'Obiettivi del progetto',
                    'name' => 'pac_project_objectives',
                    'type' => 'repeater',
                    'layout' => 'row',
                    'button_label' => 'Aggiungi obiettivo',
                    'sub_fields' => [
                        [
                            'key' => 'field_pac_project_objective_title',
                            'label' => 'Obiettivo',
                            'name' => 'title',
                            'type' => 'text',
                            'required' => 1,
                        ],
                        [
                            'key' => 'field_pac_project_objective_text',
                            'label' => 'Descrizione',
                            'name' => 'text',
                            'type' => 'textarea',
                            'rows' => 3,
                            'new_lines' => 'wpautop',
                        ],
                    ],
                ],
                [
                    'key' => 'field_pac_fundraising_status',
                    'label' => 'Stato della raccolta',
                    'name' => 'pac_fundraising_status',
                    'type' => 'select',
                    'choices' => [
                        'planning' => 'Budget in definizione',
                        'fundraising' => 'Raccolta attiva',
                        'in_progress' => 'Lavori in corso',
                        'completed' => 'Progetto completato',
                    ],
                    'default_value' => 'planning',
                    'return_format' => 'value',
                    'ui' => 1,
                ],
                [
                    'key' => 'field_pac_fundraising_target',
                    'label' => 'Obiettivo economico',
                    'name' => 'pac_fundraising_target',
                    'type' => 'number',
                    'instructions' => 'Lasciare vuoto finché il budget non è verificato.',
                    'min' => 0,
                    'step' => 0.01,
                ],
                [
                    'key' => 'field_pac_fundraising_raised',
                    'label' => 'Importo raccolto',
                    'name' => 'pac_fundraising_raised',
                    'type' => 'number',
                    'instructions' => 'Aggiornare soltanto con un dato verificato.',
                    'min' => 0,
                    'step' => 0.01,
                ],
                [
                    'key' => 'field_pac_fundraising_currency',
                    'label' => 'Valuta',
                    'name' => 'pac_fundraising_currency',
                    'type' => 'select',
                    'choices' => [
                        'EUR' => 'EUR',
                        'GHS' => 'GHS',
                        'USD' => 'USD',
                    ],
                    'default_value' => 'EUR',
                    'return_format' => 'value',
                ],
                [
                    'key' => 'field_pac_fundraising_note',
                    'label' => 'Nota di trasparenza',
                    'name' => 'pac_fundraising_note',
                    'type' => 'textarea',
                    'rows' => 3,
                    'new_lines' => 'wpautop',
                ],
                [
                    'key' => 'field_pac_expected_impact',
                    'label' => 'Impatto atteso',
                    'name' => 'pac_expected_impact',
                    'type' => 'repeater',
                    'layout' => 'row',
                    'button_label' => 'Aggiungi impatto atteso',
                    'sub_fields' => [
                        [
                            'key' => 'field_pac_expected_impact_title',
                            'label' => 'Risultato atteso',
                            'name' => 'title',
                            'type' => 'text',
                            'required' => 1,
                        ],
                        [
                            'key' => 'field_pac_expected_impact_text',
                            'label' => 'Descrizione',
                            'name' => 'text',
                            'type' => 'textarea',
                            'rows' => 3,
                            'new_lines' => 'wpautop',
                        ],
                    ],
                ],
                [
                    'key' => 'field_pac_project_updates',
                    'label' => 'Aggiornamenti collegati',
                    'name' => 'pac_project_updates',
                    'type' => 'relationship',
                    'post_type' => ['post'],
                    'post_status' => ['publish'],
                    'filters' => ['search'],
                    'return_format' => 'id',
                ],
            ],
            'location' => [
                [
                    [
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'progetto',
                    ],
                ],
            ],
            'menu_order' => 20,
            'position' => 'normal',
            'style' => 'default',
            'label_placement' => 'top',
            'instruction_placement' => 'label',
            'active' => true,
            'show_in_rest' => 0,
        ]);
    }
}

add_action('acf/init', 'pac_register_project_story_fields');
