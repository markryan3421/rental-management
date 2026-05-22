<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/Registration.php';

class RegistrationController
{
    private Registration $registration;

    public function __construct()
    {
        $this->registration = new Registration();
    }

    // show form
    public function create()
    {
        require __DIR__ . '/../views/registration/create.php';
    }

    // handle form submit
    public function store()
    {
        $name = trim((string)($_POST['name'] ?? ''));
        $email = trim((string)($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';
        $confirm = $_POST['password_confirm'] ?? '';

        if ($name === '') {
            Flash::set('error', 'Name is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Invalid email');
        }

        if (strlen($password) < 6) {
            Flash::set('error', 'Password must be at least 6 characters');
        }

        if ($password !== $confirm) {
            Flash::set('error', 'Passwords do not match');
        }

        if ($this->registration->findByEmail($email)) {
            Flash::set('error', 'Email already exists');
        }

        // if errors exist → redirect back
        if (Flash::has('error')) {
            header("Location: ?controller=registration&action=create");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $this->registration->create($name, $email, $hashedPassword);

        Flash::set('success', 'Registration successful! You can now login.');

        header("Location: ?controller=auth&action=index");
        exit;
    }
}
