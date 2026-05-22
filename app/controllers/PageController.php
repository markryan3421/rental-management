<?php

class PageController
{
    public function home()
    {
        require __DIR__ . '/../views/pages/home.php';
    }

    public function about()
    {
        require __DIR__ . '/../views/pages/about.php';
    }

    public function contact()
    {
        require __DIR__ . '/../views/pages/contact.php';
    }

    public function login()
    {
        require __DIR__ . '/../views/auth/login.php';
    }

    public function register()
    {
        require __DIR__ . '/../views/pages/registration.php';
    }

    public function shop()
    {
        require __DIR__ . '/../views/pages/shop.php';
    }
}