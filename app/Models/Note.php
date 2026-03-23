<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class Note extends BaseModel
{
    protected string $table = 'notes';
    protected function table(): Table { return Table::Notes; }

    /**
     * CREATE TABLE notes (
     *   id          INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
     *   title       VARCHAR(255) NOT NULL,
     *   content     TEXT NOT NULL,
     *   color       VARCHAR(7) DEFAULT '#ffffff',
     *   is_pinned   TINYINT(1) DEFAULT 0,
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
                n.id,
                n.title,
                n.content,
                n.color,
                n.is_pinned,
                n.tags,
                n.created_at,
                u.name AS author
             FROM {$this->table} n
             LEFT JOIN users u ON u.id = n.author_id
             WHERE n.deleted_at IS NULL
             ORDER BY n.is_pinned DESC, n.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map([$this, 'format'], $stmt->fetchAll());
    }

    private function format(array $row): array
    {
        $row['tags']      = isset($row['tags']) ? json_decode($row['tags'], true) : [];
        $row['is_pinned'] = (bool) $row['is_pinned'];
        $row['excerpt']   = mb_substr(strip_tags($row['content']), 0, 160);
        $row['type']      = 'note';
        return $row;
    }
}
