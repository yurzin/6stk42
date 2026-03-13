<?php

use core\Database;
use core\Application;

header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline'; img-src 'self' data:;");

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    session_start();

    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
}

require dirname(__DIR__) . '/config/config.php';
require ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

$db_config = require CONFIG . '/db.php';
Database::getInstance($db_config); // ← конфиг передаётся сюда, не в getConnection()

$app = new Application();

require CONFIG . '/routes.php';
