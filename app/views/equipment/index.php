<?php require_once BASE_PATH . '/app/views/layouts/layout.php'; ?>

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Equipment Management</h2>
        <a href="?controller=equipment&action=create" class="btn btn-primary">Add New Equipment</a>
    </div>

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