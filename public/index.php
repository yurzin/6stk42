<?php
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');
header("Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline';");

if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 1);  // Только для HTTPS
    ini_set('session.cookie_samesite', 'Strict');
    ini_set('session.use_strict_mode', 1);
    session_start();

    // Регенерация ID сессии для предотвращения fixation
    if (!isset($_SESSION['initiated'])) {
        session_regenerate_id(true);
        $_SESSION['initiated'] = true;
    }
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
    $viewsDir = realpath(VIEWS);
    $filePath = realpath(VIEWS . '/' . $routes[$page]);

    // Проверяем что:
    // 1. realpath успешно разрешил путь (не null)
    // 2. Файл находится строго внутри VIEWS директории
    // 3. Файл существует (доп. проверка)
    if ($filePath !== false &&
        str_starts_with($filePath, $viewsDir . DIRECTORY_SEPARATOR) &&
        is_file($filePath)) {
        include $filePath;
    } else {
        // Логируем попытку доступа к запрещённому файлу
        error_log("Security: Attempted to access invalid file path for page: {$page}");
        http_response_code(404);
        include VIEWS . '/404.php';
    }
} else {
    http_response_code(404);
    include VIEWS . '/404.php';
}