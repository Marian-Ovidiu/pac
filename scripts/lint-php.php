<?php

declare(strict_types=1);

$root = dirname(__DIR__);
$sourceRoots = [
    $root . '/wp-content/themes/my_structure',
    $root . '/wp-content/plugins/pac-core',
    $root . '/wp-content/plugins/wp-mail-smtp',
];
$excluded = ['/vendor/', '/node_modules/', '/resources/cache/'];
$files = [];

foreach ($sourceRoots as $sourceRoot) {
    $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator(
        $sourceRoot,
        FilesystemIterator::SKIP_DOTS
    ));

    foreach ($iterator as $file) {
        $path = $file->getPathname();

        if ($file->getExtension() !== 'php') {
            continue;
        }

        if (array_filter($excluded, static fn (string $part): bool => str_contains($path, $part))) {
            continue;
        }

        $files[] = $path;
    }
}

$files[] = $root . '/wp-config.example.php';
$files[] = $root . '/router.php';
$failures = [];

foreach ($files as $file) {
    $output = [];
    $exitCode = 0;
    exec(escapeshellarg(PHP_BINARY) . ' -l ' . escapeshellarg($file), $output, $exitCode);

    if ($exitCode !== 0) {
        $failures[] = $file;
    }
}

if ($failures) {
    foreach ($failures as $file) {
        fwrite(STDERR, "PHP lint failed: {$file}\n");
    }
    exit(1);
}

echo sprintf("PHP lint OK (%d files).\n", count($files));
