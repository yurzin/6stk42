<!doctype html>
<html lang="ru-RU">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="<?php echo $metaDescription ?? 'Аренда офисов в центре Кемерова, ул. Кузбасская, 33А'; ?>">
    <title><?php echo $pageTitle ?? 'Аренда офисов в центре Кемерова'; ?></title>
    <link rel="stylesheet" href="<?= asset('/css/main.css') ?>">
    <link rel="stylesheet" href="<?= asset('/css/bootstrap.min.css') ?>">
    <link rel="icon" href="/favicon.ico">
</head>

<body>
<div class="page-wrap">
    <header class="mastheader">
        <div class="header-inner">
            <div class="gc-logo-block">
                <div class="gc-container">
                    <div class="site-identity">
                        <span class="site-title"><a href="/">Аренда офисов в центре Кемерова</a></span>
                    </div>
                </div>
            </div>
            <div class="bottom-header">
                <div class="gc-container">
                    <nav>
                        <ul class="primary-menu">
                            <li class="<?php echo ($currentPage ?? '') === 'home' ? 'active' : ''; ?>">
                                <a href="<?= url() ?>">Главная</a>
                            </li>
                            <li class="<?php echo ($currentPage ?? '') === 'floor1' ? 'active' : ''; ?>">
                                <a href=="<?= url('/?page=floor1') ?>">Первый этаж</a>
                            </li>
                            <li class="<?php echo ($currentPage ?? '') === 'floor2' ? 'active' : ''; ?>">
                                <a href=="<?= url('/?page=floor2') ?>">Второй этаж</a>
                            </li>
                            <li class="<?php echo ($currentPage ?? '') === 'floor3' ? 'active' : ''; ?>">
                                <a href=="<?= url('/?page=floor3') ?>">Третий этаж</a>
                            </li>
                            <li class="<?php echo ($currentPage ?? '') === 'schema' ? 'active' : ''; ?>">
                                <a href=="<?= url('/?page=schema') ?>">Схема расположения</a>
                            </li>
                            <li class="<?php echo ($currentPage ?? '') === 'contact' ? 'active' : ''; ?>">
                                <a href=="<?= url('/?page=send-message') ?>">Отправить сообщение</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </header>