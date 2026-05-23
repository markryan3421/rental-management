<?php
// Check if user is logged in
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['name'] ?? 'User';
?>

<?php require_once BASE_PATH . '/app/core/Flash.php'; ?>
<!doctype html>
<html lang="en" data-bs-theme="dark">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <title>EventShop Rentals</title>
  <!-- Prevent scrolling on all pages -->
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }
    html, body {
      height: 100%;
      overflow: hidden;   /* No scrollbars anywhere */
    }
    main {
      height: calc(100vh - 76px);  /* 76px = navbar height (approx) */
      overflow-y: auto;   /* Internal scroll only if content overflows, but we design to avoid */
    }
    /* Hide scrollbar for main but keep functionality (optional) */
    main::-webkit-scrollbar {
      display: none;
    }
    main {
      -ms-overflow-style: none;
      scrollbar-width: none;
    }
    /* Ensure all page content fits in viewport */
    .page-section {
      min-height: 100%;
      display: flex;
      align-items: center;
      justify-content: center;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
  <link rel="stylesheet" href="assets/css/style.min.css">
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/gsap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/gsap@3.14.1/dist/ScrollTrigger.min.js"></script>
</head>
<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <div class="navbar-brand fw-bold">JB's Rentals</div>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">

          <?php if (!$isLoggedIn): ?>
            <!-- Public pages -->
            <li class="nav-item"><a class="nav-link" href="?controller=page&action=home">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="?controller=page&action=shop">Shop</a></li>
            <li class="nav-item"><a class="nav-link" href="?controller=page&action=about">About</a></li>
            <li class="nav-item"><a class="nav-link" href="?controller=page&action=contact">Contact</a></li>
            <li class="nav-item">
                <a class="nav-link" href="?controller=equipment&action=calendar">Availability Calendar</a>
            </li>
          <?php endif; ?>
            
            <!-- Admin only -->
            <?php if ($isLoggedIn && ($_SESSION['role'] ?? '') === 'admin'): ?>
              <li class="nav-item"><a class="nav-link" href="?controller=equipment&action=index">Equipment</a></li>
              <li class="nav-item"><a class="nav-link" href="?controller=booking&action=adminIndex">All Bookings</a></li>
            <?php endif; ?>
          </ul>
          
          <?php if ($isLoggedIn): ?>
            <div class="dropdown ms-2">
              <button class="btn btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">
                <i class="bi bi-person-circle"></i> <?= htmlspecialchars($userName) ?>
              </button>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="?controller=dashboard&action=admin">Dashboard</a></li>
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
      <?php foreach (Flash::get('success') as $msg): ?>
        <div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
      <?php endforeach; ?>
      <?php foreach (Flash::get('error') as $msg): ?>
        <div class="alert alert-danger alert-dismissible fade show"><?= htmlspecialchars($msg) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
      <?php endforeach; ?>
    </div>

    <?php if (isset($content)) echo $content; ?>
  </main>

  <script>
    setTimeout(() => { document.querySelectorAll('.alert').forEach(el => el.style.display = 'none'); }, 3000);
    // GSAP animations (only if elements exist)
    gsap.registerPlugin(ScrollTrigger);
    document.querySelectorAll('.fade-up').forEach(el => {
      gsap.fromTo(el, { opacity: 0, y: 50 }, {
        opacity: 1, y: 0, duration: 0.8, ease: "power2.out",
        scrollTrigger: { trigger: el, start: "top 80%" }
      });
    });
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>