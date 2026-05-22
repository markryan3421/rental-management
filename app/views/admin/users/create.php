<?php ob_start(); ?>
<h2>Create User</h2>

<form method="POST" action="?controller=user&action=store">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <button type="submit">Create</button>
</form>

<?php
$content = ob_get_clean();
require __DIR__ . '/../layouts/layout.php';