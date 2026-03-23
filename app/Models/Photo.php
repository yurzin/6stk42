<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\BelongsTo;
use PDO;

class Photo extends BaseModel
{
    protected function table(): Table { return Table::Photos; }

    /**
     * Связь: фото принадлежит пользователю (автору).
     * Использование: $photo->author()->get($row['author_id'])
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * @deprecated Используй author()->get($photoId)
     */
    public function getAuthor(int $photoId): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT u.*
             FROM users u
             INNER JOIN `{$this->table()->value}` p ON p.author_id = u.id
             WHERE p.id = :photo_id
               AND p.deleted_at IS NULL
               AND u.deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->execute([':photo_id' => $photoId]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

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
                p.created_at,
                u.id   AS author_id,
                u.name AS author_name,
                GROUP_CONCAT(DISTINCT t.name ORDER BY t.name SEPARATOR ',') AS tags
             FROM `{$this->table()->value}` p
             LEFT JOIN users u  ON u.id = p.author_id AND u.deleted_at IS NULL
             LEFT JOIN taggables tg ON tg.taggable_id = p.id AND tg.taggable_type = 'photo'
             LEFT JOIN tags t   ON t.id = tg.tag_id
             WHERE p.deleted_at IS NULL
             GROUP BY p.id, p.title, p.description, p.url,
                      p.thumbnail, p.width, p.height, p.size,
                      p.created_at, u.id, u.name
             ORDER BY p.created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return array_map([$this, 'format'], $stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    // protected — чтобы User::getPhotos() мог вызвать через array_map
    protected function format(array $row): array
    {
        $row['tags'] = $row['tags']
            ? array_values(array_unique(explode(',', $row['tags'])))
            : [];

        $row['author'] = $row['author_id'] ? [
            'id'   => (int) $row['author_id'],
            'name' => $row['author_name'],
        ] : null;

        unset($row['author_id'], $row['author_name']);

        $row['type'] = 'photo';

        return $row;
    }
}