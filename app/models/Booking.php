<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/database.php';

class Booking
{
    private PDO $db;

    public function __construct()
    {
        $this->db = require __DIR__ . '/../config/database.php';
    }

    public function getBookingItems(int $bookingId): array
    {
        $stmt = $this->db->prepare("SELECT equipment_id, quantity FROM booking_items WHERE booking_id = ?");
        $stmt->execute([$bookingId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get all bookings (admin) with optional filters
     */
    public function getAllBookings(string $status = null, string $startDate = null, string $endDate = null): array
    {
        $sql = "SELECT b.*, 
                       u.name as customer_name, 
                       u.email as customer_email,
                       GROUP_CONCAT(e.name SEPARATOR ', ') as equipment_names,
                       MAX(p.status) as payment_status,      -- ✅ aggregated
                       MAX(p.amount) as payment_amount       -- ✅ aggregated
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN booking_items bi ON b.id = bi.booking_id
                JOIN equipment e ON bi.equipment_id = e.id
                LEFT JOIN payments p ON b.id = p.booking_id
                WHERE 1=1";
        $params = [];
        
        if ($status) {
            $sql .= " AND b.status = ?";
            $params[] = $status;
        }
        if ($startDate) {
            $sql .= " AND b.start_date >= ?";
            $params[] = $startDate;
        }
        if ($endDate) {
            $sql .= " AND b.end_date <= ?";
            $params[] = $endDate;
        }
        
        $sql .= " GROUP BY b.id, u.name, u.email ORDER BY b.booking_date DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Update booking status
     */
    public function updateStatus(int $bookingId, string $status): bool
    {
        $allowed = ['pending', 'confirmed', 'completed', 'cancelled'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE bookings SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $bookingId]);
    }

    /**
     * Update payment status
     */
    public function updatePaymentStatus(int $bookingId, string $status): bool
    {
        $allowed = ['pending', 'paid', 'failed'];
        if (!in_array($status, $allowed)) {
            return false;
        }
        $stmt = $this->db->prepare("UPDATE payments SET status = ? WHERE booking_id = ?");
        return $stmt->execute([$status, $bookingId]);
    }

    /**
     * Get booking details for admin (no user_id restriction)
     */
    public function getBookingDetailsAdmin(int $bookingId): ?array
    {
        $sql = "SELECT b.*, 
                       u.name as customer_name, 
                       u.email as customer_email,
                       GROUP_CONCAT(CONCAT(e.name, ' (x', bi.quantity, ')') SEPARATOR ', ') as equipment_details,
                       MAX(p.amount) as payment_amount, 
                       MAX(p.status) as payment_status, 
                       MAX(p.payment_method) as payment_method
                FROM bookings b
                JOIN users u ON b.user_id = u.id
                JOIN booking_items bi ON b.id = bi.booking_id
                JOIN equipment e ON bi.equipment_id = e.id
                LEFT JOIN payments p ON b.id = p.booking_id
                WHERE b.id = ?
                GROUP BY b.id, u.name, u.email";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Check if equipment is available for given dates (only confirmed bookings block availability)
     */
    public function isEquipmentAvailable(int $equipmentId, string $startDate, string $endDate, int $requestedQty = 1): bool
    {
        // Total quantity already booked for this equipment during overlapping confirmed bookings
        $sql = "SELECT COALESCE(SUM(bi.quantity), 0) as total_booked
                FROM bookings b
                JOIN booking_items bi ON b.id = bi.booking_id
                WHERE bi.equipment_id = ?
                AND b.status = 'confirmed'
                AND b.start_date <= ? AND b.end_date >= ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$equipmentId, $endDate, $startDate]);
        $bookedQty = (int)$stmt->fetchColumn();

        // Total available quantity from equipment table
        $stmt = $this->db->prepare("SELECT quantity FROM equipment WHERE id = ?");
        $stmt->execute([$equipmentId]);
        $totalQty = (int)$stmt->fetchColumn();

        return ($totalQty - $bookedQty) >= $requestedQty;
    }

    /**
     * Create a new booking with items and payment record
     */
    public function createBooking(int $userId, int $equipmentId, string $startDate, string $endDate, int $quantity, float $pricePerDay): ?int
    {
        $days = (strtotime($endDate) - strtotime($startDate)) / 86400 + 1;
        $totalPrice = $days * $pricePerDay * $quantity;

        try {
            $this->db->beginTransaction();

            // Insert into bookings
            $sql = "INSERT INTO bookings (user_id, start_date, end_date, total_price, status) 
                    VALUES (?, ?, ?, ?, 'pending')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $startDate, $endDate, $totalPrice]);
            $bookingId = (int)$this->db->lastInsertId();

            // Insert into booking_items
            $sql = "INSERT INTO booking_items (booking_id, equipment_id, quantity, price) 
                    VALUES (?, ?, ?, ?)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$bookingId, $equipmentId, $quantity, $pricePerDay]);

            // Insert payment record (pending)
            $sql = "INSERT INTO payments (booking_id, amount, status) VALUES (?, ?, 'pending')";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$bookingId, $totalPrice]);

            $this->db->commit();
            return $bookingId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("Booking creation failed: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Get all bookings for a specific user (customer)
     */
    public function getUserBookings(int $userId): array
    {
        $sql = "SELECT b.*, 
                       GROUP_CONCAT(e.name SEPARATOR ', ') as equipment_names,
                       MAX(p.status) as payment_status
                FROM bookings b
                JOIN booking_items bi ON b.id = bi.booking_id
                JOIN equipment e ON bi.equipment_id = e.id
                LEFT JOIN payments p ON b.id = p.booking_id
                WHERE b.user_id = ?
                GROUP BY b.id
                ORDER BY b.booking_date DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get details of a single booking (for customer or admin)
     */
    public function getBookingDetails(int $bookingId, int $userId = null): ?array
    {
        $sql = "SELECT b.*, 
                       GROUP_CONCAT(CONCAT(e.name, ' (x', bi.quantity, ')') SEPARATOR ', ') as equipment_details,
                       MAX(p.amount) as payment_amount, 
                       MAX(p.status) as payment_status, 
                       MAX(p.payment_method) as payment_method
                FROM bookings b
                JOIN booking_items bi ON b.id = bi.booking_id
                JOIN equipment e ON bi.equipment_id = e.id
                LEFT JOIN payments p ON b.id = p.booking_id
                WHERE b.id = ?";
        if ($userId) {
            $sql .= " AND b.user_id = ?";
        }
        $sql .= " GROUP BY b.id";
        $stmt = $this->db->prepare($sql);
        if ($userId) {
            $stmt->execute([$bookingId, $userId]);
        } else {
            $stmt->execute([$bookingId]);
        }
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    public function getDb(): PDO
    {
        return $this->db;
    }
}