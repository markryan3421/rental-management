<?php

declare(strict_types=1);

require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php';
require_once __DIR__ . '/../core/Flash.php';
require_once __DIR__ . '/../core/Csrf.php';

class AuthController
{
    private User $user;

    public function __construct()
    {
        Session::start();
        $this->user = new User();
    }

    public function index()
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function login()
    {
        $email = trim((string)($_POST['email'] ?? ''));
        $password = $_POST['password'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $password === '') {
            Flash::set('error', 'Invalid input');
            header("Location: ?controller=auth&action=index");
            exit;
        }

        $user = $this->user->findByEmail($email);

        if (!$user || !password_verify($password, $user['password'])) {
            Flash::set('error', 'Invalid credentials');
            header("Location: ?controller=auth&action=index");
            exit;
        }

        $_SESSION['user'] = [
            'id' => $user['id'],
            'name' => $user['name'],
            'email' => $user['email']
        ];

        Flash::set('success', 'Welcome back, ' . $user['name']);

        header("Location: ?controller=dashboard&action=index");
        exit;
    }

    public function logout()
    {
        Session::start();
        Session::destroy();

        header("Location: ?controller=auth&action=index");
        exit;
    }
}
