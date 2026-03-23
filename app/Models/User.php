<?php

declare(strict_types=1);

namespace App\Models;

use PDO;

class User extends BaseModel
{
    protected function table(): Table { return Table::Users; }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM `{$this->table()->value}`
             WHERE email = :email AND deleted_at IS NULL
             LIMIT 1"
        );
        $stmt->execute([':email' => strtolower(trim($email))]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function photos(): \App\Models\Concerns\HasMany
    {
        return $this->hasMany(Photo::class, 'author_id');
    }
}