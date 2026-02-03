<?php

define("ROOT", dirname(__DIR__));
const WWW = ROOT . '/public';
const CONFIG = ROOT . '/config';
const APP = '/app';
const VIEWS = APP . '/views';
const VENDOR = APP . '/vendor';

define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');

// Простой парсер .env
function load_env($path): void
{
    if (!file_exists($path)) {
        return;
    }

    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue;
        }

        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);

        if (!array_key_exists($name, $_ENV)) {
            putenv("$name=$value");
            $_ENV[$name] = $value;
        }
    }
}

// Загружаем .env
$env_file = WWW . '/../.env';
if (file_exists(WWW . '/../.env.local')) {
    $env_file = WWW . '/../.env.local';
}
load_env($env_file);

// Получаем значения из окружения
$base_path = getenv('BASE_PATH') ?: '';
$app_url = getenv('APP_URL') ?: 'http://localhost:8080';

define('BASE_PATH', $base_path);
define('BASE_URL', $app_url);
define('APP_ENV', getenv('APP_ENV') ?: 'local');

function base_url($path = ''): string
{
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

function asset($path): string
{
    $path = ltrim($path, '/');
    if (BASE_PATH) {
        return BASE_PATH . '/' . $path;
    }
    return '/' . $path;
}

function url($path = ''): string
{
    $path = ltrim($path, '/');
    if (BASE_PATH) {
        return BASE_PATH . ($path ? '/' . $path : '');
    }
    return '/' . ($path ?: '');
}

function redirect($path = '') {
    header('Location: ' . url($path));
    exit;
}

function current_path() {
    $uri = $_SERVER['REQUEST_URI'];
    if (($pos = strpos($uri, '?')) !== false) {
        $uri = substr($uri, 0, $pos);
    }
    if (BASE_PATH && strpos($uri, BASE_PATH) === 0) {
        $uri = substr($uri, strlen(BASE_PATH));
    }
    return $uri ?: '/';
}