<?php ob_start(); ?>

<div class="container mt-4">
    <h2>Add New Equipment</h2>
    <form method="POST" action="?controller=equipment&action=store">
        <div class="mb-3">
            <label for="name" class="form-label">Name *</label>
            <input type="text" class="form-control" id="name" name="name" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3"></textarea>
        </div>
        <div class="mb-3">
            <label for="price_per_day" class="form-label">Price per Day *</label>
            <input type="number" step="0.01" class="form-control" id="price_per_day" name="price_per_day" required>
        </div>
        <div class="mb-3">
            <label for="quantity" class="form-label">Quantity *</label>
            <input type="number" class="form-control" id="quantity" name="quantity" required>
        </div>
        <div class="mb-3">
            <label for="image" class="form-label">Image URL (optional)</label>
            <input type="text" class="form-control" id="image" name="image" placeholder="https://...">
        </div>
        <div class="mb-3">
            <label for="status" class="form-label">Status</label>
            <select class="form-control" id="status" name="status">
                <option value="available">Available</option>
                <option value="unavailable">Unavailable</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Save Equipment</button>
        <a href="?controller=equipment&action=index" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?php 
    $content = ob_get_clean();
    require_once BASE_PATH . '/app/views/layouts/layout.php'; 
?>