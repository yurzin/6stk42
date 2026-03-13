<?php

$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI']    = '/api/photos';
$_SERVER['HTTP_HOST']      = 'localhost';

// config.php сам определяет ROOT и все константы
require '/app/config/config.php';
require '/app/vendor/autoload.php';

use core\Request;
use core\Router;

$request = new Request($_SERVER['REQUEST_URI']);
$router  = new Router($request);

$router->get('/api/photos', function() { echo 'MATCH OK'; });

echo 'PATH: [' . $request->getPath() . ']' . PHP_EOL;
echo 'METHOD: [' . $request->getMethod() . ']' . PHP_EOL;

// Показываем зарегистрированные маршруты через Reflection
$ref    = new ReflectionProperty(Router::class, 'routes');
$ref->setAccessible(true);
$routes = $ref->getValue($router);
echo 'ROUTES: ' . json_encode($routes, JSON_PRETTY_PRINT) . PHP_EOL;

echo PHP_EOL . '--- dispatch ---' . PHP_EOL;
echo $router->dispatch();