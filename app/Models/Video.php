<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Video extends BaseModel
{
    protected string $table = 'videos';

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map([$this, 'format'], $stmt->fetchAll());
    }

    private function format(array $row): array
    {
        $row['tags']           = isset($row['tags']) ? json_decode($row['tags'], true) : [];
        $row['duration_human'] = $this->formatDuration((int)($row['duration'] ?? 0));
        $row['type']           = 'video';
        return $row;
    }

    private function formatDuration(int $seconds): string
    {
        $h = intdiv($seconds, 3600);
        $m = intdiv($seconds % 3600, 60);
        $s = $seconds % 60;

        return $h > 0
            ? sprintf('%d:%02d:%02d', $h, $m, $s)
            : sprintf('%d:%02d', $m, $s);
    }
}