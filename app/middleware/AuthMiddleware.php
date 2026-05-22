<?php

class AuthMiddleware
{
    public static function check(): void
    {
        Session::start();

        // Check for flat session variable 'user_id' (set after login)
        if (!Session::get('user_id')) {
            header("Location: ?controller=auth&action=index");
            exit;
        }
    }
}