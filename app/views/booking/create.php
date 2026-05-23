<?php ob_start(); ?>
<div class="container mt-4">
  <?php if (isset($equipment)): ?>
    <h1>Book Equipment: <?= htmlspecialchars($equipment['name']) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="?controller=booking&action=store" method="POST">
                        <input type="hidden" name="equipment_id" value="<?= $equipment['id'] ?>">
                        
                        <div class="mb-3">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity (max <?= $equipment['quantity'] ?>)</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" min="1" max="<?= $equipment['quantity'] ?>" value="1" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Price per day</label>
                            <p class="fw-bold">₱<?= number_format($equipment['price_per_day'], 2) ?></p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total days</label>
                            <p class="fw-bold" id="total_days">0</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Total price</label>
                            <p class="fw-bold text-success" id="total_price">₱0.00</p>
                        </div>
                        <button type="submit" class="btn btn-primary">Confirm Booking</button>
                        <a href="?controller=page&action=shop" class="btn btn-secondary">Cancel</a>
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
        }
    }

    start.addEventListener('change', calculateTotal);
    end.addEventListener('change', calculateTotal);
    quantity.addEventListener('input', calculateTotal);
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>