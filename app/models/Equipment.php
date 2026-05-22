<?php
declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Equipment
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM equipment ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM equipment WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO equipment (name, description, price_per_day, quantity, image, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price_per_day'],
            $data['quantity'],
            $data['image'] ?? null,
            $data['status'] ?? 'available'
        ]);
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE equipment 
            SET name = ?, description = ?, price_per_day = ?, quantity = ?, image = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['description'],
            $data['price_per_day'],
            $data['quantity'],
            $data['image'] ?? null,
            $data['status'] ?? 'available',
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM equipment WHERE id = ?");
        return $stmt->execute([$id]);
    }
}