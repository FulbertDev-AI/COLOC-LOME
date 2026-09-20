<?php

declare(strict_types=1);

namespace App\Core;

use PDO;

abstract class Model
{
    protected string $table;
    protected string $primaryKey = 'id';

    protected function db(): PDO
    {
        return Database::pdo();
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db()->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch();

        return $row ?: null;
    }

    public function all(string $orderBy = 'id DESC'): array
    {
        return $this->db()->query("SELECT * FROM {$this->table} ORDER BY {$orderBy}")->fetchAll();
    }

    public function create(array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_map(static fn ($k) => ':' . $k, array_keys($data)));
        $stmt = $this->db()->prepare("INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})");
        $stmt->execute($data);

        return (int) $this->db()->lastInsertId();
    }

    public function update(int $id, array $data): void
    {
        $sets = implode(', ', array_map(static fn ($k) => "{$k} = :{$k}", array_keys($data)));
        $data[$this->primaryKey] = $id;
        $stmt = $this->db()->prepare("UPDATE {$this->table} SET {$sets} WHERE {$this->primaryKey} = :{$this->primaryKey}");
        $stmt->execute($data);
    }
}
