<?php

class RateLimiter
{
    private string $storageDir;
    private int $maxAttempts;
    private int $decayMinutes;

    public function __construct(int $maxAttempts = 3, int $decayMinutes = 15)
    {
        $this->storageDir = sys_get_temp_dir() . '/rate_limit';
        $this->maxAttempts = $maxAttempts;
        $this->decayMinutes = $decayMinutes;

        if (!is_dir($this->storageDir)) {
            mkdir($this->storageDir, 0755, true);
        }
    }

    /**
     * Проверяет, превышен ли лимит запросов
     */
    public function tooManyAttempts(string $key): bool
    {
        $attempts = $this->getAttempts($key);
        return $attempts >= $this->maxAttempts;
    }

    /**
     * Увеличивает счётчик попыток
     */
    public function hit(string $key): void
    {
        $file = $this->getFilePath($key);
        $data = $this->readFile($file);

        $data['attempts'] = ($data['attempts'] ?? 0) + 1;
        $data['expires_at'] = time() + ($this->decayMinutes * 60);

        file_put_contents($file, json_encode($data), LOCK_EX);
    }

    /**
     * Сбрасывает счётчик
     */
    public function clear(string $key): void
    {
        $file = $this->getFilePath($key);
        if (file_exists($file)) {
            unlink($file);
        }
    }

    /**
     * Возвращает количество попыток
     */
    public function getAttempts(string $key): int
    {
        $file = $this->getFilePath($key);
        $data = $this->readFile($file);

        // Если истекло время - сбрасываем
        if (isset($data['expires_at']) && $data['expires_at'] < time()) {
            $this->clear($key);
            return 0;
        }

        return $data['attempts'] ?? 0;
    }

    /**
     * Возвращает время до разблокировки в секундах
     */
    public function availableIn(string $key): int
    {
        $file = $this->getFilePath($key);
        $data = $this->readFile($file);

        if (!isset($data['expires_at'])) {
            return 0;
        }

        $remaining = $data['expires_at'] - time();
        return max(0, $remaining);
    }

    /**
     * Генерирует ключ на основе IP и действия
     */
    public static function key(string $action = 'form'): string
    {
        $ip = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
        // Хешируем для безопасности
        return hash('sha256', $action . ':' . $ip);
    }

    private function getFilePath(string $key): string
    {
        return $this->storageDir . '/' . $key . '.json';
    }

    private function readFile(string $file): array
    {
        if (!file_exists($file)) {
            return [];
        }

        $content = file_get_contents($file);
        return json_decode($content, true) ?? [];
    }

    /**
     * Очищает устаревшие файлы (вызывать периодически)
     */
    public function cleanup(): void
    {
        $files = glob($this->storageDir . '/*.json');
        $now = time();

        foreach ($files as $file) {
            $data = $this->readFile($file);
            if (isset($data['expires_at']) && $data['expires_at'] < $now) {
                unlink($file);
            }
        }
    }
}