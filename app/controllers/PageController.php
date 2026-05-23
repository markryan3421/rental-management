<?php

class PageController
{
    public function home()
    {
        require __DIR__ . '/../views/pages/home.php';
    }

    public function about()
    {
        require __DIR__ . '/../views/pages/about.php';
    }

    public function contact()
    {
        require __DIR__ . '/../views/pages/contact.php';
    }

    public function login()
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        require __DIR__ . '/../views/pages/registration.php';
    }

    public function shop()
    {
        // Require authentication
        require_once __DIR__ . '/../middleware/AuthMiddleware.php';
        AuthMiddleware::check();

        require_once __DIR__ . '/../models/Equipment.php';
        $equipmentModel = new Equipment();
        $equipmentList = $equipmentModel->getAll();
        
        require __DIR__ . '/../views/pages/shop.php';
    }

    public function checkAvailability()
    {
        header('Content-Type: application/json');
        
        $id = (int)($_GET['id'] ?? 0);
        $startDate = $_GET['start'] ?? '';
        $endDate = $_GET['end'] ?? '';
        
        if (!$id || !$startDate || !$endDate) {
            echo json_encode(['available' => false, 'error' => 'Missing parameters']);
            exit;
        }
        
        require_once __DIR__ . '/../models/Equipment.php';
        $equipment = new Equipment();
        $available = $equipment->checkAvailability($id, $startDate, $endDate);
        
        echo json_encode(['available' => $available]);
        exit;
    }
}