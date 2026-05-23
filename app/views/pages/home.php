<?php ob_start(); ?>
<div class="page-section fade-up">
  <div class="container text-center">
    <h1 class="display-3 fw-bold mb-4">Elevate Your <span class="text-warning">Events</span></h1>
    <p class="lead mb-4">Premium equipment rentals for weddings, parties, and corporate events.</p>
    <a href="?controller=page&action=shop" class="btn btn-warning btn-lg px-5">Explore Now →</a>
  </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>