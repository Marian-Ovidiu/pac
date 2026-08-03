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

$slug = 'tetto-scuola-ghana';
$existing = get_page_by_path($slug, OBJECT, 'progetto');
$mode = $existing instanceof WP_Post ? 'update' : 'create';

echo 'mode=' . ($apply ? 'apply' : 'dry-run') . PHP_EOL;
echo 'action=' . $mode . PHP_EOL;
echo 'slug=' . $slug . PHP_EOL;
echo 'status=publish' . PHP_EOL;

if (!$apply) {
    echo "Dry run only. Re-run with --apply to create or update the project.\n";
    exit(0);
}

if (!post_type_exists('progetto')) {
    fwrite(STDERR, "The progetto post type is not registered. Activate the PAC theme and ACF first.\n");
    exit(1);
}

$postData = [
    'ID' => $existing instanceof WP_Post ? (int) $existing->ID : 0,
    'post_type' => 'progetto',
    'post_status' => 'publish',
    'post_name' => $slug,
    'post_title' => 'Un tetto nuovo per la scuola in Ghana',
    'post_excerpt' => 'Ricostruiamo il tetto danneggiato di una scuola in Ghana per proteggere le aule dalla pioggia e dare continuità alle lezioni.',
    'post_content' => '<p>Il form associa la donazione a questo progetto, che ha un obiettivo economico di 10.000 EUR. PAC sta completando la verifica tecnica della copertura; avanzamento e documentazione saranno aggiornati in questa pagina.</p><p>Puoi contribuire con un importo libero oppure condividere il progetto per aiutarci a raggiungere altre persone.</p>',
    'comment_status' => 'closed',
    'ping_status' => 'closed',
];

$postId = wp_insert_post(wp_slash($postData), true);

if (is_wp_error($postId)) {
    fwrite(STDERR, 'Project save failed: ' . $postId->get_error_message() . PHP_EOL);
    exit(1);
}

$legacyFields = [
    'name' => 'Tetto scuola Ghana',
    'titolo_card' => 'Un tetto nuovo per la scuola in Ghana',
    'titolo_hero' => 'Una scuola al riparo dalla pioggia.',
    'testo_hero' => '<p>Una copertura gravemente danneggiata lascia entrare acqua nelle aule durante la stagione delle piogge. Vogliamo ricostruire il tetto perché lezioni, libri e materiali possano restare al sicuro.</p>',
    'problemi_titolo_1' => 'Quando piove, imparare diventa difficile',
    'problemi_sotto_titolo_1' => 'Le lezioni si interrompono',
    'problemi_testo_1' => '<p>L’acqua entra nelle aule e costringe insegnanti e studenti a sospendere o riorganizzare le attività educative.</p>',
    'problemi_sotto_titolo_2' => 'Si perde tempo scolastico',
    'problemi_testo_2' => '<p>Le interruzioni durante la stagione delle piogge riducono la continuità necessaria per seguire il programma e consolidare ciò che viene appreso.</p>',
    'problemi_sotto_titolo_3' => 'Libri e materiali sono esposti',
    'problemi_testo_3' => '<p>Acqua e umidità possono danneggiare quaderni, libri e strumenti didattici che la scuola utilizza ogni giorno.</p>',
    'soluzioni_titolo_1' => 'La risposta del progetto',
    'soluzioni_sottotitolo_1' => 'Ricostruire una copertura sicura',
    'soluzioni_testo_1' => '<p>Il progetto prevede la verifica tecnica, la definizione del budget e la ricostruzione del tetto per proteggere gli spazi scolastici.</p>',
];

foreach ($legacyFields as $name => $value) {
    update_post_meta($postId, $name, $value);
}

$projectFields = [
    'field_pac_project_template' => 'flagship',
    'field_pac_project_location' => 'Ghana',
    'field_pac_project_visual_theme' => 'education',
    'field_pac_project_intro' => '<p>La scuola ha bisogno di una copertura affidabile prima che nuove piogge interrompano ancora le lezioni. L’intervento parte da un obiettivo semplice: restituire a studenti e insegnanti aule utilizzabili, proteggendo anche i materiali educativi.</p><p>La pagina raccoglierà il budget verificato, l’avanzamento della raccolta e gli aggiornamenti dal progetto, senza anticipare risultati non ancora raggiunti.</p>',
    'field_pac_project_why_title' => 'La continuità scolastica conta ogni giorno',
    'field_pac_project_why_text' => '<p>Un’aula asciutta e sicura permette agli insegnanti di proseguire le attività anche durante la stagione delle piogge. Significa meno interruzioni, più tempo per imparare e una migliore protezione delle risorse che la scuola ha già a disposizione.</p><p>Ricostruire il tetto non risolve ogni bisogno della comunità scolastica, ma rimuove un ostacolo concreto alla continuità delle lezioni.</p>',
    'field_pac_project_objectives' => [
        [
            'title' => 'Verificare struttura e copertura',
            'text' => 'Definire con referenti e tecnici locali l’intervento necessario, i materiali e un budget documentabile.',
        ],
        [
            'title' => 'Ricostruire il tetto',
            'text' => 'Realizzare una copertura sicura e adatta alle condizioni della stagione delle piogge.',
        ],
        [
            'title' => 'Proteggere aule e materiali',
            'text' => 'Ridurre l’ingresso dell’acqua negli spazi didattici e il rischio di danni a libri e strumenti scolastici.',
        ],
    ],
    'field_pac_fundraising_status' => 'fundraising',
    'field_pac_fundraising_target' => 10000,
    'field_pac_fundraising_raised' => 0,
    'field_pac_fundraising_currency' => 'EUR',
    'field_pac_fundraising_note' => 'L’obiettivo economico del progetto è di 10.000 EUR. L’importo raccolto verrà aggiornato sulla base delle donazioni registrate e gli avanzamenti saranno documentati in questa pagina.',
    'field_pac_expected_impact' => [
        [
            'title' => 'Lezioni più continue',
            'text' => 'L’impatto atteso è ridurre le interruzioni direttamente causate dall’ingresso della pioggia nelle aule.',
        ],
        [
            'title' => 'Spazi più sicuri e utilizzabili',
            'text' => 'La nuova copertura dovrebbe rendere le classi più protette durante le attività scolastiche.',
        ],
        [
            'title' => 'Materiali meglio protetti',
            'text' => 'Libri, quaderni e strumenti didattici saranno meno esposti ad acqua e umidità provenienti dal tetto.',
        ],
    ],
    'field_pac_project_updates' => [],
];

foreach ($projectFields as $fieldKey => $value) {
    if (function_exists('update_field')) {
        update_field($fieldKey, $value, $postId);
        continue;
    }

    $name = str_replace('field_', '', $fieldKey);
    update_post_meta($postId, $name, $value);
}

update_post_meta($postId, 'rank_math_title', 'Un tetto nuovo per la scuola in Ghana | PAC');
update_post_meta($postId, 'rank_math_description', $postData['post_excerpt']);

clean_post_cache($postId);
flush_rewrite_rules(false);

if (class_exists('RankMath\\Sitemap\\Cache')) {
    \RankMath\Sitemap\Cache::invalidate_storage();
}

echo 'project_id=' . (int) $postId . PHP_EOL;
echo 'url=' . get_permalink($postId) . PHP_EOL;
echo "Project saved successfully.\n";
