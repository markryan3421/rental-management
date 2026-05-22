<?php
class Flash
{
    public static function set(string $key, string $message): void
    {
        $_SESSION['flash'][$key][] = $message;
    }

    public static function get(string $key): array
    {
        if (empty($_SESSION['flash'][$key])) {
            return [];
        }

        $messages = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        return $messages;
    }

    public static function has(string $key): bool
    {
        return !empty($_SESSION['flash'][$key]);
    }
}