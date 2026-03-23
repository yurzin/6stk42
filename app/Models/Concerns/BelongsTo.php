<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use PDO;

/**
 * Представляет связь belongsTo.
 *
 * $photo->user()->get($photo['author_id'])
 */
final class BelongsTo
{
    public function __construct(
        private readonly \PDO  $db,
        private readonly string $relatedClass,
        private readonly string $foreignKey,
    ) {}

    /**
     * Загрузить связанную запись по значению внешнего ключа.
     */
    public function get(int|string $foreignKeyValue): ?array
    {
        /** @var \App\Models\BaseModel $model */
        $model = new $this->relatedClass($this->db);
        $table = $model->getTable();

        $stmt = $this->db->prepare(
            "SELECT * FROM `{$table}` WHERE id = :id AND deleted_at IS NULL LIMIT 1"
        );
        $stmt->execute([':id' => $foreignKeyValue]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}