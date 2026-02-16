<!doctype html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description"
          content="<?php echo $pageTitle . ' Удобное расположение. Центр гоpoда. Транспортная доступность. Хороший пeшeхoдный трафик. 
          Собственная парковка. Качественный ремонт. Кондиционеры в каждом кабинете. Светодиодное освещение. Охрана и круглосуточный доступ. 
          Предоставляем офисную мебель при необходимости. Сдача помещений по этажам или полностью всего здания.'; ?>">
    <title><?php echo 'Офисное здание г. Кемерово, ул. Кузбасская, 33А. ' . $pageTitle; ?></title>
    <link rel="stylesheet" href="<?= asset('/css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="icon" href="/favicon.svg">
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
                        <a href="<?= url('/floor?id=1') ?>">Первый этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'floor2' ? 'active' : ''; ?>">
                        <a href="<?= url('/floor?id=2') ?>">Второй этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'floor3' ? 'active' : ''; ?>">
                        <a href="<?= url('/floor?id=3') ?>">Третий этаж</a>
                    </li>
                    <li class="<?php echo $currentPage === 'schema' ? 'active' : ''; ?>">
                        <a href="<?= url('/schema') ?>">Схема расположения</a>
                    </li>
                    <li class="<?php echo $currentPage === 'form-send-message' ? 'active' : ''; ?>">
                        <a href="<?= url('/form-send-message') ?>">Отправить сообщение</a>
                    </li>
                </ul>
        </nav>
    </header>