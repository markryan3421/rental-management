<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/models/Equipment.php';
require_once BASE_PATH . '/app/middleware/AuthMiddleware.php';

class EquipmentController
{
    private Equipment $equipment;

    public function __construct()
    {
        $action = $_GET['action'] ?? '';
        $publicActions = ['calendar', 'getBookedDates'];
        if (!in_array($action, $publicActions)) {
            AuthMiddleware::check();
            if (($_SESSION['role'] ?? '') !== 'admin') {
                header("Location: ?controller=dashboard&action=customer");
                exit;
            }
        }
        $this->equipment = new Equipment();
    }

    // Show calendar page
    public function calendar()
    {
        // Get all equipment for dropdown
        $equipmentList = $this->equipment->getAll();
        require __DIR__ . '/../views/equipment/calendar.php';
    }

    // AJAX endpoint: return booked events for an equipment
    public function getBookedDates()
    {
        header('Content-Type: application/json');
        $equipmentId = (int)($_GET['equipment_id'] ?? 0);
        if (!$equipmentId) {
            echo json_encode([]);
            exit;
        }

        $bookings = $this->equipment->getBookedDates($equipmentId);

        // Convert to FullCalendar event format
        $events = [];
        foreach ($bookings as $booking) {
            // FullCalendar uses all-day events: end date should be exclusive, so add one day
            $end = date('Y-m-d', strtotime($booking['end_date'] . ' +1 day'));
            $events[] = [
                'title' => 'Booked',
                'start' => $booking['start_date'],
                'end' => $end,
                'color' => '#dc3545',        // red background
                'textColor' => 'white'       // white text
            ];
        }
        echo json_encode($events);
        exit;
    }

    public function index(): void
    {
        $equipmentList = $this->equipment->getAll();
        require_once BASE_PATH . '/app/views/equipment/index.php';
    }

    public function create(): void
    {
        require_once BASE_PATH . '/app/views/equipment/create.php';
    }

    public function store(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('equipment', 'index');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price_per_day' => (float) ($_POST['price_per_day'] ?? 0),
            'quantity' => (int) ($_POST['quantity'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'status' => $_POST['status'] ?? 'available'
        ];

        if ($this->equipment->create($data)) {
            Flash::set('success', 'Equipment added successfully.');
        } else {
            Flash::set('error', 'Failed to add equipment.');
        }

        $this->redirect('equipment', 'index');
    }

    public function edit(int $id): void
    {
        $item = $this->equipment->find($id);
        if (!$item) {
            Flash::set('error', 'Equipment not found.');
            $this->redirect('equipment', 'index');
            return;
        }
        require_once BASE_PATH . '/app/views/equipment/edit.php';
    }

    public function update(int $id): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('equipment', 'index');
            return;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price_per_day' => (float) ($_POST['price_per_day'] ?? 0),
            'quantity' => (int) ($_POST['quantity'] ?? 0),
            'image' => $_POST['image'] ?? '',
            'status' => $_POST['status'] ?? 'available'
        ];

        if ($this->equipment->update($id, $data)) {
            Flash::set('success', 'Equipment updated successfully.');
        } else {
            Flash::set('error', 'Failed to update equipment.');
        }

        $this->redirect('equipment', 'index');
    }

    public function delete(int $id): void
    {
        if ($this->equipment->delete($id)) {
            Flash::set('success', 'Equipment deleted successfully.');
        } else {
            Flash::set('error', 'Failed to delete equipment.');
        }

        $this->redirect('equipment', 'index');
    }

    private function redirect(string $controller, string $action): void
    {
        header("Location: ?controller=$controller&action=$action");
        exit;
    }
}