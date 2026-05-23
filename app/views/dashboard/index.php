<?php ob_start(); ?>
<div class="container mt-4">
    <h1>Dashboard</h1>
    <p>Welcome to the Rental Management System Dashboard.</p>
    <p>You are logged in as: <?= htmlspecialchars($_SESSION['user']['name'] ?? 'User') ?></p>
    <div class="row">
        <div class="col-md-4">
            <a href="?controller=equipment&action=index" class="text-decoration-none">
              <div class="card">
                <div class="card-body">
                  <h5 class="card-title">Equipment</h5>
                  <p class="card-text">View and manage all rental items.</p>
                </div>
              </div>
            </a>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Active Bookings</div>
                <div class="card-body">
                    <a href="?controller=booking&action=adminIndex" class="text-decoration-none">
                        View
                    </a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-header">Pending Payments</div>
                <div class="card-body">
                    <h5 class="card-title">Coming Soon</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$dashboardContent = ob_get_clean();
require __DIR__ . '/../layouts/admin/layout.php';