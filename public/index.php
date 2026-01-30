<?php
/** @var  $routes array
 * Точка входа в приложение
 *
 * Обрабатывает все запросы и маршрутизирует их к соответствующим страницам
 */
require dirname(__DIR__) . '/config/config.php';
require CONFIG . '/routes.php';

// Получаем параметр страницы из URL
$page = $_GET['page'] ?? 'home';

// Безопасность: разрешаем только буквенно-цифровые символы
$page = preg_replace('/[^a-z0-9-]/i', '', $page);

if (array_key_exists($page, $routes)) {
    if (file_exists(VIEWS . '/'.$routes[$page])) {
        include VIEWS . '/'.$routes[$page];
    } else {
        include VIEWS . '/home.php';
    }
} else {
    include VIEWS . '/404.php';
}