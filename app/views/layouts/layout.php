<?php
// Ensure session is started (if not already, your index.php should have session_start())
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? 'User';
?>

<?php require_once BASE_PATH . '/app/core/Flash.php'; ?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rental Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand fw-bold" href="?controller=dashboard&action=admin">EventShop Rentals</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <!-- Admin only: Equipment Management (optional) -->
            <?php if ($isLoggedIn && ($_SESSION['role'] ?? '') === 'admin'): ?>
            <li class="nav-item">
              <a class="nav-link" href="?controller=equipment&action=index">Equipment</a>
            </li>
            <?php endif; ?>
          </ul>
          
          <form class="d-flex" role="search" action="?controller=page&action=shop" method="GET">
            <input type="hidden" name="controller" value="page">
            <input type="hidden" name="action" value="shop">
            <input class="form-control me-2" type="search" name="search" placeholder="Search items" aria-label="Search">
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>

          <?php if ($isLoggedIn): ?>
            <div class="dropdown ms-2">
              <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($userName) ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="?controller=user&action=dashboard">Dashboard</a></li>
                <li><a class="dropdown-item" href="?controller=booking&action=myBookings">My Rentals</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="?controller=auth&action=logout">Logout</a></li>
              </ul>
            </div>
          <?php else: ?>
            <a href="?controller=page&action=login" class="btn btn-warning ms-2">Login</a>
          <?php endif; ?>
        </div>
      </div>
    </nav>
  </header>

  <main>
    <div class="container mt-3">
      <!-- SUCCESS -->
      <?php foreach (Flash::get('success') as $msg): ?>
        <div class="alert alert-success alert-dismissible fade show">
          <?= htmlspecialchars($msg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endforeach; ?>

      <!-- ERROR -->
      <?php foreach (Flash::get('error') as $msg): ?>
        <div class="alert alert-danger alert-dismissible fade show">
          <?= htmlspecialchars($msg) ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      <?php endforeach; ?>
    </div>

    <?php if (isset($content)): ?>
      <?= $content ?>
    <?php endif; ?>
  </main>

  <script>
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(el => {
        el.style.display = 'none';
      });
    }, 3000);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>