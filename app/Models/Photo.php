<?php

declare(strict_types=1);

namespace App\Models;

class Photo extends BaseModel
{
    protected string $table = 'photos';

    /**
     * SQL для создания таблицы:
     *
     * CREATE TABLE photos (
     *   id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     *   title       VARCHAR(255) NOT NULL,
     *   description TEXT,
     *   url         VARCHAR(500) NOT NULL,
     *   thumbnail   VARCHAR(500),
     *   width       INT UNSIGNED,
     *   height      INT UNSIGNED,
     *   size        INT UNSIGNED COMMENT 'bytes',
     *   author_id   INT UNSIGNED,
     *   tags        JSON,
     *   created_at  DATETIME DEFAULT CURRENT_TIMESTAMP,
     *   updated_at  DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
     *   deleted_at  DATETIME DEFAULT NULL
     * );
     */

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT
                p.id,
                p.title,
                p.description,
                p.url,
                p.thumbnail,
                p.width,
                p.height,
                p.size,
                p.tags,
                p.created_at,
                u.name AS author
             FROM {$this->table} p
             LEFT JOIN users u ON u.id = p.author_id
             WHERE p.deleted_at IS NULL
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return array_map([$this, 'format'], $stmt->fetchAll());
    }

    private function format(array $row): array
    {
        $row['tags'] = $row['tags'] ? json_decode($row['tags'], true) : [];
        $row['type'] = 'photo';
        return $row;
    }
}
