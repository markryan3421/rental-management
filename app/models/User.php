<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class User
{
    private PDO $db;

    public function __construct()
    {
        // database.php returns a PDO instance
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function all(): array
    {
        return $this->db->query("SELECT * FROM users ORDER BY id ASC")->fetchAll() ?? [];
    }

    public function find(int $id): ?array
    {
        $queryData = $this->db->prepare("SELECT * FROM users WHERE id = :id");
        $queryData->execute(['id' => $id]);
        return $queryData->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(string $name, string $email, string $hashedPassword): bool
    {
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, created_at) VALUES (?, ?, ?, NOW())");
        return $stmt->execute([$name, $email, $hashedPassword]);
    }

    public function update(int $id, string $name, string $email): bool
    {
        $stmt = $this->db->prepare("UPDATE users SET name=?, email=? WHERE id=?");
        return $stmt->execute([$name, $email, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
}