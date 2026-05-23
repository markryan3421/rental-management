<?php ob_start(); ?>
<div class="page-section fade-up">
  <div class="container text-center">
    <h1 class="display-3 fw-bold">Our <span class="text-warning">Story</span></h1>
    <p class="lead mx-auto" style="max-width: 700px;">We provide high‑quality event rentals with exceptional service. Trusted by hundreds of customers in Victorias City.</p>
    <div class="mt-4">
      <i class="bi bi-trophy fs-1 text-warning me-3"></i>
      <i class="bi bi-calendar-check fs-1 text-warning me-3"></i>
      <i class="bi bi-people fs-1 text-warning"></i>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>