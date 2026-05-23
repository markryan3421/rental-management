<?php

declare(strict_types=1);
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class DashboardController
{
    public function __construct()
    {
        AuthMiddleware::check();
    }

    public function admin()
    {
        // Only admin allowed
        if (($_SESSION['role'] ?? '') !== 'admin') {
            header("Location: ?controller=dashboard&action=customer");
            exit;
        }
        require __DIR__ . '/../views/dashboard/admin.php';
    }

    public function customer()
    {
        require __DIR__ . '/../views/dashboard/customer.php';
    }

    public function stats()
    {
        require __DIR__ . '/../views/dashboard/stats.php';
    }

    public function settings()
    {
        require __DIR__ . '/../views/dashboard/settings.php';
    }
}