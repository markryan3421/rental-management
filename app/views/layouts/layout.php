<?php require_once BASE_PATH . '/app/core/Flash.php'; ?>
<!doctype html>
<html lang="en" data-bs-theme="dark">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD App</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

</head>

<body>
  <header>
    <nav class="navbar navbar-expand-lg bg-body-tertiary">
      <div class="container">
        <a class="navbar-brand fw-bold" href="#">MyApp</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="?controller=page&action=home">Home</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?controller=page&action=shop">Shop</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?controller=page&action=about">About</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="?controller=page&action=contact">Contact</a>
            </li>
          </ul>
          <form class="d-flex" role="search">
            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" />
            <button class="btn btn-outline-success" type="submit">Search</button>
          </form>
          <a href="?controller=page&action=login" class="btn btn-warning ms-2" type="submit">Login</a>
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

    <?php echo $content ?>
  </main>

  <script>
    setTimeout(() => {
      document.querySelectorAll('.alert').forEach(el => {
        el.style.display = 'none';
      });
    }, 3000);
  </script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
</body>

</html>