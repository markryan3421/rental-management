<?php

declare(strict_types=1);
require_once __DIR__ . '/../models/User.php';

class UserController
{
    private User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index()
    {
        $users = $this->user->all();
        require __DIR__ . '/../views/admin/users/index.php';
    }

    public function create()
    {
        require __DIR__ . '/../views/admin/users/create.php';
    }

    public function store()
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        $name = trim((string)$name);
        $email = trim((string)$email);

        $errors = [];

        if ($name === '') {
            $errors[] = 'Name is required.';
        }

        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Valid email is required.';
        }

        if (strlen($name) > 100) {
            $errors[] = 'Name is too long.';
        }

        // STOP execution if error
        if (!empty($errors)) {
            http_response_code(422);

            foreach ($errors as $error) {
                echo "<p style='color:red;'>{$error}</p>";
            }
            return; 
        }

        $this->user->create($name, $email);

        // redirect after insert
        header("Location: ?controller=user&action=index");
        exit;
    }

    public function edit(int $id)
    {
        $user = $this->user->find($id);
        require __DIR__ . '/../views/users/edit.php';
    }

    public function update(int $id)
    {
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

        $name = trim((string)$name);
        $email = trim((string)$email);

        if ($name === '' || $email === '') {
            echo "Invalid input.";
            return;
        }

        $this->user->update($id, $name, $email);

        header("Location: ?controller=user&action=index"); // FIX redirect
        exit;
    }

    public function delete(int $id)
    {
        $this->user->delete($id);
        header("Location : /");
        exit;
    }
}
