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

    public function getBookedDates(int $equipmentId): array
    {
        $sql = "SELECT b.start_date, b.end_date
                FROM bookings b
                JOIN booking_items bi ON b.id = bi.booking_id
                WHERE bi.equipment_id = ? AND b.status = 'confirmed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$equipmentId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Decrease quantity when a booking is confirmed
     */
    public function decreaseQuantity(int $equipmentId, int $quantity): bool
    {
        $stmt = $this->db->prepare("UPDATE equipment SET quantity = quantity - ? WHERE id = ? AND quantity >= ?");
        return $stmt->execute([$quantity, $equipmentId, $quantity]);
    }

    /**
     * Increase quantity when a booking is cancelled (restore stock)
     */
    public function increaseQuantity(int $equipmentId, int $quantity): bool
    {
        $stmt = $this->db->prepare("UPDATE equipment SET quantity = quantity + ? WHERE id = ?");
        return $stmt->execute([$quantity, $equipmentId]);
    }

    public function getAll(): array
    {
        $stmt = $this->db->query("SELECT * FROM equipment WHERE status = 'available' ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM equipment WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function checkAvailability(int $equipmentId, string $startDate, string $endDate): bool
    {
        // Check if there's any confirmed booking that overlaps with requested dates
        $sql = "SELECT COUNT(*) FROM bookings b
                JOIN booking_items bi ON b.id = bi.booking_id
                WHERE bi.equipment_id = ?
                AND b.status = 'confirmed'
                AND b.start_date <= ? AND b.end_date >= ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$equipmentId, $endDate, $startDate]);
        $count = $stmt->fetchColumn();
        return $count == 0;
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