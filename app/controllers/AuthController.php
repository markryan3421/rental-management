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
        $email = trim($_POST['email'] ?? '');
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

        // Flat session variables
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        Flash::set('success', 'Welcome back, ' . $user['name']);

        if ($user['role'] === 'admin') {
    header("Location: ?controller=dashboard&action=admin");
    } else {
        header("Location: ?controller=dashboard&action=customer");
    }
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
