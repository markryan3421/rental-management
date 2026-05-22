<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Registration
{
    private PDO $db;

    public function __construct()
    {
        // database.php returns a PDO instance
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function create(string $name, string $email, string $password): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (name, email, password, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        return $stmt->execute([$name, $email, $password]);
    }

    public function findByEmail(string $email): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }
}