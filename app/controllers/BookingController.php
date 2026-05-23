<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Booking.php';
require_once __DIR__ . '/../models/Equipment.php';
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class BookingController
{
    private Booking $bookingModel;
    private Equipment $equipmentModel;

    public function __construct()
    {
        AuthMiddleware::check(); // Must be logged in
        $this->bookingModel = new Booking();
        $this->equipmentModel = new Equipment();
    }

    // Admin: List all bookings
    public function adminIndex()
    {
        // Role check
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: ?controller=dashboard&action=customer");
            exit;
        }
        
        $status = $_GET['status'] ?? null;
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        $bookings = $this->bookingModel->getAllBookings($status, $startDate, $endDate);
        
        require __DIR__ . '/../views/booking/admin_index.php';
    }

    // Admin: View single booking details
    public function adminView()
    {
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: ?controller=dashboard&action=customer");
            exit;
        }
        
        $bookingId = (int)($_GET['id'] ?? 0);
        $booking = $this->bookingModel->getBookingDetailsAdmin($bookingId);
        if (!$booking) {
            Flash::set('error', 'Booking not found.');
            header("Location: ?controller=booking&action=adminIndex");
            exit;
        }
        require __DIR__ . '/../views/booking/admin_view.php';
    }

    // Admin: Update status (confirm, complete, cancel)
    public function adminUpdateStatus()
    {
        if (($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Forbidden');
        }
        
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $newStatus = $_POST['status'] ?? '';
        
        $currentBooking = $this->bookingModel->getBookingDetailsAdmin($bookingId);
        if (!$currentBooking) {
            Flash::set('error', 'Booking not found.');
            header("Location: ?controller=booking&action=adminIndex");
            exit;
        }
        
        $oldStatus = $currentBooking['status'];
        $items = $this->bookingModel->getBookingItems($bookingId);

        if (in_array($oldStatus, ['completed', 'cancelled'])) {
            Flash::set('error', 'Cannot modify a completed or cancelled booking.');
            header("Location: ?controller=booking&action=adminView&id={$bookingId}");
            exit;
        }
        
        // Stock deduction when moving to confirmed (from pending or anything else non-confirmed)
        if ($newStatus === 'confirmed' && $oldStatus !== 'confirmed') {
            foreach ($items as $item) {
                $success = $this->equipmentModel->decreaseQuantity($item['equipment_id'], $item['quantity']);
                if (!$success) {
                    Flash::set('error', "Insufficient stock for equipment ID {$item['equipment_id']}.");
                    header("Location: ?controller=booking&action=adminView&id={$bookingId}");
                    exit;
                }
            }
        }
        
        // Stock restoration when moving away from confirmed (to cancelled OR completed)
        if ($oldStatus === 'confirmed' && ($newStatus === 'cancelled' || $newStatus === 'completed')) {
            foreach ($items as $item) {
                $this->equipmentModel->increaseQuantity($item['equipment_id'], $item['quantity']);
            }
        }
        
        // If moving from pending to cancelled, no stock change.
        
        // Update status
        if ($this->bookingModel->updateStatus($bookingId, $newStatus)) {
            Flash::set('success', "Booking status updated to {$newStatus}.");
        } else {
            Flash::set('error', 'Failed to update status.');
        }
        
        header("Location: ?controller=booking&action=adminView&id={$bookingId}");
        exit;
    }

    // Admin: Update payment status
    public function adminUpdatePayment()
    {
        if (($_SESSION['role'] ?? '') !== 'admin') {
            http_response_code(403);
            exit('Forbidden');
        }
        
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $currentBooking = $this->bookingModel->getBookingDetailsAdmin($bookingId);
        if (!$currentBooking) {
            Flash::set('error', 'Booking not found.');
            header("Location: ?controller=booking&action=adminIndex");
            exit;
        }
        
        // Prevent payment update if booking is completed or cancelled
        if (in_array($currentBooking['status'], ['completed', 'cancelled'])) {
            Flash::set('error', 'Cannot change payment status for a completed or cancelled booking.');
            header("Location: ?controller=booking&action=adminView&id={$bookingId}");
            exit;
        }
        
        $status = $_POST['payment_status'] ?? '';
        if ($this->bookingModel->updatePaymentStatus($bookingId, $status)) {
            Flash::set('success', "Payment status updated to {$status}.");
        } else {
            Flash::set('error', 'Failed to update payment status.');
        }
        header("Location: ?controller=booking&action=adminView&id={$bookingId}");
        exit;
    }

    public function cancel()
    {
        $bookingId = (int)($_POST['booking_id'] ?? 0);
        $userId = $_SESSION['user_id'];
        $booking = $this->bookingModel->getBookingDetails($bookingId, $userId);
        if ($booking && $booking['status'] === 'pending') {
            $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ? AND user_id = ?";
            $db = $this->bookingModel->getDb(); // you'd need to expose getDb() or add a method in Booking model
            $stmt = $db->prepare($sql);
            $stmt->execute([$bookingId, $userId]);
            Flash::set('success', 'Booking cancelled.');
        } else {
            Flash::set('error', 'Cannot cancel this booking.');
        }
        header("Location: ?controller=booking&action=myBookings");
        exit;
    }

    public function create()
    {
        $equipmentId = (int)($_GET['equipment_id'] ?? 0);
        $equipment = $this->equipmentModel->getById($equipmentId);
        if (!$equipment) {
            Flash::set('error', 'Equipment not found.');
            header("Location: ?controller=page&action=shop");
            exit;
        }

        // Pass equipment to view
        $equipment = $equipment;
        require __DIR__ . '/../views/booking/create.php';
    }

    public function store()
    {
        $equipmentId = (int)($_POST['equipment_id'] ?? 0);
        $startDate = $_POST['start_date'] ?? '';
        $endDate = $_POST['end_date'] ?? '';
        $quantity = (int)($_POST['quantity'] ?? 1);

        // Validation
        if (!$equipmentId || !$startDate || !$endDate || $quantity < 1) {
            Flash::set('error', 'All fields are required.');
            header("Location: ?controller=booking&action=create&equipment_id=$equipmentId");
            exit;
        }

        // Check date range
        if (strtotime($startDate) > strtotime($endDate)) {
            Flash::set('error', 'End date must be after start date.');
            header("Location: ?controller=booking&action=create&equipment_id=$equipmentId");
            exit;
        }

        // Get equipment details
        $equipment = $this->equipmentModel->getById($equipmentId);
        if (!$equipment) {
            Flash::set('error', 'Equipment not found.');
            header("Location: ?controller=page&action=shop");
            exit;
        }

        // Check availability again before booking
        $available = $this->bookingModel->isEquipmentAvailable($equipmentId, $startDate, $endDate, $quantity);
        if (!$available) {
            Flash::set('error', 'Selected equipment is not available for the chosen dates. Please adjust quantity or dates.');
            header("Location: ?controller=booking&action=create&equipment_id=$equipmentId");
            exit;
        }

        // Check quantity against total stock
        if ($quantity > $equipment['quantity']) {
            Flash::set('error', 'Requested quantity exceeds available stock.');
            header("Location: ?controller=booking&action=create&equipment_id=$equipmentId");
            exit;
        }

        $userId = $_SESSION['user_id'];
        $bookingId = $this->bookingModel->createBooking($userId, $equipmentId, $startDate, $endDate, $quantity, (float)$equipment['price_per_day']);

        if ($bookingId) {
            Flash::set('success', 'Booking created successfully! Awaiting admin confirmation.');
            header("Location: ?controller=booking&action=myBookings");
        } else {
            Flash::set('error', 'Failed to create booking. Please try again.');
            header("Location: ?controller=booking&action=create&equipment_id=$equipmentId");
        }
        exit;
    }

    public function myBookings()
    {
        $userId = $_SESSION['user_id'];
        $bookings = $this->bookingModel->getUserBookings($userId);
        require __DIR__ . '/../views/booking/myBookings.php';
    }

    public function view()
    {
        $bookingId = (int)($_GET['id'] ?? 0);
        $userId = $_SESSION['user_id'];
        $booking = $this->bookingModel->getBookingDetails($bookingId, $userId);
        if (!$booking) {
            Flash::set('error', 'Booking not found.');
            header("Location: ?controller=booking&action=myBookings");
            exit;
        }
        require __DIR__ . '/../views/booking/view.php';
    }
}