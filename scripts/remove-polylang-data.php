<?php

declare(strict_types=1);

if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}

$apply = in_array('--apply', $argv, true);

$_SERVER['HTTP_HOST'] ??= 'localhost';
$_SERVER['REQUEST_URI'] ??= '/';

require dirname(__DIR__) . '/wp-load.php';

global $wpdb;

$foreignLanguages = ['en', 'fr', 'de'];
$translatedPostTypes = ['page', 'progetto'];
$legacySlugs = [
    'homepage-english', 'homepage-francais', 'homepage-deutsch',
    'projects', 'projets', 'projekte',
    'companies-english', 'entreprises-francais', 'unternehmen-deutsch',
    'galleria-english', 'galerie-francais', 'galerie-deutsch',
    'thank-you', 'merci', 'danke',
    'anti-poaching', 'anti-braconnage', 'anti-wilderei',
    'k-9-dogs', 'chiens-k-9', 'k-9-hunde',
    'social-ghana', 'social-fr-ghana', 'sozial-ghana',
    'social-nigeria', 'nigeria-social', 'soziales-nigeria',
];

$languageMarks = implode(',', array_fill(0, count($foreignLanguages), '%s'));
$postTypeMarks = implode(',', array_fill(0, count($translatedPostTypes), '%s'));
$slugMarks = implode(',', array_fill(0, count($legacySlugs), '%s'));
$queryArgs = [...$translatedPostTypes, ...$foreignLanguages, ...$legacySlugs];

$contentRows = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT p.ID, p.post_type, p.post_status, p.post_name, p.post_title,
                MAX(CASE WHEN lang.slug IN ({$languageMarks}) THEN lang.slug ELSE '' END) AS language
         FROM {$wpdb->posts} p
         LEFT JOIN {$wpdb->term_relationships} tr ON tr.object_id = p.ID
         LEFT JOIN {$wpdb->term_taxonomy} tt
                ON tt.term_taxonomy_id = tr.term_taxonomy_id
               AND tt.taxonomy = 'language'
         LEFT JOIN {$wpdb->terms} lang ON lang.term_id = tt.term_id
         WHERE p.post_type IN ({$postTypeMarks})
           AND (lang.slug IN ({$languageMarks}) OR p.post_name IN ({$slugMarks}))
         GROUP BY p.ID, p.post_type, p.post_status, p.post_name, p.post_title
         ORDER BY p.post_type, p.ID",
        ...$foreignLanguages,
        ...$queryArgs
    ),
    ARRAY_A
);

$languageSwitchers = $wpdb->get_col(
    "SELECT DISTINCT post_id FROM {$wpdb->postmeta} WHERE meta_key = '_pll_menu_item'"
);
$legacyStringPosts = $wpdb->get_col(
    "SELECT ID FROM {$wpdb->posts} WHERE post_type = 'polylang_mo'"
);
$polylangTaxonomies = ['language', 'term_language', 'post_translations', 'term_translations'];
$taxonomyMarks = implode(',', array_fill(0, count($polylangTaxonomies), '%s'));
$taxonomyRows = $wpdb->get_results(
    $wpdb->prepare(
        "SELECT term_taxonomy_id, term_id, taxonomy
         FROM {$wpdb->term_taxonomy}
         WHERE taxonomy IN ({$taxonomyMarks})",
        ...$polylangTaxonomies
    ),
    ARRAY_A
);
$polylangOptionPatterns = [
    $wpdb->esc_like('polylang') . '%',
    $wpdb->esc_like('pll_') . '%',
    $wpdb->esc_like('_transient_pll_') . '%',
    $wpdb->esc_like('_transient_timeout_pll_') . '%',
];
$optionNames = $wpdb->get_col(
    $wpdb->prepare(
        "SELECT option_name
         FROM {$wpdb->options}
         WHERE option_name LIKE %s
            OR option_name LIKE %s
            OR option_name LIKE %s
            OR option_name LIKE %s",
        ...$polylangOptionPatterns
    )
);

echo 'mode=' . ($apply ? 'apply' : 'dry-run') . PHP_EOL;
echo 'translated_content=' . count($contentRows) . PHP_EOL;
foreach ($contentRows as $row) {
    echo sprintf(
        "content %d | %s | %s | %s | %s | %s\n",
        (int) $row['ID'],
        $row['language'] !== '' ? $row['language'] : 'legacy-slug',
        $row['post_type'],
        $row['post_status'],
        $row['post_name'],
        $row['post_title']
    );
}
echo 'language_switchers=' . count($languageSwitchers) . PHP_EOL;
echo 'legacy_string_posts=' . count($legacyStringPosts) . PHP_EOL;
echo 'polylang_taxonomy_rows=' . count($taxonomyRows) . PHP_EOL;
echo 'polylang_options=' . count($optionNames) . PHP_EOL;

if (!$apply) {
    echo "Dry run only. Re-run with --apply after taking a database backup.\n";
    exit(0);
}

$wpdb->query('START TRANSACTION');

try {
    $deletedContent = 0;
    foreach ($contentRows as $row) {
        if (wp_delete_post((int) $row['ID'], true) !== false) {
            $deletedContent++;
        }
    }

    $deletedSwitchers = 0;
    foreach ($languageSwitchers as $postId) {
        if (wp_delete_post((int) $postId, true) !== false) {
            $deletedSwitchers++;
        }
    }

    $deletedStringPosts = 0;
    foreach ($legacyStringPosts as $postId) {
        if (wp_delete_post((int) $postId, true) !== false) {
            $deletedStringPosts++;
        }
    }

    $termTaxonomyIds = array_values(array_unique(array_map(
        static fn (array $row): int => (int) $row['term_taxonomy_id'],
        $taxonomyRows
    )));
    $termIds = array_values(array_unique(array_map(
        static fn (array $row): int => (int) $row['term_id'],
        $taxonomyRows
    )));

    if ($termTaxonomyIds !== []) {
        $idMarks = implode(',', array_fill(0, count($termTaxonomyIds), '%d'));
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->term_relationships} WHERE term_taxonomy_id IN ({$idMarks})",
            ...$termTaxonomyIds
        ));
        $wpdb->query($wpdb->prepare(
            "DELETE FROM {$wpdb->term_taxonomy} WHERE term_taxonomy_id IN ({$idMarks})",
            ...$termTaxonomyIds
        ));
    }

    foreach ($termIds as $termId) {
        $stillUsed = (int) $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM {$wpdb->term_taxonomy} WHERE term_id = %d",
            $termId
        ));

        if ($stillUsed === 0) {
            $wpdb->delete($wpdb->termmeta, ['term_id' => $termId], ['%d']);
            $wpdb->delete($wpdb->terms, ['term_id' => $termId], ['%d']);
        }
    }

    $languageSlugs = ['it', ...$foreignLanguages];
    $userMetaKeys = ['pll_filter_content', 'pll_dismissed_notices'];
    foreach ($languageSlugs as $languageSlug) {
        $userMetaKeys[] = 'description_' . $languageSlug;
    }
    foreach ($userMetaKeys as $metaKey) {
        delete_metadata('user', 0, $metaKey, '', true);
    }

    foreach ($optionNames as $optionName) {
        delete_option((string) $optionName);
    }

    $activePlugins = array_values(array_filter(
        (array) get_option('active_plugins', []),
        static fn ($plugin): bool => $plugin !== 'polylang/polylang.php'
    ));
    update_option('active_plugins', $activePlugins);

    $wpdb->query(
        $wpdb->prepare(
            "DELETE FROM {$wpdb->options}
             WHERE option_name LIKE %s
                OR option_name LIKE %s",
            $wpdb->esc_like('_transient_sitemap_') . '%',
            $wpdb->esc_like('_transient_timeout_sitemap_') . '%'
        )
    );

    flush_rewrite_rules(false);
    wp_cache_flush();
    $wpdb->query('COMMIT');

    echo 'deleted_content=' . $deletedContent . PHP_EOL;
    echo 'deleted_language_switchers=' . $deletedSwitchers . PHP_EOL;
    echo 'deleted_legacy_string_posts=' . $deletedStringPosts . PHP_EOL;
    echo 'deleted_polylang_taxonomy_rows=' . count($taxonomyRows) . PHP_EOL;
    echo 'deleted_polylang_options=' . count($optionNames) . PHP_EOL;
} catch (Throwable $error) {
    $wpdb->query('ROLLBACK');
    fwrite(STDERR, 'Cleanup failed: ' . $error->getMessage() . PHP_EOL);
    exit(1);
}
