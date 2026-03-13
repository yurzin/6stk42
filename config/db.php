<?php

return [
    'host'     => $_ENV['DB_HOST'] ?? 'localhost',
    'dbname'   => $_ENV['DB_DATABASE'] ?? '',
    'username' => $_ENV['DB_USERNAME'] ?? 'root',
    'password' => $_ENV['DB_PASSWORD'] ?? $_ENV['DB_ROOT_PASSWORD'] ?? '',
    'charset'  => 'utf8mb4', // utf8 не поддерживает 4-байтовые символы (эмодзи и т.д.)
    'options'  => [
        // PDO::ATTR_ERRMODE всегда ERRMODE_EXCEPTION — принудительно в Database::connect()
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ],
];