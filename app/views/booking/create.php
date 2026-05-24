<?php ob_start(); ?>
<div class="container mt-4">
  <?php if (isset($equipment)): ?>
    <div class="row g-4">
      <!-- Left column: Equipment image & info -->
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <?php
          $imageUrl = !empty($equipment['image']) 
              ? htmlspecialchars($equipment['image']) 
              : 'assets/imgs/fallback-equipment.jpg';
          ?>
          <img src="<?= $imageUrl ?>" 
               class="card-img-top" 
               alt="<?= htmlspecialchars($equipment['name']) ?>" 
               style="height: 280px; object-fit: cover;"
               onerror="this.onerror=null; this.src='/rental-management/public/assets/imgs/fallback-equipment.jpg';">
          <div class="card-body">
            <h3 class="card-title fw-bold"><?= htmlspecialchars($equipment['name']) ?></h3>
            <p class="card-text text-secondary"><?= nl2br(htmlspecialchars($equipment['description'] ?? 'No description')) ?></p>
            <hr>
            <div class="d-flex justify-content-between">
              <span><i class="bi bi-tag"></i> Price per day:</span>
              <span class="fw-bold text-success">₱<?= number_format($equipment['price_per_day'], 2) ?></span>
            </div>
            <div class="d-flex justify-content-between mt-2">
              <span><i class="bi bi-box-seam"></i> Available stock:</span>
              <span class="badge bg-<?= $equipment['quantity'] > 0 ? 'success' : 'danger' ?>">
                <?= $equipment['quantity'] ?> units
              </span>
            </div>
          </div>
        </div>
      </div>

      <!-- Right column: Booking form -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-transparent border-0 pt-4 px-4">
            <h4 class="mb-0"><i class="bi bi-calendar-plus me-2"></i> Rental Details</h4>
          </div>
          <div class="card-body p-4">
            <form action="?controller=booking&action=store" method="POST">
              <input type="hidden" name="equipment_id" value="<?= $equipment['id'] ?>">
              
              <div class="row g-3 mb-3">
                <div class="col-md-6">
                  <label for="start_date" class="form-label">Start Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="start_date" name="start_date" required 
                         min="<?= date('Y-m-d') ?>">
                </div>
                <div class="col-md-6">
                  <label for="end_date" class="form-label">End Date <span class="text-danger">*</span></label>
                  <input type="date" class="form-control" id="end_date" name="end_date" required>
                </div>
              </div>

              <div class="mb-3">
                <label for="quantity" class="form-label">Quantity (max <?= $equipment['quantity'] ?>)</label>
                <input type="number" class="form-control" id="quantity" name="quantity" 
                       min="1" max="<?= $equipment['quantity'] ?>" value="1" required>
                <?php if ($equipment['quantity'] === 0): ?>
                  <div class="text-danger small mt-1">Out of stock – please choose another item.</div>
                <?php endif; ?>
              </div>

              <!-- Dynamic summary panel -->
              <div class="bg-dark p-3 rounded-3 my-4">
                <div class="row text-center">
                  <div class="col-4">
                    <div class="small text-secondary">Total days</div>
                    <div class="fs-4 fw-bold" id="total_days">0</div>
                  </div>
                  <div class="col-4">
                    <div class="small text-secondary">Price per day</div>
                    <div class="fs-6">₱<?= number_format($equipment['price_per_day'], 2) ?></div>
                  </div>
                  <div class="col-4">
                    <div class="small text-secondary">Total price</div>
                    <div class="fs-5 fw-bold text-warning" id="total_price">₱0.00</div>
                  </div>
                </div>
              </div>

              <div class="d-flex gap-3">
                <button type="submit" class="btn btn-warning btn-lg px-4" <?= $equipment['quantity'] == 0 ? 'disabled' : '' ?>>
                  <i class="bi bi-check-circle"></i> Confirm Booking
                </button>
                <a href="?controller=page&action=shop" class="btn btn-outline-secondary btn-lg px-4">
                  <i class="bi bi-arrow-left"></i> Cancel
                </a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger">Equipment data not found.</div>
  <?php endif; ?>
</div>

<script>
    const start = document.getElementById('start_date');
    const end = document.getElementById('end_date');
    const quantity = document.getElementById('quantity');
    const totalDaysSpan = document.getElementById('total_days');
    const totalPriceSpan = document.getElementById('total_price');
    const pricePerDay = <?= $equipment['price_per_day'] ?? 0 ?>;

    function calculateTotal() {
        if (start.value && end.value) {
            const startDate = new Date(start.value);
            const endDate = new Date(end.value);
            if (endDate >= startDate) {
                const diffTime = Math.abs(endDate - startDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
                totalDaysSpan.innerText = diffDays;
                const total = diffDays * pricePerDay * (parseInt(quantity.value) || 1);
                totalPriceSpan.innerText = '₱' + total.toFixed(2);
            } else {
                totalDaysSpan.innerText = 'Invalid dates';
                totalPriceSpan.innerText = '₱0.00';
            }
        } else {
            totalDaysSpan.innerText = '0';
            totalPriceSpan.innerText = '₱0.00';
        }
    }

    start.addEventListener('change', calculateTotal);
    end.addEventListener('change', calculateTotal);
    quantity.addEventListener('input', calculateTotal);
    calculateTotal(); // run on page load if dates are prefilled
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>