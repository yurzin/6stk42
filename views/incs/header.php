<?php
/**
 * @var $metaDescription
 * @var $pageTitle
 * @var $currentPage
 */
?>
<!doctype html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="<?php echo $metaDescription . ' Аренда офисов в центре Кемерова, ул. Кузбасская, 33А'; ?>">
    <title><?php echo $pageTitle . ' Аренда офисов в центре Кемерова'; ?></title>
    <link rel="stylesheet" href="<?= asset('/css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="icon" href="/favicon.ico">
</head>

<body>
<div class="page-wrap">
    <header class="mastheader">
        <div class="header-title">
            <span class="site-title">Аренда офисов в центре Кемерова</span>
        </div>
        <nav class="navbar navbar-expand-sm navbar-light justify-content-center position-static">
            <!-- Кнопка бургер-меню -->
            <button class="navbar-toggler bg-white me-3" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                    aria-controls="navbarNav" aria-expanded="false" aria-label="Переключить навигацию">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="bottom-header collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav primary-menu">
                    <li class="<?php echo $currentPage === 'home' ? 'active' : ''; ?>">
                        <a href="<?= url() ?>">Главная</a>
                    </li>
                    <li class="<?php echo $currentPage === 'floor1' ? 'active' : ''; ?>">
                        <a href="<?= url('/?page=floor1') ?>">Первый этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'floor2' ? 'active' : ''; ?>">
                        <a href="<?= url('/?page=floor2') ?>">Второй этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'floor3' ? 'active' : ''; ?>">
                        <a href="<?= url('/?page=floor3') ?>">Третий этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'schema' ? 'active' : ''; ?>">
                        <a href="<?= url('/?page=schema') ?>">Схема расположения</a>
                    </li>
                    <li class="<?php echo $currentPage === 'send-message' ? 'active' : ''; ?>">
                        <a href="<?= url('/?page=send-message') ?>">Отправить сообщение</a>
                    </li>
                </ul>
        </nav>
    </header>