<?php

declare(strict_types=1);

namespace App\Models;

use core\Database;
use PDO;

abstract class BaseModel
{
    protected PDO $db;
    protected string $table;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table}
             WHERE deleted_at IS NULL
             ORDER BY created_at DESC
             LIMIT :limit OFFSET :offset"
        );
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function count(): int
    {
        $stmt = $this->db->query(
            "SELECT COUNT(*) FROM {$this->table} WHERE deleted_at IS NULL"
        );
        return (int) $stmt->fetchColumn();
    }

    public function findById(int $id): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM {$this->table} WHERE id = :id AND deleted_at IS NULL"
        );
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();

        return $row !== false ? $row : null;
    }
}
