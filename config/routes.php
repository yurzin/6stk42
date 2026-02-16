<?php
/**
 * @var $app
 */

use App\Controllers\MainController;

$app->router->get('/', [MainController::class, 'index']);
$app->router->get('/floor', [MainController::class, 'floor']);
$app->router->get('/schema', [MainController::class, 'schema']);
$app->router->get('/form-send-message', [MainController::class, 'formSendMessage']);
$app->router->get('/privacy-policy', [MainController::class, 'privacyPolicy']);

$app->run();