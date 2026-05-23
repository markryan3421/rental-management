<?php ob_start(); ?>
<div class="page-section fade-up">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-8 text-center mb-4">
        <h1 class="display-4 fw-bold">Get in <span class="text-warning">Touch</span></h1>
        <p class="lead">We'll reply within 24 hours.</p>
      </div>
    </div>
    <div class="row justify-content-center">
      <div class="col-md-6">
        <div class="card bg-dark border-0 rounded-4 p-4">
          <form action="?controller=page&action=sendMessage" method="POST">
            <div class="mb-3"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email Address" required></div>
            <div class="mb-3"><textarea name="message" rows="3" class="form-control" placeholder="Message" required></textarea></div>
            <button type="submit" class="btn btn-warning w-100">Send Message →</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
?>