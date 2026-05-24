<?php ob_start(); ?>
<style>
:root {
    --cal-accent:  #0b2ef5;   /* amber – energetic, rental-industry feel */
    --cal-danger:  #ef4444;   /* booked */
    --cal-available: #22c55e; /* available */
  }
/* ── Page header ─────────────────────────────────────────────── */
.cal-hero {
  background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
  border-bottom: 3px solid var(--cal-accent);
  padding: 2rem 0 1.75rem;
  margin-bottom: 2rem;
}

.cal-hero .badge-accent {
  background-color: var(--cal-accent);
  color: #1e293b;
  font-weight: 700;
  font-size: .7rem;
  letter-spacing: .08em;
  text-transform: uppercase;
  padding: .35em .75em;
  border-radius: 2rem;
}

.cal-hero h1 {
  font-size: 1.9rem;
  font-weight: 700;
  color: #f8fafc;
  margin: .4rem 0 .25rem;
  letter-spacing: -.02em;
}

.cal-hero p {
  color: #94a3b8;
  margin: 0;
  font-size: .95rem;
}
</style>

<div class="cal-hero">
    <div class="container">
        <span class="badge-accent">
        <i class="bi bi-bookmarks"></i> Rentals
        </span>
        <h1>My Rentals</h1>
        <p>Manage all the rentals booked here.</p>
    </div>
</div>

<div class="container mt-4">
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