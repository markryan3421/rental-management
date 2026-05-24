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

        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/equipment/';
            
            // Create directory if not exists
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                $imagePath = 'uploads/equipment/' . $fileName;
            } else {
                Flash::set('error', 'Image upload failed.');
                $this->redirect('equipment', 'create');
                return;
            }
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price_per_day' => (float) ($_POST['price_per_day'] ?? 0),
            'quantity' => (int) ($_POST['quantity'] ?? 0),
            'image' => $imagePath,          // store path or null
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

        // Fetch current equipment data to get old image
        $currentEquipment = $this->equipment->getById($id);
        $imagePath = $currentEquipment['image'] ?? null; // keep old by default

        // Handle new image upload
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../public/uploads/equipment/';
            
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            
            $fileExt = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $fileName = time() . '_' . uniqid() . '.' . $fileExt;
            $targetFile = $uploadDir . $fileName;
            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetFile)) {
                // Delete old image if exists
                if ($imagePath && file_exists(__DIR__ . '/../../public/' . $imagePath)) {
                    unlink(__DIR__ . '/../../public/' . $imagePath);
                }
                $imagePath = 'uploads/equipment/' . $fileName;
            } else {
                Flash::set('error', 'Image upload failed.');
                $this->redirect('equipment', 'edit', ['id' => $id]);
                return;
            }
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'description' => $_POST['description'] ?? '',
            'price_per_day' => (float) ($_POST['price_per_day'] ?? 0),
            'quantity' => (int) ($_POST['quantity'] ?? 0),
            'image' => $imagePath,
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