<?php ob_start(); ?>
<div class="container mt-4">
    <h1>My Dashboard</h1>
    <p>Welcome back, <?= htmlspecialchars($_SESSION['name'] ?? 'customer') ?>!</p>
    
    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Browse Equipment</h5>
                    <p class="card-text">View all available rental items, check prices, and see real-time availability.</p>
                    <a href="?controller=page&action=shop" class="btn btn-primary">Shop Now</a>
                </div>
            </div>
        </div>
        <div class="col-md-6 mb-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">My Rentals</h5>
                    <p class="card-text">Track your active, pending, and completed bookings. View payment status.</p>
                    <a href="?controller=booking&action=myBookings" class="btn btn-primary">View Rentals</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';  // Use the main layout (not admin layout)
?>