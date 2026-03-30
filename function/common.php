<?php

declare(strict_types=1);

function app_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }

    session_set_cookie_params([
        'lifetime' => 0,
        'path' => '/',
        'domain' => '',
        'secure' => (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);

    session_start();
}

function app_base_url(): string
{
    $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost:8083';

    return $scheme . '://' . $host . '/';
}

function app_request_method(): string
{
    return strtoupper((string) ($_SERVER['REQUEST_METHOD'] ?? 'GET'));
}

function app_request_path(): string
{
    $path = (string) parse_url((string) ($_SERVER['REQUEST_URI'] ?? '/'), PHP_URL_PATH);
    return $path !== '' ? $path : '/';
}

function app_redirect(string $location, int $statusCode = 302): void
{
    header('Location: ' . $location, true, $statusCode);
    exit;
}

function app_db(): PDO
{
    static $pdo = null;

    if ($pdo instanceof PDO) {
        return $pdo;
    }

    $host = getenv('DB_HOST') ?: 'mysql';
    $dbname = getenv('DB_DATABASE') ?: 'mini_tp_iran';
    $user = getenv('DB_USERNAME') ?: 'app';
    $password = getenv('DB_PASSWORD') ?: 'app';

    $dsn = 'mysql:host=' . $host . ';dbname=' . $dbname . ';charset=utf8mb4';

    $pdo = new PDO($dsn, $user, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    return $pdo;
}

function app_render_page(string $pageFile, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    include __DIR__ . '/../pages/' . $pageFile;
}

function app_render_template(string $page, array $vars = []): void
{
    extract($vars, EXTR_SKIP);
    include __DIR__ . '/../pages/template.php';
}

function app_parse_datetime(?string $value): ?string
{
    $value = trim((string) $value);
    if ($value === '') {
        return null;
    }

    $ts = strtotime($value);
    if ($ts === false) {
        return null;
    }

    return date('Y-m-d H:i:s', $ts);
}
