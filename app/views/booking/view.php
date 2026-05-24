<?php ob_start(); ?>
<div class="container mt-4">
  <?php if (isset($booking)): ?>
    <div class="row">
      <div class="col-12 mb-4">
        <div class="d-flex justify-content-between align-items-center">
          <h1 class="h2 fw-bold">Booking #<?= $booking['id'] ?></h1>
          <span class="badge bg-<?= ['pending'=>'warning', 'confirmed'=>'success', 'completed'=>'secondary', 'cancelled'=>'danger'][$booking['status']] ?? 'secondary' ?> fs-6">
            <?= ucfirst($booking['status']) ?>
          </span>
        </div>
        <hr class="my-2">
      </div>
    </div>

    <div class="row g-4">
      <!-- Left column: Main booking info -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <div class="d-flex align-items-center gap-2 mb-4">
              <i class="bi bi-calendar-check fs-3 text-warning"></i>
              <h5 class="card-title mb-0">Rental Details</h5>
            </div>
            
            <ul class="list-unstyled">
              <li class="mb-3"><strong>Equipment:</strong> <?= htmlspecialchars($booking['equipment_details']) ?></li>
              <li class="mb-3"><strong>Start Date:</strong> <?= date('F d, Y', strtotime($booking['start_date'])) ?></li>
              <li class="mb-3"><strong>End Date:</strong> <?= date('F d, Y', strtotime($booking['end_date'])) ?></li>
              <li class="mb-3"><strong>Total Days:</strong> <?= ceil((strtotime($booking['end_date']) - strtotime($booking['start_date'])) / 86400) + 1 ?> days</li>
              <li class="mb-3"><strong>Total Price:</strong> <span class="fs-5 fw-bold text-success">₱<?= number_format($booking['total_price'], 2) ?></span></li>
              <li class="mb-3"><strong>Payment Status:</strong> 
                <span class="badge bg-<?= ($booking['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'danger' ?>">
                  <?= ucfirst($booking['payment_status'] ?? 'pending') ?>
                </span>
              </li>
            </ul>

            <div class="d-flex gap-3 mt-4">
              <a href="?controller=booking&action=myBookings" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left"></i> Back
              </a>
              <?php if ($booking['status'] === 'pending'): ?>
                <form action="?controller=booking&action=cancel" method="POST" class="d-inline">
                  <input type="hidden" name="booking_id" value="<?= $booking['id'] ?>">
                  <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Cancel this booking?')">
                    <i class="bi bi-x-circle"></i> Cancel Booking
                  </button>
                </form>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>

      <!-- Right column: Equipment image & payment summary -->
      <div class="col-lg-5">
        <!-- Equipment Image Card -->
        <div class="card border-0 shadow-sm rounded-4 mb-4">
          <div class="card-body p-4 text-center">
            <i class="bi bi-image fs-1 text-secondary mb-2"></i>
            <?php
            // Try to fetch equipment image if equipment ID is known from booking_items
            // This part requires you to pass equipment data. You can optionally load from DB.
            // For simplicity, we show a placeholder. To enhance, pass $equipmentImage from controller.
            ?>
            <div class="bg-light rounded-3 p-3">
              <p class="text-muted mb-0">Equipment preview not available</p>
            </div>
          </div>
        </div>

        <!-- Payment Details Card -->
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-body p-4">
            <h5 class="card-title mb-3"><i class="bi bi-credit-card me-2"></i> Payment Summary</h5>
            <table class="table table-sm table-borderless">
              <tbody>
                <tr>
                  <td>Subtotal:</td>
                  <td class="text-end">₱<?= number_format($booking['total_price'], 2) ?></td>
                </tr>
                <tr>
                  <td>Amount Paid:</td>
                  <td class="text-end text-success">₱<?= number_format($booking['payment_amount'] ?? 0, 2) ?></td>
                </tr>
                <tr>
                  <td>Remaining Balance:</td>
                  <td class="text-end fw-bold">₱<?= number_format(($booking['total_price'] - ($booking['payment_amount'] ?? 0)), 2) ?></td>
                </tr>
              </tbody>
            </table>
            <div class="alert alert-info mt-2 mb-0 py-2 small">
              <i class="bi bi-info-circle-fill"></i> Please complete payment as per admin instructions.
            </div>
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