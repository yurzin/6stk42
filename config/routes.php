<?php
/**
 * @var $app
 */

use App\Controllers\Admin\AuthController;
use App\Controllers\Admin\RegisteredUserController;
use App\Controllers\View\ApiMainController;

$app->router->get('/api/index', [ApiMainController::class, 'index']);
$app->router->get('/api/photos', [ApiMainController::class, 'photos']);
$app->router->get('/api/videos', [ApiMainController::class, 'videos']);
$app->router->get('/api/notes',  [ApiMainController::class, 'notes']);
$app->router->post('/api/register',  [RegisteredUserController::class, 'store']);
$app->router->post('/api/login', [AuthController::class, 'login']);

$app->run();