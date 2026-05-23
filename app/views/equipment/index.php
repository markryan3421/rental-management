<?php require_once BASE_PATH . '/app/views/layouts/layout.php'; ?>
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
        <i class="bi bi-house-door"></i> Equipments
        </span>
        <h1>Equipment Management</h1>
        <p>Manage the equipment inventory here.</p>
    </div>
</div>

<div class="container mt-4">
    <?php if (Flash::has('success')): ?>
      <?php foreach (Flash::get('success') as $message): ?>
          <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
      <?php endforeach; ?>
  <?php endif; ?>

  <?php if (Flash::has('error')): ?>
      <?php foreach (Flash::get('error') as $message): ?>
          <div class="alert alert-danger"><?= htmlspecialchars($message) ?></div>
      <?php endforeach; ?>
  <?php endif; ?>

    <table class="table table-bordered table-striped" id="equipmentTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Price/Day</th>
                <th>Quantity</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($equipmentList ?? [] as $item): ?>
            <tr>
                <td><?= $item['id'] ?></td>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td><?= htmlspecialchars(substr($item['description'] ?? '', 0, 50)) ?>...</td>
                <td>₱<?= number_format($item['price_per_day'], 2) ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>
                    <span class="badge bg-<?= $item['status'] === 'available' ? 'success' : 'secondary' ?>">
                        <?= ucfirst($item['status']) ?>
                    </span>
                </td>
                <td>
                    <a href="?controller=equipment&action=edit&id=<?= $item['id'] ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="?controller=equipment&action=delete&id=<?= $item['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php require_once BASE_PATH . '/app/views/layouts/layout.php'; ?>