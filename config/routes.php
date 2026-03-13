<?php
/**
 * @var $app
 */

use App\Controllers\View\ApiMainController;

$app->router->get('/api/feed',        [ApiMainController::class, 'feed']);
$app->router->get('/api/photos', [ApiMainController::class, 'photos']);
$app->router->get('/api/videos', [ApiMainController::class, 'videos']);
$app->router->get('/api/notes',  [ApiMainController::class, 'notes']);

$app->run();