<?php 
require_once BASE_PATH . '/app/core/Flash.php';
ob_start(); ?>

<div class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-6 my-auto">
            <h1><i class="bi bi-box-arrow-in-right"></i> Login</h1>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam, repellat?</p>

            <div class="card rounded-5 mt-5">
                <div class="card-body shadow">
                    <form action="?controller=auth&action=login" method="POST" class="row g-3 p-4">
                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div class="col-12 mt-5">
                            <button type="submit" class="btn btn-outline-warning py-2 px-5">
                                Login
                            </button>

                            <a href="?controller=registration&action=create" class="btn btn-primary px-5 py-2">
                                Register
                            </a>
                        </div>
                    </form>

                    <?php foreach (Flash::get('error') as $msg): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="col-12 col-md-6 col-lg-6">
            <img src="https://images.pexels.com/photos/35927296/pexels-photo-35927296.jpeg" alt="Login" class="img-fluid rounded-5">
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
