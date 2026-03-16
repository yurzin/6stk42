<?php

declare(strict_types=1);

namespace core;

use PDO;
use PDOException;

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;
    private array $config;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->connect();
    }

    private function connect(): void
    {
        $host     = $this->config['host'] ?? 'localhost';
        $dbname   = $this->config['dbname'] ?? '';
        $user     = $this->config['username'] ?? $this->config['user'] ?? '';
        $password = $this->config['password'] ?? '';
        $charset  = $this->config['charset'] ?? 'utf8mb4';

        $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

        // Дефолтные опции, которые можно переопределить через конфиг
        $defaultOptions = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        // Опции из конфига мерджатся поверх дефолтных
        $options = array_merge($defaultOptions, $this->config['options'] ?? []);

        // ATTR_ERRMODE нельзя переопределить — всегда бросаем исключения
        $options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;

        try {
            $this->pdo = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            // Передаём оригинальное исключение как $previous для сохранения стектрейса
            throw new \RuntimeException('Database connection failed: ' . $e->getMessage(), 0, $e);
        }
    }

    public static function getInstance(array $config = []): self
    {
        if (self::$instance === null) {
            if (empty($config)) {
                throw new \LogicException(
                    'Database has not been initialised yet. ' .
                    'Call Database::getInstance($config) in your bootstrap file first.'
                );
            }
            self::$instance = new self($config);
        }

        return self::$instance;
    }

    public function getConnection(): PDO
    {
        return $this->pdo;
    }

    /**
     * Пересоздаёт PDO-соединение с новыми параметрами.
     *
     * Внимание: все ранее полученные через getConnection() объекты PDO
     * станут устаревшими — они продолжат работать со старым соединением.
     * После вызова reconnect() всегда запрашивайте getConnection() заново.
     */
    public function reconnect(array $newConfig): void
    {
        $this->config = array_merge($this->config, $newConfig);
        $this->connect();
    }

    /**
     * Сбрасывает singleton-экземпляр (полезно в тестах).
     */
    public static function resetInstance(): void
    {
        self::$instance = null;
    }
}