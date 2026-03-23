<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasRelationships;
use core\Database;
use PDO;

abstract class BaseModel
{
    use HasRelationships;

    protected PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
        // дефолтный режим выборки — только именованные ключи
        $this->db->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    abstract protected function table(): Table;

    public function getTable(): string
    {
        return $this->table()->value;
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table()->value}`
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit',  $limit,  PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM `{$this->table()->value}` WHERE deleted_at IS NULL"
        );

        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table()->value}`
             WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    // BaseModel.php

    public function create(array $data): ?array
    {
        $columns      = implode(', ', array_map(fn($k) => "`$k`", array_keys($data)));
        $placeholders = implode(', ', array_map(fn($k) => ":$k", array_keys($data)));

        $stmt = $this->db->prepare(
            "INSERT INTO `{$this->table()->value}` ($columns) VALUES ($placeholders)"
        );
        $stmt->execute($data);

        $id = (int) $this->db->lastInsertId();

        return $this->findById($id);
    }

}