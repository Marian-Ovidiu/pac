<?php

use Dotenv\Dotenv;

if (!function_exists('vite_manifest')) {
    function vite_manifest() {
        static $manifest = null;

        if ($manifest !== null) {
            return $manifest;
        }

        $manifestPath = get_template_directory() . '/public/.vite/manifest.json';

        if (!file_exists($manifestPath)) {
            error_log('Manifest Vite non trovato: ' . $manifestPath);
            $manifest = [];
            return $manifest;
        }

        $decoded = json_decode((string) file_get_contents($manifestPath), true);

        if (!is_array($decoded)) {
            error_log('Manifest Vite non valido: ' . $manifestPath);
            $manifest = [];
            return $manifest;
        }

        $manifest = $decoded;

        return $manifest;
    }
}

if (!function_exists('vite_entry_key')) {
    function vite_entry_key($path) {
        return 'assets/' . ltrim((string) $path, '/');
    }
}

if (!function_exists('vite_entry')) {
    function vite_entry($path) {
        $manifest = vite_manifest();
        $key = vite_entry_key($path);

        return isset($manifest[$key]) && is_array($manifest[$key])
            ? $manifest[$key]
            : null;
    }
}

if (!function_exists('vite_asset')) {
    function vite_asset($path) {
        $entry = vite_entry($path);

        if (!$entry || empty($entry['file'])) {
            error_log('Asset non trovato nel manifest: ' . vite_entry_key($path));
            return null;
        }

        return get_template_directory_uri() . '/public/' . ltrim((string) $entry['file'], '/');
    }
}

if (!function_exists('vite_asset_css')) {
    function vite_asset_css($path) {
        $entry = vite_entry($path);

        if (!$entry || empty($entry['css']) || !is_array($entry['css'])) {
            return [];
        }

        return array_values(array_map(
            static function ($cssFile) {
                return get_template_directory_uri() . '/public/' . ltrim((string) $cssFile, '/');
            },
            $entry['css']
        ));
    }
}

if (!function_exists('theme_acf_image_url')) {
    /**
     * URL file immagine da valore campo ACF "Immagine" (qualsiasi formato di ritorno).
     * Evita slide hero vuote quando il campo è "URL immagine" o "ID allegato" invece di "Array".
     *
     * @param mixed $image Valore grezzo da get_field / modello.
     */
    function theme_acf_image_url($image) {
        if ($image === null || $image === '' || $image === false) {
            return '';
        }

        if (is_array($image)) {
            if (!empty($image['url']) && is_string($image['url'])) {
                return $image['url'];
            }

            if (!empty($image['ID']) && is_numeric($image['ID']) && function_exists('wp_get_attachment_image_url')) {
                $u = wp_get_attachment_image_url((int) $image['ID'], 'full');

                return $u ? (string) $u : '';
            }
        }

        if (is_numeric($image) && function_exists('wp_get_attachment_image_url')) {
            $u = wp_get_attachment_image_url((int) $image, 'full');

            return $u ? (string) $u : '';
        }

        if (is_string($image)) {
            $t = trim($image);

            if ($t === '') {
                return '';
            }

            if (preg_match('#^https?://#i', $t) || ($t !== '' && $t[0] === '/')) {
                return $t;
            }
        }

        if (is_object($image) && isset($image->ID) && is_numeric($image->ID) && function_exists('wp_get_attachment_image_url')) {
            $u = wp_get_attachment_image_url((int) $image->ID, 'full');

            return $u ? (string) $u : '';
        }

        return '';
    }
}

if (!function_exists('camelToKebab')) {
    function camelToKebab($string) {
        return strtolower(preg_replace('/(?<!^)([A-Z])/', '-$1', $string));
    }
}

if (!function_exists('my_env')) {
    function my_env($key, $default = null) {
        static $envs = null;

        if ($envs === null) {
            $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
            $envs = $dotenv->safeLoad();
        }

        return $envs[$key] ?? $default;
    }
}

if (!function_exists('resource_path')) {
    function resource_path($path = '') {
        return __DIR__ . '/../../resources/' . $path;
    }
}

if (!function_exists('base_path')) {
    function base_path($path = '') {
        return __DIR__ . '/../../' . $path;
    }
}

if (!function_exists('config')) {
    function config($key, $default = null) {
        $configurations = [
            'laravel-translatable-string-exporter' => [
                'lang_path' => 'resources/lang',
                'strings_path' => [
                    resource_path('views'),
                ],
                'string_functions' => ['__', 'trans'],
            ],
        ];
        return $configurations[$key] ?? $default;
    }
}

if (!function_exists('theme_seo_plugin_active')) {
    function theme_seo_plugin_active() {
        return defined('RANK_MATH_VERSION')
            || defined('WPSEO_VERSION')
            || class_exists('RankMath')
            || function_exists('rank_math');
    }
}

if (!function_exists('theme_meta_description_normalize')) {
    /**
     * Plain-text meta description chunk (layout applies esc_attr on output).
     */
    function theme_meta_description_normalize($text, $maxWords = 40) {
        $text = wp_strip_all_tags((string) $text, true);
        $text = trim(preg_replace('/\s+/', ' ', $text));

        if ($text === '') {
            return '';
        }

        return wp_trim_words($text, $maxWords, '...');
    }
}

if (!function_exists('theme_meta_description')) {
    /**
     * Meta description when no SEO plugin outputs one (mainLayout, theme_open_graph_meta, theme_schema_graph).
     * Covers: singular (any), front page, blog index, taxonomy archives, post type archives, author, date; generic fallback.
     */
    function theme_meta_description() {
        if (theme_seo_plugin_active()) {
            return '';
        }

        if (is_singular()) {
            $post = get_queried_object();

            if ($post instanceof WP_Post) {
                $excerpt = has_excerpt($post) ? get_the_excerpt($post) : '';

                if ($excerpt !== '') {
                    return theme_meta_description_normalize($excerpt, 40);
                }

                $content = wp_strip_all_tags(strip_shortcodes((string) $post->post_content), true);
                $content = trim(preg_replace('/\s+/', ' ', $content));

                if ($content !== '') {
                    return theme_meta_description_normalize($content, 30);
                }
            }
        }

        if (is_front_page()) {
            $description = get_bloginfo('description', 'display');

            if (is_string($description) && $description !== '') {
                return theme_meta_description_normalize($description, 40);
            }

            return theme_meta_description_normalize(
                get_bloginfo('name', 'display') . ' – Sito ufficiale PAC - Project Africa Conservation.',
                35
            );
        }

        if (is_home() && !is_front_page()) {
            $postsPageId = (int) get_option('page_for_posts');

            if ($postsPageId > 0) {
                $page = get_post($postsPageId);

                if ($page instanceof WP_Post) {
                    if (has_excerpt($page)) {
                        $ex = theme_meta_description_normalize(get_the_excerpt($page), 40);

                        if ($ex !== '') {
                            return $ex;
                        }
                    }

                    $fromContent = theme_meta_description_normalize(
                        strip_shortcodes((string) $page->post_content),
                        30
                    );

                    if ($fromContent !== '') {
                        return $fromContent;
                    }
                }
            }

            $blogTitle = single_post_title('', false);

            if (is_string($blogTitle) && $blogTitle !== '') {
                return theme_meta_description_normalize(
                    $blogTitle . ' – ' . get_bloginfo('name', 'display'),
                    28
                );
            }
        }

        if (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();

            if ($term instanceof WP_Term) {
                $rawDesc = term_description($term);

                if (is_string($rawDesc) && trim(wp_strip_all_tags($rawDesc)) !== '') {
                    $out = theme_meta_description_normalize($rawDesc, 40);

                    if ($out !== '') {
                        return $out;
                    }
                }

                $name = single_term_title('', false);

                if (is_string($name) && $name !== '') {
                    return theme_meta_description_normalize(
                        $name . ' – ' . get_bloginfo('name', 'display'),
                        28
                    );
                }
            }
        }

        if (is_post_type_archive()) {
            $archiveDesc = get_the_archive_description();

            if (is_string($archiveDesc) && trim(wp_strip_all_tags($archiveDesc)) !== '') {
                $out = theme_meta_description_normalize($archiveDesc, 40);

                if ($out !== '') {
                    return $out;
                }
            }

            $archiveTitle = post_type_archive_title('', false);

            if (is_string($archiveTitle) && $archiveTitle !== '') {
                return theme_meta_description_normalize(
                    $archiveTitle . ' – ' . get_bloginfo('name', 'display'),
                    28
                );
            }
        }

        if (is_author()) {
            $author = get_queried_object();

            if ($author instanceof WP_User) {
                $bio = get_the_author_meta('description', $author->ID);

                if (is_string($bio) && trim(wp_strip_all_tags($bio)) !== '') {
                    $out = theme_meta_description_normalize($bio, 40);

                    if ($out !== '') {
                        return $out;
                    }
                }

                return theme_meta_description_normalize(
                    sprintf(
                        /* translators: %s: author display name */
                        __('Articoli di %s', 'my_structure'),
                        $author->display_name
                    ) . ' – ' . get_bloginfo('name', 'display'),
                    28
                );
            }
        }

        if (is_date()) {
            $archiveDesc = get_the_archive_description();

            if (is_string($archiveDesc) && trim(wp_strip_all_tags($archiveDesc)) !== '') {
                $out = theme_meta_description_normalize($archiveDesc, 40);

                if ($out !== '') {
                    return $out;
                }
            }

            $title = wp_get_document_title();

            if (is_string($title) && $title !== '') {
                return theme_meta_description_normalize($title, 35);
            }
        }

        $description = get_bloginfo('description', 'display');

        if (is_string($description) && $description !== '') {
            return theme_meta_description_normalize($description, 40);
        }

        return theme_meta_description_normalize(
            'Sito ufficiale PAC - Project Africa Conservation.',
            20
        );
    }
}

if (!function_exists('theme_social_urls')) {
    /**
     * URL profili social (footer, JSON-LD sameAs). Override da OpzioniGlobaliFields (campi url_facebook, url_instagram, url_linkedin) se valorizzati e URL validi.
     *
     * @return array{facebook: string, instagram: string, linkedin: string}
     */
    function theme_social_urls() {
        static $cached = null;

        if ($cached !== null) {
            return $cached;
        }

        $defaults = [
            'facebook' => 'https://www.facebook.com/share/15kZKmU4gr/',
            'instagram' => 'https://www.instagram.com/pacitalia?igsh=MWkycW1lZnRmNnAxMA==',
            'linkedin' => 'https://www.linkedin.com/in/project-africa-conservation-a-p-s-b81a95340/',
        ];

        $out = $defaults;

        if (class_exists('\Models\Options\OpzioniGlobaliFields')) {
            $opts = \Models\Options\OpzioniGlobaliFields::get();

            if (is_object($opts)) {
                $map = [
                    'facebook' => 'url_facebook',
                    'instagram' => 'url_instagram',
                    'linkedin' => 'url_linkedin',
                ];

                foreach ($map as $key => $prop) {
                    $val = $opts->{$prop} ?? null;

                    if (!is_string($val)) {
                        continue;
                    }

                    $val = trim($val);

                    if ($val === '') {
                        continue;
                    }

                    $clean = esc_url_raw($val);

                    if ($clean !== '' && function_exists('wp_http_validate_url') && wp_http_validate_url($clean)) {
                        $out[$key] = $clean;
                    }
                }
            }
        }

        $cached = $out;

        return $cached;
    }
}

if (!function_exists('theme_schema_organization_id')) {
    /**
     * Stable @id for Organization JSON-LD (referenced by WebPage.isPartOf).
     */
    function theme_schema_organization_id() {
        return trailingslashit(home_url('/')) . '#organization';
    }
}

if (!function_exists('theme_schema_organization_node')) {
    /**
     * @return array<string, mixed>
     */
    function theme_schema_organization_node() {
        $options = class_exists('\Models\Options\OpzioniGlobaliFields')
            ? \Models\Options\OpzioniGlobaliFields::get()
            : null;

        $logo = is_object($options) && !empty($options->logo['url'])
            ? $options->logo['url']
            : null;

        $social = theme_social_urls();
        $sameAs = array_values(array_filter([
            $social['facebook'] ?? '',
            $social['instagram'] ?? '',
            $social['linkedin'] ?? '',
        ]));

        $node = [
            '@type' => 'Organization',
            '@id' => theme_schema_organization_id(),
            'name' => get_bloginfo('name', 'display'),
            'url' => trailingslashit(home_url('/')),
        ];

        if ($logo) {
            $node['logo'] = $logo;
        }

        if (!empty($sameAs)) {
            $node['sameAs'] = $sameAs;
        }

        return $node;
    }
}

if (!function_exists('theme_canonical_url')) {
    /**
     * Canonical URL when no SEO plugin outputs one (for rel="canonical" and JSON-LD).
     */
    function theme_canonical_url() {
        if (is_front_page()) {
            return trailingslashit(home_url('/'));
        }

        if (is_home() && !is_front_page()) {
            $postsPageId = (int) get_option('page_for_posts');

            if ($postsPageId > 0) {
                $url = get_permalink($postsPageId);

                return is_string($url) && $url !== '' ? $url : null;
            }

            return trailingslashit(home_url('/'));
        }

        if (is_singular()) {
            $post = get_queried_object();

            if ($post instanceof WP_Post) {
                if (function_exists('wp_get_canonical_url')) {
                    $canonical = wp_get_canonical_url($post);

                    if (is_string($canonical) && $canonical !== '') {
                        return $canonical;
                    }
                }

                $url = get_permalink($post);

                return is_string($url) && $url !== '' ? $url : null;
            }
        }

        if (is_category() || is_tag() || is_tax()) {
            $term = get_queried_object();

            if ($term instanceof WP_Term) {
                $link = get_term_link($term);

                return !is_wp_error($link) && is_string($link) ? $link : null;
            }
        }

        if (is_post_type_archive()) {
            $postType = get_query_var('post_type');

            if (is_array($postType)) {
                $postType = reset($postType);
            }

            if (!is_string($postType) || $postType === '') {
                $postType = get_post_type();
            }

            if (!is_string($postType) || $postType === '') {
                return null;
            }

            $link = get_post_type_archive_link($postType);

            return is_string($link) && $link !== '' ? $link : null;
        }

        if (is_author()) {
            $author = get_queried_object();

            if ($author instanceof WP_User) {
                return get_author_posts_url((int) $author->ID);
            }
        }

        if (is_date()) {
            $year = get_query_var('year');
            $monthnum = get_query_var('monthnum');
            $day = get_query_var('day');

            if ($year) {
                if ($monthnum && $day) {
                    return get_day_link((int) $year, (int) $monthnum, (int) $day);
                }

                if ($monthnum) {
                    return get_month_link((int) $year, (int) $monthnum);
                }

                return get_year_link((int) $year);
            }
        }

        return null;
    }
}

if (!function_exists('theme_open_graph_meta')) {
    /**
     * Open Graph meta values when no SEO plugin is active (output as property/content in layout).
     *
     * @return array<string, string>
     */
    function theme_open_graph_meta() {
        $canonical = theme_canonical_url();
        $description = theme_meta_description();
        $title = function_exists('wp_get_document_title') ? wp_get_document_title() : wp_title('|', false, 'right');

        $type = 'website';

        if (is_singular('post')) {
            $type = 'article';
        }

        $options = class_exists('\Models\Options\OpzioniGlobaliFields')
            ? \Models\Options\OpzioniGlobaliFields::get()
            : null;

        $image = is_object($options) && !empty($options->logo['url'])
            ? (string) $options->logo['url']
            : '';

        $out = [
            'og:title' => is_string($title) ? $title : '',
            'og:description' => is_string($description) ? $description : '',
            'og:type' => $type,
            'og:site_name' => get_bloginfo('name', 'display'),
        ];

        if (is_string($canonical) && $canonical !== '') {
            $out['og:url'] = $canonical;
        }

        if ($image !== '') {
            $out['og:image'] = $image;
        }

        return $out;
    }
}

if (!function_exists('theme_schema_graph')) {
    /**
     * JSON-LD @graph when no SEO plugin: Organization on all pages; WebPage on non-front pages.
     *
     * @return array<string, mixed>
     */
    function theme_schema_graph() {
        $organization = theme_schema_organization_node();

        if (is_front_page()) {
            return [
                '@context' => 'https://schema.org',
                '@graph' => [$organization],
            ];
        }

        $canonical = theme_canonical_url();

        if (!is_string($canonical) || $canonical === '') {
            return [
                '@context' => 'https://schema.org',
                '@graph' => [$organization],
            ];
        }

        $docTitle = function_exists('wp_get_document_title') ? wp_get_document_title() : '';

        $webPage = [
            '@type' => 'WebPage',
            '@id' => trailingslashit($canonical) . '#webpage',
            'url' => $canonical,
            'name' => is_string($docTitle) && $docTitle !== '' ? $docTitle : get_bloginfo('name', 'display'),
            'isPartOf' => [
                '@id' => theme_schema_organization_id(),
            ],
        ];

        $metaDesc = theme_meta_description();

        if (is_string($metaDesc) && $metaDesc !== '') {
            $webPage['description'] = $metaDesc;
        }

        return [
            '@context' => 'https://schema.org',
            '@graph' => [$organization, $webPage],
        ];
    }
}
