<?php

/**
 * Router for PHP's built-in development server.
 *
 * Existing files and directories are served directly. Every other request is
 * passed to WordPress so pretty permalinks work without Apache/mod_rewrite.
 */

$requestPath = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$requestPath = is_string($requestPath) ? rawurldecode($requestPath) : '/';
$documentRoot = __DIR__;
$requestedFile = $documentRoot . '/' . ltrim($requestPath, '/');

if ($requestPath !== '/' && (is_file($requestedFile) || is_dir($requestedFile))) {
    return false;
}

require $documentRoot . '/index.php';
