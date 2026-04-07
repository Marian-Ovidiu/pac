<?php

declare(strict_types=1);

$apply = in_array('--apply', $argv, true);

$pdo = new PDO('mysql:host=localhost;dbname=pac;charset=utf8mb4', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$uploadsBase = realpath(__DIR__ . '/../wp-content/uploads');

if ($uploadsBase === false) {
    fwrite(STDERR, "Uploads directory not found.\n");
    exit(1);
}

$sql = <<<SQL
SELECT pm.meta_value AS attached_file, MIN(p.post_title) AS post_title
FROM wp_posts p
JOIN wp_postmeta pm
  ON pm.post_id = p.ID
 AND pm.meta_key = '_wp_attached_file'
WHERE p.post_type = 'attachment'
GROUP BY pm.meta_value
ORDER BY pm.meta_value
SQL;

$rows = $pdo->query($sql)->fetchAll();

$referencedPaths = [];
foreach ($rows as $row) {
    $referencedPaths[$uploadsBase . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, (string) $row['attached_file'])] = true;
}

$proposals = [];
$renames = [];
$copies = [];
$unmatched = [];

foreach ($rows as $row) {
    $relativePath = (string) $row['attached_file'];
    $expectedPath = $uploadsBase . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath);
    if (file_exists($expectedPath)) {
        continue;
    }

    $directory = dirname($expectedPath);
    if (!is_dir($directory)) {
        $unmatched[] = [$relativePath, 'directory-missing'];
        continue;
    }

    $expectedName = basename($expectedPath);
    $title = (string) $row['post_title'];
    $expectedNorm = normalize_name(pathinfo($expectedName, PATHINFO_FILENAME));
    $titleNorm = normalize_name($title);

    $candidates = [];
    foreach (scandir($directory) ?: [] as $entry) {
        if ($entry === '.' || $entry === '..') {
            continue;
        }

        $candidatePath = $directory . DIRECTORY_SEPARATOR . $entry;
        if (!is_file($candidatePath)) {
            continue;
        }

        if (strtolower((string) pathinfo($entry, PATHINFO_EXTENSION)) !== strtolower((string) pathinfo($expectedName, PATHINFO_EXTENSION))) {
            continue;
        }

        $candidateNorm = normalize_name(pathinfo($entry, PATHINFO_FILENAME));
        $score = null;
        if ($candidateNorm === $expectedNorm) {
            $score = 0;
        } elseif ($candidateNorm === $titleNorm) {
            $score = 1;
        } elseif (trim_numeric_suffix($candidateNorm) === trim_numeric_suffix($expectedNorm)) {
            $score = 2;
        } elseif (trim_numeric_suffix($candidateNorm) === trim_numeric_suffix($titleNorm)) {
            $score = 3;
        }

        if ($score !== null) {
            $candidates[$candidatePath] = $score;
        }
    }

    if ($candidates === []) {
        $unmatched[] = [$relativePath, 'no-match'];
        continue;
    }

    asort($candidates);
    $proposals[] = [
        'relative' => $relativePath,
        'expected' => $expectedPath,
        'candidates' => $candidates,
    ];
}

usort($proposals, static function (array $left, array $right): int {
    $leftCount = count($left['candidates']);
    $rightCount = count($right['candidates']);
    if ($leftCount !== $rightCount) {
        return $leftCount <=> $rightCount;
    }

    $leftBest = min($left['candidates']);
    $rightBest = min($right['candidates']);

    return $leftBest <=> $rightBest;
});

$usedSources = [];
foreach ($proposals as $proposal) {
    $selected = null;
    foreach ($proposal['candidates'] as $candidatePath => $score) {
        $isReferencedCanonical = isset($referencedPaths[$candidatePath]);
        if ($isReferencedCanonical || !isset($usedSources[$candidatePath])) {
            $selected = $candidatePath;
            break;
        }
    }

    if ($selected === null) {
        $unmatched[] = [$proposal['relative'], 'ambiguous'];
        continue;
    }

    if (isset($referencedPaths[$selected])) {
        $copies[] = [$selected, $proposal['expected'], $proposal['relative']];
        continue;
    }

    $usedSources[$selected] = true;
    $renames[] = [$selected, $proposal['expected'], $proposal['relative']];
}

echo 'mode=' . ($apply ? 'apply' : 'dry-run') . PHP_EOL;
echo 'planned_renames=' . count($renames) . PHP_EOL;
echo 'planned_copies=' . count($copies) . PHP_EOL;
echo 'unmatched=' . count($unmatched) . PHP_EOL;

foreach (array_slice($renames, 0, 15) as [$from, $to, $relativePath]) {
    echo 'rename ' . $from . ' => ' . $relativePath . PHP_EOL;
}

foreach (array_slice($copies, 0, 15) as [$from, $to, $relativePath]) {
    echo 'copy ' . $from . ' => ' . $relativePath . PHP_EOL;
}

foreach (array_slice($unmatched, 0, 15) as [$relativePath, $reason]) {
    echo 'unmatched ' . $reason . ' ' . $relativePath . PHP_EOL;
}

if (!$apply) {
    exit(0);
}

$renamed = 0;
foreach ($renames as [$from, $to]) {
    if (file_exists($to)) {
        continue;
    }

    if (!@rename($from, $to)) {
        fwrite(STDERR, "Failed to rename {$from} -> {$to}\n");
        exit(1);
    }

    $renamed++;
}

$copied = 0;
foreach ($copies as [$from, $to]) {
    if (file_exists($to)) {
        continue;
    }

    if (!@copy($from, $to)) {
        fwrite(STDERR, "Failed to copy {$from} -> {$to}\n");
        exit(1);
    }

    $copied++;
}

echo 'renamed=' . $renamed . PHP_EOL;
echo 'copied=' . $copied . PHP_EOL;

function normalize_name(string $value): string
{
    $value = str_replace(["\u{2019}", "\u{2018}", "\u{201C}", "\u{201D}", "'"], '', $value);
    $transliterated = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $value);
    if ($transliterated !== false) {
        $value = $transliterated;
    }

    $value = strtolower($value);
    $value = preg_replace('/[^a-z0-9]+/', '', $value) ?? '';

    return $value;
}

function trim_numeric_suffix(string $value): string
{
    return preg_replace('/\d+$/', '', $value) ?? $value;
}
