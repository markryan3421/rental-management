<?php ob_start(); ?>
<h2>Edit User</h2>

<div class="container">
    <div class="row">
        <div class="col-12">
            <div class="card p-4">
                <form method="POST" action="?controller=user&action=update&id=<?php echo $user['id'] ?>">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Name</label>
                        <input type="text" name="name" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo htmlspecialchars($user['name']); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email address</label>
                        <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" value="<?php echo htmlspecialchars($user['email']); ?>    ">

                    </div>

                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>



<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
