<?php

namespace App\Middleware;

use App\Core\Auth;
use App\Core\Session;

class AuthMiddleware
{
    public function handle(): void
    {
        if (!Auth::isLoggedIn()) {
            Session::setFlash('error', 'Please log in to access this area.');
            header('Location: /login');
            exit;
        }
    }
}
