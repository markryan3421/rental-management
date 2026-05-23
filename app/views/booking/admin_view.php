<?php ob_start(); ?>
<div class="container mt-4">
    <?php if (isset($booking)): ?>
      <h1>Booking #<?= $booking['id'] ?></h1>
      <div class="row">
          <div class="col-md-6">
              <div class="card shadow-sm mb-4">
                  <div class="card-header bg-primary text-white">
                      <h5 class="mb-0">Booking Details</h5>
                  </div>
                  <div class="card-body">
                      <p><strong>Customer:</strong> <?= htmlspecialchars($booking['customer_name']) ?> (<?= htmlspecialchars($booking['customer_email']) ?>)</p>
                      <p><strong>Equipment:</strong> <?= htmlspecialchars($booking['equipment_details']) ?></p>
                      <p><strong>Start Date:</strong> <?= date('F d, Y', strtotime($booking['start_date'])) ?></p>
                      <p><strong>End Date:</strong> <?= date('F d, Y', strtotime($booking['end_date'])) ?></p>
                      <p><strong>Total Price:</strong> ₱<?= number_format($booking['total_price'], 2) ?></p>
                      <p><strong>Booking Status:</strong> 
                          <span class="badge bg-<?= ['pending'=>'warning','confirmed'=>'success','completed'=>'secondary','cancelled'=>'danger'][$booking['status']] ?? 'secondary' ?>">
                              <?= ucfirst($booking['status']) ?>
                          </span>
                      </p>
                      <p><strong>Payment Status:</strong> 
                          <span class="badge bg-<?= ($booking['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'danger' ?>">
                              <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
                          </span>
                      </p>
                  </div>
              </div>
          </div>
          
          <div class="col-md-6">
                <!-- Update Booking Status -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-secondary text-white">
                        <h5 class="mb-0">Update Booking Status</h5>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($booking['status'], ['completed', 'cancelled'])): ?>
                            <div class="alert alert-warning mb-0">
                                <i class="bi bi-lock-fill"></i> This booking is <?= $booking['status'] ?> and cannot be modified.
                            </div>
                        <?php else: ?>
                            <form action="?controller=booking&action=adminUpdateStatus" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">New Status</label>
                                    <select name="status" class="form-select">
                                        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                                        <?php if ($booking['status'] !== 'confirmed'): ?>
                                        <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                        <?php endif; ?>
                                        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Status</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
              
                <!-- Update Payment Status -->
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">Payment Management</h5>
                    </div>
                    <div class="card-body">
                        <?php if (in_array($booking['status'], ['completed', 'cancelled'])): ?>
                            <div class="alert alert-secondary mb-0">
                                Payment status is locked because booking is <?= $booking['status'] ?>.
                            </div>
                        <?php else: ?>
                            <form action="?controller=booking&action=adminUpdatePayment" method="POST">
                                <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                                <div class="mb-3">
                                    <label class="form-label">Payment Status</label>
                                    <select name="payment_status" class="form-select">
                                        <option value="pending" <?= ($booking['payment_status'] ?? 'pending') === 'pending' ? 'selected' : '' ?>>Pending</option>
                                        <option value="paid" <?= ($booking['payment_status'] ?? '') === 'paid' ? 'selected' : '' ?>>Paid</option>
                                        <option value="failed" <?= ($booking['payment_status'] ?? '') === 'failed' ? 'selected' : '' ?>>Failed</option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-success">Update Payment</button>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
          </div>
      </div>
    <?php else: ?>
        <div class="alert alert-danger">No data found.</div>
    <?php endif; ?>
    
    <div class="mt-3">
        <a href="?controller=booking&action=adminIndex" class="btn btn-secondary">Back to All Bookings</a>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>