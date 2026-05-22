<?php

class AuthMiddleware
{
    public static function check(): void
    {
        Session::start();

        if (!Session::get('user')) {
            header("Location: ?controller=auth&action=index");
            exit;
        }
    }
}