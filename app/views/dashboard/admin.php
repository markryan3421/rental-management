<?php ob_start(); ?>
<style>
:root {
    --cal-accent:  #f59e0b;   /* amber – energetic, rental-industry feel */
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
        <i class="bi bi-house-door"></i> Admin Dashboard
        </span>
        <h1>Welcome, <?= htmlspecialchars($_SESSION['name'] ?? 'admin') ?>.</h1>
        <p>Manage the bookings and equipment inventory.</p>
    </div>
</div>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Equipment Management</h5>
                    <p class="card-text">Add, edit, or remove rental items. Update inventory and pricing.</p>
                    <a href="?controller=equipment&action=index" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">All Bookings</h5>
                    <p class="card-text">View and manage customer bookings, confirm or cancel.</p>
                    <a href="?controller=booking&action=adminIndex" class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>