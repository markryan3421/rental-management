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
        <i class="bi bi-house-door"></i> Rental
        </span>
        <h1>Rental Shop</h1>
        <p>Browse our equipment and check availability for your event dates.</p>
    </div>
</div>

<div class="container mt-4">
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
                        <?php
                        $imageUrl = !empty($item['image']) 
                            ? htmlspecialchars($item['image']) 
                            : 'assets/imgs/fallback-equipment.jpg';
                        ?>
                        <img src="<?= $imageUrl ?>" 
                            class="card-img-top" 
                            alt="<?= htmlspecialchars($item['name']) ?>" 
                            style="height: 200px; object-fit: cover;"
                            onerror="this.onerror=null; this.src='/rental-management/public/assets/imgs/fallback-equipment.jpg';">
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