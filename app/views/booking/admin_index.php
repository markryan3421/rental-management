<?php ob_start(); ?>
<div class="container mt-4">
    <h1>Manage Bookings</h1>
    
    <!-- Filter Form -->
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <input type="hidden" name="controller" value="booking">
                <input type="hidden" name="action" value="adminIndex">
                <div class="col-md-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="">All</option>
                        <option value="pending" <?= ($_GET['status'] ?? '') === 'pending' ? 'selected' : '' ?>>Pending</option>
                        <option value="confirmed" <?= ($_GET['status'] ?? '') === 'confirmed' ? 'selected' : '' ?>>Confirmed</option>
                        <option value="completed" <?= ($_GET['status'] ?? '') === 'completed' ? 'selected' : '' ?>>Completed</option>
                        <option value="cancelled" <?= ($_GET['status'] ?? '') === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Start Date From</label>
                    <input type="date" name="start_date" class="form-control" value="<?= htmlspecialchars($_GET['start_date'] ?? '') ?>">
                </div>
                <div class="col-md-3">
                    <label class="form-label">End Date To</label>
                    <input type="date" name="end_date" class="form-control" value="<?= htmlspecialchars($_GET['end_date'] ?? '') ?>">
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary">Filter</button>
                    <a href="?controller=booking&action=adminIndex" class="btn btn-secondary ms-2">Reset</a>
                </div>
            </form>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-bordered table-hover" id="bookingsTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Equipment</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Total</th>
                    <th>Booking Status</th>
                    <th>Payment Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (isset($bookings) && !empty($bookings)): ?>
                  <?php foreach ($bookings as $booking): ?>
                  <tr>
                      <td>#<?= $booking['id'] ?></td>
                      <td><?= htmlspecialchars($booking['customer_name']) ?><br><small><?= htmlspecialchars($booking['customer_email']) ?></small></td>
                      <td><?= htmlspecialchars($booking['equipment_names']) ?></td>
                      <td><?= date('M d, Y', strtotime($booking['start_date'])) ?></td>
                      <td><?= date('M d, Y', strtotime($booking['end_date'])) ?></td>
                      <td>₱<?= number_format($booking['total_price'], 2) ?></td>
                      <td>
                          <?php
                          $statusClass = [
                              'pending' => 'warning',
                              'confirmed' => 'success',
                              'completed' => 'secondary',
                              'cancelled' => 'danger'
                          ][$booking['status']] ?? 'secondary';
                          ?>
                          <span class="badge bg-<?= $statusClass ?>"><?= ucfirst($booking['status']) ?></span>
                      </td>
                      <td>
                          <?php
                          $payClass = ($booking['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'danger';
                          ?>
                          <span class="badge bg-<?= $payClass ?>"><?= ucfirst($booking['payment_status'] ?? 'pending') ?></span>
                      </td>
                      <td>
                          <a href="?controller=booking&action=adminView&id=<?= $booking['id'] ?>" class="btn btn-sm btn-primary">View</a>
                      </td>
                  </tr>
                  <?php endforeach; ?>
                <?php else : ?>
                  <td colspan="9" style="text-align: center; color: red;">No data found.</td>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Optional: DataTables for better sorting/filtering -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css">
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#bookingsTable').DataTable({
            order: [[0, 'desc']],
            pageLength: 25
        });
    });
</script>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>