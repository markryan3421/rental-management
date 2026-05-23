<?php ob_start(); ?>
<div class="container mt-4">
  <?php if (isset($booking)): ?>
    <h1>Booking #<?= $booking['id'] ?></h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Booking Details</h5>
                    <p><strong>Equipment:</strong> <?= htmlspecialchars($booking['equipment_details']) ?></p>
                    <p><strong>Start Date:</strong> <?= date('F d, Y', strtotime($booking['start_date'])) ?></p>
                    <p><strong>End Date:</strong> <?= date('F d, Y', strtotime($booking['end_date'])) ?></p>
                    <p><strong>Total Price:</strong> ₱<?= number_format($booking['total_price'], 2) ?></p>
                    <p><strong>Status:</strong> 
                        <span class="badge bg-<?= ['pending'=>'warning','confirmed'=>'success','completed'=>'secondary','cancelled'=>'danger'][$booking['status']] ?? 'secondary' ?>">
                            <?= ucfirst($booking['status']) ?>
                        </span>
                    </p>
                    <p><strong>Payment Status:</strong> 
                        <span class="badge bg-<?= $booking['payment_status'] === 'paid' ? 'success' : 'danger' ?>">
                            <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
                        </span>
                    </p>
                    <a href="?controller=booking&action=myBookings" class="btn btn-secondary">Back to My Rentals</a>
                    <?php if ($booking['status'] === 'pending'): ?>
                        <form action="?controller=booking&action=cancel" method="POST" class="d-inline">
                            <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                            <button type="submit" class="btn btn-danger" onclick="return confirm('Cancel this booking?')">Cancel Booking</button>
                        </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger">No data found.</div>
  <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>