<?php
class Csrf
{
    public static function generate(): string
    {
        if (!isset($_SESSION['_csrf'])) {
            $_SESSION['_csrf'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['_csrf'];
    }

    public static function validate(string $token): bool
    {
        return isset($_SESSION['_csrf']) && hash_equals($_SESSION['_csrf'], $token);
    }
}