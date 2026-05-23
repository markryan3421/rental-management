<?php ob_start(); ?>
<div class="container mt-4">
    <h1 class="mb-4">Rental Shop</h1>
    <p class="lead">Browse our equipment and check availability for your event dates.</p>

    <!-- Optional: Date range picker to filter equipment -->
    <div class="row mb-4">
        <div class="col-md-6 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Select your rental period</h5>
                    <div class="row g-3">
                        <div class="col-md-5">
                            <label for="start_date" class="form-label">Start Date</label>
                            <input type="date" id="start_date" class="form-control">
                        </div>
                        <div class="col-md-5">
                            <label for="end_date" class="form-label">End Date</label>
                            <input type="date" id="end_date" class="form-control">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button id="checkAvailabilityBtn" class="btn btn-primary w-100">Check</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4" id="equipment-container">
        <?php if (isset($equipmentList)): ?>
            <?php foreach ($equipmentList as $item): ?>
                <div class="col equipment-card" data-id="<?= $item['id'] ?>">
                    <div class="card h-100 shadow-sm">
                        <?php if (!empty($item['image'])): ?>
                            <img src="<?= htmlspecialchars($item['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($item['name']) ?>" style="height: 200px; object-fit: cover;">
                        <?php else: ?>
                            <div class="bg-secondary text-white text-center py-5">No Image</div>
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($item['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($item['description'] ?? 'No description') ?></p>
                            <p class="card-text"><strong>₱<?= number_format($item['price_per_day'], 2) ?></strong> / day</p>
                            <p class="card-text">Available: <span class="badge bg-success"><?= $item['quantity'] ?> in stock</span></p>
                            <div class="availability-status mb-2" id="avail-<?= $item['id'] ?>"></div>
                            <?php if ($item['quantity'] > 0) : ?>
                                <a href="?controller=booking&action=create&equipment_id=<?= $item['id'] ?>" class="btn btn-primary book-btn">Book Now</a>
                            <?php else : ?>
                                <p><span class="badge bg-danger">Out of stock, please choose other items.</span></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Include Flatpickr for better date picker (optional) -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const checkBtn = document.getElementById('checkAvailabilityBtn');

    // Optional: set min date to today
    const today = new Date().toISOString().split('T')[0];
    startDateInput.min = today;
    endDateInput.min = today;

    function updateAvailabilityDisplay() {
        const startDate = startDateInput.value;
        const endDate = endDateInput.value;
        if (!startDate || !endDate) return;

        // Show loading indicator
        document.querySelectorAll('.availability-status').forEach(el => {
            el.innerHTML = '<span class="badge bg-secondary">Checking...</span>';
        });

        // For each equipment card, fetch availability via AJAX
        const equipmentIds = Array.from(document.querySelectorAll('.equipment-card')).map(card => card.dataset.id);
        
        equipmentIds.forEach(id => {
            fetch(`?controller=page&action=checkAvailability&id=${id}&start=${startDate}&end=${endDate}`)
                .then(response => response.json())
                .then(data => {
                    const statusDiv = document.getElementById(`avail-${id}`);
                    if (data.available) {
                        statusDiv.innerHTML = '<span class="badge bg-success">Available for selected dates</span>';
                    } else {
                        statusDiv.innerHTML = '<span class="badge bg-danger">Not available – please select other dates</span>';
                        // Optionally disable book button
                        const bookBtn = document.querySelector(`.equipment-card[data-id="${id}"] .book-btn`);
                        if (bookBtn) bookBtn.style.opacity = '0.5';
                    }
                })
                .catch(() => {
                    document.getElementById(`avail-${id}`).innerHTML = '<span class="badge bg-warning">Error checking</span>';
                });
        });
    }

    checkBtn.addEventListener('click', updateAvailabilityDisplay);
    startDateInput.addEventListener('change', updateAvailabilityDisplay);
    endDateInput.addEventListener('change', updateAvailabilityDisplay);
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>