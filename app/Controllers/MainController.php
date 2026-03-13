<?php

namespace App\Controllers;

/** @var Db $db */

use core\Db;

use core\Request;

class MainController
{
    private $db;

    public function __construct()
    {
        // Получаем экземпляр Db
        $this->db = Db::getInstance();
        $db_config = require CONFIG . '/db.php';
        $this->db->getConnection($db_config);

        // Если соединение еще не установлено, устанавливаем
        try {
            // Проверяем, есть ли свойство connection и установлено ли оно
            $reflection = new \ReflectionClass($this->db);
            $property = $reflection->getProperty('connection');
            $property->setAccessible(true);
            $connection = $property->getValue($this->db);

            if (!$connection) {
                $this->db->getConnection($db_config);
            }
        } catch (\Exception $e) {
            // Если не получается проверить, просто пытаемся подключиться
            $this->db->getConnection($db_config);
        }
    }

    public function index(Request $request): void
    {
        $currentPage = 'home';
        $pageTitle = 'Аренда офисов в центре Кемерова, ул. Кузбасская, 33А.';

        // Получаем фото из БД
        try {
            $photos = $this->db->query("SELECT * FROM photos ORDER BY id DESC")->findAll();
        } catch (\Exception $e) {
            $photos = [];
            // Логируем ошибку
            error_log("DB Error in index: " . $e->getMessage());
        }
        // Передаем переменные в представление
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