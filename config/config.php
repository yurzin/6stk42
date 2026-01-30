<?php

define("ROOT", dirname(__DIR__));
const WWW = ROOT . '/public';
const CORE = ROOT . '/core';
const CONFIG = ROOT . '/config';
const APP = '/app';
const VIEWS = APP . '/views';
const VENDOR = APP . '/vendor';

define('APP_URL', $_ENV['APP_URL'] ?? 'http://localhost:8080');

// Определяем базовый путь
define('BASE_PATH', '/arenda');
define('BASE_URL', 'http://yurzin.com/arenda');

// Функция для генерации URL
function base_url($path = '')
{
    $path = ltrim($path, '/');
    return BASE_URL . ($path ? '/' . $path : '');
}

// Функция для путей к статическим файлам
function asset($path)
{
    $path = ltrim($path, '/');
    return BASE_PATH . '/' . $path;
}

// Функция для внутренних ссылок
function url($path = '')
{
    $path = ltrim($path, '/');
    return BASE_PATH . ($path ? '/' . $path : '');
}

// Функция для редиректов
function redirect($path = '')
{
    header('Location: ' . url($path));
    exit;
}

// Функция для получения текущего URL без BASE_PATH
function current_path()
{
    $uri = $_SERVER['REQUEST_URI'];
    if (strpos($uri, BASE_PATH) === 0) {
        $uri = substr($uri, strlen(BASE_PATH));
    }
    return $uri ?: '/';
}