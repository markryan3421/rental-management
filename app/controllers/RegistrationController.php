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
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';
        $mobileSuffix = trim($_POST['mobile_suffix'] ?? '');   // new: only the 10-digit part
        $barangay = $_POST['barangay'] ?? '';
        $street = trim($_POST['street'] ?? '');

        // --- Basic validation ---
        if ($name === '' || $email === '' || $password === '' || $mobileSuffix === '' || $barangay === '' || $street === '') {
            Flash::set('error', 'All fields are required.');
            header("Location: ?controller=registration&action=create");
            exit;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            Flash::set('error', 'Invalid email address.');
            header("Location: ?controller=registration&action=create");
            exit;
        }

        if ($password !== $confirmPassword) {
            Flash::set('error', 'Passwords do not match.');
            header("Location: ?controller=registration&action=create");
            exit;
        }

        if (strlen($password) < 6) {
            Flash::set('error', 'Password must be at least 6 characters.');
            header("Location: ?controller=registration&action=create");
            exit;
        }

        // --- Philippine mobile number validation (suffix only) ---
        // Remove any non‑digit characters
        $mobileSuffix = preg_replace('/[^0-9]/', '', $mobileSuffix);
        // Must be exactly 10 digits and start with 9
        if (!preg_match('/^9[0-9]{9}$/', $mobileSuffix)) {
            Flash::set('error', 'Mobile number must be exactly 10 digits starting with 9 (e.g., 9123456789).');
            header("Location: ?controller=registration&action=create");
            exit;
        }
        // Prepend +63 to form the full number
        $fullMobile = '+63' . $mobileSuffix;

        // --- Check if email already exists ---
        $existingUser = $this->registration->findByEmail($email);
        if ($existingUser) {
            Flash::set('error', 'Email already registered.');
            header("Location: ?controller=registration&action=create");
            exit;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Pass the full mobile number ($fullMobile) to the model
        $result = $this->registration->create($name, $email, $hashedPassword, $fullMobile, $barangay, $street);

        if ($result) {
            Flash::set('success', 'Registration successful! Please log in.');
            header("Location: ?controller=auth&action=index");
        } else {
            Flash::set('error', 'Registration failed. Please try again.');
            header("Location: ?controller=registration&action=create");
        }
        exit;
    }
}
