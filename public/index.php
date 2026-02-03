<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/** @var  $routes array
 * Точка входа в приложение
 *
 * Обрабатывает все запросы и маршрутизирует их к соответствующим страницам
 */
require dirname(__DIR__) . '/config/config.php';


require ROOT . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT);
$dotenv->load();

require CONFIG . '/routes.php';

// Получаем параметр страницы из URL
$page = $_GET['page'] ?? 'home';

// Безопасность: разрешаем только буквенно-цифровые символы
$page = preg_replace('/[^a-z0-9-]/i', '', $page);

$pageTitle = 'Аренда офисов в центре Кемерова';
$metaDescription = 'Аренда офисов в центре Кемерова, ул. Кузбасская, 33А';
$currentPage = $page;

// Устанавливаем заголовки для конкретных страниц
switch ($page) {
    case 'home':
        $pageTitle = 'Главная.';
        $metaDescription = '';
        break;
    case 'floor1':
        $pageTitle = 'Первый этаж.';
        $metaDescription = 'Изображения офисов на первом этаже.';
        break;
    case 'floor2':
        $pageTitle = 'Второй этаж.';
        $metaDescription = 'Изображения офисов на втором этаже.';
        break;
    case 'floor3':
        $pageTitle = 'Третий этаж.';
        $metaDescription = 'Изображения офисов на третьем этаже.';
        break;
}

if (array_key_exists($page, $routes)) {
    if (file_exists(VIEWS . '/'.$routes[$page])) {
        include VIEWS . '/'.$routes[$page];
    } else {
        include VIEWS . '/home.php';
    }
} else {
    include VIEWS . '/404.php';
}