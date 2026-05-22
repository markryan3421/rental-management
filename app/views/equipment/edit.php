<?php require_once BASE_PATH . '/app/views/layouts/layout.php'; ?>

<div class="container mt-4">
    <h2>Edit Equipment</h2>
    <?php if (isset($item)): ?>
    <form method="POST" action="?controller=equipment&action=update&id=<?= $item['id'] ?>">
        <div class="mb-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($item['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($item['description'] ?? '') ?></textarea>
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per Day *</label>
            <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" value="<?= $item['price_per_day'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity *</label>
            <input type="number" class="form-control" id="quantity" name="quantity" value="<?= $item['quantity'] ?>" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image URL</label>
            <input type="text" class="form-control" id="image" name="image" value="<?= htmlspecialchars($item['image'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="available" <?= $item['status'] === 'available' ? 'selected' : '' ?>>Available</option>
                <option value="unavailable" <?= $item['status'] === 'unavailable' ? 'selected' : '' ?>>Unavailable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Update Equipment</button>
        <a href="?controller=equipment&action=index" class="btn btn-secondary">Cancel</a>
    </form>
    <?php else: ?>
    <div class="alert alert-danger">Equipment data not found.</div>
    <?php endif; ?>
</div>

<?php require_once BASE_PATH . '/app/views/layouts/layout.php'; ?>