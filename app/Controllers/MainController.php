<?php

namespace App\Controllers;

use core\Request;

class MainController
{
    public function index(): void
    {
        $currentPage = 'home';
        $pageTitle = 'Аренда офисов в центре Кемерова, ул. Кузбасская, 33А.';
        require VIEWS . '/home.php';
    }

    public function floor(Request $request): void
    {
        $id = $request->getInt('id', 0);
        $currentPage = 'floor'.$id;
        $pageTitle = 'Изображения офисов на первом этаже.';
        require VIEWS . '/floor.php';
    }

    public function schema(): void
    {
        $currentPage = 'schema';
        $pageTitle = 'Схема расположения кабинетов поэтажно.';
        require VIEWS . '/schema.php';
    }

    public function formSendMessage(): void
    {
        $currentPage = 'form-send-message';
        $pageTitle = 'Страница с формой обратной связи. ';
        require VIEWS . '/send-message.php';
    }

    public function privacyPolicy(): void
    {
        $currentPage = 'privacy-policy';
        $pageTitle = 'Политика конфиденциальности. ';
        require VIEWS . '/privacy-policy.php';
    }
}