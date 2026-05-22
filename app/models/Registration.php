<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Registration
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    public function create(string $name, string $email, string $password): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, created_at, updated_at)
            VALUES (?, ?, ?, NOW(), NOW())
        ");

        return $stmt->execute([$name, $email, $password]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch() ?: null;
    }
}