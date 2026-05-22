<?php ob_start(); ?>


<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Users</h2>
            <a href="?controller=user&action=create" class="btn btn-primary">Create User</a>

            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Name</th>
                        <th scope="col">Email</th>
                        <th scope="col">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $user) : ?>
                        <tr>
                            <th scope="row"><?php echo $user['id']; ?></th>
                            <td><?php echo htmlspecialchars($user['name']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <a class="btn btn-warning px-4 py-2" href="?controller=user&action=edit&id=<?php echo $user['id']; ?>">Edit</a>
                                <a class="btn btn-danger px-4 py-2" href="?controller=user&action=delete&id=<?php echo $user['id']; ?>" onclick="return confirm('Are you sure?')">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';
