<?php ob_start(); ?>
<div class="container mt-4">
    <h1>My Rentals</h1>
    <?php if (empty($bookings)): ?>
        <div class="alert alert-info">You have no bookings yet. <a href="?controller=page&action=shop">Start shopping</a></div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Equipment</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                        <th>Total Price</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td>#<?= $booking['id'] ?></td>
                            <td><?= htmlspecialchars($booking['equipment_names']) ?></td>
                            <td><?= date('M d, Y', strtotime($booking['start_date'])) ?></td>
                            <td><?= date('M d, Y', strtotime($booking['end_date'])) ?></td>
                            <td>₱<?= number_format($booking['total_price'], 2) ?></td>
                            <td>
                                <?php
                                $statusClass = [
                                    'pending' => 'warning',
                                    'confirmed' => 'success',
                                    'completed' => 'secondary',
                                    'cancelled' => 'danger'
                                ][$booking['status']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($booking['status']) ?></span>
                            </td>
                            <td>
                                <?php
                                $paymentStatus = $booking['payment_status'] ?? 'pending';
                                $payClass = $paymentStatus === 'paid' ? 'success' : 'danger';
                                ?>
                                <span class="badge bg-<?= $payClass ?>"><?= ucfirst($paymentStatus) ?></span>
                            </td>
                            <td>
                                <a href="?controller=booking&action=view&id=<?= $booking['id'] ?>" class="btn btn-sm btn-info">View</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>