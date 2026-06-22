<?php
declare(strict_types=1);

// app/core/core_response.php

// ── BASE_URL ───────────────────────────────────────────────────────────────
if (!defined('BASE_URL')) {
    $projectRoot = realpath(__DIR__ . '/../..');
    $docRoot     = realpath($_SERVER['DOCUMENT_ROOT'] ?? '');

    $base = '';
    if ($docRoot && $projectRoot && str_starts_with($projectRoot, $docRoot)) {
        $base = substr($projectRoot, strlen($docRoot));
        $base = str_replace('\\', '/', $base);
        $base = rtrim($base, '/');
    }

    define('BASE_URL', $base);
}

// Build an app-relative URL, accounting for BASE_URL.

function url(string $path = ''): string
{
    $path = trim($path, '/');
    return $path === '' ? (BASE_URL ?: '/') : BASE_URL . '/' . $path;
}

set_exception_handler(function (Throwable $e) {
    error_log($e);
    json_error('An unexpected server error occurred.', 500);
});

function json_ok(array $data = [], string $message = 'OK'): void
{
    http_response_code(200);
    header('Content-Type: application/json');
    echo json_encode(['success' => true, 'message' => $message, 'data' => $data]);
    exit;
}

function json_error(string $message, int $code = 400): void
{
    http_response_code($code);
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => $message, 'data' => null]);
    exit;
}

function redirect(string $path): void
{
    header("Location: {$path}");
    exit;
}

function abort(int $code, string $message = ''): void
{
    http_response_code($code);
    exit($message);
}
