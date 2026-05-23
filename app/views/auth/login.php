<?php 
require_once BASE_PATH . '/app/core/Flash.php';
ob_start(); 
?>
<div class="page-section fade-up">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card border-0 bg-dark rounded-4 shadow">
          <div class="card-body p-5">
            <div class="text-center mb-4">
              <i class="bi bi-person-fill display-6"></i>
              <h2 class="fw-bold mt-2">Login</h2>
            </div>
            <form action="?controller=auth&action=login" method="POST">
              <div class="mb-3"><input type="email" name="email" class="form-control form-control-lg" placeholder="Email" required></div>
              <div class="mb-4"><input type="password" name="password" class="form-control form-control-lg" placeholder="Password" required></div>
              <button type="submit" class="btn btn-warning btn-lg w-100">Sign In</button>
              <div class="text-center mt-3">
                <a href="?controller=registration&action=create">Create an account</a>
              </div>
            </form>
            <?php foreach (Flash::get('error') as $msg): ?>
              <div class="alert alert-danger mt-3"><?= htmlspecialchars($msg) ?></div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>