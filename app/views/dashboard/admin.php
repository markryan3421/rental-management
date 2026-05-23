<?php ob_start(); ?>
<div class="container mt-4">
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'admin') ?>.</p>
    <div class="row">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Equipment Management</h5>
                    <p class="card-text">Add, edit, or remove rental items. Update inventory and pricing.</p>
                    <a href="?controller=equipment&action=index" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">All Bookings</h5>
                    <p class="card-text">View and manage customer bookings, confirm or cancel.</p>
                    <a href="?controller=booking&action=adminIndex" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Reports</h5>
                    <p class="card-text">View sales, rental trends, and analytics.</p>
                    <a href="#" class="btn btn-sm btn-primary">Coming Soon</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>