<?php ob_start(); ?>

<div class="container pt-5">
    <div class="row">
        <div class="col-12 col-md-6 mx-auto my-auto">

            <div class="card rounded-5 mt-5">
                <div class="card-body shadow">
                    <div class="text-center mx-auto pt-4">
                        <h1><i class="bi bi-person-circle"></i></h1>
                        <h1>Registration</h1>
                        <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Laboriosam</p>
                    </div>
                    <form action="?controller=registration&action=store" method="POST" class="row g-3 p-4">

                        <div>
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>

                        <div>
                            <label class="form-label">Confirm Password</label>
                            <input type="password" name="password_confirm" class="form-control" required>
                        </div>

                        <div class="col-12 mt-4">
                            <button type="submit" class="btn btn-primary">Create Account</button>
                        </div>
                    </form>
                    <?php foreach (Flash::get('error') as $msg): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($msg) ?></div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
