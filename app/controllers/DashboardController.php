<?php

declare(strict_types=1);
require_once __DIR__ . '/../middleware/AuthMiddleware.php';

class DashboardController
{
    public function __construct()
    {
         AuthMiddleware::check();
    }
    public function index()
    {
        require __DIR__ . '/../views/dashboard/index.php';
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
