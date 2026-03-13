<?php

define("ROOT", dirname(__DIR__));
const WWW = ROOT . '/public';
const CONFIG = ROOT . '/config';
const APP = ROOT . '/app';
const CONTROLLERS = APP . '/Controllers/View';

const VIEWS = APP . '/Views';
const VENDOR = ROOT . '/vendor';

function load_env($path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) {
            continue;
        }

        if (strpos($line, '=') === false) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value, '"\'');

        // Перезаписываем значения из .env
        $_ENV[$name] = $value;
        $_SERVER[$name] = $value;
    }
}

$env_file = ROOT . '/.env';
if (file_exists(ROOT . '/.env.local')) {
    $env_file = ROOT . '/.env.local';
}

load_env($env_file);

define('BASE_PATH', $_ENV['BASE_PATH'] ?? '');
define('BASE_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');
define('APP_ENV', $_ENV['APP_ENV'] ?? 'local');

function base_url($path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

function asset($path): string
{
    $path = ltrim($path, '/');
    return BASE_PATH ? BASE_PATH . '/' . $path : '/' . $path;
}

function url($path = ''): string
{
    $path = ltrim($path, '/');

    // Если путь пустой, возвращаем корень
    if ($path === '') {
        return BASE_PATH ?: '/';
    }

    return BASE_PATH . '/' . $path;
}

function redirect($path = ''): void
{
    // Запрещаем абсолютные URL и схемы
    if (filter_var($path, FILTER_VALIDATE_URL) ||
        str_contains($path, '://') ||
        str_starts_with($path, '//')) {
        http_response_code(400);
        die('Invalid redirect');
    }

    $path = ltrim($path, '/');
    header('Location: ' . url($path), true, 302);
    exit;
}

function current_path(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '/';

    if (BASE_PATH && strpos($uri, BASE_PATH) === 0) {
        $uri = substr($uri, strlen(BASE_PATH));
    }

    return $uri ?: '/';
}

function abort()
{
    http_response_code(404);
    include VIEWS . '/404.php';
    die;
}