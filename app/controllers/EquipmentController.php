<?php
declare(strict_types=1);

require_once BASE_PATH . '/app/models/Equipment.php';
require_once BASE_PATH . '/app/middleware/AuthMiddleware.php';

class EquipmentController
{
    private Equipment $equipment;

    public function __construct()
    {
        $this->equipment = new Equipment();

        AuthMiddleware::check();
        // Only admin can access equipment management
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: ?controller=dashboard&action=customer");
            exit;
        }
    }

    public function index(): void
    {
        $equipmentList = $this->equipment->all();
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