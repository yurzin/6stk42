<?php

declare(strict_types=1);

namespace App\Models\Concerns;

use PDO;

/**
 * Представляет связь hasMany.
 *
 * $user->photos()->get($user['id'])
 * $user->photos()->get($user['id'], limit: 10, offset: 20)
 */
final class HasMany
{
    public function __construct(
        private readonly \PDO   $db,
        private readonly string $relatedClass,
        private readonly string $foreignKey,
    ) {}

    /**
     * Получить все связанные записи.
     */
    public function get(int|string $ownerKeyValue, int $limit = 20, int $offset = 0): array
    {
        /** @var \App\Models\BaseModel $model */
        $model = new $this->relatedClass($this->db);
        $table = $model->getTable();

        $stmt = $this->db->prepare(
            "SELECT * FROM `{$table}`
             WHERE `{$this->foreignKey}` = :owner AND deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':owner',  $ownerKeyValue, PDO::PARAM_INT);
        $stmt->bindValue(':limit',  $limit,          PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset,         PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Посчитать количество связанных записей.
     */
    public function count(int|string $ownerKeyValue): int
    {
        /** @var \App\Models\BaseModel $model */
        $model = new $this->relatedClass($this->db);
        $table = $model->getTable();

        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM `{$table}`
             WHERE `{$this->foreignKey}` = :owner AND deleted_at IS NULL"
        );
        $stmt->execute([':owner' => $ownerKeyValue]);

        return (int)$stmt->fetchColumn();
    }
}