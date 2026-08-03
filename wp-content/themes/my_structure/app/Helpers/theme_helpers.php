<?php

if (!function_exists('my_theme_setup')) {
    function my_theme_setup() {
        // SEO / markup: immagine in evidenza (Rank Math e social usano spesso la featured image); markup HTML5 pulito.
        add_theme_support('post-thumbnails');
        add_theme_support('html5', [
            'search-form',
            'comment-form',
            'comment-list',
            'gallery',
            'caption',
            'style',
            'script',
        ]);

        add_base_js();
        add_base_css();
        register_menus();
    }
}

if (!function_exists('add_base_css')) {
    function add_base_css() {
        add_action('wp_enqueue_scripts', function() {
            $fullSrcStyle = vite_asset('scss/style.scss');
            if ($fullSrcStyle) {
                wp_enqueue_style('theme-style', $fullSrcStyle, [], null);
            }
        });
    }
}

if (!function_exists('add_base_js')) {
    function add_base_js() {
        add_action('wp_enqueue_scripts', function () {
            $fullSrc = vite_asset('js/main.js');
            $publishableKey = function_exists('pac_core_publishable_key')
                ? pac_core_publishable_key()
                : '';

            if (!$fullSrc) {
                return;
            }

            $cssAssets = function_exists('vite_asset_css') ? vite_asset_css('js/main.js') : [];

            foreach ($cssAssets as $index => $cssUrl) {
                $styleHandle = sprintf('main-css-%d', $index);
                if (!wp_style_is($styleHandle, 'enqueued')) {
                    wp_enqueue_style($styleHandle, $cssUrl, [], null);
                }
            }

            wp_enqueue_script('main', $fullSrc, ['jquery'], null, true);
            wp_localize_script('main', 'pacPayments', [
                'ajaxUrl'        => admin_url('admin-ajax.php'),
                'nonce'          => wp_create_nonce('pac_stripe_donation'),
                'publishableKey' => $publishableKey,
                'actions'        => [
                    'createIntent' => 'pac_create_payment_intent',
                    'complete'     => 'pac_complete_donation',
                ],
            ]);
            wp_script_add_data('main', 'data-iub-consent', 'necessary');
        });
    }
}

/**
 * Bundle Vite = ES modules (import/export). WordPress aggiunge <script> senza type → errore "Cannot use import statement outside a module".
 */
if (!function_exists('pac_vite_script_type_module')) {
    function pac_vite_script_type_module($tag, $handle, $src) {
        $vite_module_handles = ['main', 'home-slider', 'progetto-slider'];

        if (!in_array($handle, $vite_module_handles, true)) {
            return $tag;
        }

        if (strpos($tag, 'type="module"') !== false) {
            return $tag;
        }

        return (string) preg_replace('/<script\b/', '<script type="module"', $tag, 1);
    }
}

add_filter('script_loader_tag', 'pac_vite_script_type_module', 10, 3);

if (!function_exists('register_my_widgets')) {
    function register_my_widgets() {
        register_widget('Widget\MenuWidget');
    }
}

if (!function_exists('register_menus')) {
    function register_menus()
    {
        add_theme_support('menus');
        $menus = include get_template_directory() . '/app/Config/menus.php';
        register_nav_menus($menus);
    }
}


if (!function_exists('exclude_page_from_sitemap')) {
    function exclude_page_from_sitemap($url, $type, $object)
    {
        switch ($type){
            case 'category':
            case 'author':
                return false;
            default:
                return $url;
        }
    }
}

/**
 * Archivi blog category / post_tag non usati in front: 404 + esclusione dalle sitemap.
 * Le tassonomie restano in admin (assegna categorie ai post se serve); spariscono solo le URL di archivio.
 */
if (!function_exists('pac_disable_category_tag_archives')) {
    function pac_disable_category_tag_archives() {
        if (is_admin() || wp_doing_ajax() || wp_doing_cron()) {
            return;
        }

        if (is_category() || is_tag()) {
            global $wp_query;
            $wp_query->set_404();
            status_header(404);
            nocache_headers();
        }
    }
}

add_action('template_redirect', 'pac_disable_category_tag_archives', 2);

add_filter('wp_sitemaps_taxonomies', static function ($taxonomies) {
    if (!is_array($taxonomies)) {
        return $taxonomies;
    }
    unset($taxonomies['category'], $taxonomies['post_tag']);

    return $taxonomies;
}, 10, 1);

add_filter('rank_math/sitemap/exclude_taxonomy', static function ($exclude, $type) {
    if (in_array((string) $type, ['category', 'post_tag'], true)) {
        return true;
    }

    return $exclude;
}, 10, 2);

if (!function_exists('pac_localized_document_title')) {
    function pac_localized_document_title($title) {
        if (is_404()) {
            return 'Pagina non trovata — PAC';
        }

        if (is_search()) {
            $query = get_search_query();
            return $query !== ''
                ? sprintf('Risultati per “%s” — PAC', $query)
                : 'Ricerca — PAC';
        }

        return $title;
    }
}

add_filter('pre_get_document_title', 'pac_localized_document_title', 20);
add_filter('rank_math/frontend/title', 'pac_localized_document_title', 20);

if (!function_exists('pac_label_company_contact_form')) {
    function pac_label_company_contact_form($content) {
        if (!is_page_template('template-aziende.php')) {
            return $content;
        }

        foreach (['your-name', 'your-email', 'tel-212', 'rs', 'your-message'] as $fieldName) {
            $pattern = sprintf(
                '/<(input|textarea)(?![^>]*\bid=)([^>]*\bname=["\']%s["\'])/i',
                preg_quote($fieldName, '/')
            );
            $content = preg_replace($pattern, '<$1 id="' . $fieldName . '"$2', $content, 1);
        }

        return $content;
    }
}

add_filter('wpcf7_form_elements', 'pac_label_company_contact_form', 20);

add_filter('wpcf7_form_additional_atts', static function ($attributes) {
    if (is_page_template('template-aziende.php')) {
        $attributes['aria-label'] = 'Modulo contatto aziende';
    }

    return $attributes;
}, 20);

/**
 * SEO strutturato — vedi docs/priority-8-seo-hardening.md.
 *
 * Rank Math non emette le entità globali sui contenuti singoli quando il tipo di
 * schema di default del post type è disattivato, e nessun contenuto esistente ha
 * postmeta rank_math_schema_*. Il risultato è che progetti e articoli espongono
 * soltanto BreadcrumbList. Questi filtri riportano il grafo a uno stato corretto
 * senza toccare le opzioni del plugin, che vivono nel database e non sono
 * deployabili.
 */

if (!function_exists('pac_schema_force_global_entities')) {
    function pac_schema_force_global_entities($canAdd) {
        return is_singular(['progetto', 'post']) ? true : $canAdd;
    }
}

add_filter('rank_math/schema/add_global_entities', 'pac_schema_force_global_entities', 20);

if (!function_exists('pac_schema_donation_url')) {
    /**
     * Il form di donazione vive sulle pagine progetto: la DonateAction punta lì,
     * non a una pagina di donazione dedicata che non esiste.
     */
    function pac_schema_donation_url() {
        if (is_singular('progetto')) {
            return (string) get_permalink();
        }

        $projects = get_posts([
            'post_type' => 'progetto',
            'numberposts' => 1,
            'post_status' => 'publish',
            'fields' => 'ids',
        ]);

        return $projects ? (string) get_permalink($projects[0]) : '';
    }
}

if (!function_exists('pac_schema_adjust_json_ld')) {
    function pac_schema_adjust_json_ld($data, $jsonld = null) {
        if (!is_array($data)) {
            return $data;
        }

        // PAC è una no profit: NGO invece di Organization generica.
        if (isset($data['publisher']['@type'])) {
            $types = (array) $data['publisher']['@type'];
            if (in_array('Organization', $types, true)) {
                $data['publisher']['@type'] = 'NGO';
            }
        }

        if (!is_singular()) {
            return $data;
        }

        $post = get_queried_object();
        if (!$post instanceof WP_Post) {
            return $data;
        }

        $publisherId = $data['publisher']['@id'] ?? '';
        $permalink = (string) get_permalink($post);

        // Articoli del Diario: BlogPosting completo.
        if ($post->post_type === 'post') {
            $article = [
                '@type' => 'BlogPosting',
                '@id' => $permalink . '#blogposting',
                'headline' => wp_strip_all_tags(get_the_title($post)),
                'url' => $permalink,
                'datePublished' => get_the_date('c', $post),
                'dateModified' => get_the_modified_date('c', $post),
                'inLanguage' => get_bloginfo('language'),
                'mainEntityOfPage' => ['@id' => $permalink . '#webpage'],
            ];

            $authorName = get_the_author_meta('display_name', (int) $post->post_author);
            if ($authorName !== '') {
                $article['author'] = ['@type' => 'Person', 'name' => $authorName];
            }

            if ($publisherId !== '') {
                $article['publisher'] = ['@id' => $publisherId];
            }

            $thumbnail = get_the_post_thumbnail_url($post, 'full');
            if (is_string($thumbnail) && $thumbnail !== '') {
                $article['image'] = $thumbnail;
            }

            $data['pacBlogPosting'] = $article;
        }

        // Pagine progetto: la donazione è l'azione primaria della pagina.
        if ($post->post_type === 'progetto') {
            $donation = [
                '@type' => 'DonateAction',
                '@id' => $permalink . '#donateaction',
                'name' => sprintf('Sostieni %s', wp_strip_all_tags(get_the_title($post))),
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => $permalink,
                    'actionPlatform' => 'http://schema.org/DesktopWebPlatform',
                ],
            ];

            if ($publisherId !== '') {
                $donation['recipient'] = ['@id' => $publisherId];
            }

            $data['pacDonateAction'] = $donation;
        }

        return $data;
    }
}

add_filter('rank_math/json_ld', 'pac_schema_adjust_json_ld', 99, 2);

if (!function_exists('pac_seo_trim_description')) {
    /**
     * Le description sono generate da %excerpt% e vengono tagliate a lunghezza
     * fissa, quindi finiscono a metà parola. Qui il taglio avviene alla fine di
     * una frase, o almeno di una parola.
     */
    function pac_seo_trim_description($description) {
        $description = trim(preg_replace('/\s+/u', ' ', wp_strip_all_tags((string) $description)));
        $limit = 155;

        // Un excerpt che introduce un elenco lascia due punti penzolanti in SERP.
        $description = rtrim($description, " ,;:–-");

        if ($description === '' || mb_strlen($description) <= $limit) {
            return $description;
        }

        $window = mb_substr($description, 0, $limit);

        // Preferisci la fine di frase, se cade in una porzione utile del testo.
        if (preg_match_all('/[.!?](?=\s|$)/u', $window, $matches, PREG_OFFSET_CAPTURE)) {
            $lastSentence = end($matches[0]);
            $offset = mb_strlen(substr($window, 0, $lastSentence[1])) + 1;
            if ($offset >= 80) {
                return trim(mb_substr($description, 0, $offset));
            }
        }

        $lastSpace = mb_strrpos($window, ' ');
        $cut = $lastSpace !== false && $lastSpace >= 60 ? $lastSpace : $limit;

        return rtrim(trim(mb_substr($description, 0, $cut)), " ,;:–-") . '…';
    }
}

add_filter('rank_math/frontend/description', 'pac_seo_trim_description', 20);
