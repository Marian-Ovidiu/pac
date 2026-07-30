<?php

declare(strict_types=1);

$root = dirname(__DIR__);
chdir($root);

$trackedOutput = [];
$exitCode = 0;
exec('git ls-files', $trackedOutput, $exitCode);

if ($exitCode !== 0) {
    fwrite(STDERR, "Unable to read the Git index.\n");
    exit(1);
}

$tracked = array_fill_keys($trackedOutput, true);
$errors = [];

$forbiddenExact = [
    '.env',
    'wp-config.php',
    'wp-content/themes/my_structure/.env',
];

$forbiddenPrefixes = [
    '.idea/',
    'wp-content/themes/my_structure/node_modules/',
    'wp-content/themes/my_structure/vendor/',
    'wp-content/plugins/pac-core/vendor/',
    'wp-content/themes/my_structure/resources/cache/',
    'wp-content/uploads/',
];

foreach ($forbiddenExact as $path) {
    if (isset($tracked[$path])) {
        $errors[] = "Sensitive/generated path is tracked: {$path}";
    }
}

foreach (array_keys($tracked) as $path) {
    foreach ($forbiddenPrefixes as $prefix) {
        if (str_starts_with($path, $prefix)) {
            $errors[] = "Generated path is tracked: {$path}";
            break;
        }
    }

    if (preg_match('~(^|/)create_autologin_[^/]+\.php$~', $path)) {
        $errors[] = "Host autologin file is tracked: {$path}";
    }

    if (preg_match('~(^|/)\.env(?:\..+)?$~', $path) && !str_ends_with($path, '.env.example')) {
        $errors[] = "Environment secret file is tracked: {$path}";
    }
}

$required = [
    'wp-config.example.php',
    '.env.example',
    'wp-content/plugins/pac-core/pac-core.php',
    'wp-content/plugins/pac-core/composer.lock',
    'wp-content/plugins/wp-mail-smtp/wp_mail_smtp.php',
];

foreach ($required as $path) {
    if (!isset($tracked[$path])) {
        $errors[] = "Required reproducibility file is not tracked: {$path}";
    }
}

if ($errors) {
    foreach ($errors as $error) {
        fwrite(STDERR, "ERROR {$error}\n");
    }
    exit(1);
}

echo sprintf("Repository policy OK (%d tracked files checked).\n", count($tracked));
